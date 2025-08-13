<div class="flex flex-col gap-6">
    <x-auth-header :title="\App\Models\Websystem::first()->title ?? env('APP_NAME')"
        :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <div class="{{ $currentStep != 1 ? 'hidden' : '' }} flex flex-col gap-6">
            <!-- First Name -->
            <flux:input wire:model="input.first_name" id="first_name" :label="__('customer.label.first-name')"
                type="text" name="first_name" autofocus autocomplete="first_name"
                placeholder="{{ __('customer.ph.first-name') }}" />

            <!--Last Name -->
            <flux:input wire:model="input.last_name" id="last_name" :label="__('customer.label.last-name')" type="text"
                name="last_name" autofocus autocomplete="last_name" placeholder="{{ __('customer.ph.last-name') }}" />

            <!--Gender-->
            <flux:field>
                <flux:select wire:model="input.gender" :label="__('customer.label.gender')">
                    <flux:select.option variant="">{{ trans('customer.ph.select-gender') }}</flux:select.option>
                    <flux:select.option value="male">{{ trans('customer.ph.male') }}</flux:select.option>
                    <flux:select.option value="female">{{ trans('customer.ph.female') }}</flux:select.option>
                </flux:select>
                <flux:error name="gender" />
            </flux:field>

            <!--DOB-->
            <flux:field>
                <flux:label>{{ __('customer.label.dob') }}</flux:label>
                <flux:input placeholder="{{ __('customer.ph.dob') }}" id="dob" wire:model="input.dob" type="text"
                    x-ref="datepicker" x-init="new Pikaday({
                        field: document.getElementById('dob'),
                        format: 'YYYY-MM-DD',
                        toString(date, format) {
                            const day = String(date.getDate()).padStart(2, 0);
                            const month = String(date.getMonth() + 1).padStart(2, 0);
                            const year = date.getFullYear();
                            return `${day}/${month}/${year}`;
                        },
                        minDate: new Date(1950, 0, 1),
                        maxDate: new Date(2009, 11, 31),
                        yearRange: [1950, 2009],
                        defaultDate: new Date(2009, 11, 31),
                        onSelect: function() {
                            $wire.$set('input.dob', moment(this.getDate()).format('DD MMMM YYYY'), false);
                        }
                    })" />
                <flux:error name="dob" />
            </flux:field>
            <div class="flex items-center justify-end">
                <flux:button wire:click='firstStepSubmit' variant="primary">
                    {{ __('Next') }}
                </flux:button>
            </div>

        </div>
        <div class="{{ $currentStep != 2 ? 'hidden' : '' }} flex flex-col gap-6">
            <flux:field>
                <flux:select wire:model.change="input.country" :label="__('customer.label.address')">
                    <flux:select.option value="">{{ trans('address.select-country') }}</flux:select.option>
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

            @if ($address)
            <!--Address -->
            <flux:field>
                <flux:input wire:model="input.address" id="address" type="text" name="address" autofocus
                    autocomplete="address" placeholder="{{ __('customer.ph.address') }}" />
                <flux:error name="address" />
            </flux:field>
            @endif

            <!--phone -->
            <flux:input wire:model="input.phone" id="phone" :label="__('customer.label.phone')" type="text" name="phone"
                autofocus autocomplete="phone" placeholder="{{ __('customer.ph.phone') }}" />

            <div class="flex items-center justify-end">
                <flux:button wire:click='secondStepSubmit' variant="primary">
                    {{ __('Next') }}
                </flux:button>
            </div>
        </div>

        <div class="{{ $currentStep != 3 ? 'hidden' : '' }} flex flex-col gap-6">
            <!-- Email Address -->
            <flux:input wire:model="input.email" id="email" :label="__('Email address')" type="email" name="email"
                autocomplete="email" placeholder="email@example.com" />

            <!-- Password -->
            <flux:input wire:model="input.password" id="password" :label="__('Password')" type="password"
                name="password" autocomplete="new-password" placeholder="Password" />

            <!-- Confirm Password -->
            <flux:input wire:model="input.password_confirmation" id="password_confirmation"
                :label="__('Confirm password')" type="password" name="password_confirmation" autocomplete="new-password"
                placeholder="Confirm password" />

            <div class="flex items-center justify-end">
                <flux:button wire:click="back(1)" variant="primary" class="me-2">
                    {{ __('Back') }}
                </flux:button>
                <flux:button type="submit" variant="primary">
                    {{ __('Create account') }}
                </flux:button>
            </div>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>
