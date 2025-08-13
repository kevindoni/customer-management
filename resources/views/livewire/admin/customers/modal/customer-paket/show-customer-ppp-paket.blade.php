<div>
    @if ($showCustomerPppPaketModal)
        <flux:modal class="md:w-96" wire:model="showCustomerPppPaketModal" :dismissible="false"
            @close="$dispatch('close-show-customer-ppp-paket-modal')">
            <div class="space-y-6">
                    <div class="flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.show-ppp-detail') }}
                            </flux:heading>
                            <flux:text class="mt-2">{{ trans('customer.paket.show-ppp-detail-description') }}</flux:text>
                        </div>

                        <flux:input wire:model="usernamePpp" icon="user-circle" readonly/>
                        <flux:input wire:model="passwordPpp" icon="key" readonly/>


                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('showCustomerPppPaketModal', false)">
                                {{ trans('customer.button.close') }}
                            </flux:button>
                        </div>
                    </div>
            </div>
        </flux:modal>
    @endif
</div>
