<div>
    @if ($mikrotikOnline)
        <div class="w-full">
            <x-select wire:model.live="selectedInterface" class="block mt-1 w-full" id="interface">
                @foreach ($interfaces as $interface)
                    <option value="{{ $interface['name'] }}">
                        {{ $interface['name'] }}
                    </option>
                @endforeach
            </x-select>
            <div wire:poll.10s="routerTraffic">
                <x-mikrotik-chart.traffic-monitoring-chart :chart-id="$mikrotik->slug" />

            </div>
        </div>
    @else
        <flux:badge color="red">Offline</flux:badge>
    @endif

</div>
