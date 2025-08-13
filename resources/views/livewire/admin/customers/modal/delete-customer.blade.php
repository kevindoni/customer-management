<div>
    @if ($deleteCustomerModal)
        <flux:modal class="md:w-120" wire:model="deleteCustomerModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.alert.header-delete-customer', ['customer' => $user->full_name]) }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!!trans('customer.alert.content-delete-customer', ['customer' => $user->first_name, 'count_paket' => count($user->customer_pakets)]) !!}
                    </flux:text>
                </div>
                <form wire:submit='deleteCustomer' class="space-y-6">

                    <div class="space-y-4">
                        @if($user->customer_pakets()->whereNotNull('activation_date')->withTrashed()->count())
                        <flux:field variant="inline">
                            <flux:checkbox wire:model.live="input.deleteOnMikrotik" />
                            <flux:label>
                                {{ trans('customer.label.delete-on-mikrotik') }}
                            </flux:label>
                        </flux:field>
                        @endif

                        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" viewable
                            type="password" name="current_password" placeholder="{{ __('Input your password') }}" />
                    </div>

                    <div class="flex gap-2">
                        <flux:button class="cursor-pointer" variant="ghost"
                            wire:click="$set('deleteCustomerModal', false)">
                            {{ trans('user.button.cancel') }}</flux:button>

                        <flux:button type="submit" variant="danger" icon="trash" class="cursor-pointer">
                            {{ trans('customer.button.delete')}}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
