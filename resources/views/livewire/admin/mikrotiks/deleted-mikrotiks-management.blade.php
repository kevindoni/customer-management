<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.mikrotiks') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.mikrotiks') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>
        <div class="mb-2 max-w-max">
            <flux:input wire:model.live.debounce.500ms="search_name_or_ip"
                placeholder="{{ trans('mikrotik.ph.search-with-name-or-ip') }}" />
        </div>

        <div class="md:flex justify-between mb-2">
            <div class="flex justify-start">
                <div class="mb-2 max-w-max">
                    <flux:select wire:model.live="perPage">
                        <flux:select.option value="5">Per Page 5</flux:select.option>
                    </flux:select>
                </div>
            </div>
            <div class="flex justify-between gap-2">

                <flux:tooltip :content="__('mikrotik.button.mikrotik-list')" position="bottom">
                <flux:button size="sm" :href="route('managements.mikrotiks')" wire:navigate style="cursor: pointer;"
                    variant="primary" icon="list-bullet">
                    {{ __('mikrotik.button.mikrotik-list') }}
                </flux:button>
                </flux:tooltip>
            </div>
        </div>

        <div wire:loading.class="opacity-75">
            <x-tables.table>
                <x-slot name="header">
                    <x-tables.theader>
                        <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.no') }}</x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('name')"
                            :direction="$sortField === 'name' ? $sortDirection : null">{{ trans('mikrotik.table.name') }}
                        </x-tables.header>
                        <x-tables.header sortable wire:click.prevent="sortBy('host')"
                            :direction="$sortField === 'host' ? $sortDirection : null">{{ trans('mikrotik.table.host') }}
                        </x-tables.header>
                        <x-tables.header
                            wire:click="$wire.sortField('merk_router')">{{ trans('mikrotik.table.platform') }}
                        </x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.version') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.boardname') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.action') }}</x-tables.header>
                    </x-tables.theader>
                </x-slot>
                <x-slot name="body">
                    @forelse ($mikrotiks as $key => $mikrotik)
                        <x-tables.row>
                            <x-tables.cell
                                class="text-center">{{ ($mikrotiks->currentpage() - 1) * $mikrotiks->perpage() + $loop->index + 1 }}</x-tables.cell>
                            <x-tables.cell>
                                {{ $mikrotik->name }}
                            </x-tables.cell>
                            <x-tables.cell>{{ $mikrotik->host }}</x-tables.cell>
                            <x-tables.cell>
                                <div class="flex justify-center">
                                    {{ $mikrotik->merk_router ?? '' }}
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex justify-center">
                                    {{ $mikrotik->version ?? '' }}
                                </div>
                            </x-tables.cell>
                            <x-tables.cell>
                                <div class="flex justify-center">
                                    {{ $mikrotik->type_router ?? '' }}
                                </div>
                            </x-tables.cell>


                            <x-tables.cell>
                                <div class="flex gap-2 justify-end">
                                    <flux:button.group>

                                        <flux:tooltip content="{{ trans('mikrotik.button.restore-mikrotik') }}">
                                            <flux:button size="sm" variant="success" icon="arrow-uturn-left" class="cursor-pointer"
                                                wire:click="$dispatch('show-restore-mikrotik-modal',{mikrotikId: '{{ $mikrotik->id }}'})"/>
                                        </flux:tooltip>

                                        <flux:tooltip :content="__('mikrotik.button.delete-permanently')" position="bottom">
                                            <flux:button size="sm" variant="danger" icon="trash"
                                                style="cursor: pointer;"
                                                wire:click="$dispatch('show-permanently-delete-mikrotik-modal', {mikrotikId: '{{ $mikrotik->id }}'})" />
                                        </flux:tooltip>
                                    </flux:button.group>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.row>
                            <x-tables.cell colspan=9>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('mikrotik.notfound') }}
                                    </span>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @endforelse
                </x-slot>
            </x-tables.table>

            @if ($mikrotiks->hasPages())
                <div class="p-3">
                    {{ $mikrotiks->links() }}
                </div>
            @endif
        </div>
        <livewire:admin.mikrotiks.modal.permanently-delete-mikrotik />
        <livewire:admin.mikrotiks.modal.restore-deleted-mikrotik />
    </x-layouts.general-setting>
</section>
