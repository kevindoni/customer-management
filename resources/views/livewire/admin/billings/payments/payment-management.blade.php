<div class="flex gap-2 flex-col">


    <!-- Date Income -->
    <div class="grid lg:grid-cols-4 gap-4">
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @php
                $todayPayments = \App\Models\Billings\Payment::where('reconciliation_status', '!=', 'discrepancy')->whereDate('payment_date', \Carbon\Carbon::now())->get();
            @endphp
            <div class="p-4">
                <flux:text>Today's income</flux:text>
                <flux:heading size="lg" class="mb-1">@moneyIDR($todayPayments->sum('amount'))</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:icon.calendar-days variant="micro" class="text-green-600 dark:text-green-500" />
                    <span class="text-sm text-green-600 dark:text-green-500">{{ \Carbon\Carbon::now()->format('d F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
             @php
                $startDate = \Carbon\Carbon::now()->subDays(7);
                $endDate = \Carbon\Carbon::now();
                $weekPayments = \App\Models\Billings\Payment::where('reconciliation_status', '!=', 'discrepancy')->whereBetween('payment_date', [$startDate, $endDate])->get();
            @endphp
            <div class="p-4">
                <flux:text>Last 7 days income</flux:text>
                <flux:heading size="lg" class="mb-1">@moneyIDR($weekPayments->sum('amount'))</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:icon.calendar-days variant="micro" class="text-green-600 dark:text-green-500" />
                    <span class="text-sm text-green-600 dark:text-green-500">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-4">
                @php
                    $month = \Carbon\Carbon::now()->format('m');
                    $year = \Carbon\Carbon::now()->format('Y');
                    $monthPayments = \App\Models\Billings\Payment::where('reconciliation_status', '!=', 'discrepancy')
                        ->whereMonth('payment_date', $month) // Filter by December
                        ->whereYear('payment_date', $year)
                        ->get();
                @endphp
                <flux:text>This month income</flux:text>
                <flux:heading size="lg" class="mb-1">@moneyIDR($monthPayments->sum('amount'))</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:icon.calendar-days variant="micro" class="text-green-600 dark:text-green-500" />
                    <span class="text-sm text-green-600 dark:text-green-500">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                </div>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-4">
                @php
                    $year = \Carbon\Carbon::now()->format('Y');
                    $yearPayments = \App\Models\Billings\Payment::where('reconciliation_status', '!=', 'discrepancy')
                        ->whereYear('payment_date', $year)
                        ->get();
                @endphp
                <flux:text>This year income</flux:text>
                <flux:heading size="lg" class="mb-1">@moneyIDR($yearPayments->sum('amount'))</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:icon.calendar-days variant="micro" class="text-green-600 dark:text-green-500" />
                    <span class="text-sm text-green-600 dark:text-green-500">{{ $year }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Teller Income -->
    <div class="grid lg:grid-cols-4 gap-4">

         @foreach ($paymentTellers as $paymentTeller)
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            @php
                $tellerPayments = \App\Models\Billings\Payment::where('reconciliation_status', '!=', 'discrepancy')
                    ->whereTeller($paymentTeller->teller)
                    ->whereMonth('payment_date', $month) // Filter by December
                    ->whereYear('payment_date', $year)
                    ->get();
            @endphp
            <div class="p-4">
                <flux:text>{{ $paymentTeller->teller }}</flux:text>
                <flux:heading size="lg" class="mb-1">@moneyIDR($tellerPayments->sum('amount'))</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:icon.calendar-days variant="micro" class="text-green-600 dark:text-green-500" />
                    <span class="text-sm text-green-600 dark:text-green-500">{{ \Carbon\Carbon::now()->format('F Y') }}</span>
                </div>
            </div>
        </div>

        @endforeach

    </div>

    <!--Search-->
    <div class="grid lg:grid-cols-6 gap-4">

        @php
            $disable = empty($startDateDeadline) ? false:true;
        @endphp
        <flux:select wire:model.change="search_with_year" :disable="$disable" click="clearSearchWithYear">
            <flux:select.option value="">{{ __('billing.ph.all-year') }}</flux:select.option>
            @foreach ($years as $year)
                <flux:select.option value="{{ $year->year }}">
                    {{ $year->year }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.change="search_with_month" name="search_with_month" :disable="$disable"  click="clearSearchWithMonth">
            <flux:select.option value="">{{ __('billing.ph.all-month') }}</flux:select.option>
            @foreach (array_reverse(\Carbon\CarbonPeriod::create(now()->addMonth(), '1 month', now()->addMonths(12))->toArray()) as $date)
                <flux:select.option value="{{ $date->format('m') }}">
                    {{ $date->format('F') }}
                </flux:select.option>
            @endforeach
        </flux:select>



        <x-input-date-range class="col-span-2" click="clearSearchDateDeadline">
            <x-slot name="start_date" wire:model="startDateDeadline"></x-slot>
            <x-slot name="end_date" wire:model="endDateDeadline"></x-slot>
        </x-input-date-range>

        <flux:select wire:model.change="search_with_teller"  click="clearSearchWithTeller">
            <flux:select.option value="">{{ trans('billing.ph.search-teller') }}</flux:select.option>
            @foreach ($paymentTellers as $paymentTeller)
                <flux:select.option value="{{ $paymentTeller->teller }}">
                    {{ $paymentTeller->teller }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <div class="place-content-center">
            <flux:button size="sm" wire:click="clearSearch" title="{{ __('Reset') }}" style="cursor: pointer;"
                variant="primary" iconTrailing="x-circle">{{ __('Reset') }}
            </flux:button>
        </div>

    </div>

    <div wire:loading.class="opacity-75">
        <x-tables.table class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header class="w-2 text-center">{{ trans('billing.table.no') }}</x-tables.header>
                    <x-tables.header>
                        {{ trans('billing.table.customer-name') }}
                    </x-tables.header>

                    <x-tables.header>
                        {{ trans('billing.table.customer-paket') }}
                    </x-tables.header>

                    <x-tables.header>
                        {{ trans('billing.table.periode') }}
                    </x-tables.header>
                    <x-tables.header>
                        {{ trans('billing.table.payments') }}
                    </x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @forelse ($invoices as $key => $invoice)
                    <x-tables.row>
                        <x-tables.cell
                            class="text-center">{{ ($invoices->currentpage() - 1) * $invoices->perpage() + $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>
                            <div class="flex gap-2">
                                <flux:icon.eye variant="solid" class="text-sky-500 dark:text-sky-300 size-4" style="cursor: pointer;" href="{{ route('customer.show', $invoice->customer_paket->user->username) }}" wire:navigate
                                title="{{ trans('customer.button.show-customer') }}" />
                                    {{$invoice->customer_paket->user->full_name}}
                            </div>
                            <div class="text-xs">

                            </div>
                        </x-tables.cell>
                        <x-tables.cell>
                            {{$invoice->customer_paket->paket->name}}
                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex justify-center">
                                <flux:badge size="sm">
                                    {{ \Carbon\Carbon::parse($invoice->periode)->format('F Y') }}
                                </flux:badge>
                            </div>

                        </x-tables.cell>
                        <x-tables.cell>

                            <x-tables.table-1 class="table-fixed">
                                <x-slot name="header">
                                    <x-tables.header-table-1>
                                        #
                                    </x-tables.header-table-1>

                                    <x-tables.header-table-1 class="text-center">
                                        {{ trans('billing.table.payment-status') }}
                                    </x-tables.header-table-1>

                                    <x-tables.header-table-1 class="text-center">
                                        {{ trans('billing.table.teller') }}
                                    </x-tables.header-table-1>

                                    <x-tables.header-table-1 class="text-center">
                                        {{ trans('billing.table.payment-time') }}
                                    </x-tables.header-table-1>

                                    <x-tables.header-table-1 class="text-center">
                                        {{ trans('billing.table.bill') }}
                                    </x-tables.header-table-1>


                                </x-slot>
                                <x-slot name="body">
                                    @forelse($invoice->payments as $payment)
                                        <x-tables.row-table-1>
                                            <x-tables.cell-table-1 class="text-center text-xs">
                                            </x-tables.cell-table-1>

                                            <x-tables.cell-table-1 class="text-xs text-center">
                                                {{ $payment->reconciliation_status }}
                                            </x-tables.cell-table-1>

                                            <x-tables.cell-table-1 class="text-xs text-center">
                                                {{ $payment->teller }}
                                            </x-tables.cell-table-1>

                                            <x-tables.cell-table-1 class="text-xs text-center">
                                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('d F Y, h:i') }}
                                            </x-tables.cell-table-1>

                                            <x-tables.cell-table-1 class="text-right text-xs">
                                                @moneyIDR($payment->amount)
                                            </x-tables.cell-table-1>

                                        </x-tables.row-table-1>
                                    @empty
                                        <x-tables.row-table-1>
                                            <x-tables.cell-table-1 colspan=5>
                                                <div class="flex justify-center items-center">
                                                    <span class="font-medium text-gray-700 text-sm">
                                                        {{ trans('Payment not found') }}
                                                    </span>
                                                </div>
                                            </x-tables.cell-table-1>
                                        </x-tables.row-table-1>
                                    @endforelse
                                </x-slot>
                                <x-slot name="footer">
                                    <x-tables.cell-table-1 colspan=4>
                                        <dif class="justify-end flex text-xs font-semibold">
                                            {!! trans('billing.total-bills') !!}:
                                        </dif>
                                    </x-tables.cell-table-1>
                                    <x-tables.cell-table-1>
                                        <dif class="justify-end flex text-xs font-semibold">
                                             @moneyIDR($invoice->payments->sum('amount'))
                                        </dif>
                                    </x-tables.cell-table-1>
                                </x-slot>

                            </x-tables.table-1>


                        </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=8>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('Payment not found') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse


            </x-slot>

            <x-slot name="footer">
                <x-tables.row>
                       @if ($invoices->hasPages())
                    <x-tables.cell-table-1 colspan=5>


                                {{ $invoices->links() }}


                    </x-tables.cell-table-1>
                      @endif
                </x-tables.row>

                <x-tables.row>
                    <x-tables.cell-table-1 colspan=4 class="bg-gray-300 dark:bg-white/10">
                        <dif class="justify-end flex text-xs font-semibold p-2">
                            {!! trans('billing.total-payments', ['teller' => empty($search_with_teller) ? 'All Teller': $search_with_teller]) !!}:
                        </dif>
                    </x-tables.cell-table-1>
                    <x-tables.cell-table-1 class="bg-gray-300 dark:bg-white/10">
                        <dif class="justify-end flex text-xs font-semibold p-2">
                            @moneyIDR($paymentsAmount)
                        </dif>
                    </x-tables.cell-table-1>
                </x-tables.row>
            </x-slot>
        </x-tables.table>


    </div>

</div>
