<?php

namespace App\Handler;

use Illuminate\Http\Request;
use App\Models\Billings\Order;
use App\Models\PaymentGateway;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Spatie\WebhookClient\Models\WebhookCall;
use App\Services\Payments\PartialPaymentService;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;
use App\Livewire\Admin\WhatsappGateway\Modal\Payment;

//The class extends "ProcessWebhookJob" class as that is the class
//that will handle the job of processing our webhook before we have
//access to it.

class ProcessTripayWebhook extends ProcessWebhookJob
{
    public WebhookCall $webhookCall;
    public function __construct(WebhookCall $webhookCall)
    {
        $this->webhookCall = $webhookCall;
    }

    public function handle(Request $request, PartialPaymentService $partialPaymentService)
    {
        $dat = json_decode($this->webhookCall, true);
        $data = $dat['payload'];

        if (PaymentGateway::active('tripay')){
            if ($data['is_closed_payment'] == 1) {
                $order = Order::whereMerchantRef($data['merchant_ref'])
                ->wherePaymentGatewayChannel('tripay')
                ->where('status', '!=', 'paid')
                ->wherePaymentMethod($data['payment_method_code'])
                ->whereAmount($data['total_amount'])
                ->first();

                if (!$order) return [
                    'success' => false,
                    'message' => 'Order not found'
                ];

                $invoice = $order->invoice;
               return $partialPaymentService->processPartialPayment(
                    $invoice,
                    $data['total_amount'],
                    'tripay',
                    null,
                    null,
                    'Tripay'
                );
            } else {
                Log::warning('This not payment closed');
            }
        }

        //Acknowledge you received the response
        http_response_code(200);
    }
}
