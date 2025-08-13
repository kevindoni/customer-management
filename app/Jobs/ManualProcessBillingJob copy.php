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
                $nextBilledAt =  Carbon::parse($customerPaket->next_billed_at);
                $diffMonths = $nextBilledAt->diffInMonths(Carbon::now());

                for ($i = 0; $i <= (int)$diffMonths; $i++) {
                    $latestInvoices = $customerPaket->invoices()->latest()->first();
                   // dd($latestInvoices);
                    if ($latestInvoices) {
                        $customerPaket->update([
                            'start_date' => $latestInvoices->start_periode,
                            'expired_date' => $latestInvoices->end_periode
                        ]);

                        $startInvoicePeriod = Carbon::parse($latestInvoices->end_periode);
                        $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();
                    } else {
                       // dd($customerPaket->activation_date);
                       $intervalInvoiceDay = Websystem::first()->different_day_create_billing;
                        $startInvoicePeriod = $customerPaket->activation_date ?? Carbon::parse($customerPaket->next_billed_at)->addDays($intervalInvoiceDay);
                        $invoicePeriod = Carbon::parse($startInvoicePeriod)->startOfMonth();
                    }

                    //dd($invoicePeriod);


                    if ($customerPaket->needsBilling($invoicePeriod, $nextBilledAt)) {
                        $billingService->generateInvoice(
                            $customerPaket
                        );
                    }
                }
            });
    }
}
