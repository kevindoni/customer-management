<div>
    @if ($disableWaInstallationAddressModal)
        <flux:modal class="md:w-120" wire:model="disableWaInstallationAddressModal" :dismissible="false"
            @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        @if ($customerPaket->customer_installation_address->wa_notification)
                            {{ trans('customer.paket.disable-wa-installation-address-notification', ['customer' => $customerPaket->user->full_name]) }}
                        @else
                            {{ trans('customer.paket.enable-wa-installation-address-notification', ['customer' => $customerPaket->user->full_name]) }}
                        @endif
                    </flux:heading>

                    <flux:text class="mt-2">
                        @if ($customerPaket->customer_installation_address->wa_notification)
                            {{ trans('customer.paket.disable-wa-installation-address-detail') }}
                        @else
                            {{ trans('customer.paket.enable-wa-installation-address-detail') }}
                        @endif
                    </flux:text>
                </div>
                <form wire:submit='disable_wa_notification_installation_address'>
                    <div class="flex flex-col gap-6">
                        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                            type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button style="cursor: pointer;" variant="ghost" size="sm" wire:click="$set('disableWaInstallationAddressModal', false)">
                                {{ trans('customer.button.cancel') }}
                            </flux:button>

                            <flux:button type="submit" variant="primary" icon="check" style="cursor: pointer;"
                                size="sm">
                                @if ($customerPaket->customer_installation_address->wa_notification)
                                    {{ trans('customer.button.disable-wa-notification') }}
                                @else
                                    {{ trans('customer.button.enable-wa-notification') }}
                                @endif

                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>

