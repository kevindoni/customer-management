<?php

namespace App\Http\Resources\Mikrotik;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class MikrotikTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'time' => Carbon::parse($this['date'] . ' ' . $this['time'])->format('d F Y, H:i'),
            // 'time' => Carbon::parse('jan-28-2025')->format('d F Y'),
            'date' => $this['date'],
            'timeZone' => $this['time-zone-autodetect'],
            'timeZoneName' => $this['time-zone-name'],
            'gmtOffset' => $this['gmt-offset'],
        ];
    }
}
