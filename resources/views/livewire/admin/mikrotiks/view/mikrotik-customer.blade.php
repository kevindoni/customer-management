<section class="w-full">
    @include('partials.show-mikrotik-heading')
    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.server-name', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik" :heading="__('mikrotik.server-customers')" :subheading="__('mikrotik.title.dashboard-description')">
        <div class="md:flex justify-between mb-2">
            <div class="flex justify-start gap-2">
            </div>
            <div class="flex justify-between gap-2">
                <flux:button variant="primary"
                    wire:click="$dispatch('show-export-customer-modal', {mikrotik: '{{ $mikrotik->slug }}'})"
                    size="sm" iconTrailing="arrow-up-tray" style="cursor:pointer">
                    {{ __('mikrotik.button.export-customer-to-mikrotik') }}
                </flux:button>
            </div>
        </div>

            <div wire:loading.class="opacity-75">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.no') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.name') }}</x-tables.header>
                            <x-tables.header>{{ trans('user.table.address') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.pakets') }}</x-tables.header>
                            <x-tables.header>{{ trans('customer.table.internet-service') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($customerPakets as $customer_paket)
                            <x-tables.row>
                                <x-tables.cell>{{ ($customerPakets->currentpage() - 1) * $customerPakets->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ $customer_paket->user->full_name }}
                                </x-tables.cell>
                                <x-tables.cell>{{ $customer_paket->user->user_customer->address }}</x-tables.cell>
                                <x-tables.cell>{{ $customer_paket->paket->name }}</x-tables.cell>
                                <x-tables.cell>{{ $customer_paket->internet_service->name }}</x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=5>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('customer.notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse
                    </x-slot>
                </x-tables.table>
                @if ($customerPakets->hasPages())
                    <div class="p-3">
                        {{ $customerPakets->links() }}
                    </div>
                @endif
            </div>

        <livewire:admin.mikrotiks.modal.export-customer-modal />
    </x-layouts.mikrotik-view>
</section>
