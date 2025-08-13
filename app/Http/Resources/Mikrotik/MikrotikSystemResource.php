<?php

namespace App\Http\Resources\Mikrotik;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MikrotikSystemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uptime' => $this['uptime'],
            'version' => $this['version'],
            'freeMemory' => $this['free-memory'],
            'totalMemory' => $this['total-memory'],
            // 'freeMemoryByte' => formatByte($this['free-memory']),
            'useMemoryByte' => formatByte($this['total-memory'] - $this['free-memory']),
            'totalMemoryByte' => formatByte($this['total-memory']),
            'cpu' => $this['cpu'] ?? null,
            'cpuFrequency' => $this['cpu-frequency'] ?? null,
            'cpuCount' => $this['cpu-count'] ?? null,
            'cpuLoad' => $this['cpu-load'] ?? null,
            //    'freeHdd' => $this['free-hdd-space'],
            //    'totalHdd' => $this['total-hdd-space'],
            'arc' => $this['architecture-name'] ?? null,
            'boardName' => $this['board-name'] ?? null,
            'platform' => $this['platform'] ?? null,

            // 'freeHdd' => $this['free-hdd-space'],
            'useHdd' => $this['total-hdd-space'] - $this['free-hdd-space'],
            'totalHdd' => $this['total-hdd-space'],
            'useHddByte' => formatByte($this['total-hdd-space'] - $this['free-hdd-space']),
            'totalHddByte' => formatByte($this['total-hdd-space']),
            //     'hddUsage' => formatByte($this['total-hdd-space'] - $this['free-hdd-space']),
            //  'memoryUsage' => formatByte($this['total-memory'] - $this['free-memory']),
        ];
    }
}
