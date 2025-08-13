<div>
    @if ($deleteDeviceModal)
    <flux:modal class="md:w-120" wire:model="deleteDeviceModal" :dismissible="false">
        <div class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ trans('device.heading.delete-number',['number' => $number]) }}
                </flux:heading>
                <flux:subheading>{{ trans('device.heading.subtitle-delete-number',['number' => $number]) }}
                </flux:subheading>
            </div>

            <div class="flex flex-col gap-6">
                <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                type="password" name="current_password" placeholder="{{ __('Input your current password') }}" />
                <div class="flex items-center justify-end">
                    <flux:button wire:click="$set('deleteDeviceModal', false)" variant="ghost" class="me-2"
                        style="cursor:pointer">
                        {{ __('device.button.cancel') }}
                    </flux:button>
                    <flux:button icon="trash" variant="danger" style="cursor:pointer" wire:click="deleteDevice">
                        {{ __('device.button.delete') }}
                    </flux:button>
                </div>

            </div>
        </div>

    </flux:modal>
    @endif
</div>
