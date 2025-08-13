<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Queue\SerializesModels;
use App\Models\Customers\CustomerPaket;
use App\Models\Websystem;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\Billings\BillingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Carbon;

class ManualProcessBillingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(BillingService $billingService)
    {
        CustomerPaket::query()
            ->where('status', 'active')
            ->where('auto_renew', true)
            ->get()
            ->each(function ($customerPaket) use ($billingService) {
                 $billingService->generateInvoiceFromLatestInvoice($customerPaket);
            });
    }
}
