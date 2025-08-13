<div>
    @if ($editNotificationMessageModal)
    <flux:modal class="md:w-screen" wire:model="editNotificationMessageModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('whatsapp-gateway.heading.edit-message') }}
                </flux:heading>
                <flux:subheading>{{ trans('whatsapp-gateway.heading.subtitle-edit-message') }}</flux:subheading>
            </div>
            <flux:error name="status_error" />

            <form wire:submit="update">
                <div class="flex flex-col gap-6">
                    <div>
                        <flux:input wire:model="input.name" :label="__('whatsapp-gateway.label.name')"
                            type="text" name="name" autocomplete="name" disabled filled iconTrailing="lock-closed"
                            placeholder="{{ __('whatsapp-gateway.helper.name') }}" />
                    </div>
                    <flux:field>
                        <flux:textarea rows="10" wire:model="input.message" :label="__('whatsapp-gateway.label.message')"
                            type="text" name="message" autocomplete="message"
                            placeholder="{{ __('whatsapp-gateway.helper.message') }}" />
                        <flux:error name="message" />
                    </flux:field>
                    <flux:field>
                        <flux:textarea wire:model="input.description" :label="__('whatsapp-gateway.label.description')"
                            type="text" name="description" autocomplete="description"
                            placeholder="{{ __('whatsapp-gateway.helper.description') }}" />
                        <flux:error name="description" />
                    </flux:field>

                    <div class="flex items-center justify-end">
                        <flux:button wire:click="$set('editNotificationMessageModal', false)" variant="ghost" class="me-2"
                            style="cursor:pointer">
                            {{ __('device.button.cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary" style="cursor:pointer">
                            {{ __('device.button.update') }}
                        </flux:button>
                    </div>

                </div>

            </form>
        </div>
    </flux:modal>
    @endif
</div>
