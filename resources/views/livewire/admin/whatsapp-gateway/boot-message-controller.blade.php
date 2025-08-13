<section class="w-full">
    <x-layouts.general-setting :heading="__('Boot Message')" :subheading="__('Auto reply message.')">
        <x-layouts.whatsapp-gateway.nav-mobile/>
        <div class="my-6 w-full space-y-6">

            <x-customer-management.admin-tree-message />

            <!--Button-->
            <div class="sm:flex justify-between pt-1 pb-1">
                <div class="justify-start">
                </div>
                <div class="justify-end">
                    <flux:button size="sm" wire:click="$dispatch('show-reset-boot-message-modal')"
                        style="cursor: pointer;" variant="danger" icon="arrow-path-rounded-square">
                        {!! trans('billing.button.reset-message') !!}
                    </flux:button>
                </div>
            </div>
            <!--Table-->
            <div wire:loading.class="opacity-75" class="relative overflow-x-auto ">
                <x-tables.table class="table-fixed">
                    <x-slot name="header">
                        <x-tables.theader>
                            <x-tables.header>{{ trans('whatsapp-gateway.table.no') }}</x-tables.header>
                                    <x-tables.header sortable wire:click.prevent="sortBy('name')"
                                        :direction="$sortField === 'name' ? $sortDirection : null">{{ trans('whatsapp-gateway.table.name') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.parent-message') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.command-number') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.action-message') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.message') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.enable') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.hide') }}</x-tables.header>
                                    <x-tables.header>{{ trans('whatsapp-gateway.table.description') }}</x-tables.header>

                                    <x-tables.header>{{ trans('whatsapp-gateway.table.action') }}</x-tables.header>
                        </x-tables.theader>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($bootMessages as $key => $bootMessage)
                            <x-tables.row>
                                <x-tables.cell
                                    class="text-center">{{ ($bootMessages->currentpage() - 1) * $bootMessages->perpage() + $loop->index + 1 }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ $bootMessage->name }}
                                </x-tables.cell>
                                <x-tables.cell>
                                    @if (!is_null($bootMessage->whatsapp_message_boot_id))
                                        {{ $bootMessages->where('id', $bootMessage->whatsapp_message_boot_id)->first()->name }}
                                    @endif
                                </x-tables.cell>
                                <x-tables.cell class="text-center">

                                    {{ $bootMessage->command_number }}
                                    @if ($bootMessage->end_message)
                                        {{ trans('wa-gateway.end-message') }}
                                    @endif

                                </x-tables.cell>
                                <x-tables.cell>{{ $bootMessage->action_message }}</x-tables.cell>
                                <x-tables.cell>
                                    {{ Illuminate\Support\Str::limit($bootMessage->message, 20, '...') }}
                                </x-tables.cell>
                                <x-tables.cell class="text-center">
                                    <div class="inline-flex">
                                        <livewire:components.toogle-button :model="$bootMessage" field="disabled"
                                            dispatch="refresh-wa-boot-message" key="{{ now() }}" />
                                    </div>
                                </x-tables.cell>
                                <x-tables.cell class="text-center">
                                    <div class="inline-flex">
                                        <livewire:components.toogle-button-enable :model="$bootMessage"
                                            field="hidden_message" dispatch="refresh-wa-boot-message"
                                            key="{{ now() }}" />
                                    </div>
                                </x-tables.cell>
                                <x-tables.cell>{{ $bootMessage->description }}</x-tables.cell>

                                <x-tables.cell class="text-right">
                                    <div class="flex">
                                        <flux:tooltip content="{{ trans('whatsapp-gateway.button.delete-message') }}" style="cursor: pointer">
                                            <flux:button icon="trash" variant="danger" size="sm"
                                                wire:click="$dispatch('delete-message-modal', {whatsappMessageBoot: '{{ $bootMessage->id }}'})">
                                            </flux:button>
                                        </flux:tooltip>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @empty
                            <x-tables.row>
                                <x-tables.cell colspan=9>
                                    <div class="flex justify-center items-center">
                                        <span class="font-medium py-8 text-gray-400 text-xl">
                                            {{ trans('whatsapp-gateway.message-not-found') }}
                                        </span>
                                    </div>
                                </x-tables.cell>
                            </x-tables.row>
                        @endforelse

                    </x-slot>
                </x-tables.table>
            </div>

        </div>
    </x-layouts.general-setting>
    <livewire:admin.whatsapp-gateway.modal.boot-messages.edit-boot-message />
    <livewire:admin.whatsapp-gateway.modal.boot-messages.reset-messages />
</section>
