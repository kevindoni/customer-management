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
use App\Models\Websystem;

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

                Log::info('ProcessSubscriptionBillingJob..........');
                $billingService->generateInvoiceFromLatestInvoice($customerPaket);
            });
    }
}
