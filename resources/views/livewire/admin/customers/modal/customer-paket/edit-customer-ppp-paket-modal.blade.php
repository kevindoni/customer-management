<div>
    @if ($editCustomerPppPaketModal)
        <flux:modal class="md:w-120" wire:model="editCustomerPppPaketModal" :dismissible="false"
            @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6 pr-4">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.paket.edit-customer-paket', [
                            'paket' => $customerPppPaket->customer_paket->paket->name,
                            'customer' => $customerPppPaket->customer_paket->user->full_name,
                            'mikrotik' => $customerPppPaket->customer_paket->paket->mikrotik->name,
                        ]) }}
                    </flux:heading>
                </div>
                <form wire:submit="editPppPaket" class="flex flex-col gap-6">
                    <flux:select wire:model.change="input.selectedPppService">

                        @foreach (\App\Models\Pakets\PppType::all() as $pppType)
                            <flux:select.option value="{{ $pppType->id }}">
                                {{ $pppType->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:input wire:model="input.username" :label="__('customer.paket.label.username-ppp')" clearable
                        id="username" type="text" name="username" autofocus autocomplete="username"
                        placeholder="{{ __('customer.paket.label.username-ppp') }}" />
                    <flux:input wire:model="input.password_ppp" :label="__('customer.paket.label.password-ppp')" clearable
                        id="password_ppp" type="text" name="password_ppp" autofocus autocomplete="password_ppp"
                        placeholder="{{ __('customer.paket.label.password-ppp') }}" />
                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                            wire:click="$set('editCustomerPppPaketModal', false)">
                            {{ trans('customer.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" icon="arrow-up-right" style="cursor: pointer;"
                            size="sm">
                            {{ trans('customer.button.update') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
