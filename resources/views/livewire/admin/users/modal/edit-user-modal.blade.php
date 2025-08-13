<div>
    @if ($editUserModal)
        <flux:modal class="md:w-120" wire:model="editUserModal" :dismissible="false"
            @close="$dispatch('close-edit-user-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('user.title.edit-user', ['user' => $userSelect->full_name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('user.title.edit-user', ['user' => $userSelect->full_name]) }}
                    </flux:subheading>
                </div>
                <form wire:submit='updateUserInformation'>
                    <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
                        <flux:input wire:model="input.first_name" :label="__('user.label.first-name')" type="text"
                            name="first_name" autofocus autocomplete="first_name"
                            placeholder="{{ __('user.ph.first-name') }}" />
                        <flux:input wire:model="input.last_name" :label="__('user.label.last-name')" type="text"
                            name="last_name" autofocus autocomplete="last_name"
                            placeholder="{{ __('user.ph.last-name') }}" />
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
                            <flux:input wire:model="input.dob" type="date" max="2010-12-31" />
                            <flux:error name="dob" />
                        </flux:field>

                        <div class="flex items-center justify-end">
                            <flux:button wire:click='firstStepSubmit' variant="primary" iconTrailing="arrow-right">
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


                        <!--Address -->
                        <flux:field>
                            <flux:input wire:model="input.address" id="address" type="text" name="address"
                                autofocus autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                            <flux:error name="address" />
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
                        <flux:field>
                            <flux:select wire:model="input.role" :label="__('user.label.role')">
                                <flux:select.option variant="">{{ trans('user.ph.select-role') }}
                                </flux:select.option>
                                @foreach (Spatie\Permission\Models\Role::all()->pluck('name') as $role)
                                    <flux:select.option value="{{ $role }}">{{ ucfirst($role) }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="role" />
                        </flux:field>
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

                        <div class="flex items-center justify-end  gap-2">
                            <flux:button wire:click="back(2)" variant="primary" icon="arrow-left">
                                {{ __('Back') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('Update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>

        </flux:modal>
    @endif
</div>
