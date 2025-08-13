<section class="w-full">
<div class="relative mb-6 w-full">
    <flux:heading size="xl" level="1">{{ __('system.title.users') }}</flux:heading>
    <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.users') }}</flux:subheading>
    <flux:separator variant="subtle" />
</div>
    <x-layouts.general-setting>
        <div class="flex-col md:flex-row flex justify-between mb-2">
            <div class="flex justify-start gap-2">
                <div class="mb-2 max-w-max">
                    <flux:select wire:model.live="perPage">
                        <flux:select.option value="5">Per Page 5</flux:select.option>
                    </flux:select>
                </div>
            </div>
            <div class="flex justify-between gap-2">
                <flux:button wire:click="exportUser" style="cursor: pointer;" variant="primary"
                    iconTrailing="arrow-up-right">
                    {{ __('user.button.export') }}
                </flux:button>

                <flux:button wire:click="$dispatch('add-user-modal')" style="cursor: pointer;" variant="primary"
                    icon="plus-circle">
                    {{ __('user.button.create') }}
                </flux:button>
            </div>
        </div>
        <div class="mb-2 max-w-max flex-col md:flex-row flex gap-2">
            <flux:input wire:model.live.debounce.500ms="search_name_or_email" type="text"
                placeholder="{{ trans('user.ph.search-with-name-email') }}" clearable/>
            <flux:select wire:model.change="search_gender">
                <flux:select.option value="">{{ trans('customer.ph.select-gender') }}
                </flux:select.option>
                <flux:select.option value="male">{{ trans('customer.ph.male') }}
                </flux:select.option>
                <flux:select.option value="female">{{ trans('customer.ph.female') }}
                </flux:select.option>
            </flux:select>
        </div>
        <div class="mb-2 max-w-max md:flex-row flex-col flex gap-2">
            <flux:input wire:model.live.debounce.500ms="search_address" id="search" type="text" name="search"
                placeholder="{{ trans('user.ph.search-with-address') }}" clearable/>
            <flux:input wire:model.live.debounce.500ms="search_subdistrict" id="search_subdistrict" type="text"
                name="search_subdistrict" placeholder="{{ trans('user.ph.subdistrict') }}" clearable/>
            <flux:input wire:model.live.debounce.500ms="search_district" id="search_district" type="text"
                name="search_district" placeholder="{{ trans('user.ph.district') }}" clearable/>
            <flux:input wire:model.live.debounce.500ms="search_city" id="search_city" type="text" name="search_city"
                placeholder="{{ trans('user.ph.city') }}" clearable/>
            <flux:input wire:model.live.debounce.500ms="search_province" id="search_province" type="text"
                name="search_province" placeholder="{{ trans('user.ph.province') }}" clearable/>
        </div>

        <div wire:loading.class="opacity-75">
            <x-tables.table>
                <x-slot name="header">
                    <x-tables.theader>
                        <x-tables.header>{{ trans('user.table.no') }}</x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('full_name')"
                            :direction="$sortField === 'full_name' ? $sortDirection : null">{{ trans('user.table.full-name') }}</x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('address')"
                            :direction="$sortField === 'address' ? $sortDirection : null">{{ trans('user.table.address') }}
                        </x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('email')"
                            :direction="$sortField === 'email' ? $sortDirection : null">{{ trans('user.table.email') }}
                        </x-tables.header>
                        <x-tables.header>{{ trans('user.table.phone') }}</x-tables.header>
                        <x-tables.header>{{ trans('user.table.status') }}</x-tables.header>
                        <x-tables.header>{{ trans('user.table.action') }}</x-tables.header>
                    </x-tables.theader>
                </x-slot>
                <x-slot name="body">
                    @forelse ($users as $key => $user)
                        <x-tables.row>
                            <x-tables.cell
                                class="text-center">{{ ($users->currentpage() - 1) * $users->perpage() + $loop->index + 1 }}</x-tables.cell>
                            <x-tables.cell>
                                {{ $user->full_name }}
                            </x-tables.cell>
                            @php
                                $province = json_decode($user->province, true);
                                $city = json_decode($user->city, true);
                                $district = json_decode($user->district, true);
                                $subdistrict = json_decode($user->subdistrict, true);
                            @endphp
                            <x-tables.cell>{{ $user->address ?? '' }}, {{ $subdistrict['text'] ?? '' }},
                                {{ $district['text'] ?? '' }},
                                {{ $city['text'] ?? '' }}, {{ $province['text'] ?? '' }}</x-tables.cell>
                            <x-tables.cell>{{ $user->email }}</x-tables.cell>
                            <x-tables.cell>
                                @if ($user->phone)
                                    <flux:button.group>
                                        <flux:button size="sm">
                                            {{ $user->phone }}
                                        </flux:button>
                                        <flux:button size="sm" style="cursor: pointer;"
                                            wire:click="$dispatch('disable-wa-notification-modal',{userAddress: '{{ $user->address_id }}'})">
                                            <flux:icon.wa variant="solid"
                                                class="{{ $user->wa_notification ? 'text-green-500 dark:text-green-300' : 'text-gray-500 dark:text-gray-300' }} size-4" />
                                        </flux:button>
                                    </flux:button.group>
                                @endif
                            </x-tables.cell>
                            <x-tables.cell class="text-center">
                                <div class="inline-flex">
                                    <livewire:components.toogle-button :model="$user" field="disabled"
                                        dispatch="user-disable" key="{{ now() }}" />
                                    <span
                                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300 hidden sm:inline">
                                        @if ($user->disabled)
                                            {{ trans('system.disable') }}
                                        @else
                                            {{ trans('system.enable') }}
                                        @endif
                                    </span>
                                </div>
                            </x-tables.cell>
                            <x-tables.cell class="text-right">
                                <div class="flex gap-2 justify-end">
                                    <flux:button size="sm" variant="primary" icon="pencil"
                                        style="cursor: pointer;"
                                        wire:click="$dispatch('edit-user-modal', {user: '{{ $user->username }}'})" />
                                    <flux:button size="sm" variant="primary" icon="eye"
                                        style="cursor: pointer;" wire:navigate href="#}" />
                                    <flux:button size="sm" variant="primary" icon="user-circle"
                                        style="cursor: pointer;" />
                                    <flux:button size="sm" variant="danger" icon="trash" style="cursor: pointer;"
                                        wire:click="$dispatch('delete-user-modal', {user: '{{ $user->username }}'})" />
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.row>
                            <x-tables.cell colspan=7>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('user.notfound') }}
                                    </span>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @endforelse

                </x-slot>
            </x-tables.table>
            @if ($users->hasPages())
                <div class="p-3">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
        <livewire:admin.users.modal.create-new-user-modal />
        <livewire:admin.users.modal.edit-user-modal />
        <livewire:admin.users.modal.delete-user-modal />
        <livewire:admin.users.modal.disable-wa-notification />
    </x-layouts.general-setting>
</section>
