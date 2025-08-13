<?php

namespace App\Services\Payments;

use App\Models\Billings\Invoice;
use App\Models\Billings\Payment;
use Illuminate\Support\Facades\Log;
use App\Models\Billings\PaymentHistory;

class PaymentReconciliationService
{
    public function reconcilePayment(Payment $payment)
    {
        try {
            // Try to match payment with invoice
            $invoice = $this->findMatchingInvoice($payment);

            if ($invoice) {

                $processPartialPayment = $this->processPartialPaymentStatus($payment, $invoice);

                $payments = Payment::whereInvoiceId($invoice->id)->whereReconciliationStatus('partial')->get();
                if($payments){
                    $totalDiscrepancyPayment = $payments->sum('amount');
                    $totalPayment = $totalDiscrepancyPayment + $payment->amount;

                   if($totalPayment >= $invoice->amount){
                       foreach($payments as $payment_discrepancy){
                            $payment_discrepancy->forceFill([
                               'reconciliation_status' => 'discrepancy',
                           ])->save();
                       }
                       $payment->amount = $totalPayment;
                    return $this->processReconciliation($payment, $invoice);
                   }
                }

                return $processPartialPayment;
            }
            // Mark as unreconciled if no match found
            $payment->forceFill([
                'reconciliation_status' => 'unmatched',
                'reconciliation_notes' => 'No matching invoice found'
            ]);
            $this->logReconciliationHistory($payment, null, 'unmatched');

            return false;
        } catch (\Exception $e) {
            Log::error('Payment reconciliation failed', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage()
            ]);

            $payment->forceFill([
                'reconciliation_status' => 'failed',
                'reconciliation_notes' => $e->getMessage()
            ]);
            return false;
        }
    }

    protected function findMatchingInvoice(Payment $payment)
    {
        // Try to match by invoice_id if available
        if ($payment->invoice_id) {
            return Invoice::find($payment->invoice_id);
        }

        // Try to match by amount and customer
        return Invoice::where('user_customer_id', $payment->user_customer_id)
            ->where('amount', $payment->amount)
            ->where('status', 'pending')
            ->orderBy('due_date')
            ->first();
    }

    protected function processPartialPaymentStatus(Payment $payment, Invoice $invoice)
    {
       // Check for discrepancies
       $discrepancy = $this->checkForDiscrepancies($payment, $invoice);
       if ($discrepancy) {
           $payment->forceFill([
               'reconciliation_status' => 'partial',
               'reconciliation_notes' => $discrepancy
           ]);

           $this->logReconciliationHistory($payment, $invoice, 'partial');
           return false;
       }

       // Process successful reconciliation
       $payment->forceFill([
           'invoice_id' => $invoice->id,
           'reconciliation_status' => 'reconciled',
           'reconciliation_notes' => null
       ]);

       $this->logReconciliationHistory($payment, $invoice, 'reconciled');
       return true;
    }


    protected function processReconciliation(Payment $payment, Invoice $invoice)
    {
       // Check for discrepancies
       $discrepancy = $this->checkForDiscrepancies($payment, $invoice);
       if ($discrepancy) {
           $payment->forceFill([
               'reconciliation_status' => 'discrepancy',
               'reconciliation_notes' => $discrepancy
           ]);

           $this->logReconciliationHistory($payment, $invoice, 'discrepancy');
           return false;
       }

       // Process successful reconciliation
       $payment->forceFill([
           'invoice_id' => $invoice->id,
           'reconciliation_status' => 'reconciled',
           'reconciliation_notes' => null
       ]);

       $this->logReconciliationHistory($payment, $invoice, 'reconciled');
       return true;
    }


    protected function checkForDiscrepancies(Payment $payment, Invoice $invoice)
    {
        if ($payment->amount != $invoice->amount) {
            return "Payment amount ({$payment->amount}) does not match invoice amount ({$invoice->amount})";
        }
        return null;
    }

    protected function logReconciliationHistory(Payment $payment, ?Invoice $invoice, string $status)
    {
      //  $last

        PaymentHistory::create([
            'payment_id' => $payment->id,
            'invoice_id' => $invoice?->id,
            'user_customer_id' => $payment->user_customer_id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'paylater_date' => $payment->paylater_date,
            'transaction_id' => $payment->transaction_id,
            'status' => $status,
            'notes' => $payment->reconciliation_notes
        ]);
    }

    public function handleManualReconciliation(Payment $payment, Invoice $invoice)
    {
        return $this->processReconciliation($payment, $invoice);
    }
}
