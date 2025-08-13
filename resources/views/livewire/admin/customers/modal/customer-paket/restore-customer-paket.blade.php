<div>
    @if ($restoreCustomerPaketModal)
        <flux:modal class="md:w-120" wire:model="restoreCustomerPaketModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.alert.header-restore-customer-paket', ['customer_paket' => $customerPaket->paket->name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('customer.alert.content-restore-customer-paket', ['customer_paket' => $customerPaket->paket->name]) }}
                    </flux:subheading>
                </div>

                <flux:field variant="inline">
                            <flux:checkbox wire:model.live="input.restoreOnMikrotik" />
                            <flux:label>
                                {{ trans('customer.label.restore-on-mikrotik') }}
                            </flux:label>
                        </flux:field>

                    <div class="flex items-center justify-end gap-2">
                            <flux:button  wire:click="$set('restoreCustomerPaketModal', false)" variant="ghost" icon="x-circle" style="cursor: pointer">
                                {{ trans('user.button.cancel') }}
                            </flux:button>
                            <flux:button wire:click="restoredCustomerPaket" variant="success" iconTrailing="arrow-uturn-left" style="cursor: pointer">
                                {{ __('Restore') }}
                            </flux:button>
                        </div>


            </div>
        </flux:modal>
        @endif

</div>
