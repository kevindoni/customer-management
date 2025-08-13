<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendInvoiceReminderJob;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoices:send-reminders';
    protected $description = 'Send SMS reminders for upcoming invoice due dates';

    // public function handle(WhatsappNotificationService $whatsappNotificationService)
    public function handle()
    {
        $this->info('Sending invoice reminders...');

        try {
            //$whatsappNotificationService->sendUpcomingDueReminders();
            dispatch(new SendInvoiceReminderJob())->onQueue('default');
            $this->info('Upcoming due date reminders sent successfully.');
            Log::info('Upcoming due date reminders sent successfully.');

            //  $billingService->sendOverdueReminders();
            //  $this->info('Overdue reminders sent successfully.');

        } catch (\Exception $e) {
            $this->error('Error sending reminders: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
