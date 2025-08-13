<div>
    @if ($mikrotikOnline && $mikrotik->mikrotik_monitoring)
        <div class="mt-2">
            @if($mikrotik->wan_monitorings()->count())
            <div class="pt-1 pb-1">
                <div class="mb-4">
                   
                    <div class="flex gap-2">
                        <flux:input wire:model="startDate" type="date"
                            min="{{ \Carbon\Carbon::parse($mikrotik->wan_monitorings()->first()->created_at)->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" label="Start Date" />
                        <flux:input wire:model="endDate" type="date" min="{{ $startDate }}" :disabled="$startDate ? false:true"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" label="End Date" />
                        <flux:select wire:model.change="limit" name="limit" :label="__('Show Traffic')">
                            <flux:select.option value="25">25</flux:select.option>
                            <flux:select.option value="50">50</flux:select.option>
                            <flux:select.option value="100">100</flux:select.option>
                            <flux:select.option value="200">200</flux:select.option>
                        </flux:select>
                    </div>
                    
                </div>
                <div wire:poll wire:ignore wire:key={{ $chart_id }}>
                    @if ($chart)
                        {!! $chart->container() !!}
                    @endif
                </div>
                @if ($chart)
                    @push('scripts')
                        {!! $chart->scriptTraffic() !!}
                        <script>
                            document.addEventListener('livewire:navigated', (event) => {
                                Livewire.on('chartUpdate', (chartData) => {

                                    chart{!! $chart->id !!}.updateOptions({
                                        xaxis: {
                                            categories: chartData[1]
                                        },
                                    });
                                    chart{!! $chart->id !!}.updateSeries([{
                                            name: 'Tx Rate',
                                            data: chartData[2][1]
                                        },
                                        {
                                            label: chartData[1],
                                            name: 'Rx Rate',
                                            data: chartData[2][0]
                                        },
                                    ])
                                });
                            })
                        </script>
                    @endpush
                @endif
            </div>
            @else
            <flux:subheading>
                Data not found.
            </flux:subheading>
            @endif
    @endif

</div>
