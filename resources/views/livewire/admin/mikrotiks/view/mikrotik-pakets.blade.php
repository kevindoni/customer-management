<section class="w-full">
    @include('partials.show-mikrotik-heading')
    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.server-name', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik" :heading="__('mikrotik.server-pakets')" :subheading="__('mikrotik.title.dashboard-description')">

        <div class="md:flex justify-between mb-2">
            <div class="flex justify-start gap-2">
            </div>
            <div class="flex justify-between gap-2">
                    <flux:button variant="primary"
                        wire:click="$dispatch('show-export-paket-modal', {mikrotik: '{{ $mikrotik->slug }}'})"
                        size="sm" iconTrailing="arrow-up-tray" style="cursor:pointer">
                        {{ __('mikrotik.button.export-pakets-to-mikrotik') }}
                    </flux:button>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div wire:loading.class="opacity-75">
                <x-tables.table wire:loading.class="opacity-75" class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.no') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.paket-name') }}</x-tables.header>
                            <x-tables.header>{{ trans('paket.table.profile') }}</x-tables.header>
                            <x-tables.header>{{ trans('paket.table.client') }}</x-tables.header>
                            <x-tables.header>{{ trans('paket.table.price') }}</x-tables.header>
                            <x-tables.header class="w-1">
                                <flux:badge size="sm" icon-trailing="power">{{ trans('paket.table.status') }}
                                </flux:badge>
                            </x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">

                        @forelse ($pakets as $paket)
                            <x-tables.row>
                                <x-tables.cell
                                    class="text-center">{{ ($pakets->currentpage() - 1) * $pakets->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>
                                    <flux:badge size="xs" color="lime">
                                        {{ $paket->name }}
                                    </flux:badge>
                                </x-tables.cell>
                                <x-tables.cell>
                                    {{ $paket->mikrotik_ppp_profile_id }} {{ $paket->paket_profile->profile_name }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    {!! trans_choice('paket.label.customer-paket-count', $paket->customer_pakets->count(), [
                                        'count_customer' => $paket->customer_pakets->count(),
                                    ]) !!}
                                </x-tables.cell>
                                <x-tables.cell>
                                    <span class="flex justify-end">
                                        @if ($paket->price == 0)
                                            {{ trans('paket.free') }}
                                        @else
                                            @moneyIDR($paket->price)
                                        @endif
                                    </span>
                                </x-tables.cell>
                                <x-tables.cell>
                                    <div class="inline-flex">
                                        <livewire:components.toogle-button :model="$paket" field="disabled"
                                            dispatch="paket-disable" key="{{ now() }}" />
                                        <span class="ms-2 font-medium text-gray-900 dark:text-gray-300">
                                            @if ($paket->disabled)
                                                {{ trans('system.disable') }}
                                            @else
                                                {{ trans('system.enable') }}
                                            @endif
                                        </span>
                                    </div>
                                </x-tables.cell>

                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=6>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('mikrotik.pakets-notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse

                    </x-slot>
                </x-tables.table>
                @if ($pakets->hasPages())
                    <div class="p-3">
                        {{ $pakets->links() }}
                    </div>
                @endif
            </div>
        </div>
        <livewire:admin.mikrotiks.modal.export-paket-modal />
    </x-layouts.mikrotik-view>
</section>
