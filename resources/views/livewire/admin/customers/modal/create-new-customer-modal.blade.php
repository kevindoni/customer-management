<div>
    <flux:modal name="add-customer-modal" class="md:w-120" :dismissible="false"
    @close="$dispatch('close-add-customer-modal')">
            <div class="space-y-6">
                <form wire:submit="addCustomer">
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.add-customer') }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.add-customer') }}
                            </flux:subheading>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <flux:input wire:model="input.first_name" :label="__('user.label.first-name')" type="text"
                                name="first_name" autofocus autocomplete="mikrotik_name"
                                placeholder="{{ __('user.ph.first-name') }}" />
                            <flux:input wire:model="input.last_name" :label="__('user.label.last-name')" type="text"
                                name="last_name" autofocus autocomplete="mikrotik_host"
                                placeholder="{{ __('user.ph.last-name') }}" />
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!--Gender-->
                            <flux:field>
                                <flux:select wire:model="input.gender" :label="__('customer.label.gender')">
                                    <flux:select.option variant="">{{ trans('customer.ph.select-gender') }}
                                    </flux:select.option>
                                    <flux:select.option value="male">{{ trans('customer.ph.male') }}
                                    </flux:select.option>
                                    <flux:select.option value="female">{{ trans('customer.ph.female') }}
                                    </flux:select.option>
                                </flux:select>
                                <flux:error name="gender" />
                            </flux:field>

                            <!--DOB-->
                            <flux:field>
                                <flux:label>{{ __('customer.label.dob') }}</flux:label>
                                <flux:input wire:model="input.dob" type="date" max="2010-12-31" />
                                <flux:error name="dob" />
                            </flux:field>
                        </div>

                        <div class="flex items-center justify-end">
                            <flux:button wire:click='firstStepSubmit' variant="primary" iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.add-customer') }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.add-customer') }}
                            </flux:subheading>
                        </div>
                        <flux:field>
                            <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                                <flux:select.option value="">{{ trans('address.select-country') }}
                                </flux:select.option>
                                <flux:select.option value="id">Indonesia</flux:select.option>
                            </flux:select>
                            <flux:error name="country" />
                        </flux:field>

                        <div class="grid md:grid-cols-2 gap-4">
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
                        <div class="grid md:grid-cols-2 gap-4">
                            <!--Address -->
                            <flux:field>
                                <flux:input wire:model="input.address" id="address" type="text" name="address"
                                    autofocus autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                                <flux:error name="address" />
                            </flux:field>

                            <!--phone -->
                            <flux:input wire:model="input.phone" id="phone" type="text" name="phone" autofocus autocomplete="phone"
                                placeholder="{{ __('customer.ph.phone') }}" />
                        </div>
                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="back(1)" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button wire:click='secondStepSubmit' variant="primary" iconTrailing="arrow-right">
                                {{ __('Next') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="{{ $currentStep != 3 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('customer.add-customer') }}
                            </flux:heading>
                            <flux:subheading>
                                {{ trans('customer.subheading.provide-email-address-to-login') }}
                            </flux:subheading>
                        </div>
                        <!-- Email Address -->
                        <flux:input wire:model="input.email" id="email" :label="__('Email address')" type="email"
                            name="email" autocomplete="email" placeholder="email@example.com" />

                        <!-- Password -->
                        <flux:input wire:model="input.password" id="password" :label="__('Password')" type="password"
                            name="password" autocomplete="new-password" placeholder="Password" />



                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="back(2)" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('Add Customer') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>

    </flux:modal>

</div>
