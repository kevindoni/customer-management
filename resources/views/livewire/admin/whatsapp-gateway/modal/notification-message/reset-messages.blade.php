<div>
    @if ($resetNotificationMessageModal)
    <flux:modal class="md:w-160" wire:model="resetNotificationMessageModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('Reset Notification Message') }}
                </flux:heading>
                <flux:subheading>{{ trans('Warning!! Notification messages will be reset to default settings.') }}</flux:subheading>
            </div>


            <form wire:submit="resetNotificationMessage">
                <div class="flex flex-col gap-6">

                     <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                    <div class="flex items-center justify-end">
                        <flux:button wire:click="$set('resetNotificationMessageModal', false)" variant="primary" class="me-2"
                            style="cursor:pointer">
                            {{ __('device.button.cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="danger" style="cursor:pointer" icon="arrow-path-rounded-square">
                            {{ __('Reset') }}
                        </flux:button>
                    </div>

                </div>

            </form>
        </div>
    </flux:modal>
    @endif
</div>
