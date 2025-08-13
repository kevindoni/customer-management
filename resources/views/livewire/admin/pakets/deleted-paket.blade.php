<section class="w-full">
    @include('partials.settings-heading')

    <x-layouts.paket.layout :heading="__('paket.pakets')" :subheading="__('Pakets description')">
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

                <flux:button size="sm" :href="route('pakets.management')" wire:navigate style="cursor: pointer;" variant="primary"
                    iconTrailing="trash">
                    {{ __('paket.button.paket-list') }}
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
                        <x-tables.header>{{ trans('paket.table.action') }}</x-tables.header>
                    </x-tables.theader>
                </x-slot>
                <x-slot name="body">
                    @forelse ($deletedPakets as $key => $paket)
                        <x-tables.row>
                            <x-tables.cell class="text-center">
                                {{ ($deletedPakets->currentpage() - 1) * $deletedPakets->perpage() + $loop->index + 1 }}
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


                            <x-tables.cell>
                                <div class="flex gap-2 justify-end">
                                    <flux:button.group>
                                        <flux:tooltip content="{{ trans('paket.button.restore-paket') }}">
                                            <flux:button size="xs" variant="success" icon="arrow-uturn-left" class="cursor-pointer"
                                                wire:click="$dispatch('restore-paket-modal',{paketId: '{{ $paket->id }}'})"/>
                                        </flux:tooltip>

                                        <flux:tooltip content="{{ trans('paket.button.delete-permanent') }}">
                                            <flux:button size="xs"
                                            variant="danger" style="cursor: pointer;" icon="trash"
                                            wire:click="$dispatch('show-delete-paket-permanently-modal', {paketId: '{{ $paket->id }}'})" />
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
            @if ($deletedPakets->hasPages())
                <div class="p-3">
                    {{ $deletedPakets->links() }}
                </div>
            @endif
        </div>
    </x-layouts.paket.layout>
    <livewire:admin.pakets.modal.restore-paket />
    <livewire:admin.pakets.modal.permanently-delete-paket />

</section>
