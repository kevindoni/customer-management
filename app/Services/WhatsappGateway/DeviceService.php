<?php

namespace App\Services\WhatsappGateway;


use Illuminate\Support\Facades\Cache;
use App\Services\WhatsappGateway\GatewayApiService;
use App\Http\Controllers\API\WhatsappGateway;


class DeviceService
{

    public function getDevices()
    {
        $devices =  Cache::remember('whatsapp-gateway-device', now()->addMinutes(15), function () {
            return (new GatewayApiService())->getRequest(WhatsappGateway::DEVICES);
        });
        if ($devices['success']) {
            return $devices['data']['devices'];
        }
       // Cache::forget('whatsapp-gateway-device');

    }

    public function getOnlineDevices()
    {
        $devices =  $this->getDevices();
        return collect($devices)->where('status', 'Connected')->toArray();
    }
}
