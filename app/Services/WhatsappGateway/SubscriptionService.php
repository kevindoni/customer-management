<?php

namespace App\Services\WhatsappGateway;

use App\Traits\WhatsappGatewayTrait;

use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\API\WhatsappGateway;
use App\Services\WhatsappGateway\GatewayApiService;


class SubscriptionService
{
   // use WhatsappGatewayTrait;
    public function getSubscription()
    {
        try {
            return  Cache::remember('whatsapp-gateway-subscription', now()->addMinutes(15), function () {
               /// $subscriptionURL = $this->subscription_url();
                return (new GatewayApiService())->getRequest(WhatsappGateway::SUBSCRIPTION);
                // return json_decode($getSubscription->getBody()->getContents(), true);
            });
            //  dd($subscription);
            //  if ($subscription['success']) {
            //      return $subscription['data']['subscription'];
            //  }
            // Cache::forget('whatsapp-gateway-subscription');
        } catch (\Exception $e) {
        }

        // return [];

    }



}
