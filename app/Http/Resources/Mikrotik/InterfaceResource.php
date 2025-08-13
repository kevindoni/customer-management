<?php

namespace App\Http\Resources\Mikrotik;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterfaceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['.id'],
            'name' => $this['name'],
            'type' => $this['type'],

        ];
    }
}
