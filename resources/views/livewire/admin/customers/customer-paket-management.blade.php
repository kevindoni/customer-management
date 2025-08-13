<div>
    <div class="md:flex justify-between mb-2">
        <div class="flex justify-start gap-2">
            <div class="mb-2 max-w-max">
                <flux:select wire:model.live="perPage">
                    <flux:select.option value="10">Per Page 10</flux:select.option>
                    <flux:select.option value="25">Per Page 25</flux:select.option>
                    <flux:select.option value="50">Per Page 50</flux:select.option>
                </flux:select>
            </div>
        </div>

        <div class="flex justify-between gap-2">
            @php
                $deletedCustomerPakets = App\Models\Customers\CustomerPaket::whereHas('user')->whereHas('paket')->onlyTrashed();
                $whatsappGatewayDisabled = \App\Models\WhatsappGateway\WhatsappGatewayGeneral::first()->disabled;
            @endphp
            @if($deletedCustomerPakets->count())
                <flux:button size="sm" :href="route('deletedCustomers.paket.management')" wire:navigate style="cursor: pointer;" variant="danger"
                    iconTrailing="trash">
                    {{ __('customer.button.deleted-customer-pakets',['count' => $deletedCustomerPakets->count()]) }}
                </flux:button>
            @endif
            @if (!$whatsappGatewayDisabled)
                <flux:button size="sm" wire:click="$dispatch('show-send-message-all-customer-paket-modal')" style="cursor: pointer;"
                    variant="success" icon="wa">
                    {{ __('Send Message') }}
                </flux:button>
            @endif
        </div>


    </div>

    <!--Search-->
    <div class="max-w-max grid md:grid-cols-6 gap-2">
        <flux:input wire:model.live.debounce.500ms="search_name_or_email" type="text" clearable icon="magnifying-glass"
            kbd="⌘K" placeholder="{{ trans('user.ph.search-with-name-email') }}" clearable />

        <flux:input wire:model.live.debounce.500ms="search_with_address" id="search" type="text" name="search"
            kbd="⌘K" clearable icon="magnifying-glass" placeholder="{{ trans('user.ph.search-with-address') }}"
            clearable />

        <flux:select wire:model.change="selectedServer">
            <flux:select.option value=""> {{ trans('customer.paket.ph.all-server') }}
            </flux:select.option>
            @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $mikrotik)
                <flux:select.option value="{{ $mikrotik->id }}">
                    {{ $mikrotik->name }}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select wire:model.change="search_with_paket" wire:key="{{ $selectedServer }}">
            <flux:select.option value="">{{ trans('customer.paket.ph.all-paket') }}</flux:select.option>
            @if ($selectedServer)
                @foreach (\App\Models\Servers\Mikrotik::whereId($selectedServer)->first()->paketsOrderByPrice->where('disabled', false) as $paket)
                    <flux:select.option value="{{ $paket->id }}">{{ $paket->name }}</flux:select.option>
                @endforeach
            @endif
        </flux:select>

        <flux:select wire:model.change="search_with_internet_service">
            <flux:select.option value="">{{ trans('customer.paket.ph.all-internet-service') }}
            </flux:select.option>
            <flux:select.option value="1">{{ trans('customer.paket.ph.ppp') }}
            </flux:select.option>
            <flux:select.option value="2">{{ trans('customer.paket.ph.static') }}</flux:select.option>
        </flux:select>

        <flux:select wire:model.change="search_with_status_customer_paket">
            <flux:select.option value="">{{ trans('customer.paket.status.all-status') }}</flux:select.option>
            <flux:select.option value="active">{{ trans('customer.paket.status.active') }}</flux:select.option>
            <flux:select.option value="suspended">{{ trans('customer.paket.status.suspended') }}</flux:select.option>
            <flux:select.option value="cancelled">{{ trans('customer.paket.status.cancelled') }}</flux:select.option>
            <flux:select.option value="expired">{{ trans('customer.paket.status.expired') }}</flux:select.option>
            <flux:select.option value="pending">{{ trans('customer.paket.status.pending') }}</flux:select.option>
            <flux:select.option value="online">{{ trans('customer.paket.status.up') }}</flux:select.option>
            <flux:select.option value="offline">{{ trans('customer.paket.status.down') }}</flux:select.option>
        </flux:select>
    </div>

    <div wire:loading.class="opacity-75" class="mt-2">
        <flux:checkbox.group>
        <x-tables.table>
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header>{{ trans('customer.table.no') }}</x-tables.header>
                    <x-tables.header sortable wire:click.prevent="sortBy('full_name')"
                        :direction="$sortField === 'full_name' ? $sortDirection : null">{{ trans('customer.table.name') }}</x-tables.header>
                    <x-tables.header sortable wire:click.prevent="sortBy('address')"
                        :direction="$sortField === 'address' ? $sortDirection : null">{{ trans('customer.table.address') }}
                    </x-tables.header>
                    <x-tables.header>{{ trans('customer.table.paket') }}
                    </x-tables.header>
                    <x-tables.header>{{ trans('customer.table.action') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @forelse ($customers as $key => $user)
                    <x-tables.row>
                        <x-tables.cell class="text-center">
                            {{ ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1 }}
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:button.group>
                                <flux:button size="xs" variant="{{ $user->disabled ? 'danger' : 'primary' }}"
                                    style="cursor: pointer;">
                                    {{ $user->full_name }}
                                </flux:button>
                                <flux:button size="xs"
                                    variant="{{ $user->user_address->wa_notification ? 'success' : 'danger' }}"
                                    icon="wa" style="cursor: pointer;"
                                    wire:click="$dispatch('disable-wa-notification-modal',{userAddress: '{{ $user->user_address->id }}'})" />
                            </flux:button.group>
                        </x-tables.cell>
                        <x-tables.cell>{{ $user->user_address->address }}</x-tables.cell>
                        <x-tables.cell>

                             @forelse ($user->customer_pakets as $key => $customer_paket)
                                <div>
                                    <flux:button.group>
                                        <flux:button size="xs" justify="start" class="w-full">

                                        <div class="flex gap-2">
                                            <flux:checkbox wire:model.live="selectedCustomerPaket"  wire:key="{{ $user->id }}" value="{{ $customer_paket->id }}"/>
                                            <span
                                                class="{{ $customer_paket->disabled ? 'text-red-700' : 'text-zinc-800' }} text-left">
                                                {{ $customer_paket->paket->name }} {{ trans('customer.on') }}
                                                {{ $customer_paket->paket->mikrotik->name }}
                                            </span>

                                        </div>

                                        </flux:button>

                                        @php
                                            $expiredDate = \Carbon\Carbon::parse($customer_paket->expired_date);
                                        @endphp
                                        <flux:button variant="primary" size="xs" class="w-[85%]"
                                        :color="$expiredDate->isPast() ? 'rose' : ($expiredDate->subDays(3)->isPast() ? 'amber' : 'emerald')">
                                            S: {{ \Carbon\Carbon::parse($customer_paket->start_date)->format('d-m-Y') }}
                                            E: {{ \Carbon\Carbon::parse($customer_paket->expired_date)->format('d-m-Y') }}

                                        </flux:button>
                                        <x-customer-paket.status-and-action :customerPaket="$customer_paket" :user="$user" size="xs" />
                                    </flux:button.group>
                                </div>
                             @empty
                                <flux:badge size="sm" color="zinc">
                                    No Paket
                                </flux:badge>
                            @endforelse

                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button.group>
                                    @if (is_null($user->email_verified_at))
                                        <flux:button size="xs" title="Verification" style="cursor: pointer;"
                                            wire:click="verificationUser('{{ $user->username }}')">
                                            <flux:icon.check variant="micro" class="text-red-500" />
                                        </flux:button>
                                    @else
                                        @if (!$user->disabled)
                                            <flux:button size="xs" variant="primary" icon="plus-circle"
                                                style="cursor: pointer;"
                                                wire:click="$dispatch('add-customer-paket-modal',{user: '{{ $user->username }}'})"
                                                title="{{ trans('customer.button.add-paket') }}" />
                                        @endif



                                        @if ($user->invoices->where('status', '!=', 'paid')->count())
                                            <flux:tooltip
                                                content="{{ $user->invoices->where('status', '!=', 'paid')->count() }} invoices">
                                                <flux:button size="xs" variant="success" icon="currency-dollar"
                                                    style="cursor: pointer;" wire:navigate
                                                    href="{{ route('customer.billing', $user->username) }}" />
                                            </flux:tooltip>
                                        @endif

                                        <flux:button size="xs" variant="primary" icon="eye"
                                            style="cursor: pointer;" wire:navigate
                                            href="{{ route('customer.show', $user->username) }}"
                                            title="{{ trans('customer.button.show-customer') }}" />
                                    @endif
                                </flux:button.group>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=5>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('customer.customer-paket-notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse
                @if ($customers->count())
                <x-tables.row>
                    <x-tables.cell colspan="3" class="text-right">
                        {{ trans('customer.label.action-with-selected') }}
                    </x-tables.cell>
                    <x-tables.cell colspan="2" class="flex pt-1 pb-1 gap-2">
                        <flux:checkbox.all wire:model="checkAll" wire:key="{{ Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage() }}" label="Select All"/>
                        @if (collect($selectedCustomerPaket)->count())
                            <flux:tooltip content="{{ trans('customer.helper.tooltip-bulk-delete-customer-paket') }}">
                                <flux:button size="xs" variant="danger" icon="trash" style="cursor: pointer;"
                                    wire:click="bulkDelete" disabled>
                                    {{ trans('customer.button.bulk-delete-customer-paket', ['count' => collect($selectedCustomerPaket)->count()]) }}
                                </flux:button>
                            </flux:tooltip>
                            <flux:tooltip content="{{ trans('customer.helper.tooltip-bulk-edit-activation-customer-paket') }}">
                                <flux:button size="xs" variant="primary" icon="pencil"
                                    style="cursor: pointer;" wire:click="bulkEdit">
                                    {{ trans('customer.button.bulk-edit-activation', ['count' => collect($selectedCustomerPaket)->count()]) }}
                                </flux:button>
                            </flux:tooltip>
                        @endif
                    </x-tables.cell>
                </x-tables.row>
                @endif
            </x-slot>
        </x-tables.table>
        </flux:checkbox.group>
        @if ($customers->hasPages())
            <div class="p-3">
                {{ $customers->links() }}
            </div>
        @endif
    </div>


    <livewire:admin.customers.modal.disable-wa-notification />
    <livewire:admin.customers.modal.customer-paket.add-customer-paket-modal />
    <livewire:admin.customers.modal.customer-paket.edit-customer-paket-address-modal />
    <livewire:admin.customers.modal.customer-paket.edit-mac-address />
    <livewire:admin.customers.modal.customer-paket.activation-paket />
    <livewire:admin.customers.modal.customer-paket.bulk-edit-activation />
    <livewire:admin.customers.modal.customer-paket.disable-customer-paket />
    <livewire:admin.customers.modal.customer-paket.disable-wa-notification-installation-address />

    <!--Send message-->
    <livewire:admin.whatsapp-gateway.modal.notification-message.send-message-all-customer-paket />
</div>
