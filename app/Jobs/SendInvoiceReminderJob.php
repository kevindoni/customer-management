<?php

namespace App\Jobs;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralLogServices;
use Illuminate\Queue\SerializesModels;
use App\Models\Customers\CustomerPaket;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\WhatsappGateway\WhatsappNotificationService;
//use Spatie\SslCertificate\SslCertificate;

class SendInvoiceReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $iUserBill = 0;
    protected $userBills = '';
    protected $iUserIsolir = 0;
    protected $userIsolirs = '';

    /**
     * Execute the job.
     */
    public function handle(WhatsappNotificationService $whatsappNotificationService, GeneralLogServices $generalLogServices): void
    {
        CustomerPaket::query()
            // ->where('status', 'active')
            ->get()
            ->each(function ($customerPaket) use ($whatsappNotificationService, $generalLogServices) {
                $lastInvoice = $customerPaket->invoices()->latest()->first();
                //Log::info($lastInvoice);
                $customerDetail = $customerPaket->user->user_address;

                $invoices = $customerPaket->invoices->where('status', '!=', 'paid');
                $userCustomer = $customerPaket->user->user_customer;
                $totalCustomerPaid = $userCustomer->payments->sum('amount');
                $totalCustomerRefunded = $userCustomer->payments->sum('refunded_amount');
                $netCustomerPaid = $totalCustomerPaid - $totalCustomerRefunded;
                $totalCustomerBill = $invoices->sum('amount') - $netCustomerPaid;
                // Log::info($customerPaket->user->full_name.' Need reminder: '.$lastInvoice->needsReminder());
                //Send reminder duedate invoices to custmer
                if (!is_null($lastInvoice) && $invoices->count()) {
                    Log::info($customerPaket->user->full_name . ' Need reminder: ' . $lastInvoice->needsReminder());
                    if ($lastInvoice->needsReminder()) {
                        //Log::info($lastInvoice);
                        $nowDate = new DateTime(Carbon::now()->startOfDay());
                        $deadline = new DateTime($lastInvoice->due_date);
                        $interval_day = $nowDate->diff($deadline)->format('%a');
                        if ($customerDetail->wa_notification  && $customerDetail->phone != null) {
                            $whatsappNotificationService->sendUpcomingDueReminders($customerPaket, $interval_day);
                            $generalLogServices->send_customer_notification($customerPaket, $generalLogServices::NOTIFIACTION_REMINDER_PAYMENT);
                        }

                        $this->iUserBill++;
                        //Log::info('User Bill: ' . $this->iUserBill);
                        $this->userBills .= $this->iUserBill . '. ' . $customerPaket->user->full_name . ', tagihan: ' . $invoices->count() . ' bulan ( Rp. ' . number_format($totalCustomerBill, 2) . '), Deadline: -' . $interval_day . ' hari%0a';
                    }
                }

                //Send reminder expired to custmer
                if ($customerPaket->status === 'expired' && $invoices->count()) {
                    $this->iUserIsolir++;
                    $this->userIsolirs .= $this->iUserIsolir . '. ' . $customerPaket->user->full_name . ', tagihan: ' . $invoices->count() . ' bulan (Rp. ' . number_format($totalCustomerBill, 2) . '), Deadline: ' . Carbon::parse($invoices->last()->first()->due_date)->format('d F Y') . '%0a';
                }
            });

        //send message to admin
        if ($this->iUserBill > 0 || $this->iUserIsolir > 0) {
            $whatsappNotificationService->sendBillsAndIsolirsToAdmin($this->userBills, $this->userIsolirs);
        }
    }
}
