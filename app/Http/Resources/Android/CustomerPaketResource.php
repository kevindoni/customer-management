<?php

namespace App\Http\Resources\Android;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerPaketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'paket' => $this->paket->name,
            'price' => $this->price,
            'renewalPeriod' => $this->renewal_period,
            'status' => $this->status,
            'startDate' => $this->start_date,
            'expiredDate' => $this->expired_date,
        ];
    }
}
