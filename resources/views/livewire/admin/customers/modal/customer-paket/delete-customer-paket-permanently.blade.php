<div>
    @if ($deletePermanentlyCustomerPaketModal)
        <flux:modal class="md:w-120" wire:model="deletePermanentlyCustomerPaketModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {!! trans('customer.alert.delete-permanently') !!}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!!trans('customer.paket.alert.customer-paket-delete-permanently') !!}
                    </flux:text>
                    @if ($input['deleteOnMikrotik'])
                    <flux:text>
                        {!!trans('customer.paket.alert.content-delete-customer-paket2') !!}
                    </flux:text>
                     @endif

                </div>
                <form wire:submit='deletePermanentlyCustomerPaket' class="flex flex-col gap-6">

                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('customer.ph.input-your-password') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('deletePermanentlyCustomerPaketModal', false)">
                                {{ trans('user.button.cancel') }}</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="danger" icon="trash">
                            {{ trans('customer.button.delete')}}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
