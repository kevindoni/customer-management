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
            <flux:button size="sm" :href="route('customers.management')" wire:navigate style="cursor: pointer;" variant="primary"
                iconTrailing="users">
                {{ __('user.button.customer-list') }}
            </flux:button>

        </div>
    </div>

    <div class="mb-2 max-w-max flex md:flex-row flex-col gap-2">
        <flux:input wire:model.live.debounce.500ms="search_name_or_email" type="text" clearable
            icon="magnifying-glass" kbd="⌘K" placeholder="{{ trans('user.ph.search-with-name-email') }}"
            clearable />

        <flux:input wire:model.live.debounce.500ms="search_address" id="search" type="text" name="search"
            kbd="⌘K" clearable icon="magnifying-glass" placeholder="{{ trans('user.ph.search-with-address') }}"
            clearable />

    </div>

    <div wire:loading.class="opacity-75">
        <flux:checkbox.group>
        <x-tables.table>
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header>#</x-tables.header>
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
                        <x-tables.cell>
                            <div class="flex justify-center">
                            <flux:checkbox wire:model.live="selectedUser" wire:key="{{ $user->id }}" value="{{ $user->id }}"/>
                            </div>
                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex justify-center">
                                {{ ($customers->currentpage() - 1) * $customers->perpage() + $loop->index + 1 }}
                            </div>
                        </x-tables.cell>
                        <x-tables.cell>
                            <flux:button.group>
                                <flux:button size="xs" variant="{{ $user->disabled ? 'danger' : 'primary' }}"
                                    style="cursor: pointer;">
                                    {{ $user->full_name }}
                                </flux:button>

                            </flux:button.group>
                        </x-tables.cell>
                        <x-tables.cell>{{ $user->user_address()->withTrashed()->first()->address }}</x-tables.cell>
                        <x-tables.cell>
                            {{ $user->customer_pakets()->withTrashed()->whereHas('paket')->count() }} pakets
                        </x-tables.cell>

                        <x-tables.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button.group>

                                    <flux:tooltip content="{{ trans('user.button.restore-customer') }}">
                                        <flux:button size="xs" variant="success" icon="arrow-uturn-left"
                                            style="cursor: pointer;"
                                            wire:click="$dispatch('restore-customer-modal',{user: '{{ $user->id}}'})"
                                            title="{{ trans('user.button.restore-customer') }}" />
                                    </flux:tooltip>


                                    <flux:tooltip content="{{ trans('user.button.delete-permanently') }}">
                                        <flux:button size="xs"
                                            variant="danger" icon="trash"
                                            style="cursor: pointer;"
                                            wire:click="$dispatch('delete-customer-permanently-modal',{userId: '{{ $user->id }}'})"
                                            title="{{ trans('user.button.delete-permanently') }}" />
                                    </flux:tooltip>

                                </flux:button.group>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>

                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=6>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('customer.deleted-notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse
                @if ($customers->count())
                <x-tables.row>
                    <x-tables.cell>
                        <div class="flex justify-center">
                            <flux:checkbox.all wire:model="checkAll" wire:key="{{ Illuminate\Pagination\LengthAwarePaginator::resolveCurrentPage() }}"/>
                        </div>
                    </x-tables.cell>
                    <x-tables.cell colspan=2 class="text-right">
                        {{ trans('customer.label.action-with-selected') }}
                    </x-tables.cell>
                    <x-tables.cell colspan="4" class="text-center">
                        @if (collect($selectedUser)->count())
                            <flux:tooltip content="{{ trans('user.button.bulk-restore-customer', ['count' => collect($selectedUser)->count()]) }}">
                                <flux:button size="xs" variant="success" icon="arrow-uturn-left"
                                    style="cursor: pointer;" disabled
                                    wire:click="$dispatch('restore-customer-modal',{user: '{{ $user->id}}'})"
                                    title="{{ trans('user.button.restore-customer') }}">
                                    {{ trans('user.button.bulk-restore-customer', ['count' => collect($selectedUser)->count()]) }}
                                </flux:button>
                            </flux:tooltip>

                            <flux:tooltip content="{{ trans('user.button.bulk-delete-permanently', ['count' => collect($selectedUser)->count()]) }}">
                                <flux:button size="xs" disabled
                                    variant="danger" icon="trash"
                                    style="cursor: pointer;"
                                    wire:click="$dispatch('disable-customer-modal',{user: '{{ $user->id }}'})"
                                    title="{{ trans('user.button.delete-permanently') }}">
                                    {{ trans('user.button.bulk-delete-permanently', ['count' => collect($selectedUser)->count()]) }}
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

    <livewire:admin.customers.modal.restore-customer />
    <livewire:admin.customers.modal.delete-customer-permanently />

</div>
