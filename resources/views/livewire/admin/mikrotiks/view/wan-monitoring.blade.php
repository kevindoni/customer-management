<section class="w-full">
    @include('partials.show-mikrotik-heading')
    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.wan-monitoring', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik">


        <div class="flex flex-col">

            <div wire:loading.class="opacity-75" class="relative overflow-x-auto">
                @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->get() as $mikrotik)
                    <div class="mt-2 p-4">
                        <livewire:admin.mikrotiks.component.traffic-interface-recording-apexchart :mikrotik="$mikrotik"
                            key="{{ now() }}" />
                    </div>
                @endforeach

            </div>


    </x-layouts.mikrotik-view>
</section>
