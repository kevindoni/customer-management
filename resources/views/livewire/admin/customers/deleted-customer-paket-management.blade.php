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
            <div class="flex justify-between gap-2">
                <flux:button size="sm" :href="route('customers.paket.management')" wire:navigate
                    style="cursor: pointer;" variant="primary" iconTrailing="users">
                    {{ __('customer.button.customer-paket-list') }}
                </flux:button>

            </div>
        </div>


    </div>

    <div class="mb-2 max-w-max flex md:flex-row flex-col gap-2">
        <flux:input wire:model.live.debounce.500ms="search_name_or_email" type="text" clearable
            icon="magnifying-glass" kbd="⌘K" placeholder="{{ trans('user.ph.search-with-name-email') }}"
            clearable />

        <flux:input wire:model.live.debounce.500ms="search_address" id="search" type="text" name="search"
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


    </div>

    <div wire:loading.class="opacity-75">
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

                                </flux:button.group>
                            </x-tables.cell>
                            <x-tables.cell>{{ $user->user_address->address }}</x-tables.cell>
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
                                            {{ trans('billing.table.action') }}
                                        </x-tables.header-table-1>

                                    </x-slot>

                                    <x-slot name="body">
                                        @forelse ($user->customer_pakets as $key => $customer_paket)
                                            <x-tables.row-table-1>
                                                <x-tables.cell-table-1 class="text-xs">
                                                    <div class="flex justify-center">
                                                        <flux:checkbox wire:model.live="selectedCustomerPaket"
                                                            wire:key="{{ $user->id }}"
                                                            value="{{ $customer_paket->id }}" />
                                                    </div>
                                                </x-tables.cell-table-1>

                                                <x-tables.cell-table-1 class="text-xs">
                                                    {{ $customer_paket->paket->name }} {{ trans('customer.on') }}
                                                    {{ $customer_paket->paket->mikrotik->name }}
                                                </x-tables.cell-table-1>

                                                <x-tables.cell-table-1>
                                                    <div class="flex justify-end">
                                                        <flux:button.group>
                                                            <flux:tooltip content="{{ trans('customer.button.restore-customer-paket') }}">
                                                                <flux:button size="xs" variant="success" icon="arrow-uturn-left"
                                                                    style="cursor: pointer;"
                                                                    wire:click="$dispatch('restore-customer-paket-modal',{customerPaketId: '{{ $customer_paket->id}}'})"
                                                                    title="{{ trans('customer.button.restore-customer-paket') }}" />
                                                            </flux:tooltip>
                                                            <flux:tooltip content="{{ trans('customer.button.delete-permanently') }}">
                                                                <flux:button size="xs"
                                                                    variant="danger" icon="trash"
                                                                    style="cursor: pointer;"
                                                                    wire:click="$dispatch('delete-permanently-customer-paket-modal',{customerPaketId: '{{ $customer_paket->id }}'})"
                                                                    title="{{ trans('customer.button.delete-permanently') }}" />
                                                            </flux:tooltip>
                                                        </flux:button.group>
                                                    </div>
                                                </x-tables.cell-table-1>
                                            </x-tables.row-table-1>
                                        @empty
                                            <x-tables.row-table-1>
                                                <x-tables.cell-table-1 colspan=6>
                                                    <div class="flex justify-center items-center">
                                                        <span class="font-medium text-gray-700 text-sm">
                                                            {{ trans('customer.deleted-customer-paket-notfound') }}
                                                        </span>
                                                    </div>
                                                </x-tables.cell-table-1>
                                            </x-tables.row-table-1>
                                        @endforelse

                                    </x-slot>


                                </x-tables.table-1>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.row>
                            <x-tables.cell colspan=5>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('customer.deleted-customer-paket-notfound') }}
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
                                <flux:checkbox.all wire:model="checkAll"
                                    wire:key="{{ Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage() }}"
                                    label="Select All" />
                                @if (collect($selectedCustomerPaket)->count())
                                    <flux:tooltip
                                        content="{{ trans('customer.button.bulk-delete-permanently-customer-paket') }}">
                                        <flux:button size="xs" variant="danger" icon="trash"
                                            style="cursor: pointer;" wire:click="bulkDeletePermanently" disabled>
                                            {{ trans('customer.button.bulk-delete-permanently-customer-paket', ['count' => collect($selectedCustomerPaket)->count()]) }}
                                        </flux:button>
                                    </flux:tooltip>
                                    <flux:tooltip
                                        content="{{ trans('customer.button.bulk-restore-customer-paket') }}">
                                        <flux:button size="xs" variant="success" icon="arrow-uturn-left" disabled
                                            style="cursor: pointer;" wire:click="bulkRestore">
                                            {{ trans('customer.button.bulk-restore-customer-paket', ['count' => collect($selectedCustomerPaket)->count()]) }}
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

<livewire:admin.customers.modal.customer-paket.restore-customer-paket />
<livewire:admin.customers.modal.customer-paket.delete-customer-paket-permanently />

</div>
