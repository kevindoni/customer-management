<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.banks') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.banks') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>
        <div class="flex items-center justify-end  mb-2">
            <flux:button.group>
                <flux:button title="{{ __('bank.button.add-bank') }}" size="sm" variant="primary"
                    style="cursor: pointer;" icon="plus-circle" wire:click="$dispatch('show-add-bank-modal')">
                    {{ __('bank.button.add-bank') }}
                </flux:button>
            </flux:button.group>
        </div>

        <x-tables.table class="table-fixed">
            <x-slot name="header">
                <x-tables.theader>
                    <x-tables.header class="w-2 px-4 py-2">{{ trans('bank.table.no') }}</x-tables.header>
                    <x-tables.header>{{ trans('bank.table.bank') }}</x-tables.header>
                    <x-tables.header>{{ trans('bank.table.account-number') }}</x-tables.header>
                    <x-tables.header>{{ trans('bank.table.account-name') }}</x-tables.header>
                    <x-tables.header>{{ trans('bank.table.status') }}</x-tables.header>
                    <x-tables.header>{{ trans('bank.table.action') }}</x-tables.header>
                </x-tables.theader>
            </x-slot>
            <x-slot name="body">
                @forelse ($banks as $key => $bank)
                    <x-tables.row>
                        <x-tables.cell
                            class="text-center">{{ ($banks->currentpage() - 1) * $banks->perpage() + $loop->index + 1 }}</x-tables.cell>
                        <x-tables.cell>{{ $bank->bank_name }}</x-tables.cell>
                        <x-tables.cell>{{ $bank->account_number }}</x-tables.cell>
                        <x-tables.cell>{{ $bank->account_name }}</x-tables.cell>
                        <x-tables.cell>
                            <div class="flex justify-center">
                                <livewire:components.toogle-button :model="$bank" field="disabled"
                                    dispatch="bank-disable" key="{{ now() }}" />
                                <span class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    @if ($bank->disabled)
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
                                    <flux:tooltip :content="__('bank.tooltip.edit')" position="bottom">
                                        <flux:button size="xs" variant="primary" icon="pencil"  style="cursor: pointer;"
                                            wire:click="$dispatch('show-add-bank-modal', {bank: '{{ $bank->slug }}'})" />
                                    </flux:tooltip>
                                    <flux:tooltip :content="__('bank.tooltip.view')" position="bottom">
                                        <flux:button size="xs" variant="primary" icon="eye" style="cursor: pointer;"
                                            wire:click="$dispatch('show-view-bank-modal', {bank: '{{ $bank->slug }}'})" />
                                    </flux:tooltip>

                                    <flux:tooltip :content="__('bank.tooltip.delete')" position="bottom">
                                        <flux:button size="xs" variant="danger" icon="trash" style="cursor: pointer;"
                                            wire:click="$dispatch('show-delete-bank-modal', {bank: '{{ $bank->slug }}'})" />
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
        @if ($banks->hasPages())
            <div class="p-3">
                {{ $banks->links() }}
            </div>
        @endif

        <livewire:admin.settings.banks.modal.add-bank />
        <livewire:admin.settings.banks.modal.view-bank/>
        <livewire:admin.settings.banks.modal.delete-bank />

    </x-layouts.general-setting>
</section>
