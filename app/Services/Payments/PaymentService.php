<?php

namespace App\Services\Payments;

use App\Models\Billings\Payment;
use Illuminate\Support\Facades\Log;

/**
 * Summary of PartialPaymentService
 */
class PaymentService
{
    private $maxRetries = 3;
    private $retryDelay = 5; // seconds
    private $supportedMethods = [
        'cash',
        'paylater',
        'bank_transfer',
        'tripay',
        'midtrans'
    ];

    public function validatePaymentMethod($method)
    {
        return in_array($method, $this->supportedMethods);
    }
    public function processPayment($payment, $paymentMethod)
    {

        if (!$this->validatePaymentMethod($paymentMethod)) {
            return [
                'success' => false,
                'message' =>  'Unsupported payment method: ' . $paymentMethod
            ];
        }

      //  Log::info('Payment process 1');

        try {
            $result = $this->attemptPayment($payment, $paymentMethod);

            // Trigger reconciliation after successful payment
            if ($result['success']) {
                app(PaymentReconciliationService::class)->reconcilePayment($payment);
            }

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'method' => $payment->payment_method
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::warning('Payment attempt failed', [
                'payment_id' => $payment->id,
                'method' => $payment->payment_method,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function attemptPayment(Payment $payment, $paymentMethod)
    {
        switch ($paymentMethod) {
            case 'cash':
                return $this->processCashPayment($payment);
            case 'paylater':
                return $this->processPaylaterPayment($payment);
            case 'bank_transfer':
                return $this->processCashPayment($payment);
            case 'midtrans':
                return $this->processMidtransPayment($payment);
            case 'tripay':
                return $this->processTripayPayment($payment);
            default:
                return [
                    'success' => false,
                    'message' =>  'Unsupported payment method: ' . $paymentMethod
                ];
        }
    }

    private function processCashPayment(Payment $payment)
    {
        $invoice = $payment->invoice;
        $totalPaid = $invoice->payments->sum('amount');
        $totalRefunded = $invoice->payments->sum('refunded_amount');
        $netPaid = $totalPaid - $totalRefunded + $payment->amount;

        if ($netPaid >= $invoice->amount) {
            return $this->updateMikrotik($payment);
        }
        return ['success' => true];
    }


    private function processPaylaterPayment(Payment $payment)
    {

        return $this->updateMikrotik($payment);
    }
    /*
    private function processBankTransferPayment(Payment $payment)
    {
        return ['success' => true];
    }
        */

    private function processMidtransPayment(Payment $payment)
    {
        //
    }

    private function processTripayPayment(Payment $payment)
    {
        $invoice = $payment->invoice;
        $totalPaid = $invoice->payments->sum('amount');
        $totalRefunded = $invoice->payments->sum('refunded_amount');
        $netPaid = $totalPaid - $totalRefunded + $payment->amount;

        if ($netPaid >= $invoice->amount) {
            return $this->updateMikrotik($payment);
        }
        return ['success' => true];
    }

    private function updateMikrotik($payment)
    {
        $retries = 0;
        while ($retries < $this->maxRetries) {
            try {
                $updateMikrotikResult = (new MikrotikPaymentService())->mikrotik_payment_process($payment);
                if (!$updateMikrotikResult['success']) return [
                    'success' => false,
                    //'message' => throw new \Exception($updateMikrotikResult['message'])
                    'message' => $updateMikrotikResult['message']
                ];

                return ['success' => true];
            } catch (\Exception $e) {
                $retries++;
                Log::warning('Update status payment mikrotik failed', [
                    'mikrotik' => $payment->invoice->customer_paket->paket->mikrotik->name,
                    'invoice_id' => $payment->invoice->id,
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);

                if ($retries >= $this->maxRetries) {
                    Log::error('Update status payment mikrotik failed after max retries (' . $this->maxRetries . ')', [
                        'mikrotik' => $payment->invoice->customer_paket->paket->mikrotik->name,
                        'invoice_id' => $payment->invoice->id,
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    //throw $e;
                    return ['success' => false, 'message' => $e->getMessage()];
                }

                sleep($this->retryDelay);
            }
        }
    }

    /*
    private function updateInvoiceStatus(Payment $payment, $amount, $payment_method, $teller = 'tripay')
    {
       if ( Websystem::first()->isolir_driver == 'mikrotik'){
                $updateMikrotikResult = (new MikrotikPaymentService())->mikrotik_payment_comment($invoice, $comment);
                if (!$updateMikrotikResult['success']) return [
                    'success' => false,
                    'message' => throw new \Exception($updateMikrotikResult['message'])
                ];

        $payment->save();
        $totalPaid = $amount + $invoice->payments->sum('amount');
        $totalBill = $invoice->total_amount - $invoice->special_discount;
        if ($totalPaid > $totalBill) return ['success' => false, 'message' => 'Amoount greather than bill.'];
        if ($totalPaid == $totalBill) {
            $invoice->status = 'paid';
            $invoice->customer_paket->paylater_date = null;
        } elseif ($totalPaid < $totalBill) {
            if ($invoice->status != 'paylater') {
                $invoice->status = 'partially_paid';
            }
        }
        $invoice->teller_name = $teller;
        $invoice->paid_at = now();
        $invoice->save();
        $this->payment_history($invoice, $payment, 'success', $input);
        return ['success' => true];
    }

    private function updateInvoicePaylaterStatus(Invoice $invoice, $input)
    {
        //  dd($input);
        $invoice->status = 'paylater';
        $invoice->customer_paket->paylater_date = $input['paylaterDate'];
        $invoice->teller_name = Auth::user()->full_name;
        $invoice->save();
        return ['success' => true];
    }

    private function payment_history($invoice, $payment, $status, $input)
    {

        $paymentHistory = new PaymentHistory([
            'payment_id' => $invoice->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_paket->user->user_customer->customer_id,
            'amount' => $payment->amount ?? 0,
            'payment_method' => $input['selectedPaymentMethode'],
            'paylater_date' => $input['paylaterDate'] ?? null,
            'transaction_id' => $payment->transaction_id ?? null,
            'status' => $status,
            'notes' => $invoice->note,
        ]);
        $paymentHistory->save();
    }
        */
}
