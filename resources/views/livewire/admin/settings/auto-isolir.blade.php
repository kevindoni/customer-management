<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.websystem') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.websystem') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>

        <div class="flex justify-between pt-1 pb-3 mt-2">
            <div class="flex justify-start">
                <div class="mr-2">
                    <x-input wire:model.live.debounce.500ms="search_name_or_ip" id="search" class="block  w-full"
                        type="text" name="search" placeholder="{{ trans('mikrotik.ph.search-with-name-or-ip') }}" />
                </div>
            </div>
            <div class="flex justify-between">
                <flux:button.group>

                    <flux:button title="{{ trans('autoisolir.button.edit') }}" size="sm" variant="primary"
                        style="cursor: pointer;" icon="pencil" wire:click="$dispatch('edit-general-autoisolir-modal')">
                        {{ __('autoisolir.button.general') }}
                    </flux:button>
                </flux:button.group>
            </div>
        </div>
        <x-tables.table wire:loading.class="opacity-75" class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header>{{ __('autoisolir.table.no') }}</x-tables.header>
                    <x-tables.header sortable wire:click.prevent="sortBy('name')"
                        :direction="$sortField === 'name' ? $sortDirection : null">{{ __('autoisolir.table.server-name') }}</x-tables.header>
                    <x-tables.header>{{ __('autoisolir.table.profile-name') }}</x-tables.header>
                    <x-tables.header class="w-fit">{{ __('autoisolir.table.ros-version') }}</x-tables.header>
                    <x-tables.header>{{ __('autoisolir.table.type') }}</x-tables.header>

                    <x-tables.header>{{ __('autoisolir.table.status') }}</x-tables.header>
                    <x-tables.header class="flex justify-end">{{ __('customer.table.action') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>

            <x-slot name="body">
                @forelse ($autoisolirs as $key => $autoisolir)
                    <x-tables.row>
                        <x-tables.cell>{{ ($autoisolirs->currentpage() - 1) * $autoisolirs->perpage() + $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>
                            {{ $autoisolir->mikrotik->name }}
                        </x-tables.cell>
                        <x-tables.cell>
                            {{ $autoisolir->profile_id }}
                        </x-tables.cell>
                        <x-tables.cell>
                            {{ $autoisolir->mikrotik->version }}

                        </x-tables.cell>
                        <x-tables.cell class="text-center">
                            @if ($autoisolir->activation_date)
                                {{ __('autoisolir.status.activation-date') }}
                            @else
                                {{ __('autoisolir.status.due-date') }}
                            @endif
                        </x-tables.cell>

                        <x-tables.cell>
                            @if ($websystem->isolir_driver == 'mikrotik')
                                <span class="flex justify-center">
                                    @if ($autoisolir->schedule_id == 0 || $autoisolir->script_id == 0)
                                        {{ trans('autoisolir.not-activation') }}
                                    @else
                                        <livewire:components.toogle-button :model="$autoisolir" field="disabled"
                                            dispatch="autoisolir-disable" key="{{ now() }}" />
                                    @endif
                                </span>
                            @elseif ($websystem->isolir_driver == 'cronjob')
                                <span class="flex justify-center">
                                    <livewire:components.toogle-button :model="$autoisolir" field="disabled"
                                        dispatch="autoisolir-disable" key="{{ now() }}" />
                                </span>
                            @endif
                        </x-tables.cell>
                        <x-tables.cell>
                            <div class="flex gap-2 justify-end">
                                <flux:button.group>
                                    <flux:tooltip content="{{ trans('autoisolir.button.edit') }}">
                                        <flux:button size="xs" variant="primary" style="cursor: pointer;"
                                            icon="pencil"
                                            wire:click="$dispatch('add-or-edit-autoisolir-modal', {autoIsolir: '{{ $autoisolir->slug }}'})" />
                                    </flux:tooltip>

                                    @if ($websystem->isolir_driver == 'mikrotik')
                                        @if ($autoisolir->script_id == 0 && $autoisolir->scedule_id == 0)
                                            <flux:tooltip content="{{ trans('autoisolir.button.activation') }}">
                                                <flux:button title="{{ trans('autoisolir.button.activation') }}"
                                                    size="xs" variant="success" style="cursor: pointer;"
                                                    icon="check" wire:key="{{ $autoisolir->slug }}"
                                                    wire:click="activation_auto_isolir_mikrotik('{{ $autoisolir->slug }}') " />
                                            </flux:tooltip>
                                        @else
                                            <flux:tooltip content="{{ trans('autoisolir.button.reactivation') }}">
                                                <flux:button title="{{ trans('autoisolir.button.reactivation') }}"
                                                    size="xs" wire:key="{{ $autoisolir->slug }}" variant="danger"
                                                    style="cursor: pointer;" icon="arrow-uturn-down"
                                                    wire:click="reset_auto_isolir('{{ $autoisolir->slug }}')" />
                                            </flux:tooltip>
                                        @endif
                                    @endif



                                </flux:button.group>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @empty
                    <x-tables.row>
                        <x-tables.cell colspan=9>
                            <div class="flex justify-center items-center">
                                <span class="font-medium py-8 text-gray-400 text-xl">
                                    {{ trans('autoisolir.notfound') }}
                                </span>
                            </div>
                        </x-tables.cell>
                    </x-tables.row>
                @endforelse
            </x-slot>
        </x-tables.table>
        <livewire:admin.settings.autoisolir.modal.add-auto-isolir />
        <livewire:admin.settings.autoisolir.modal.general />
    </x-layouts.general-setting>
</section>
