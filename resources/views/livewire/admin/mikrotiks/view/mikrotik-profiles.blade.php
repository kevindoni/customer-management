<section class="w-full">
    @include('partials.show-mikrotik-heading')

    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.server-name', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik" :heading="__('mikrotik.server-profiles')" :subheading="__('mikrotik.title.dashboard-description')">
        <div class="md:flex justify-between mb-2">
            <div class="flex justify-start gap-2">
            </div>
            <div class="flex justify-between gap-2">
                @if ($online)
                    <flux:button wire:click="$dispatch('show-import-paket-modal', {mikrotik: '{{ $mikrotik->slug }}'})" variant="primary"
                        size="sm" iconTrailing="arrow-down-tray" style="cursor:pointer">
                        {{ __('mikrotik.button.import-pakets-to-customer-management') }}
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
                            <x-tables.header>{{ trans('mikrotik.table.local-address') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.remote-address') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.rate-limit') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.parent-queue') }}</x-tables.header>
                            <x-tables.header>{{ trans('mikrotik.table.queue-type') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($mikrotikProfiles as $profile)
                            <x-tables.row>
                                <x-tables.cell>{{ ($mikrotikProfiles->currentpage() - 1) * $mikrotikProfiles->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['.id'] }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['name'] }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['local-address'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['remote-address'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['rate-limit'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['parent-queue'] ?? '' }}</x-tables.cell>
                                <x-tables.cell>{{ $profile['queue-type'] ?? '' }}</x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=8>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('mikrotik.profile-notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse
                    </x-slot>
                </x-tables.table>
                @if ($mikrotikProfiles->hasPages())
                    <div class="p-3">
                        {{ $mikrotikProfiles->links() }}
                    </div>
                @endif
            </div>


        </div>
        <livewire:admin.mikrotiks.modal.import-paket-modal />
    </x-layouts.mikrotik-view>
</section>
