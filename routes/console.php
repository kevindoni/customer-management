<?php

use App\Models\WhatsappGateway\WhatsappGatewayGeneral;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('invoices:process-subscription-billing')->dailyAt(env('CHECK_SUBSCRIPTION_TIME', '23:50')); //->everyMinute();//->dailyAt('23:59');//->everyMinute();
Schedule::command('invoices:send-reminders')->dailyAt(env('WA_REMINDER_TIME', '10:00')); //->everyMinute();

