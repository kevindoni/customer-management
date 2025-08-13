<div class="flex gap-2 flex-col">
    @php
       $whatsappGatewayDisabled = \App\Models\WhatsappGateway\WhatsappGatewayGeneral::first()->disabled;
        $billingUnpayments = $billings->where('status', '!=', 'paid');
    @endphp
    <div class="sm:flex justify-between pt-1 pb-1">
        <div class="justify-start pt-1 pb-1 dark:text-gray-200 text-gray-600 text-xs sm:text-sm">
            <div>
                {!! trans('billing.periode') !!}
                <span class="font-bold ms-1">
                    @if ($search_with_month == 'all-month')
                        All Month
                    @else
                        {{ \Carbon\Carbon::create()->month((int) $search_with_month)->format('F') }}
                    @endif
                </span>
                <span class="font-bold ms-1">
                    @if ($search_with_year == 'all-year')
                        All Year
                    @else
                        {{ \Carbon\Carbon::create()->year((int) $search_with_year)->format('Y') }}
                    @endif
                </span>
            </div>

        </div>
        <div class="justify-end">
            <flux:button.group>
                @if (count($billingUnpayments))
                    <flux:button size="sm" wire:click="$dispatch('show-create-invoices-modal')"
                        style="cursor: pointer;" variant="primary" iconTrailing="printer">
                        {!! trans('billing.button.invoice') !!}
                    </flux:button>
                    <flux:button size="sm" wire:click="$dispatch('export-billing-modal')" style="cursor: pointer;"
                        variant="primary" iconTrailing="printer" disabled>
                        {!! trans('billing.button.billing') !!}
                    </flux:button>
                @endif
                <flux:button size="sm" wire:click="$dispatch('create-new-billings-modal')" style="cursor: pointer;"
                    variant="primary" icon="plus-circle">
                    {!! trans('billing.button.create') !!}
                </flux:button>
                <flux:button size="sm" wire:click="$dispatch('show-reset-next-bill-modal')" style="cursor: pointer;"
                    variant="primary" icon="arrow-path">
                    {!! trans('billing.button.reset-next-bill') !!}
                </flux:button>

                @if (!$whatsappGatewayDisabled)
                    <flux:button size="sm" wire:click="$dispatch('send-notification-modal')" style="cursor: pointer;"
                        variant="success" icon="wa">
                        {{ __('billing.button.send-notification') }}
                    </flux:button>
                @endif
            </flux:button.group>
        </div>
    </div>

    <div class="grid lg:grid-cols-6 gap-4">
        <flux:input wire:model.live.debounce.500ms="search_name" type="text"
            placeholder="{{ trans('billing.ph.search-with-name') }}" clearable/>
        <flux:input wire:model.live.debounce.500ms="search_address" type="text"
            placeholder="{{ trans('billing.ph.search-with-address') }}" clearable/>

        <flux:select wire:model.change="search_with_status">
            <flux:select.option value="pending">{{ trans('billing.status.unpayment') }}</flux:select.option>
            <flux:select.option value="paylater">{{ trans('billing.status.pay-later') }}</flux:select.option>
            <flux:select.option value="overdue">{{ trans('billing.status.overdue') }}</flux:select.option>
            <flux:select.option value="partially_paid">{{ trans('billing.status.partial-paid') }}</flux:select.option>
            <flux:select.option value="paid">{{ trans('billing.status.payment') }}</flux:select.option>
            <flux:select.option value="">{{ trans('billing.ph.all') }}</flux:select.option>
        </flux:select>

        @if($search_with_status === 'paylater' || $search_with_status === 'partially_paid' || $search_with_status === 'paid')
        <flux:select wire:model.change="search_with_teller">
            <flux:select.option value="">{{ trans('billing.ph.search-teller') }}</flux:select.option>
            @foreach ($payments as $payment)
                <flux:select.option value="{{ $payment->teller }}">
                    {{ $payment->teller }}
                </flux:select.option>
            @endforeach
        </flux:select>
        @endif


        <flux:select wire:model.change="search_with_year">
            <flux:select.option value="all-year">{{ __('billing.ph.all-year') }}</flux:select.option>
            @foreach ($years as $year)
                <flux:select.option value="{{ $year->year }}">
                    {{ $year->year }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.change="search_with_month" name="search_with_month">
            <flux:select.option value="all-month">{{ __('billing.ph.all-month') }}</flux:select.option>
            @foreach (array_reverse(\Carbon\CarbonPeriod::create(now()->addMonth(), '1 month', now()->addMonths(12))->toArray()) as $date)
                <flux:select.option value="{{ $date->format('m') }}">
                    {{ $date->format('F') }}
                </flux:select.option>
            @endforeach
        </flux:select>
    </div>

    <div class="flex flex-col md:flex-row gap-2 mt-2">

        <x-input-date-range>
            <x-slot name="start_date" wire:model="startDateDeadline"></x-slot>
            <x-slot name="end_date" wire:model="endDateDeadline"></x-slot>
        </x-input-date-range>

        <flux:button size="sm" wire:click="clearSearch" title="{{ __('Reset') }}" style="cursor: pointer;"
            variant="primary" iconTrailing="x-circle">{{ __('Reset') }}
        </flux:button>
        <flux:modal.trigger name="advance-search">
            <flux:button iconTrailing="magnifying-glass-circle" size="sm" variant="primary" disabled
                style="cursor: pointer;">
                {{ __('Advance Search') }}
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div wire:loading.class="opacity-75">
        <flux:checkbox.group>
        <x-tables.table class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header class="w-2 text-center">{{ trans('billing.table.no') }}</x-tables.header>
                    <x-tables.header class="w-max" sortable wire:click.prevent="sortBy('full_name')"
                        :direction="$sortField === 'full_name' ? $sortDirection : null">{{ trans('billing.table.customer-name') }}</x-tables.header>

                    <x-tables.header class="w-max text-center">{{ trans('billing.table.bill') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @forelse ($users as $key => $user)
                    <x-tables.row>
                        <x-tables.cell
                            class="text-center">{{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>
                            <div class="flex gap-2">
                            <flux:icon.eye variant="solid" class="text-sky-500 dark:text-sky-300 size-4" style="cursor: pointer;" wire:navigate
                                href="{{ route('customer.show', $user->username) }}"
                                title="{{ trans('customer.button.show-customer') }}" />
                                {{ $user->full_name }}
                            </div>
                            <div class="text-xs">
                                {{ $user->user_address->address }}
                            </div>
                        </x-tables.cell>
                        <x-tables.cell>

                            <x-tables.table-1 class="table-fixed">
                                <x-slot name="header">
                                    <x-tables.header-table-1>
                                        #
                                    </x-tables.header-table-1>
                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.customer-paket') }}
                                    </x-tables.header-table-1>
                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.periode') }}
                                    </x-tables.header-table-1>
                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.bill') }}
                                    </x-tables.header-table-1>
                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.deadline') }}
                                    </x-tables.header-table-1>
                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.payment-time') }}
                                    </x-tables.header-table-1>

                                    <x-tables.header-table-1>
                                        {{ trans('billing.table.action') }}
                                    </x-tables.header-table-1>

                                </x-slot>

                                <x-slot name="body">
                                    @php
                                        if ($search_with_status == 'paid') {
                                            $billings = $user->invoices->sortByDesc('periode');
                                        } else {
                                            $billings = $user->invoices->sortBy('periode')->sortBy('customer_paket_id');
                                        }
                                    @endphp

                                    @forelse ($billings as $key => $billing)
                                        <x-tables.row-table-1>
                                            <x-tables.cell-table-1 class="text-center text-xs">
                                                <flux:checkbox wire:model.live="selectedInvoice" wire:key="{{ $billing->id }}" value="{{ $billing->id }}"/>
                                            </x-tables.cell-table-1>
                                                <x-tables.cell-table-1 class="text-center text-xs">
                                                    <flux:text class="text-xs">
                                                        {{ $billing->customer_paket->paket->name.' - '.$billing->customer_paket->internet_service->value }}
                                                    </flux:text>

                                                    <flux:badge size="sm">{{ Str::apa($billing->customer_paket->renewal_period) }}</flux:badge>
                                            </x-tables.cell-table-1>
                                            <x-tables.cell-table-1 class="text-center text-xs">
                                                <flux:tooltip
                                                    content="{{ \Carbon\Carbon::parse($billing->start_periode)->format('d F Y').' - '. \Carbon\Carbon::parse($billing->end_periode)->format('d F Y') }}">
                                                    <div>
                                                        <flux:badge size="sm">
                                                            {{ \Carbon\Carbon::parse($billing->periode)->format('F Y') }}
                                                        </flux:badge>
                                                        <flux:text size="sm">
                                                            {{ \Carbon\Carbon::parse($billing->start_periode)->format('d-m-y') }} - {{ \Carbon\Carbon::parse($billing->end_periode)->format('d-m-y') }}
                                                        </flux:text>


                                                    </div>
                                                </flux:tooltip>


                                            </x-tables.cell-table-1>
                                            <x-tables.cell-table-1>
                                                <x-billings.bill-amount :billing="$billing" />
                                            </x-tables.cell-table-1>
                                            <x-tables.cell-table-1 class="text-center text-xs">
                                                <x-billings.deadline-bill :billing="$billing" />
                                            </x-tables.cell-table-1>
                                            <x-tables.cell-table-1>
                                                <x-billings.status-payment :billing="$billing" />
                                            </x-tables.cell-table-1>
                                            <x-tables.cell-table-1>
                                                <div class="flex justify-end">
                                                    <x-billings.bill-action :billing="$billing" />
                                                </div>
                                            </x-tables.cell-table-1>
                                        </x-tables.row-table-1>
                                    @empty
                                        <x-tables.row-table-1>
                                            <x-tables.cell-table-1 colspan=6>
                                                <div class="flex justify-center items-center">
                                                    <span class="font-medium text-gray-700 text-sm">
                                                        {{ trans('billing.notfound') }}
                                                    </span>
                                                </div>
                                            </x-tables.cell-table-1>
                                        </x-tables.row-table-1>
                                    @endforelse

                                </x-slot>
                                <x-slot name="footer">
                                    <x-billings.total-bill-footer :user="$user" :whatsappGatewayDisabled="$whatsappGatewayDisabled"/>
                                </x-slot>

                            </x-tables.table-1>


                        </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=8>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('billing.notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse
                @if ($users->count())
                 <x-tables.row>
                    <x-tables.cell colspan=2>

                        <div class="sm:flex justify-between pt-1 pb-1">
                            <div class="justify-start pt-1 pb-1 dark:text-gray-200 text-gray-600 text-xs sm:text-sm">
                            </div>
                            <div class="justify-end pt-1 pb-1">
                                {{ trans('billing.label.action-with-selected') }}
                            </div>
                        </div>

                    </x-tables.cell>

                    <x-tables.cell>


                        <div class="flex pt-1 pb-1 gap-2">
                                <flux:checkbox.all wire:model="checkAll" wire:key="{{ Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage() }}" label="Select All"/>
                                @if (collect($selectedInvoice)->count())
                                    @if($search_with_status === 'paid')
                                        <flux:tooltip content="{!! trans_choice('billing.payment.tooltip-bulk-unpayment', collect($selectedInvoice)->count(), [
                                            'count' => collect($selectedInvoice)->count(),]) !!}">
                                            <flux:button size="xs" variant="primary" color="red" class="cursor-pointer" disabled>
                                                {!! trans_choice('billing.button.bulk-unpayment', collect($selectedInvoice)->count(), [
                                                'count' => collect($selectedInvoice)->count(),]) !!}
                                            </flux:button>
                                        </flux:tooltip>
                                    @else
                                        <flux:tooltip content="{!! trans_choice('billing.payment.tooltip-bulk-payment', collect($selectedInvoice)->count(), [
                                            'count' => collect($selectedInvoice)->count(),]) !!}">
                                            <flux:button size="xs" variant="primary" color="green" class="cursor-pointer"
                                                wire:click="bulkSelectedPayment">
                                                {!! trans_choice('billing.button.bulk-payment', collect($selectedInvoice)->count(), [
                                                'count' => collect($selectedInvoice)->count(),]) !!}
                                            </flux:button>
                                        </flux:tooltip>

                                        <flux:tooltip content="{!! trans_choice('billing.tooltip-bulk-delete', collect($selectedInvoice)->count(), [
                                            'count' => collect($selectedInvoice)->count(),]) !!}">
                                            <flux:button icon="trash" size="xs" variant="primary" color="red" class="cursor-pointer" wire:click="bulkDeleteSelected">
                                                {!! trans_choice('billing.button.bulk-delete-invoice', collect($selectedInvoice)->count(), [
                                                'count' => collect($selectedInvoice)->count(),]) !!}
                                            </flux:button>
                                        </flux:tooltip>
                                    @endif
                                @endif

                        </div>
                    </x-tables.cell>
                </x-tables.row>
                 @endif
            </x-slot>
        </x-tables.table>
        </flux:checkbox.group>
        @if ($users->hasPages())
            <div class="p-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
    <x-advance-search-billing modalName="advance-search" />

    <livewire:admin.billings.modal.add-discount />
    <livewire:admin.billings.modal.billing-payment />
    <livewire:admin.billings.modal.bulk-payment />
    <livewire:admin.billings.modal.billing-unpayment />
    <livewire:admin.billings.modal.create-invoices-modal />
    <livewire:admin.billings.modal.create-new-billings />
    <livewire:admin.billings.modal.send-notifications />
    <livewire:admin.billings.modal.delete-invoice />
    <livewire:admin.billings.modal.reset-next-bill/>
    <livewire:admin.billings.modal.bulk-delete-invoice />
</div>
