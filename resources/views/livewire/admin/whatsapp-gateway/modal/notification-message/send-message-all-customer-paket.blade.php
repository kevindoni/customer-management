<div>
    @if ($sendMessageAllCustomerPaketModal)
    <flux:modal class="md:w-screen" wire:model="sendMessageAllCustomerPaketModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('Send WA Message') }}
                </flux:heading>
                <flux:subheading>{{ trans('Send a message to all customers who have a package') }}</flux:subheading>
            </div>
            <flux:error name="status_error" />

            <form wire:submit="sendMessageAllCustomerPaket">
                <div class="flex flex-col gap-6">

                    <flux:field>
                        <flux:textarea rows="10" wire:model="input.message" :label="__('whatsapp-gateway.label.message')"
                            type="text" name="message" autocomplete="message"
                            placeholder="{{ __('whatsapp-gateway.helper.message') }}" />
                        <flux:error name="message" />
                    </flux:field>


                    <div class="flex items-center justify-end">
                        <flux:button wire:click="$set('sendMessageAllCustomerPaketModal', false)" variant="ghost" class="me-2"
                            style="cursor:pointer">
                            {{ __('device.button.cancel') }}
                        </flux:button>

                        <flux:button size="sm" type="submit" style="cursor: pointer;"
                            variant="success" icon="wa">
                            {{ __('Send Message') }}
                        </flux:button>
                    </div>

                </div>

            </form>
        </div>
    </flux:modal>
    @endif
</div>
