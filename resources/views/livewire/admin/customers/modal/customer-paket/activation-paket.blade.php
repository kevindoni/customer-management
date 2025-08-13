<div>
    @if ($activationCustomerPaketModal)
        <flux:modal wire:model="activationCustomerPaketModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.paket.alert.header-activation-paket', [
                            'paket' => $customerPaket->paket->name,
                            'customer' => $customerPaket->user->first_name,
                        ]) }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {{ trans('customer.paket.alert.content-activation-paket', [
                            'paket' => $customerPaket->paket->name,
                            'customer' => $customerPaket->user->first_name,
                        ]) }}
                    </flux:text>
                </div>
                <form wire:submit='activation_paket'>
                    <div class="flex flex-col gap-6">
                        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                            type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                                wire:click="$set('activationCustomerPaketModal', false)">
                                {{ trans('customer.button.cancel') }}
                            </flux:button>


                            <flux:button type="submit" variant="primary" icon="check" style="cursor: pointer;"
                                size="sm">
                                {{ trans('customer.paket.button.activation-paket') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
