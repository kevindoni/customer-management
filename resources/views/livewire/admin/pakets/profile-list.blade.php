<section class="w-full">
    @include('partials.settings-heading')

    <x-layouts.paket.layout :heading="__('paket.profiles')" :subheading="__('Profiles description')">
        <div class="md:flex justify-between mb-2">
            <div class="md:flex justify-start gap-2">
                <div class="grid lg:grid-cols-2 gap-4">
                    <flux:input wire:model.live.debounce.500ms="search_name" type="text"
                        placeholder="{{ trans('paket.ph.search-with-name') }}" />

                    <flux:select wire:model.change="search_with_status">
                        <flux:select.option value=""> {{ trans('paket.ph.search-with-status') }}</flux:select.option>
                        <flux:select.option value="true"> {{ trans('paket.status.disable') }}</flux:select.option>
                        <flux:select.option value="false"> {{ trans('paket.status.enable') }}</flux:select.option>
                    </flux:select>

                </div>
            </div>
            <div class="md:flex justify-between gap-2">
                <flux:button wire:click="$dispatch('show-add-paket-profile-modal')" style="cursor: pointer;"
                    variant="primary" icon="plus-circle">
                    {{ __('paket.button.add-profile') }}
                </flux:button>

            </div>
        </div>

        <div wire:loading.class="opacity-75">

            <x-tables.table>
                <x-slot name="header">
                    <x-tables.theader>
                        <x-tables.header>{{ trans('paket.table.no') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.name') }}</x-tables.header>
                        <x-tables.header>{{ trans('paket.table.paket') }}</x-tables.header>
                        <x-tables.header>{{ trans('paket.table.client') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.rate-limit') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.parent-queue') }}</x-tables.header>
                        <x-tables.header>{{ trans('mikrotik.table.action') }}</x-tables.header>
                    </x-tables.theader>
                </x-slot>
                <x-slot name="body">
                    @forelse ($paketProfiles as $profile)
                        <x-tables.row>
                            <x-tables.cell
                                class="text-center">{{ ($paketProfiles->currentpage() - 1) * $paketProfiles->perpage() + $loop->index + 1 }}</x-tables.cell>
                            <x-tables.cell>{{ $profile->profile_name }}</x-tables.cell>
                            <x-tables.cell class="text-center">
                                @forelse ($profile->pakets as $paket)
                                    <flux:badge size="sm" color="blue"> {{ $paket->name }} -
                                        {{ $paket->mikrotik->name }}</flux:badge>
                                @empty
                                    <flux:badge size="sm" color="red">{{ trans('paket.not-used-in-paket') }}
                                    </flux:badge>
                                @endforelse
                            </x-tables.cell>
                            <x-tables.cell class="text-center">
                                <flux:badge size="sm" color="blue"> {{ $profile->customer_pakets->count() }}
                                    clients</flux:badge>
                            </x-tables.cell>

                            <x-tables.cell>{{ $profile->rate_limit }}</x-tables.cell>
                            <x-tables.cell>{{ $profile->parent_queue }}</x-tables.cell>
                            <x-tables.cell>
                                <flux:button.group>
                                <flux:button title="{{ trans('paket.button.edit') }}" size="xs" variant="primary"
                                    style="cursor: pointer;" icon="pencil"
                                    wire:click="$dispatch('show-add-paket-profile-modal', {paketProfile: '{{ $profile->slug }}'})" />
                                <flux:button title="{{ trans('paket.button.delete') }}" size="xs" variant="danger"
                                    style="cursor: pointer;" icon="trash"
                                    wire:click="$dispatch('show-delete-paket-profile-modal', {paketProfile: '{{ $profile->slug }}'})" />
                                </flux:button.group>
                            </x-tables.cell>
                        </x-tables.row>
                    @empty
                        <x-tables.row>
                            <x-tables.cell colspan=9>
                                <div class="flex justify-center items-center">
                                    <span class="font-medium py-8 text-gray-400 text-xl">
                                        {{ trans('paket.profile-not-found') }}
                                    </span>
                                </div>
                            </x-tables.cell>
                        </x-tables.row>
                    @endforelse


                </x-slot>
            </x-tables.table>
            @if ($paketProfiles->hasPages())
                <div class="p-3">
                    {{ $paketProfiles->links() }}
                </div>
            @endif
        </div>

    </x-layouts.paket.layout>
    <!-- Add Paket Profile Modal -->
    <livewire:admin.pakets.modal.add-paket-profile-modal />
    <!-- Add Paket Profile Modal -->
    <livewire:admin.pakets.modal.delete-paket-profile-modal />
</section>
