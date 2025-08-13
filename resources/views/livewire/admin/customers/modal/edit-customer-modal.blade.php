<div>
    @if ($editCustomerModal)
        <flux:modal class="md:w-120" wire:model="editCustomerModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('user.title.edit-user', ['user' => $user->full_name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('user.title.edit-user', ['user' => $user->full_name]) }}
                    </flux:subheading>
                </div>
                <form wire:submit='updateCustomerInformation'>
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <div class="grid md:grid-cols-2 gap-4">
                            <flux:input wire:model="input.first_name" :label="__('user.label.first-name')" type="text"
                                name="first_name" autofocus autocomplete="first_name"
                                placeholder="{{ __('user.ph.first-name') }}" />
                            <flux:input wire:model="input.last_name" :label="__('user.label.last-name')" type="text"
                                name="last_name" autofocus autocomplete="last_name"
                                placeholder="{{ __('user.ph.last-name') }}" />
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                        <!--Gender-->
                            <flux:field>
                                <flux:select wire:model="input.gender" :label="__('customer.label.gender')">
                                    <flux:select.option value="" variant="">
                                        {{ trans('customer.ph.select-gender') }}
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
                                <flux:input wire:model="input.dob" type="date" max="2010-12-31" autofocus/>
                                <flux:error name="dob" />
                            </flux:field>
                        </div>

                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:modal.close>
                                <flux:button style="cursor: pointer;" variant="ghost" wire:click="$set('editCustomerModal', false)" style="cursor: pointer">
                                    {{ trans('user.button.cancel') }}</flux:button>
                            </flux:modal.close>
                            <flux:button wire:click='firstStepSubmit' variant="primary" iconTrailing="arrow-right" style="cursor: pointer">
                                {{ __('Next') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
                        <flux:field>
                            <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                                <flux:select.option value="">{{ trans('address.select-country') }}
                                </flux:select.option>
                                <flux:select.option value="id">Indonesia</flux:select.option>
                            </flux:select>
                            <flux:error name="country" />
                        </flux:field>

                        <div class="grid md:grid-cols-2 gap-4">
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
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
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
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            <!--Address -->
                            <flux:field>
                                <flux:input wire:model="input.address" id="address" type="text" name="address" autofocus
                                    autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                                <flux:error name="address" />
                            </flux:field>
                            <!--phone -->
                            <flux:input wire:model="input.phone" id="phone"
                                type="text" name="phone" autofocus autocomplete="phone"
                                placeholder="{{ __('customer.ph.phone') }}" />
                        </div>

                        @if ($user->customer_pakets->count())
                            <!--update installation address -->
                            <flux:checkbox wire:model="changeInstallationAddress"
                                label="{{ trans('customer.label.add-installation-address') }}" />
                            <!--update billing address -->
                            <flux:checkbox wire:model="changeBillingAddress"
                                label="{{ trans('customer.label.add-billing-address') }}" />
                         @endif
                        <div class="flex items-center justify-end  gap-2">
                            <flux:button wire:click="back(1)" variant="primary" icon="arrow-left" style="cursor: pointer">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button wire:click='secondStepSubmit' variant="primary" iconTrailing="arrow-right" style="cursor: pointer">
                                {{ __('Next') }}
                            </flux:button>
                        </div>
                    </div>

                    <div class="{{ $currentStep != 3 ? 'hidden' : '' }} flex flex-col gap-6">

                        <!-- Email Address -->
                        <flux:input wire:model="input.email" id="email" :label="__('Email address')" type="email"
                            name="email" autocomplete="email" placeholder="email@example.com" />

                        <!-- Password -->
                        <flux:input wire:model="input.password" id="password" :label="__('Password')" type="password"
                            name="password" autocomplete="new-password" placeholder="Password" />

                        <!-- Confirm Password -->
                        <flux:input wire:model="input.password_confirmation" id="password_confirmation"
                            :label="__('Confirm password')" type="password" name="password_confirmation"
                            autocomplete="new-password" placeholder="Confirm password" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="back(2)" variant="primary" icon="arrow-left" style="cursor: pointer">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right" style="cursor: pointer">
                                {{ __('Update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
