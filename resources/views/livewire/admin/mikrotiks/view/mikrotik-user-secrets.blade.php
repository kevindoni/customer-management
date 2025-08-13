<section class="w-full">
    @include('partials.show-mikrotik-heading')



    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.server-name', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik">
        <div class="md:flex justify-between mb-2">
            <div class="flex justify-start gap-2">
                <div class="mb-2 max-w-max">
                    <flux:select wire:model.live="searchByImported">
                        <flux:select.option value="">{{ trans('mikrotik.ph.all') }}</flux:select.option>
                        <flux:select.option value="imported">{{ trans('mikrotik.ph.imported') }}</flux:select.option>
                        <flux:select.option value="not-imported">{{ trans('mikrotik.ph.not-imported') }}</flux:select.option>
                    </flux:select>
                </div>
            </div>
            <div class="flex justify-between gap-2">
                @if ($online)
                    <flux:button wire:click="$dispatch('show-import-customer-modal', {mikrotik: '{{ $mikrotik->slug }}'})" variant="primary"
                        size="sm" iconTrailing="arrow-down-tray" style="cursor:pointer">
                        {{ __('mikrotik.button.import-customer-from-mikrotik',['mikrotik' => $mikrotik->name]) }}
                    </flux:button>
                @endif
            </div>
        </div>
        <div class="flex flex-col gap-6">
            <div wire:loading.class="opacity-75">
                <x-tables.table wire:loading.class="opacity-75" class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.no') }}</x-tables.header>
                            <x-tables.header class="w-2 px-4 py-2">{{ trans('mikrotik.table.id') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.name') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.password') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.service') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.caller-id') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.profile') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.disabled') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.comment') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($secrets as $userSecret)
                            <x-tables.row>
                                <x-tables.cell>{{ ($secrets->currentpage() - 1) * $secrets->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['.id'] }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['name'] }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['password'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['service'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['caller-id'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['profile'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['disabled'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $userSecret['comment'] ?? '' }}</x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=9>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('mikrotik.user-secrets-notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse
                    </x-slot>
                </x-tables.table>
                @if ($secrets->hasPages())
                    <div class="p-3">
                        {{ $secrets->links() }}
                    </div>
                @endif
            </div>
        </div>
        <livewire:admin.mikrotiks.modal.import-customer-modal />
    </x-layouts.mikrotik-view>
</section>
