<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\ProcessSubscriptionBillingJob;
use App\Services\Billings\BillingService;
use Illuminate\Support\Facades\Log;


class ProcessSubscriptionBilling extends Command
{
    protected $signature = 'invoices:process-subscription-billing';
    protected $description = 'Processing create new invoices';

    protected $billingService;

    public function __construct()
    {
        parent::__construct();
        $this->billingService = new BillingService();
    }

    public function handle()
    {
        if (cache()->get('processing_subscription_billing')) {
            $this->warn('Processing create new invoices is already running');
            return Command::FAILURE;
        }
        
        cache()->put('processing_subscription_billing', true, 60); // Lock for 60 minutes

        try {

            $this->info('Processing create new invoices...');
            dispatch(new ProcessSubscriptionBillingJob())->onQueue('default');
            cache()->forget('processing_subscription_billing');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            cache()->forget('processing_subscription_billing');
            $this->error("Error processing reminders: " . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
