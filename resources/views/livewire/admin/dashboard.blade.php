<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-4">

                <flux:heading size="lg" level="3" class="mb-1">
                    Login History
                </flux:heading>

                <div>
                    @php
                    $loginHistories = \App\Models\LoginHistory::limit(5)->orderBy('created_at', 'DESC')->get();
                    @endphp
                    <div class="flex flex-col gap-2">
                        @forelse ($loginHistories as $loginHistory)
                       <div class="md:flex gap-1 justify-between">
                            <div class="flex justify-start mb-1 sm:mb-0">
                            <flux:badge size="sm" color="sky" icon="user-circle">
                                {{ $loop->index + 1 . '. ' . $loginHistory->user->full_name }}
                            </flux:badge>
                            </div>
                            <div class="flex justify-between">
                            <flux:badge size="sm" color="zinc" icon="clock">
                                {{ \Carbon\Carbon::parse($loginHistory->created_at)->diffForHumans() }}
                            </flux:badge>
                            </div>
                        </div>

                        @empty
                        No Newest User
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-4">

                <flux:heading size="lg" level="3" class="mb-1">
                    Newest User
                </flux:heading>

                <div>
                    @php
                    $newestUsers = \App\Models\User::limit(5)->orderBy('created_at', 'DESC')->get();
                    @endphp
                    <div class="flex flex-col gap-1">
                        @forelse ($newestUsers as $user)
                        <div class="md:flex gap-1 justify-between">
                            <div class="flex justify-start mb-1 sm:mb-0">
                            <flux:badge size="sm" color="sky" icon="user-circle">
                                {{ $loop->index + 1 . '. ' . $user->full_name }}
                            </flux:badge>
                            </div>
                            <div class="flex justify-between">
                            <flux:badge size="sm" color="zinc" icon="clock">
                                {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}
                            </flux:badge>
                            </div>
                        </div>

                        @empty
                        No Newest User
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div
            class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 dark:bg-neutral-200">
            <livewire:livewire-pie-chart key="{{ $pieChartModel->reactiveKey() }}" :pie-chart-model="$pieChartModel" />
        </div>
    </div>




    <!--General Log-->
    <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <div class="col-span-2 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <div class="p-4">

                <flux:heading size="lg" level="3" class="mb-1">
                    General Log
                </flux:heading>

                <div>
                    @php
                    $generalLogs = \App\Models\GeneralLog::limit(5)->orderBy('created_at', 'DESC')->get();
                    @endphp
                    <div class="flex flex-col gap-2">
                        @forelse ($generalLogs as $generalLog)
                        <div class="md:flex gap-1 justify-between">
                            @php
                            foreach($generalLog->log_data as $log_data){

                            $logMsg = $log_data['log_history'];

                            }
                            @endphp

                            <div class="flex justify-start mb-1 sm:mb-0">
                                <flux:badge size="sm"
                                    :color="$generalLog->log_type === App\Services\GeneralLogServices::CUSTOMER_PAKET_EXPIRED ? 'rose': ($generalLog->log_type === App\Services\GeneralLogServices::CREATE_INVOICE ? 'teal' : 'sky')">
                                    <x-slot name="icon">
                                        @if ($generalLog->log_type ===
                                        App\Services\GeneralLogServices::SEND_CUSTOMER_NOTIFICATION)
                                        <flux:icon.paper-airplane variant="micro" class="me-2" />
                                        @elseif ($generalLog->log_type ===
                                        App\Services\GeneralLogServices::CUSTOMER_PAKET_EXPIRED)
                                        <flux:icon.x-circle variant="micro"
                                            class="text-red-500 dark:text-red-300 me-2" />
                                        @elseif ($generalLog->log_type ===
                                        App\Services\GeneralLogServices::CREATE_INVOICE)
                                        <flux:icon.document-plus variant="micro" class="me-2" />
                                        @else
                                        <flux:icon.information-circle variant="micro" class="me-2" />
                                        @endif

                                    </x-slot>
                                    {{ $loop->index + 1 . '. ' . $logMsg. ' by '.$generalLog->author }}
                                </flux:badge>
                            </div>
                            <div class="flex justify-between">
                                <flux:badge size="sm" color="zinc" icon="clock">
                                    {{ \Carbon\Carbon::parse($generalLog->created_at)->format('d F Y H:i') }}
                                </flux:badge>
                            </div>
                        </div>

                        @empty
                        Log empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Billing Chart-->
    <div class="relative h-full overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 ">
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <div class="p-4 flex-1" style="height: 30rem;">
                <div class="flex flex-row gap-2">
                    <div class="w-1/2">
                        <flux:heading size="lg" level="3" class="mb-1">
                            Billing Status
                        </flux:heading>
                    </div>
                    <x-select wire:model.change="search_with_year" id="search_with_year" name="search_year"
                        class="w-1/2" size="xs">
                        @foreach ($years as $year)
                        <option value="{{ $year->year }}">
                            {{ $year->year }}
                        </option>
                        @endforeach
                        <option value="all-year">{{ __('billing.ph.all-year') }}</option>
                    </x-select>
                </div>

                @if (count($billingPakets) > 0)
                <livewire:livewire-column-chart key="{{ $billingChart->reactiveKey() }}"
                    :column-chart-model="$billingChart" class="h-250" />
                @endif
            </div>
            <div class="p-4 flex-1" style="height: 30rem;">
                <div class="flex flex-col gap-4 p-4">
                    <div>
                        @php
                        $countCustomerPppPakets = \App\Models\Customers\CustomerPppPaket::orderBy(
                        'updated_at',
                        'DESC',
                        )->count();
                        $offlineCustomerPppPakets = \App\Models\Customers\CustomerPppPaket::whereOnline(false)
                        ->orderBy('updated_at', 'DESC')
                        ->get();

                        @endphp
                        <flux:heading size="lg" level="3" class="mb-1">
                            Offline PPP Customer {{ $offlineCustomerPppPakets->count() }} from
                            {{ $countCustomerPppPakets }}
                        </flux:heading>

                        <div class="flex flex-col gap-2">
                            @forelse ($offlineCustomerPppPakets->slice(0, 4) as $offlineCustomerPppPaket)
                            <div class="md:flex gap-1">
                                <flux:badge size="xs" color="rose">
                                    {{ $loop->index + 1 . '. ' .
                                    $offlineCustomerPppPaket->customer_paket->user->full_name
                                    }}
                                </flux:badge>

                                <flux:badge size="xs" color="indigo" icon="server">
                                    {{ $offlineCustomerPppPaket->paket->mikrotik->name }}
                                </flux:badge>
                                <flux:badge size="xs" color="zinc" icon="clock">
                                    {{ \Carbon\Carbon::parse($offlineCustomerPppPaket->updated_at)->format('d M Y h:i')
                                    }}
                                </flux:badge>
                            </div>
                            @empty
                            <flux:badge size="xs" color="lime">
                                All Customer Online
                            </flux:badge>
                            @endforelse
                        </div>
                    </div>

                    <flux:separator />

                    <div>
                        @php
                        $countCustomerStaticPakets = \App\Models\Customers\CustomerStaticPaket::orderBy(
                        'updated_at',
                        'DESC',
                        )->count();
                        $offlineCustomerStaticPakets = \App\Models\Customers\CustomerStaticPaket::whereOnline(false)
                        ->orderBy('updated_at', 'DESC')
                        ->get();
                        @endphp
                        <flux:heading size="lg" level="3" class="mb-1">
                            Offline IP Static Customer {{ $offlineCustomerStaticPakets->count() }} from
                            {{ $countCustomerStaticPakets }}
                        </flux:heading>


                        <div class="flex flex-col gap-2">
                            @forelse ($offlineCustomerStaticPakets->slice(0, 4) as $offlineCustomerStaticPaket)
                            <div class="md:flex gap-1">
                                <flux:badge size="xs" color="rose">
                                    {{ $loop->index + 1 . '. ' .
                                    $offlineCustomerStaticPaket->customer_paket->user->full_name }}
                                </flux:badge>

                                <flux:badge size="xs" color="indigo" icon="server">
                                    {{ $offlineCustomerStaticPaket->paket->mikrotik->name }}
                                </flux:badge>
                                <flux:badge size="xs" color="zinc" icon="clock">
                                    {{ \Carbon\Carbon::parse($offlineCustomerStaticPaket->updated_at)->format('d M Y
                                    h:i')
                                    }}
                                </flux:badge>
                            </div>
                            @empty
                            <flux:badge size="xs" color="lime">
                                All Customer Online
                            </flux:badge>
                            @endforelse
                        </div>

                    </div>

                    <flux:separator />

                    <div>

                        @php
                        $histories = \App\Models\Servers\MikrotikClientHistory::orderBy(
                        'created_at',
                        'DESC',
                        )->get();

                        @endphp
                        <flux:heading size="lg" level="3" class="mb-1"> 4 from {{ $histories->count() }}
                            Histories</flux:heading>


                        <div class="flex flex-col gap-2">
                            @forelse ($histories->slice(0, 4) as $history)
                            <div class="md:flex gap-1">
                                <flux:badge size="xs" color="rose">
                                    {{ $loop->index + 1 . '. ' . $history->user->full_name }}
                                </flux:badge>
                                <flux:badge size="xs" color="sky" icon="user-circle">
                                    {{ $history->type }}
                                </flux:badge>
                                <flux:badge size="xs" color="indigo" icon="server">
                                    {{ $history->mikrotik->name }}
                                </flux:badge>
                                <flux:badge size="xs" color="{{ $history->status == 'up' ? 'green' : 'red' }}"
                                    icon="{{ $history->status == 'up' ? 'arrow-up-circle' : 'arrow-down-circle' }}">
                                    {{ $history->status }}
                                </flux:badge>
                                <flux:badge size="xs" color="zinc" icon="clock">
                                    {{ \Carbon\Carbon::parse($history->created_at)->translatedFormat('d M Y H:i') }}
                                </flux:badge>
                            </div>
                            @empty
                            <flux:badge size="xs" color="lime">
                                No history
                            </flux:badge>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
