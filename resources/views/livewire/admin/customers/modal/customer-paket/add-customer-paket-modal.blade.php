<div>
    @if ($addCustomerPaketModal)
        <flux:modal class="md:w-160" wire:model="addCustomerPaketModal" :dismissible="false"
            @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6">

                <form wire:submit="addCustomerPaket">
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.add-customer-paket', ['customer' => $user->full_name]) }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.paket.add-customer-paket', ['customer' => $user->full_name]) }}
                            </flux:subheading>
                        </div>
                        <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                            <div>
                                <flux:select wire:model.change="selectedServer" name="selectedServer"
                                    :label="__('customer.paket.label.server')">
                                    <flux:select.option value=""> {{ trans('customer.paket.ph.select-server') }}
                                    </flux:select.option>
                                    @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $mikrotik)
                                        <flux:select.option value="{{ $mikrotik->id }}">
                                            {{ $mikrotik->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>

                            </div>
                            <div>
                                <flux:select wire:model.change="input.selectedPaket" wire:key="{{ $selectedServer }}"
                                    :label="__('customer.paket.ph.select-paket')" name="selectedPaket">
                                    <flux:select.option value=""> {{ trans('customer.paket.ph.select-paket') }}
                                    </flux:select.option>
                                    @if ($selectedServer)
                                        @foreach (\App\Models\Servers\Mikrotik::whereId($selectedServer)->first()->paketsOrderByPrice->where('disabled', false) as $paket)
                                            <flux:select.option value="{{ $paket->id }}">
                                                {{ $paket->name }} - @if ($paket->price == 0)
                                                    {{ trans('paket.free') }}
                                                @else
                                                    @moneyIDR($paket->price)
                                                @endif
                                            </flux:select.option>
                                        @endforeach
                                    @endif
                                </flux:select>
                            </div>
                        </div>

                        @if ($selectedPaket != '')
                            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                                <div>
                                    <flux:select wire:model.change="input.selectedInternetService"
                                        :label="__('customer.paket.label.internet-service')"
                                        name="selectedInternetService">
                                        <flux:select.option value="">
                                            {{ trans('customer.paket.ph.select-service') }}
                                        </flux:select.option>
                                        @foreach ($internet_services as $internet_service)
                                            <flux:select.option value="{{ $internet_service->value }}">
                                                {{ $internet_service->name }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                </div>
                                <div>
                                    @if ($selectedInternetService == 'ppp')
                                        <flux:select wire:model.change="input.selectedPppService"
                                            :label="__('customer.paket.label.ppp-services')" name="selectedPppService">
                                            <flux:select.option value="">
                                                {{ trans('customer.paket.ph.select-ppp-service') }}
                                            </flux:select.option>
                                            @foreach ($ppp_services as $ppp_service)
                                                <flux:select.option value="{{ $ppp_service->id }}">
                                                    {{ $ppp_service->name }}
                                                </flux:select.option>
                                            @endforeach
                                        </flux:select>
                                    @elseif ($selectedInternetService == 'ip_static')
                                        <flux:select wire:model.change="input.selectedMikrotikInterface"
                                            :label="__('customer.paket.label.interface')"
                                            name="selectedMikrotikInterface">
                                            <flux:select.option value="">
                                                {{ trans('customer.paket.ph.select-interface') }}
                                            </flux:select.option>
                                            @if ($mikrotik_interfaces)
                                                @foreach ($mikrotik_interfaces as $interface)
                                                    <flux:select.option value="{{ $interface['.id'] }}">
                                                        {{ $interface['name'] }}
                                                    </flux:select.option>
                                                @endforeach
                                            @endif
                                        </flux:select>
                                    @endif
                                </div>
                            </div>

                            <div class="grid auto-rows-min gap-4 md:grid-cols-2">

                                @if ($selectedInternetService == 'ip_static')
                                    <div>
                                        <flux:input wire:model="input.ip_address"
                                            :label="__('customer.paket.label.ip-address')" type="text"
                                            name="ip_address" autofocus autocomplete="ip_address"
                                            placeholder="{{ __('customer.paket.label.ip-address') }}" />
                                    </div>
                                @endif
                            </div>
                            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                                <flux:select wire:model.change="input.renewal_period"
                                        :label="__('customer.paket.ph.renewal-periode')" name="renewal_periode">
                                    <flux:select.option value=""> {{ trans('customer.paket.ph.select-renewal-periode') }}</flux:select.option>
                                    <flux:select.option value="monthly">{{ trans('customer.paket.ph.monthly') }}</flux:select.option>
                                    <flux:select.option value="bimonthly">{{ trans('customer.paket.ph.bimonthly') }}</flux:select.option>
                                    <flux:select.option value="quarterly">{{ trans('customer.paket.ph.quarterly') }}</flux:select.option>
                                    <flux:select.option value="semi-annually">{{ trans('customer.paket.ph.semi-annually') }}</flux:select.option>
                                    <flux:select.option value="annually">{{ trans('customer.paket.ph.annually') }}</flux:select.option>
                                </flux:select>
                                @if ($selectedPaket->price > 0)
                                    <div>
                                        <flux:input wire:model="input.discount"
                                            :label="__('customer.paket.label.discount')" type="text" name="discount"
                                            autofocus autocomplete="discount"
                                            placeholder="{{ __('customer.paket.label.discount') }}" />
                                    </div>
                                @endif
                            </div>
                        @endif


                        <div class="flex gap-2">
                            <flux:spacer />

                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('addCustomerPaketModal', false)">
                                {{ trans('customer.button.cancel') }}
                            </flux:button>


                            <flux:button wire:click="addressInstallation" variant="primary" iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>

                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.add-installation-address', ['customer' => $user->full_name]) }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.paket.add-installation-address', ['customer' => $user->full_name]) }}
                            </flux:subheading>
                        </div>

                        <flux:checkbox wire:model.live="checkbox_installation_address"
                            label="{{ trans('customer.label.add-installation-address') }}" />

                        @if ($checkbox_installation_address)
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
                        @endif


                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button wire:click="back(1)" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>

                            <flux:button wire:click="addressBilling" variant="primary" iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>

                        </div>
                    </div>

                    <div class="{{ $currentStep != 3 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.paket.add-billing-address', ['customer' => $user->full_name]) }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.paket.add-billing-address', ['customer' => $user->full_name]) }}
                            </flux:subheading>
                        </div>

                        <flux:checkbox wire:model.live="checkbox_billing_address"
                            label="{{ trans('customer.label.add-billing-address') }}" />

                        @if ($checkbox_billing_address)
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

                        @endif

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button wire:click="back(2)" variant="primary" icon="arrow-left">
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
