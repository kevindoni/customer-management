<div>
    @if ($editCustomerStaticPaketModal)
        <flux:modal class="md:w-120" wire:model="editCustomerStaticPaketModal" :dismissible="false"
            @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.paket.edit-customer-paket', [
                            'paket' => $customerStaticPaket->customer_paket->paket->name,
                            'customer' => $customerStaticPaket->customer_paket->user->full_name,
                            'mikrotik' => $customerStaticPaket->customer_paket->paket->mikrotik->name,
                        ]) }}
                    </flux:heading>
                </div>
                <form wire:submit="editIpStaticPaket" class="flex flex-col gap-6">
                    <flux:select wire:model="input.selectedMikrotikInterface" name="selectedMikrotikInterface"
                        :label="__('customer.paket.label.interface')">

                        @if ($mikrotik_interfaces)
                            <flux:select.option value="">{{ trans('customer.paket.ph.select-interface') }}
                            </flux:select.option>
                            @foreach ($mikrotik_interfaces as $interface)
                                <flux:select.option value="{{ $interface['.id'] }}">
                                    {{ $interface['name'] }}
                                </flux:select.option>
                            @endforeach
                        @else
                            <flux:select.option value="">{{ trans('customer.paket.label.router-offline') }} </flux:select.option>
                        @endif
                    </flux:select>


                    <flux:input wire:model="input.ip_address" :label="__('customer.paket.label.ip-address')"
                        id="ip_address" type="text" name="ip_address" autofocus autocomplete="ip_address"
                        placeholder="{{ __('customer.paket.label.ip-address') }}" />

                    <flux:input wire:model="input.mac_address" :label="__('customer.paket.label.mac-address')"
                        id="mac_address" type="text" name="mac_address" autofocus autocomplete="mac_address"
                        placeholder="{{ __('customer.paket.label.mac-address') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                            wire:click="$set('editCustomerStaticPaketModal', false)">
                            {{ trans('customer.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" icon="arrow-up-right" style="cursor: pointer;"
                            size="sm">
                            {{ __('customer.button.update') }}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
