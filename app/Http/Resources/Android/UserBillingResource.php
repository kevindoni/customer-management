<?php

namespace App\Http\Resources\Android;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserBillingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->order){
            $orderId = $this->order->id;
        } else {
            $orderId = null;
        }
        return [
            'id' => $this->id,
            'orderId' => $orderId,
            'paket' => $this->customer_paket->paket->name,
            'invoiceNumber' => $this->invoice_number,
            'period' => Carbon::parse($this->periode)->format('F Y'),
            'startPeriod' => Carbon::parse($this->start_periode)->format('d F Y'),
            'endPeriod' => Carbon::parse($this->end_periode)->format('d F Y'),
            'duedate' => Carbon::parse($this->due_date)->format('d F Y'),
            'amount' => $this->amount,
            'status' => $this->status
        ];
    }
}
