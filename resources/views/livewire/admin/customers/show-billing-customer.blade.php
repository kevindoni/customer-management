<section class="w-full">
    @include('partials.show-customer-heading')

    <x-layouts.admin-customer.layout :heading="__('customer.billings')" :subheading="__('customer.billings-description')" :user="$user">

        <x-tables.table class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header class="w-2 px-4 py-2">{{ trans('billing.table.no') }}</x-tables.header>
                    <x-tables.header>{{ trans('billing.table.periode') }}</x-tables.header>
                    <x-tables.header class="w-max">{{ trans('billing.table.bill') }}</x-tables.header>
                    <x-tables.header>{{ trans('billing.table.deadline') }}</x-tables.header>
                    <x-tables.header>{{ trans('billing.table.status') }}</x-tables.header>
                    <x-tables.header>{{ trans('billing.table.action') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @php
                    $websystem = \App\Models\Websystem::first();
                @endphp
                @forelse ($user->invoices->whereNull('paid_at')->sortByDesc('periode') as $key => $billing)
                    <x-tables.row>
                        <x-tables.cell class="text-center">{{ $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>
                            {{ \Carbon\Carbon::parse($billing->periode)->format('M Y') }}
                        </x-tables.cell>

                        <x-tables.cell>
                            <x-billings.bill-amount :billing="$billing" />
                        </x-tables.cell>
                        <x-tables.cell>
                            <x-billings.deadline-bill :billing="$billing" />
                        </x-tables.cell>

                        <x-tables.cell>
                            <x-billings.status-payment :billing="$billing" />
                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex justify-end">
                                <x-billings.bill-action :billing="$billing" />
                            </div>

                        </x-tables.cell>

                    </x-tables.row>
                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=8 class="hidden sm:table-cell">
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('billing.notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                        <x-tables.cell colspan=6 class="sm:hidden">
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('billing.notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse
                @php
                    $billingUnpayment = $user->invoices->whereNull('paid_at');
                @endphp
                @if (count($billingUnpayment))
                    <x-tables.row>

                            @php
                                $whatsappGatewayDisabled = \App\Models\WhatsappGateway\WhatsappGatewayGeneral::first()
                                    ->disabled;
                            @endphp
                            <x-billings.total-bill-footer :user="$user" :whatsappGatewayDisabled="$whatsappGatewayDisabled" />

                    </x-tables.row>
                @endif

            </x-slot>
        </x-tables.table>

        <livewire:admin.billings.modal.billing-payment />
        <livewire:admin.billings.modal.add-discount />
        <livewire:admin.billings.modal.delete-invoice/>
    </x-layouts.admin-customer.layout>

</section>
