<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.wan-monitorings') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.wan-monitorings') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>
        <div class="flex items-center justify-end  mb-2">
            <flux:button.group>
                <flux:button title="{{ __('autoisolir.button.create') }}" size="sm" variant="primary"
                    style="cursor: pointer;" icon="plus-circle"
                    wire:click="$dispatch('show-add-mikrotik-monitoring-modal')">
                    {{ __('websystem.button.add') }}
                </flux:button>
            </flux:button.group>
        </div>

        <x-tables.table class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.no') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.server') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.interface') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.interface_type') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.min-upload') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.max-upload') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.min-download') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.max-download') }}</x-tables.header>

                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.status') }}</x-tables.header>
                    <x-tables.header>{{ trans('websystem.mikrotik-monitoring.table.action') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @forelse ($mikrotikMonitorings as $key => $mikrotikMonitoring)
                    <x-tables.row>
                        <x-tables.cell
                            class="text-center">{{ ($mikrotikMonitorings->currentpage() - 1) * $mikrotikMonitorings->perpage() + $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>
                            {{ $mikrotikMonitoring->mikrotik->name }}
                        </x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->interface }}</x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->interface_type }}</x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->min_upload }}M</x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->max_upload }}M</x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->min_download }}M</x-tables.cell>
                        <x-tables.cell class="text-center">{{ $mikrotikMonitoring->max_download }}M</x-tables.cell>

                        <x-tables.cell class="text-center">
                            <div class="inline-flex">
                                <livewire:components.toogle-button :model="$mikrotikMonitoring" field="disabled"
                                    dispatch="mikrotik-monitoring-disable" key="{{ now() }}" />
                                <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    @if ($mikrotikMonitoring->disabled)
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
                                    <flux:button title="{{ trans('autoisolir.button.edit') }}" size="xs"
                                        variant="primary" style="cursor: pointer;" icon="pencil"
                                        wire:click="$dispatch('show-add-mikrotik-monitoring-modal', {mikrotikMonitoring: '{{ $mikrotikMonitoring->slug }}'})" />
                                        <flux:button size="xs" variant="primary" icon="eye" style="cursor: pointer;" wire:navigate
                                        href="{{ route('managements.mikrotik.wanmonitoring', $mikrotikMonitoring->mikrotik->slug) }}" />
                                    <flux:button title="Reactivation" size="xs"
                                        variant="primary" style="cursor: pointer;" icon="check"
                                        wire:click="add_script_to_mikrotik('{{ $mikrotikMonitoring->slug }}')" />
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
        @if ($mikrotikMonitorings->hasPages())
            <div class="p-3">
                {{ $mikrotikMonitorings->links() }}
            </div>
        @endif
        <livewire:admin.settings.wan-monitoring.modal.add-wan-monitoring />
    </x-layouts.general-setting>
</section>
