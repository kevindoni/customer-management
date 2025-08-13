<?php

namespace App\Handler;


use Illuminate\Support\Facades\Log;

use Spatie\WebhookClient\Models\WebhookCall;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;
use App\Services\WhatsappGateway\WhatsappBootMessageService;


//The class extends "ProcessWebhookJob" class as that is the class
//that will handle the job of processing our webhook before we have
//access to it.

class ProcessWhatsappWebhook extends ProcessWebhookJob
{
    
    public WebhookCall $webhookCall;
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(WhatsappBootMessageService $whatsappBootMessageService)
    {
        $waGateway = WhatsappGatewayGeneral::first();
        if($waGateway->isDisable()) return false;

        $dat = json_decode($this->webhookCall, true);
        $data = $dat['payload'];
      //  Log::info('Receipt: '.$waGateway->bootNumberMessage().' Sender :'.$data['from']);
        if ($data['receipt'] == $waGateway->bootNumberMessage()){
            $whatsappBootMessageService->replyMessage($data['from'], $data['receipt'], $data['message']);
        }

       // Log::info($data);


        //Acknowledge you received the response
        http_response_code(200);
    }
}
