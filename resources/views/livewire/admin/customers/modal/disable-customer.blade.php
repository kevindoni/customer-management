<div>
    @if ($disableCustomerModal)
        <flux:modal class="md:w-120" wire:model="disableCustomerModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $user->disabled ? trans('customer.alert.header-enable-customer', ['customer' => $user->full_name]) : trans('customer.alert.header-disable-customer', ['customer' => $user->full_name]) }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!! $user->disabled
                            ? trans('customer.alert.content-enable-customer', [
                                'customer' => $user->full_name,
                                'count_paket' => count($user->customer_pakets),
                            ])
                            : trans('customer.alert.content-disable-customer', [
                                'customer' => $user->full_name,
                                'count_paket' => count($user->customer_pakets),
                            ]) !!}
                    </flux:text>
                </div>
                <form wire:submit='disableCustomer' class="flex flex-col gap-6">
                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('disableCustomerModal', false)">
                                {{ trans('user.button.cancel') }}</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="{{ $user->disabled ? 'success' : 'danger' }}" icon="power">
                            {{ $user->disabled ? trans('customer.button.enable') : trans('customer.button.disable') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
