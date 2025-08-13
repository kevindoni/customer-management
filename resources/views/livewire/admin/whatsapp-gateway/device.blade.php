<section class="w-full">
    <x-layouts.general-setting :heading="__('Devices')" :subheading="__('Add or delete device')">
        <x-layouts.whatsapp-gateway.nav-mobile/>
        <div class="my-6 w-full space-y-6">
            <div class="flex justify-between mb-2">

                <div class="flex justify-between">
                    <flux:button wire:click="$dispatch('show-add-device-modal')" style="cursor: pointer;"
                        variant="primary" icon="plus-circle">
                        <span class="md:flex hidden">{{ __('whatsapp-gateway.button.add-device') }}</span>
                    </flux:button>
                </div>
            </div>

            <div wire:loading.class="opacity-75" class="relative overflow-x-auto ">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header class="w-1">{{ trans('No') }}</x-tables.header>
                            <x-tables.header>Name</x-tables.header>
                            <x-tables.header>Number</x-tables.header>
                            <x-tables.header>Messages Sent</x-tables.header>
                            <x-tables.header>Status</x-tables.header>
                            <x-tables.header>Connection</x-tables.header>
                            <x-tables.header>Action</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($devices as $key => $number)
                            <x-tables.row>
                                <x-tables.cell
                                    class="text-center">{{ ($devices->currentpage() - 1) * $devices->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ $number['device_name'] }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    {{ $number['body'] }}
                                </x-tables.cell>
                                <x-tables.cell class="text-center">
                                    <flux:badge variant="pill" size="sm" color="indigo" variant="solid">
                                        {{ $number['message_count'] ?? '-' }}
                                    </flux:badge>
                                </x-tables.cell>
                                <x-tables.cell class="text-center">
                                    <flux:badge size="sm"
                                        color="{{ $number['status'] == 'Connected' ? 'green' : 'red' }}"
                                        variant="solid">{{ $number['status'] }}
                                    </flux:badge>

                                </x-tables.cell>
                                <x-tables.cell>
                                    <div class="flex gap-4 justify-center">
                                        <flux:tooltip content="Scan with Code">
                                            <flux:button variant="primary" size="xs" icon="device-phone-mobile"
                                                style="cursor: pointer;"
                                                wire:click="$dispatch('show-scan-code-modal', {number: '{{ $number['body'] }}'})" />
                                        </flux:tooltip>
                                        <flux:tooltip content="Scan with QR Code">
                                            <flux:button variant="primary" size="xs" icon="qr-code"
                                                style="cursor: pointer;"
                                                wire:click="$dispatch('show-scan-barcode-modal', {number: '{{ $number['body'] }}'})" />
                                        </flux:tooltip>
                                    </div>
                                </x-tables.cell>
                                <x-tables.cell>
                                    <div class="flex gap-2 justify-end">
                                        @if ($number['status'] == 'Connected')
                                            <flux:tooltip content="Test Send Message">
                                                <flux:button wire:click="testSendMessage('{{ $number['body'] }}')"
                                                    size="xs" variant="primary" icon="envelope"
                                                    style="cursor: pointer;" />
                                            </flux:tooltip>
                                        @endif
                                        <flux:tooltip content="Edit Device">
                                            <flux:button
                                                wire:click="$dispatch('show-edit-device-modal', {number: '{{ $number['body'] }}'})"
                                                size="xs" variant="primary" icon="pencil"
                                                style="cursor: pointer;" />
                                        </flux:tooltip>
                                        <flux:tooltip content="Delete Device">
                                            <flux:button
                                                wire:click="$dispatch('show-delete-device-modal', {number: '{{ $number['body'] }}'})"
                                                size="xs" variant="danger" icon="trash"
                                                style="cursor: pointer;" />
                                        </flux:tooltip>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=7>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('whatsapp-gateway.number-notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse
                    </x-slot>
                </x-tables.table>
                @if ($devices->hasPages())
                    <div class="p-3">
                        {{ $devices->links() }}
                    </div>
                @endif
            </div>
        </div>


    </x-layouts.general-setting>
    <livewire:admin.whatsapp-gateway.modal.add-device />
    <livewire:admin.whatsapp-gateway.modal.edit-device />
    <livewire:admin.whatsapp-gateway.modal.scan-barcode />
    <livewire:admin.whatsapp-gateway.modal.scan-code />
    <livewire:admin.whatsapp-gateway.modal.delete-device />

    @push('scripts')
    @endpush
</section>
