<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Models\Billings\Invoice;
use Illuminate\Support\Facades\Log;
use App\Services\GeneralLogServices;
use App\Services\CustomerPaketService;
use Illuminate\Queue\SerializesModels;
use App\Models\Customers\CustomerPaket;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Billings\BillingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\WhatsappGateway\WhatsappNotificationService;

class ProcessSubscriptionBillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(
        BillingService $billingService,
        CustomerPaketService $customerPaketService,
        WhatsappNotificationService $whatsappNotificationService,
        GeneralLogServices $generalLogServices,
        //Invoice $newInvoice
    ) {
        Log::info('ProcessSubscriptionBillingJob...');
        $generalLogServices->job_process('Process Subscription Billing Job...');
        CustomerPaket::where('status', 'active')
            ->where('auto_renew', true)
            ->get()
            ->each(function ($customerPaket) use ($billingService, $customerPaketService, $whatsappNotificationService, $generalLogServices) {
                $autoIsolir = $customerPaket->paket->mikrotik->auto_isolir;
                //Auto Isolir
                if ($autoIsolir && !$autoIsolir->disabled) {
                    Log::info('Processing auto isolir job...');
                    $customerPaketStatus = $customerPaket->checkSubscriptionStatus();

                    //Deactivated if test
                    //Isolir customer
                    if ($customerPaketStatus === 'expired') {
                        //  Log::info($customerPaket->user->full_name . ' set to Expired');

                        $isolirCustomerPaket = false;
                        if ($customerPaket->isPpp()) $isolirCustomerPaket = $customerPaketService->isolir_secret_ppp_on_mikrotik($customerPaket);
                        if ($customerPaket->isPpp()) $isolirCustomerPaket = $customerPaketService->isolir_paket_static_on_mikrotik($customerPaket);


                        $customerDetail = $customerPaket->user->user_address;
                        //Deactivated if test
                        if ($isolirCustomerPaket) {
                            //Send WA Notification to customer
                            if (!is_null($customerDetail->phone)) {
                                $whatsappNotificationService->sendIsolirNotification($customerPaket);
                                $generalLogServices->send_customer_notification($customerPaket, $generalLogServices::NOTIFIACTION_EXPIRED);
                            }
                            //Create log customer expired
                            $generalLogServices->expired($customerPaket);
                        }
                        $whatsappNotificationService->sendAdminNotification($customerPaket->user->full_name . ' ' . $customerDetail->address . ' set to Expired');
                    }
                }


                /*
                $nextBilledAt =  Carbon::parse($customerPaket->next_billed_at);
                $diffMonths = $nextBilledAt->diffInMonths(Carbon::now());

                $intervalDateInvoice = 7;

                for ($i = 0; $i <= (int)$diffMonths; $i++) {
                    if ($newInvoice) {
                        $customerPaket->update([
                            'start_date' => $newInvoice->start_periode,
                            'expired_date' => $newInvoice->end_periode
                        ]);
                    }
                    $renewalPeriod = $customerPaket->getRenewalPeriod();
                    $nextBilledAt = Carbon::parse($nextBilledAt)->add($renewalPeriod);
                    $startInvoicePeriod = Carbon::parse($nextBilledAt)->addDays($intervalDateInvoice);
                    $endInvociePeriod = Carbon::parse($startInvoicePeriod)->add($customerPaket->getRenewalPeriod());
                    $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();

                    if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
                        $newInvoice = $billingService->generateInvoice(
                            $customerPaket,
                            $invoicePeriod,
                            $startInvoicePeriod,
                            $endInvociePeriod,
                            $nextBilledAt
                        );

                        Log::info('NextBill: ' . $nextBilledAt . ', Period: ' . $invoicePeriod . ', StartPeriod: ' . $startInvoicePeriod . ', EndPeriod: ' . $endInvociePeriod);
                    }
                }

                //Create invoice
                // if ($customerPaket->needsBilling()) {
                //Log::info($customerPaket->user->full_name . ' create invoice');
                //$billingService->generateInvoice($customerPaket);

                // }
                */
                Log::info('ProcessSubscriptionBillingJob..........');
                $nextBilledAt =  Carbon::parse($customerPaket->next_billed_at);
                $diffMonths = $nextBilledAt->diffInMonths(Carbon::now());

               // Log::info($invoicePeriod.' - '. $nextBilledAt . ' - ' . $diffMonths);
                for ($i = 0; $i <= (int)$diffMonths; $i++) {
                    $latestInvoices = $customerPaket->invoices()->latest()->first();
                    if ($latestInvoices) {
                        $customerPaket->update([
                            'start_date' => $latestInvoices->start_periode,
                            'expired_date' => $latestInvoices->end_periode
                        ]);
                    }

                    $startInvoicePeriod = Carbon::parse($latestInvoices->end_periode)->subDay();
                   $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();


                    if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
                        Log::info($customerPaket->user->full_name.' need invoice.');
                        $billingService->generateInvoice(
                            $customerPaket
                        );
                    }
                }
            });
    }
}
