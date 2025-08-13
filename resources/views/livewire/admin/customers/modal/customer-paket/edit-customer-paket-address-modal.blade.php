<div>
    @if ($editCustomerPaketAddressModal)
        <flux:modal class="md:w-120" wire:model="editCustomerPaketAddressModal" :dismissible="false"
            @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6">
                <form wire:submit="updateCustomerPaketAddress">
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.update-installation-address', ['customer' => $customerPaket->user->full_name]) }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.paket.update-installation-address', ['customer' => $customerPaket->user->full_name]) }}
                            </flux:subheading>
                        </div>
                        <!--Address -->
                        <flux:field>
                            <flux:input wire:model="input.address" id="address" type="text" name="address" autofocus
                                autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                            <flux:error name="address" />
                        </flux:field>

                        <flux:field>
                            <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                                <flux:select.option value="">{{ trans('address.select-country') }}
                                </flux:select.option>
                                <flux:select.option value="id">Indonesia</flux:select.option>
                            </flux:select>
                            <flux:error name="country" />
                        </flux:field>

                        @if (!is_null($provinces))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.province">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-province') }}
                                    </option>
                                    @foreach ($provinces['result'] as $province)
                                        <option value="{{ json_encode($province) }}">
                                            {{ $province['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="province" />
                            </flux:field>
                        @endif

                        @if (!is_null($cities))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.city">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-city') }}
                                    </option>
                                    @foreach ($cities['result'] as $city)
                                        <option value="{{ json_encode($city) }}">
                                            {{ $city['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="city" />
                            </flux:field>
                        @endif

                        @if (!is_null($districts))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.district">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @foreach ($districts['result'] as $district)
                                        <option value="{{ json_encode($district) }}">
                                            {{ $district['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="district" />
                            </flux:field>
                        @endif

                        @if (!is_null($subDistricts))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.subdistrict">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @foreach ($subDistricts['result'] as $subDistrict)
                                        <option value="{{ json_encode($subDistrict) }}">
                                            {{ $subDistrict['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="subdistrict" />
                            </flux:field>
                        @endif
                        <!--phone -->
                        <flux:input wire:model="input.phone" id="phone" :label="__('customer.label.phone')"
                            type="text" name="phone" autofocus autocomplete="phone"
                            placeholder="{{ __('customer.ph.phone') }}" />

                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:button wire:click="updateInstallationAddress" variant="primary"
                                iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>

                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.update-billing-address', ['customer' => $customerPaket->user->full_name]) }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.paket.update-billing-address', ['customer' => $customerPaket->user->full_name]) }}
                            </flux:subheading>
                        </div>

                        <!--Address -->
                        <flux:field>
                            <flux:input wire:model="input.address" id="address" type="text" name="address"
                                autofocus autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                            <flux:error name="address" />
                        </flux:field>


                        <flux:field>
                            <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                                <flux:select.option value="">{{ trans('address.select-country') }}
                                </flux:select.option>
                                <flux:select.option value="id">Indonesia</flux:select.option>
                            </flux:select>
                            <flux:error name="country" />
                        </flux:field>

                        @if (!is_null($provinces))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.province">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-province') }}
                                    </option>
                                    @foreach ($provinces['result'] as $province)
                                        <option value="{{ json_encode($province) }}">
                                            {{ $province['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="province" />
                            </flux:field>
                        @endif

                        @if (!is_null($cities))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.city">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-city') }}
                                    </option>
                                    @foreach ($cities['result'] as $city)
                                        <option value="{{ json_encode($city) }}">
                                            {{ $city['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="city" />
                            </flux:field>
                        @endif

                        @if (!is_null($districts))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.district">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @foreach ($districts['result'] as $district)
                                        <option value="{{ json_encode($district) }}">
                                            {{ $district['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="district" />
                            </flux:field>
                        @endif

                        @if (!is_null($subDistricts))
                            <flux:field>
                                <x-customer-management.select wire:model.change="input.subdistrict">
                                    <option value="" selected class="placeholder">
                                        {{ trans('address.select-district') }}
                                    </option>
                                    @foreach ($subDistricts['result'] as $subDistrict)
                                        <option value="{{ json_encode($subDistrict) }}">
                                            {{ $subDistrict['text'] }}
                                        </option>
                                    @endforeach
                                </x-customer-management.select>
                                <flux:error name="subdistrict" />
                            </flux:field>
                        @endif

                        <!--phone -->
                        <flux:input wire:model="input.phone" id="phone" :label="__('customer.label.phone')"
                            type="text" name="phone" autofocus autocomplete="phone"
                            placeholder="{{ __('customer.ph.phone') }}" />

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button wire:click="showEditCustomerPaketModal('{{ $customerPaket->slug }}')" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('customer.button.save') }}
                            </flux:button>

                        </div>
                    </div>

                </form>
            </div>
        </flux:modal>
    @endif
</div>
