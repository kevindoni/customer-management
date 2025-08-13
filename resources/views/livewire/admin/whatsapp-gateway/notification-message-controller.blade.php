<section class="w-full">
    <x-layouts.general-setting :heading="__('Notification Message')" :subheading="__('Notification message customer.')">
        <x-layouts.whatsapp-gateway.nav-mobile/>
        <div class="my-6 w-full space-y-6">
            <!--Button-->
            <div class="sm:flex justify-between pt-1 pb-1">
                <div class="justify-start">
                </div>
                <div class="justify-end">
                    <flux:button size="sm" wire:click="$dispatch('show-reset-notification-message-modal')"
                        style="cursor: pointer;" variant="danger" icon="arrow-path-rounded-square">
                        {!! trans('billing.button.reset-message') !!}
                    </flux:button>
                </div>
            </div>

            <div wire:loading.class="opacity-75" class="relative overflow-x-auto ">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.no') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.name') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.reply-message') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.status') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.description') }}</x-tables.header>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.action') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($notificationMessages as $key => $message)
                            <x-tables.row>
                                <x-tables.cell
                                    class="text-center">{{ ($notificationMessages->currentpage() - 1) * $notificationMessages->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ $message['name'] }}
                                </x-tables.cell>
                                <x-tables.cell>

                                    {{ $message['message'] }}
                                </x-tables.cell>
                                <x-tables.cell>

                                    {{ $message['disabled'] ? 'Disable' : 'Enable' }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    {{ $message['description'] }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    <flux:button.group>
                                        <flux:button size="xs" variant="primary" icon="pencil"
                                            style="cursor: pointer;"
                                            wire:click="$dispatch('show-edit-notification-message-modal',{message: '{{ $message['id'] }}'})"
                                            title="{{ trans('whatsapp-gateway.button.edit-message') }}" />

                                            <flux:button size="xs" variant="primary" icon="eye" disabled
                                            style="cursor: pointer;" title="{{ trans('whatsapp-gateway.button.edit-message') }}" />
                                        </flux:button.group>
                                </x-tables.cell>


                            </x-tables.row>

                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=6>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('whatsapp-gateway.notification-message-notfound') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse

                    </x-slot>
                </x-tables.table>

                @if ($notificationMessages->hasPages())
                    <div class="p-3">
                        {{ $notificationMessages->links() }}
                    </div>
                @endif
            </div>
    </x-layouts.whatsapp-gateway.layout>
    <livewire:admin.whatsapp-gateway.modal.notification-message.edit-notification-message />
    <livewire:admin.whatsapp-gateway.modal.notification-message.reset-messages />
</section>
