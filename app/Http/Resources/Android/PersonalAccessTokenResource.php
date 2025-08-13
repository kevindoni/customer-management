<?php

namespace App\Http\Resources\Android;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalAccessTokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'accessToken' => $this->token['login_token'] ?? null,
            'refreshToken' => $this->token['login_token'] ?? null,
        ];
    }
}
