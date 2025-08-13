<div>
    @if ($bulkDeleteCustomerModal)
        <flux:modal class="md:w-120" wire:model="bulkDeleteCustomerModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.alert.header-bulk-delete-customer', ['count' => $users->count()]) }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!!trans('customer.alert.content-bulk-delete-customer', ['count' => $users->count()]) !!}
                    </flux:text>
                </div>
                <form wire:submit='bulkDeleteCustomer'  class="space-y-6">
                     <div class="space-y-4">
                        <flux:field variant="inline">
                            <flux:checkbox wire:model.live="input.deleteOnMikrotik" />
                            <flux:label>
                                {{ trans('whatsapp-gateway.label.delete-on-mikrotik') }}
                            </flux:label>
                        </flux:field>


                        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" viewable
                            type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                     </div>

                    <div class="flex gap-2">
                        <flux:button class="cursor-pointer" variant="ghost"
                            wire:click="$set('bulkDeleteCustomerModal', false)">
                            {{ trans('user.button.cancel') }}</flux:button>

                        <flux:button type="submit" variant="danger" icon="trash"  class="cursor-pointer">
                            {{ trans('customer.button.delete')}}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
