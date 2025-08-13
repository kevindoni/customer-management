<div>
    @if ($disableCustomerPaketModal)
    <flux:modal class="md:w-120" wire:model="disableCustomerPaketModal" :dismissible="false"
        @close="$dispatch('close-add-customer-modal')">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    @if ($customerPaket->disabled)
                    {{ trans('customer.paket.alert.header-enable-paket', [
                    'paket' => $customerPaket->paket->name,
                    'customer' => $customerPaket->user->full_name,
                    ]) }}
                    @else
                    {{ trans('customer.paket.alert.header-disable-paket', [
                    'paket' => $customerPaket->paket->name,
                    'customer' => $customerPaket->user->full_name,
                    ]) }}
                    @endif
                </flux:heading>

                <flux:text class="mt-2">
                    @if ($customerPaket->disabled)
                    {{ trans('customer.paket.alert.content-enable-paket', [
                    'paket' => $customerPaket->paket->name,
                    'customer' => $customerPaket->user->full_name,
                    ]) }}
                    @else
                    {{ trans('customer.paket.alert.content-disable-paket', [
                    'paket' => $customerPaket->paket->name,
                    'customer' => $customerPaket->user->full_name,
                    ]) }}
                    @endif
                </flux:text>
            </div>
            <form wire:submit='disable_paket'>
                <div class="flex flex-col gap-6">
                    <flux:select wire:model.change="input.status"
                        :label="__('customer.paket.ph.status')" name="status">
                        <flux:select.option value="active">{{ trans('customer.status.active') }}</flux:select.option>
                        <flux:select.option value="suspended">{{ trans('customer.status.suspend') }}</flux:select.option>
                        <flux:select.option value="cancelled">{{ trans('customer.status.cancel') }}</flux:select.option>
                    </flux:select>

                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                            wire:click="$set('disableCustomerPaketModal', false)">
                            {{ trans('customer.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit"
                            variant="{{ $button == 'active' ? 'success':'danger' }}" icon="check"
                            style="cursor: pointer;" size="sm">
                            {{ trans('customer.paket.button.'.$button) }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>
    @endif
</div>
