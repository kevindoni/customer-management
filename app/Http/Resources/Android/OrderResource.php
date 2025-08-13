<?php

namespace App\Http\Resources\Android;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'invoiceId' => $this->invoice->id,
            'paket' => $this->invoice->customer_paket->paket->name,
            'orderNumber' => $this->reference,
            'invoiceNumber' => $this->merchant_ref,
            'period' => Carbon::parse($this->invoice->periode)->format('F Y'),
            'startPeriod' => Carbon::parse($this->invoice->start_periode)->format('d F Y'),
            'endPeriod' => Carbon::parse($this->invoice->end_periode)->format('d F Y'),
            'expiredTime' => Carbon::parse($this->expired_time)->format('d F Y'),
            'amount' => $this->amount,
            'status' => $this->status,
            'payCode' => $this->pay_code
        ];
    }
}
