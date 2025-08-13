<section class="w-full">
    <x-layouts.general-setting :heading="__('Invoices')">
        <x-layouts.whatsapp-gateway.nav-mobile/>
        <div class="my-6 w-full space-y-6">
            <div wire:loading.class="opacity-75" class="relative overflow-x-auto ">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.no') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.invoice.table.product') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.invoice.table.subscription-plan') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.invoice.table.total-amount') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.invoice.table.expired-date') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.status') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.action') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($invoices as $key => $invoice)
                        <x-tables.row>
                            <x-tables.cell
                                    class="text-center"> {{ $invoice['invoice_number'] }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ $invoice['product'] }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    {{ $invoice['subscription_plan'] }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    @moneyIDR($invoice['total_amount'])
                                </x-tables.cell>
                                <x-tables.cell>
                                    {{ $invoice['expired_date']}}
                                </x-tables.cell>
                                <x-tables.cell>
                                    @if($invoice['status_payment'] == 'Paid')
                                    {{ \Carbon\Carbon::parse($invoice['paid_at'])->format('d M Y, H:i:s') }}
                                    @else
                                    {{ $invoice['status_payment'] }}
                                    @endif
                                </x-tables.cell>
                                <x-tables.cell>
                                    @if($invoice['status_payment'] == 'Paid')
                                    <flux:badge color="lime">Lunas</flux:badge>
                                    @else
                                    <flux:tooltip content="Bayar">
                                        <flux:button wire:click="$dispatch('show-payment-modal',{invoiceID: '{{ $invoice['slug'] }}'})" variant="primary" icon="currency-dollar" style="cursor: pointer"/>
                                    </flux:tooltip>
                                    @endif
                                </x-tables.cell>

                        </x-tables.row>
                        @empty

                        <x-tables.row>
                            <x-tables.cell colspan=7>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('Anda belum memiliki tagihan') }}
                                    </span>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @endforelse

                    </x-slot>
                </x-tables.table>
                @if ($invoices->hasPages())
                    <div class="p-3">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </x-layouts.whatsapp-gateway.layout>
        <livewire:admin.whatsapp-gateway.modal.payment />
    </section>
