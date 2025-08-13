<section class="w-full">
    @include('partials.settings-heading')

    <x-layouts.paket.layout :heading="__('paket.pakets')" :subheading="__('Pakets description')">

        <livewire:livewire-pie-chart key="{{ $pieChartModel->reactiveKey() }}" :pie-chart-model="$pieChartModel" />
        <div class="md:flex-row flex-col flex gap-2 justify-between mb-4">
            <div class="grid lg:grid-cols-3 gap-4">
                <flux:input wire:model.live.debounce.500ms="search_name" type="text"
                    placeholder="{{ trans('paket.ph.search-with-name') }}" />

                <flux:select wire:model.change="search_server">
                    <flux:select.option value="">{{ trans('paket.ph.search-with-server') }}</flux:select.option>
                    @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $mikrotik)
                        <flux:select.option value="{{ $mikrotik->id }}">{{ $mikrotik->name }}</flux:select.option>
                    @endforeach
                </flux:select>

                <flux:select wire:model.change="search_with_status">
                    <flux:select.option value="">{{ trans('paket.ph.search-with-status') }}</flux:select.option>
                    <flux:select.option value="true"> {{ trans('paket.status.disable') }}</flux:select.option>
                    <flux:select.option value="false"> {{ trans('paket.status.enable') }}</flux:select.option>
                </flux:select>
            </div>
            <div class="md:flex justify-between gap-2">
                @php
                    $deletedPakets = App\Models\Pakets\Paket::whereHas('mikrotik')->onlyTrashed();
                @endphp
                @if ($deletedPakets->count())
                    <flux:button size="sm" :href="route('deletedPakets.management')" wire:navigate style="cursor: pointer;" variant="danger"
                        iconTrailing="trash">
                        {{ __('paket.button.deleted-pakets',['count'=>$deletedPakets->count()]) }}
                    </flux:button>
                @endif
                <flux:button size="sm" wire:click="$dispatch('show-add-paket-modal')" style="cursor: pointer;" variant="primary"
                    icon="plus-circle">
                    {{ __('paket.button.create') }}
                </flux:button>

            </div>
        </div>

        <div wire:loading.class="opacity-75">
            <x-tables.table>
                <x-slot name="header">
                    <x-tables.theader>
                        <x-tables.header class="w-1">{{ trans('paket.table.no') }}</x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('name')"
                            :direction="$sortField === 'name' ? $sortDirection : null">{{ trans('paket.table.name') }}
                        </x-tables.header>

                        <x-tables.header sortable wire:click.prevent="sortBy('price')" :direction="$sortField === 'price' ? $sortDirection : null">
                            {{ trans('paket.table.price') }}
                        </x-tables.header>
                        <x-tables.header>{{ trans('paket.table.profile') }}</x-tables.header>
                        <x-tables.header>{{ trans('paket.table.server') }}</x-tables.header>

                        <x-tables.header>
                            {{ trans('paket.table.client') }}
                        </x-tables.header>
                        <x-tables.header>
                            {{ trans('paket.table.trial-days') }}
                        </x-tables.header>
                        <x-tables.header class="w-1">
                            <flux:badge size="sm" icon-trailing="power">{{ trans('paket.table.status') }}
                            </flux:badge>
                        </x-tables.header>
                        <x-tables.header>{{ trans('paket.table.action') }}</x-tables.header>
                    </x-tables.theader>
                </x-slot>
                <x-slot name="body">
                    @forelse ($pakets as $key => $paket)
                        <x-tables.row>
                            <x-tables.cell class="text-center">
                                {{ ($pakets->currentpage() - 1) * $pakets->perpage() + $loop->index + 1 }}
                            </x-tables.cell>
                            <x-tables.cell>
                                <flux:button.group>
                                    <flux:button size="xs" variant="primary" style="cursor: pointer;">
                                        {{ $paket->name }}
                                    </flux:button>
                                    <flux:button size="xs"
                                        variant="{{ $paket->show_on_customer ? 'success' : 'danger' }}" icon="eye"
                                        style="cursor: pointer;" />
                                </flux:button.group>
                            </x-tables.cell>

                            <x-tables.cell>
                                <span class="flex justify-end">
                                    @if ($paket->price == 0)
                                        {{ trans('paket.free') }}
                                    @else
                                        @moneyIDR($paket->price)
                                    @endif
                                </span>
                            </x-tables.cell>
                            <x-tables.cell class="text-center">
                                <flux:badge class="text-xs" color="sky">
                                    {{ $paket->paket_profile->profile_name ?? '' }} -
                                    {{ $paket->mikrotik_ppp_profile_id ?? '' }}
                                </flux:badge>
                            </x-tables.cell>
                            <x-tables.cell class="text-center">
                                {{ $paket->mikrotik->name ?? '' }}
                            </x-tables.cell>

                            <x-tables.cell class="text-center">
                                <a wire:click="showViewCustomerPaketModal('{{ $paket->slug }}')"
                                    style="cursor: pointer" wire:loading.attr="disabled"
                                    title="{{ trans('paket.button.view-customer') }}">
                                    {!! trans_choice('paket.label.customer-paket-count', $paket->customer_pakets->count(), [
                                        'count_customer' => $paket->customer_pakets->count(),
                                    ]) !!}
                                </a>
                            </x-tables.cell>
                            <x-tables.cell class="text-center">
                                {{ $paket->trial_days }}
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="inline-flex">

                                    <livewire:components.toogle-button :model="$paket" field="disabled" :disableButton="$paket->customer_pakets->count()?true : false"
                                        dispatch="paket-disable" key="{{ now() }}" />

                                    <span class="ms-2 font-medium text-gray-900 dark:text-gray-300">
                                        @if ($paket->disabled)
                                            {{ trans('system.disable') }}
                                        @else
                                            {{ trans('system.enable') }}
                                        @endif
                                    </span>
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex gap-2 justify-end">
                                    <flux:button.group>
                                        <flux:tooltip content="{{ trans('paket.button.show') }}">
                                            <flux:button size="xs" variant="primary" style="cursor: pointer;"
                                                icon="eye" disabled
                                                wire:click="$dispatch('show-view-paket-modal', {paket: '{{ $paket->slug }}'})" />
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ trans('paket.button.edit') }}">
                                        <flux:button size="xs"
                                            variant="primary" style="cursor: pointer;" icon="pencil"
                                            wire:click="$dispatch('show-add-paket-modal', {paket: '{{ $paket->slug }}'})" />
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ trans('paket.button.update-script') }}">
                                            <flux:button size="xs"
                                            variant="primary" style="cursor: pointer;" icon="arrow-up-circle"
                                            wire:click="updateProfileScript('{{ $paket->slug }}')" />
                                        </flux:tooltip>
                                        <flux:tooltip content="{{ trans('paket.button.delete') }}">
                                            <flux:button size="xs"
                                            variant="danger" style="cursor: pointer;" icon="trash"
                                            wire:click="$dispatch('show-delete-paket-modal', {paket: '{{ $paket->slug }}'})" />
                                        </flux:tooltip>
                                        </flux:button.group>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.row>
                            <x-tables.cell colspan=8>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('paket.notfound') }}
                                    </span>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @endforelse
                </x-slot>
            </x-tables.table>
            @if ($pakets->hasPages())
                <div class="p-3">
                    {{ $pakets->links() }}
                </div>
            @endif
        </div>
    </x-layouts.paket.layout>
    <!-- Add Paket Profile Modal -->
    <livewire:admin.pakets.modal.add-paket-modal />
    <livewire:admin.pakets.modal.delete-paket-modal />

</section>
