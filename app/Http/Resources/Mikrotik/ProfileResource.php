<?php

namespace App\Http\Resources\Mikrotik;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'local-address' => $this['local-address'] ?? null,
            'remote-address' => $this['remote-address'] ?? null,
            'bridge-learning' => $this['bridge-learning'] ?? null,
            'session-timeout' => $this['session-timeout'] ?? null,
            'idle-timeout' => $this['idle-timeout'] ?? null,
            'use-ipv6' => $this['use-ipv6'] ?? null,
            'use-mpls' => $this['use-mpls'] ?? null,
            'use-compression' => $this['use-compression'] ?? null,
            'use-encryption' => $this['use-encryption'] ?? null,
            'only-one' => $this['only-one'],
            'change-tcp-mss' => $this['change-tcp-mss'] ?? null,
            'use-upnp' => $this['use-upnp'] ?? null,
            'rate-limit' => $this['rate-limit'] ?? null,
            'insert-queue-before' => $this['insert-queue-before'] ?? null,
            'parent-queue' => $this['parent-queue'] ?? null,
            'queue-type' => $this['queue-type'] ?? null,
            'address-list' => $this['address-list'] ?? null,
            'dns-server' => $this['dns-server'] ?? null,
            'on-up' => $this['on-up'] ?? null,
            'on-down' => $this['on-down'] ?? null,
            'default' => $this['default'] ?? null,
            'comment' => $this['comment'] ?? null

        ];
    }
}
