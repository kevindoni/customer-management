<div>
    @if ($addDeviceModal)
    <flux:modal class="md:w-120" wire:model="addDeviceModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('whatsapp-gateway.heading.add-device') }}
                </flux:heading>
                <flux:subheading>{{ trans('whatsapp-gateway.heading.subtitle-add-device') }}</flux:subheading>
            </div>

            <form wire:submit="addNumber">
                <div class="flex flex-col gap-6">
                    <div class="flex flex-col gap-4">
                        <flux:input wire:model="input.device_name" :label="__('whatsapp-gateway.label.device-name')" type="text"
                            name="device_name" autocomplete="device_name" placeholder="{{ __('whatsapp-gateway.helper.device-name') }}" />
                        <flux:input wire:model="input.body" :label="__('whatsapp-gateway.label.number')" type="text"
                            name="body" autocomplete="body" placeholder="{{ __('whatsapp-gateway.helper.number') }}" />
                    </div>


                    <flux:field>
                        <flux:textarea wire:model="input.description" :label="__('whatsapp-gateway.label.description')"
                            type="text" name="description" autocomplete="description"
                            placeholder="{{ __('whatsapp-gateway.helper.description') }}" />
                        <flux:error name="description" />
                    </flux:field>

                    <div class="flex items-center justify-end">
                        <flux:button wire:click="$set('addDeviceModal', false)" variant="ghost" class="me-2"
                            style="cursor:pointer">
                            {{ __('whatsapp-gateway.button.cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="primary" style="cursor:pointer">
                            {{ __('whatsapp-gateway.button.add-device') }}
                        </flux:button>
                    </div>

                </div>

            </form>
        </div>
    </flux:modal>
    @endif
</div>
