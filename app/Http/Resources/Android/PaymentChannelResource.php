<?php

namespace App\Http\Resources\Android;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentChannelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'paymentChannelCode' => $this['code'],
            'paymentChannelName' => $this['name'],
        ];
    }
}
