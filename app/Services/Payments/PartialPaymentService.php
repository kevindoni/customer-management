<?php

namespace App\Services\Payments;

use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use App\Models\Billings\Payment;
use App\Models\Billings\UserTellerPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\Mikrotiks\MikrotikPppService;
use App\Services\WhatsappGateway\WhatsappNotificationService;
use Illuminate\Support\Str;

class PartialPaymentService
{
    protected $paymentService, $whatsappNotificationService;
    private $maxRetries = 3;
    private $retryDelay = 5; // seconds

    public function __construct(PaymentService $paymentService, WhatsappNotificationService $whatsappNotificationService)
    {
        $this->paymentService = $paymentService;
        $this->whatsappNotificationService = $whatsappNotificationService;
    }

    public function processPartialPayment(Invoice $invoice, float $amount, $paymentMethod, $bank = null, $paylaterDate = null, $teller)
    {

        $remainingAmount = ($invoice->amount - $invoice->discount + $invoice->tax) - $invoice->payments()->whereReconciliationStatus('partial')->whereNull('refund_status')->sum('amount');
        if ($paymentMethod != 'paylater' && $amount <= 0 || $amount > $remainingAmount) {
            return [
                'success' => false,
                'message' =>  'Invalid partial payment amount.'
            ];
        }


        DB::beginTransaction();
        try {
            $user = $invoice->customer_paket->user;
            $uuid = Str::orderedUuid()->toString();

            $payment = new Payment([
                'id' => $uuid,
                'user_customer_id' => $user->user_customer->id,
                'customer_name' => $user->full_name,
                'customer_address' => $user->user_address->address,
                'invoice_id' => $invoice->id,
                'amount' => $amount,
                'payment_date' => now(),
                'transaction_id' => 'TR-' . Carbon::now()->format('dmY') . strtoupper(uniqid()),
                'payment_method' => $paymentMethod,
                'paylater_date' => $paylaterDate,
                'bank' => $bank,
                'teller' => $teller,
            ]);



            $paymentResult = $this->paymentService->processPayment($payment, $paymentMethod);

            //Log::info($paymentMethod);
            if ($paymentResult['success']) {
                $payment->save();
                $userTeller = new UserTellerPayment([
                    'user_id' => Auth::user()->id
                ]);

                $payment->user_teller_payments()->save($userTeller);
                $this->updateInvoiceStatus($invoice, $payment);
                DB::commit();
                return ['success' => true, 'message' => 'Partial payment processed successfully.', 'payment' => $payment];
            } else {
                DB::rollBack();
                return ['success' => false, 'message' => $paymentResult['message']];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ['success' => false, 'message' => 'Partial payment failed: ' . $e->getMessage()];
        }
    }

    private function updateInvoiceStatus(Invoice $invoice, $payment)
    {
        $totalPaid = $invoice->payments->sum('amount');
        $totalRefunded = $invoice->payments->sum('refunded_amount');
        $netPaid = $totalPaid - $totalRefunded + $payment->amount;

        if ($netPaid >= $invoice->amount) {
            $invoice->status = 'paid';
            $invoice->paid_at = now();
            $invoice->save();


            $customerPaket = $invoice->customer_paket;

            $customerPaket->forceFill([
                'start_date' => $invoice->start_periode,
                'expired_date' => $invoice->end_periode,
                'paylater_date' => null,
            ])->save();

            if (is_null($customerPaket->activation_date)) {
                $customerPaket->forceFill([
                    'activation_date' => $invoice->start_periode,
                ])->save();
            }
            $invoice->customer_paket->setActive();
        } elseif ($totalPaid > 0) {
            $invoice->status = 'partially_paid';
            $invoice->save();
        }

        if ($payment->payment_method == 'paylater') {
            $invoice->customer_paket->forceFill([
                'paylater_date' => $payment->paylater_date,
            ])->save();
            $invoice->customer_paket->setActive();
        }
        $this->whatsappNotificationService->sendPaymentNotification($payment);
    }
}
