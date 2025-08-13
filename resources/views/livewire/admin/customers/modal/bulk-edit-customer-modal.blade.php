<div>
    @if ($bulkEditCustomerModal)
        <flux:modal class="md:w-120" wire:model="bulkEditCustomerModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.alert.header-bulk-edit-address-customer', ['count' => $users->count()]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('customer.alert.content-bulk-edit-address-customer') }}
                    </flux:subheading>
                </div>
                <form wire:submit='updateBulkCustomerInformation'>
                    <div class="flex flex-col gap-6">
                        <flux:field>
                            <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                                <flux:select.option value="">{{ trans('address.select-country') }}
                                </flux:select.option>
                                <flux:select.option value="id">Indonesia</flux:select.option>
                            </flux:select>
                            <flux:error name="country" />
                        </flux:field>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!-- Provinces-->
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.province">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-province') }}
                                    </option>
                                    @if (!is_null($provinces))
                                        @foreach ($provinces['result'] as $province)
                                            <option value="{{ json_encode($province) }}">
                                                {{ $province['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-customer-management.select>
                                <flux:error name="province" />
                            </flux:field>

                            <!-- Cities-->
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.city">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-city') }}
                                    </option>
                                    @if (!is_null($cities))
                                        @foreach ($cities['result'] as $city)
                                            <option value="{{ json_encode($city) }}">
                                                {{ $city['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-customer-management.select>
                                <flux:error name="city" />
                            </flux:field>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!--District-->
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.district">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @if (!is_null($districts))
                                        @foreach ($districts['result'] as $district)
                                            <option value="{{ json_encode($district) }}">
                                                {{ $district['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-customer-management.select>
                                <flux:error name="district" />
                            </flux:field>

                            <!--Sub District-->
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.subdistrict">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @if (!is_null($subDistricts))
                                        @foreach ($subDistricts['result'] as $subDistrict)
                                            <option value="{{ json_encode($subDistrict) }}">
                                                {{ $subDistrict['text'] }}
                                            </option>
                                        @endforeach
                                    @endif
                                </x-customer-management.select>
                                <flux:error name="subdistrict" />
                            </flux:field>
                        </div>

                        <!--Address -->
                        <flux:field>
                            <flux:input wire:model="input.address" id="address" type="text" name="address"
                                autofocus autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                            <flux:error name="address" />
                        </flux:field>

                        <!--update installation address -->
                        <flux:checkbox wire:model="changeInstallationAddress"
                            label="{{ trans('customer.label.add-installation-address') }}" />
                        <!--update billing address -->
                        <flux:checkbox wire:model="changeBillingAddress"
                            label="{{ trans('customer.label.add-billing-address') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="$set('bulkEditCustomerModal', false)" variant="ghost"
                                icon="arrow-left" style="cursor: pointer">
                                {{ __('customer.button.cancel') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right"
                                style="cursor: pointer">
                                {{ __('customer.button.update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
