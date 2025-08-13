<div>
    @if ($editMacAddressModal)
        <flux:modal wire:model="editMacAddressModal" class="md:w-96">
            <div class="space-y-6">

                <form wire:submit='edit_mac_address'>
                    <div class="flex flex-col gap-6">
                        <flux:input wire:model="input.mac_address" :label="__('customer.paket.label.mac-address')"
                        type="text" name="mac_address" placeholder="{{ __('Add MAC Address') }}" />

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                                wire:click="$set('editMacAddressModal', false)">
                                {{ trans('customer.button.cancel') }}
                            </flux:button>

                            <flux:button type="submit" variant="primary" icon="arrow-up-right" style="cursor: pointer;"
                                size="sm">
                                {{ trans('customer.paket.button.update-mac-address') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
