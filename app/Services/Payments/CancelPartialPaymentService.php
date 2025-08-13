<?php

namespace App\Services\Payments;

use Illuminate\Support\Carbon;
use App\Models\Billings\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Billings\PaymentHistory;
use App\Services\WhatsappGateway\WhatsappNotificationService;

class CancelPartialPaymentService
{
    private $maxRetries = 3;
    private $retryDelay = 5; // seconds
    protected $whatsappNotificationService;

    private $supportedMethods = [
        'cash',
        'paylater',
        'bank_transfer',
    ];

    public function __construct(WhatsappNotificationService $whatsappNotificationService)
    {
        $this->whatsappNotificationService = $whatsappNotificationService;
    }

    private function validatePaymentMethod($method)
    {
        return in_array($method, $this->supportedMethods);
    }

    public function processCancelPayment(Payment $lastPaymentInvoice, float $amount)
    {
        if (!$this->validatePaymentMethod($lastPaymentInvoice->payment_method)) {
            return [
                'success' => false,
                'message' =>  'Unsupported payment method: ' . $lastPaymentInvoice->payment_method
            ];
        }

        if (!$lastPaymentInvoice->isCancelled()) {
            return ['success' => false, 'message' => 'This payment cant cancelled.'];
        }

        if ($amount > $lastPaymentInvoice->amount) {
            return ['success' => false, 'message' => 'Amount cannot exceed the original payment amount.'];
        }

        DB::beginTransaction();
        try {
            $invoice = $lastPaymentInvoice->invoice;
            //$latestInvoice = $lastPaymentInvoice->invoice->customer_paket->invoices->last();
            $this->updateInvoiceStatus($invoice, $lastPaymentInvoice->amount);

            $updateMikrotik = $this->updateMikrotik($lastPaymentInvoice);
            if ($updateMikrotik['success']) {
               // if ($invoice->periode === $latestInvoice->periode) {
                    $paylaterDate = $lastPaymentInvoice ? ($lastPaymentInvoice->payment_method === 'paylater' ? $lastPaymentInvoice->paylater_date : null) : null;
                    $this->updateCustomerPaket($invoice, $paylaterDate);
                //}
            } else {
                DB::rollBack();
                return $updateMikrotik;
            }

            PaymentHistory::wherePaymentId($lastPaymentInvoice->id)->delete();
            $this->whatsappNotificationService->sendUnpaymentNotification($lastPaymentInvoice);

            $payments = Payment::whereInvoiceId($lastPaymentInvoice->invoice_id)->get();
            //Update status payment to partial
            if ($payments->where('reconciliation_status', 'reconciled')->count()) {
                foreach ($payments->where('reconciliation_status', 'discrepancy') as $payment_invoice) {
                    $payment_invoice->update([
                        'reconciliation_status' => 'partial'
                    ]);
                }
            }

            $lastPaymentInvoice->delete();
            DB::commit();
            return ['success' => true, 'message' => 'Cancel payment processed successfully.'];

        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Cancel payment failed: ' . $e->getMessage()];
        }
    }

    private function updateInvoiceStatus($invoice, $amount)
    {
        $totalPaid = $invoice->payments->sum('amount');
        $totalRefunded = $invoice->payments->sum('refunded_amount');
        $netPaid = $totalPaid - $totalRefunded - $amount;
        if ($netPaid <= 0) {
            $invoice->status = 'pending';
        } elseif ($netPaid < $invoice->amount) {
            $invoice->status = 'partially_paid';
        }
        $invoice->paid_at = null;
        $invoice->save();
    }

    private function updateCustomerPaket($invoice, $paylaterDate)
    {
        $customerPaket = $invoice->customer_paket;
        $startPeriode = $invoice->start_periode;
        $customerPaket->forceFill([
            'start_date' => Carbon::parse($startPeriode)->sub($customerPaket->getRenewalPeriod()),
            'expired_date' => $startPeriode,
            'paylater_date' => $paylaterDate
        ])->save();
        $customerPaket->setSubscriptionStatus();
    }

    private function updateMikrotik($payment)
    {
        $retries = 0;
        while ($retries < $this->maxRetries) {
            try {
                $updateMikrotikResult = (new MikrotikPaymentService())->mikrotik_unpayment_process($payment);
                if (!$updateMikrotikResult['success']) return [
                    'success' => false,
                    //'message' => throw new \Exception($updateMikrotikResult['message'])
                    'message' => $updateMikrotikResult['message']
                ];

                return ['success' => true];
            } catch (\Exception $e) {
                $retries++;
                Log::warning('Update status cancel payment mikrotik failed', [
                    'mikrotik' => $payment->invoice->customer_paket->paket->mikrotik->name,
                    'invoice_id' => $payment->invoice->id,
                    'payment_id' => $payment->id,
                    'error' => $e->getMessage()
                ]);

                if ($retries >= $this->maxRetries) {
                    Log::error('Update status cancel payment mikrotik failed after max retries', [
                        'mikrotik' => $payment->invoice->customer_paket->paket->mikrotik->name,
                        'invoice_id' => $payment->invoice->id,
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw $e;
                }

                sleep($this->retryDelay);
            }
        }
    }
}
