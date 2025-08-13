<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\WhatsappService;
use App\Services\WhatsappGateway\GatewayApiService;
use App\Models\WhatsappGateway\WhatsappGatewayGeneral;

class SendWhatsappJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $number, $message;

    //private string $secretId;
    /**
     * Create a new job instance.
     */
    public function __construct($number, $message)
    {
        $this->number = $number;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(GatewayApiService $gatewayApiService): void
    {
        // $whatsappService->sendNotification($this->number, $this->message);
        $whatsappGateway = WhatsappGatewayGeneral::first();
        $data = [
            'sender' => $whatsappGateway->whatsapp_number_notification,
            'number' => $this->number,
            'message' => $this->message
        ];
        //$response = (new GatewayApiService)->sendNotificationMessage(WhatsappGateway::SEND_MESSAGE, $data);
        $gatewayApiService->sendMessage(WhatsappGateway::SEND_MESSAGE, $data);
    }
}
