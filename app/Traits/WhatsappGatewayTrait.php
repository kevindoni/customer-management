<?php

namespace App\Traits;


trait WhatsappGatewayTrait
{
    public function getBarcode($apikey, $device): string
    {
        return 'api/generate-qrcode?api_key=' . $apikey . '&device=' . $device;
    }

}
