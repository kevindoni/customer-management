<?php

namespace App\Services\WhatsappGateway;

use App\Models\Bank;
use App\Models\User;
use App\Traits\Billing;
use App\Jobs\SendWhatsappJob;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsappGateway\WhatsappService;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;
use App\Models\WhatsappGateway\WhatsappNotificationMessage;

class WhatsappNotificationService
{
    use Billing;
    protected $whatsappService;
    public function __construct(
        WhatsappService $whatsappService = null
    ) {
        $this->whatsappService = $whatsappService ?? new WhatsappService();
    }

    private function delayProcess()
    {
        if (env('QUEUE_CONNECTION') === 'database'){
            return DB::table('jobs')->count()*10;
        } else {
            return 0;
        }

    }
    private function isEnable()
    {
        return !WhatsappGatewayGeneral::first()->disabled;
    }
    private function getDefaultMessage($slug)
    {
        return WhatsappNotificationMessage::whereSlug($slug)
            ->where('disabled', false)
            ->value('message');
    }

    private function getAccountBank()
    {
        $account_banks_text = '';
        $account_banks = Bank::where('disabled', false)
            ->get();

        if (count($account_banks)) {
            $i = 0;
            foreach ($account_banks as $account_bank) {
                $i++;
                $account_banks_text .= $i . '. ' . $account_bank->bank_name . ' - ' . $account_bank->account_number . ' - ' . $account_bank->account_name . '%0a';
            }
        }
        return $account_banks_text;
    }

    public function sendUpcomingDueReminders($customerPaket, $interval_day)
    {
        if ($this->isEnable()) {
            $i = 0;
            $pakets = '';
            $message = $this->getDefaultMessage('warning_bill');
            if ($message) {
                $totalAllBills = 0;
                $billing_unpayments = $customerPaket->invoices->where('status', '!=', 'paid');
                foreach ($billing_unpayments as $billing_unpayment) {
                    $billing_unpayment->forceFill([
                        'last_reminder_date' => Carbon::now()
                    ])->save();
                    $i++;
                    $periode = Carbon::parse($billing_unpayment->start_periode)->format('d F Y - ') . Carbon::parse($billing_unpayment->end_periode)->format('d F Y');
                    $totalPaid = $billing_unpayment->payments->sum('amount');
                    $totalRefunded = $billing_unpayment->payments->sum('refunded_amount');
                    $netPaid = $totalPaid - $totalRefunded;
                    $totalBill = $billing_unpayment->amount - $netPaid;

                    if ($billing_unpayment->payments()->where('payment_method', 'paylater')->count()) {
                        $payment = $billing_unpayment->payments()->where('payment_method', 'paylater')->latest()->first();
                        $detail_bill = trans('whatsapp-gateway.wa-message.notif-lessthan-deadline-payment-with-pay-later', [
                            'periode' => $periode,
                            'bill' => number_format($totalBill, 2),
                            'deadline' => Carbon::parse($billing_unpayment->due_date)->format('d F Y'),
                            'paylater' => Carbon::parse($payment->paylater_date)->format('d F Y'),
                            'invoice_number' => $billing_unpayment->invoice_number
                        ]) . '%0a';
                    } else {
                        $detail_bill = trans('whatsapp-gateway.wa-message.notif-lessthan-deadline-payment', [
                            'periode' => $periode,
                            'bill' => number_format($totalBill, 2),
                            'deadline' => Carbon::parse($billing_unpayment->due_date)->format('d F Y'),
                            'invoice_number' => $billing_unpayment->invoice_number
                        ]) . '%0a';
                    }
                    $pakets .= $i . '. ' . $detail_bill;
                    $totalAllBills += $totalBill;
                }

                $deadline = $billing_unpayments->toQuery()->latest()->first()->due_date;
                if ($interval_day == 0) {
                    $day = trans('whatsapp-gateway.wa-message.today');
                } else if ($interval_day == 1) {
                    $day = trans('whatsapp-gateway.wa-message.tomorrow');
                } else {
                    $day = trans('whatsapp-gateway.wa-message.days-again', ['day' => $interval_day]);
                }
                $replace = [
                    '%name%' => $customerPaket->user->full_name,
					'%email%' => $customerPaket->user->email,
                    '%customer_id%' => $customerPaket->user->user_customer->id,
                    '%address%' => $customerPaket->user->user_address->address,
                    '%day%' => $day,
                    '%pakets%' => $pakets,
                    '%paket-name%' => $billing_unpayments->first()->customer_paket->paket->name,
                    '%total-bill%' => ' Rp. ' . number_format($totalAllBills, 2),
                    '%count-bill%' => $billing_unpayments->count(),
                    //'%deadline-bill%' => Carbon::parse($deadline)->format('d F Y'),
                    '%deadline%' => Carbon::parse($deadline)->format('d F Y'),
                    '%account-bank%' => $this->getAccountBank(),
                ];

                $message =  str_replace(array_keys($replace), $replace, $message);
                Log::info($message);
                $receive = $customerPaket->customer_billing_address->phone;
                dispatch(new SendWhatsappJob(
                    $receive,
                    $message
                ))->delay($this->delayProcess());
                //(new WhatsappService())->sendNotification($receive, $message);
            }
        }
    }

    public function sendIsolirNotification($customerPaket)
    {
        if ($this->isEnable()) {
            $message = $this->getDefaultMessage('isolir_paket');
            if ($message) {
                $i = 0;
                $pakets = '';
                $totalAllBills = 0;
                $billing_unpayments = $customerPaket->invoices->where('status', '!=', 'paid');
                foreach ($billing_unpayments as $billing_unpayment) {
                    $i++;
                    $periode = Carbon::parse($billing_unpayment->start_periode)->format('d F Y - ') . Carbon::parse($billing_unpayment->end_periode)->format('d F Y');
                    $totalPaid = $billing_unpayment->payments->sum('amount');
                    $totalRefunded = $billing_unpayment->payments->sum('refunded_amount');
                    $netPaid = $totalPaid - $totalRefunded;
                    $totalBill = $billing_unpayment->amount - $netPaid;

                    if ($billing_unpayment->payments()->where('payment_method', 'paylater')->count()) {
                        $payment = $billing_unpayment->payments()->where('payment_method', 'paylater')->latest()->first();
                        $detail_bill = trans('whatsapp-gateway.wa-message.notif-lessthan-deadline-payment-with-pay-later', [
                            'periode' => $periode,
                            'bill' => number_format($totalBill, 2),
                            'deadline' => Carbon::parse($billing_unpayment->due_date)->format('d F Y'),
                            'paylater' => Carbon::parse($payment->paylater_date)->format('d F Y'),
                            'invoice_number' => $billing_unpayment->invoice_number
                        ]) . '%0a';
                    } else {
                        $detail_bill = trans('whatsapp-gateway.wa-message.notif-lessthan-deadline-payment', [
                            'periode' => $periode,
                            'bill' => number_format($totalBill, 2),
                            'deadline' => Carbon::parse($billing_unpayment->due_date)->format('d F Y'),
                            'invoice_number' => $billing_unpayment->invoice_number
                        ]) . '%0a';
                    }
                    $pakets .= $i . '. ' . $detail_bill;
                    $totalAllBills = +$totalBill;
                }
                $latestInvoiceUnpayment = $billing_unpayments->toQuery()->latest()->first();
                if ($latestInvoiceUnpayment){
                    $deadline = $latestInvoiceUnpayment->due_date;
                    $replace = [
                        '%name%' => $customerPaket->user->full_name,
    					'%email%' => $customerPaket->user->email,
                        '%customer_id%' => $customerPaket->user->user_customer->id,
                        '%address%' => $customerPaket->user->user_address->address,
                        '%pakets%' => $pakets,
                        '%paket-name%' => $billing_unpayments->first()->customer_paket->paket->name,
                        '%total-bill%' => ' Rp. ' . number_format($totalAllBills, 2),
                        '%count-bill%' => $billing_unpayments->count(),
                        '%deadline%' => Carbon::parse($deadline)->format('d F Y'),
                        '%account-bank%' => $this->getAccountBank(),
                    ];

                    $receive = $customerPaket->customer_billing_address->phone;
                    $message =  str_replace(array_keys($replace), $replace, $message);
                    dispatch(new SendWhatsappJob(
                        $receive,
                        $message
                    ))->delay($this->delayProcess());
                }

                //(new WhatsappService())->sendNotification($receive, $message);
            }
        }
    }

    public function sendPaymentNotification($payment)
    {
        if ($this->isEnable()) {
            $customerPaket = $payment->invoice->customer_paket;
            $receive = $customerPaket->customer_billing_address->phone;
            $replace = [
                '%name%' =>  $customerPaket->user->full_name,
				'%email%' => $customerPaket->user->email,
                '%address%' => $customerPaket->user->user_address->address,
                '%customer_id%' => $customerPaket->user->user_customer->id,
                '%invoice_number%' =>  $payment->invoice->invoice_number,
                '%transaction_id%' =>  $payment->transaction_id,
                '%paket%' => $customerPaket->paket->name,
                '%periode%' =>  Carbon::parse($payment->invoice->start_periode)->format('d F Y - ') . Carbon::parse($payment->invoice->end_periode)->format('d F Y'),
                '%bill%' => 'Rp.' . number_format($payment->amount, 2),
                '%teller%' => $payment->teller,
                '%payment_time%' =>  Carbon::parse($payment->payment_date)->format('d F Y H:i:s'),
            ];
            if ($payment['payment_method'] === 'paylater') {
                $adminMessage = $this->getDefaultMessage('notif_admin_paylater');
                $customerMessage = $this->getDefaultMessage('paylater');
                $replace['%payment_methode%'] = 'Pay Later';
                $replace['%paylater%'] = Carbon::parse($payment->paylater_date)->format('d F Y');
            } else {
                $adminMessage = $this->getDefaultMessage('notif_admin_payment');
                $customerMessage = $this->getDefaultMessage('payment');
                $replace['%payment_methode%'] = $payment['payment_method'] === 'bank_transfer' ? $payment['payment_method'] . ' ' . $payment['bank'] . '.' : $payment['payment_method'] . '.';
            }
            if ($receive && $customerMessage) {
                $message =  str_replace(array_keys($replace), $replace, $customerMessage);
                dispatch(new SendWhatsappJob(
                    $receive,
                    $message
                ))->delay($this->delayProcess());
                // (new WhatsappService())->sendNotification($receive, $message);
            }

            if ($adminMessage) {
                $adminMessage = str_replace(array_keys($replace), $replace, $adminMessage);
                $this->sendAdminNotification($adminMessage);
            }
        }
    }

    public function sendUnpaymentNotification($payment)
    {
        if ($this->isEnable()) {
            $customerPaket = $payment->invoice->customer_paket;
            $receive = $customerPaket->customer_billing_address->phone;

            $replace = [
                '%name%' =>  $customerPaket->user->full_name,
				'%email%' => $customerPaket->user->email,
                '%address%' => $customerPaket->user->user_address->address,
                '%customer_id%' => $customerPaket->user->user_customer->id,
                '%invoice_number%' =>  $payment->invoice->invoice_number,
                '%transaction_id%' =>  $payment->transaction_id,
                '%paket%' => $customerPaket->paket->name,
                '%periode%' =>  Carbon::parse($payment->invoice->start_periode)->format('d F Y - ') . Carbon::parse($payment->invoice->end_periode)->format('d F Y'),
                '%bill%' => 'Rp.' . number_format($payment->amount, 2),
                '%teller%' => $payment->teller,
                '%payment_time%' =>  Carbon::parse($payment->payment_date)->format('d F Y H:i:s'),
            ];

            $customerMessage = $this->getDefaultMessage('unpayment');
            if ($receive && $customerMessage) {

                $message =  str_replace(array_keys($replace), $replace, $customerMessage);
                dispatch(new SendWhatsappJob(
                    $receive,
                    $message
                ))->delay($this->delayProcess());
                //(new WhatsappService())->sendNotification($receive, $message);
            }

            $adminMessage = $this->getDefaultMessage('notif_admin_unpayment');
            Log::info($adminMessage);
            if ($adminMessage) {
                $adminMessage = str_replace(array_keys($replace), $replace, $adminMessage);
                $this->sendAdminNotification($adminMessage);
            }
        }
    }

    public function sendBillsAndIsolirsToAdmin($customerBills, $customerIsolirs)
    {
        if ($this->isEnable()) {
            $adminMessage = $this->getDefaultMessage('notif_admin_bill_and_isolir');
            if ($adminMessage) {
                $replace = [
                    '%customer_bills%' =>  $customerBills,
                    '%customer_isolirs%' => $customerIsolirs,
                ];
                $adminMessage = str_replace(array_keys($replace), $replace, $adminMessage);
                $this->sendAdminNotification($adminMessage);
            }
        }
    }
    public function sendAdminNotification($message)
    {
        $admins = User::whereHas('user_admin')->get();
        foreach ($admins as $admin) {
            if ($admin->user_address->wa_notification && $admin->user_address->phone != null) {
                dispatch(new SendWhatsappJob(
                    $admin->user_address->phone,
                    $message
                ))->delay($this->delayProcess());
                //  (new WhatsappService())->sendNotification($admin->user_address->phone, $message);
            }
        }
    }
}
