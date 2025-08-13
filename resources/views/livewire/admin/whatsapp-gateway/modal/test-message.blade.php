<div>
    @if ($testMessageModal)
    <flux:modal class="md:w-120" wire:model="testMessageModal" :dismissible="false">
        <div>
            <flux:heading size="lg">
                {{ trans('device.heading.whatsapp-account') }}
            </flux:heading>
            <flux:heading>{{ $number->body }} </flux:heading>
            <flux:subheading>{{ trans('device.heading.dont-leave-phone') }}
            </flux:subheading>
        </div>

        <form wire:submit="sendMessage">
            <div class="flex flex-col gap-6">
                <flux:input wire:model="input.number" :label="__('device.label.number')" type="text" name="number"
                    autocomplete="number" placeholder="{{ __('device.helper.number') }}" />

                <div class="flex items-center justify-end">
                    <flux:button wire:click="$set('testMessageModal', false)" variant="primary" class="me-2"
                        style="cursor:pointer">
                        {{ __('device.button.cancel') }}
                    </flux:button>
                    <flux:button type="submit" variant="primary" style="cursor:pointer">
                        {{ __('device.button.send') }}
                    </flux:button>
                </div>

            </div>

    </flux:modal>
    @endif
</div>
