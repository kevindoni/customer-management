<?php

namespace App\Handler;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use App\Services\Mikrotiks\MikrotikMonitoringService;
use App\Jobs\Mikrotiks\UpdateStatusPppPaketFromMikrotikJob;

//The class extends "ProcessWebhookJob" class as that is the class
//that will handle the job of processing our webhook before we have
//access to it.

class ProcessMikrotikWebhook extends ProcessWebhookJob
{
    public WebhookCall $webhookCall;
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(MikrotikMonitoringService $mikrotikMonitoringService)
    {
        $dat = json_decode($this->webhookCall, true);
        $data = $dat['payload'];
        // dispatch(new UpdateStatusPppPaketFromMikrotikJob($data))->onQueue('default');
        if ($data['monitoring'] == 'ppp-status') {
            $mikrotikMonitoringService->user_ppp_monitoring($data);
            Cache::forget('active-user-secrets-mikrotik-' . $data['mikrotik_id']);
        } else if ($data['monitoring'] == 'wan-monitoring') {
            $mikrotikMonitoringService->wan_monitoring($data);
        }


        //Acknowledge you received the response
        http_response_code(200);
    }
}
