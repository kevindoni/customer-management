<div>
    <div class="stepwizard mb-4">
        <div class="stepwizard-row setup-panel">
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 1 ? 'zinc' : 'blue' }}">1</flux:badge>
            </div>
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 2 ? 'zinc' : 'blue' }}">2</flux:badge>
            </div>
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 3 ? 'zinc' : 'blue' }}">3</flux:badge>
            </div>
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 4 ? 'zinc' : 'blue' }}">4</flux:badge>
            </div>
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 5 ? 'zinc' : 'blue' }}">5</flux:badge>
            </div>
            <div class="stepwizard-step">
                <flux:badge variant="solid" color="{{ $currentStep != 6 ? 'zinc' : 'blue' }}">6</flux:badge>
            </div>
        </div>
    </div>

    <div class="row setup-content {{ $currentStep != 1 ? 'displayNone' : '' }}" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-2">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Script requirements') }} </h3>
                <table class="min-w-full divide-y divide-gray-200">
                    <tbody>
                        <tr>
                            <td class="six column wide">{{ __('PHP version') }}</td>
                            <td>>= {{ $requirements['php']['version'] }}</td>
                            <td
                                class="font-bold {{ $requirements['php']['version'] <= $requirements['php']['current'] ? 'text-green-500' : 'text-red-500' }}">
                                {{ $requirements['php']['current'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="six column wide">{{ __('MySQL version') }}</td>
                            <td>>= {{ $requirements['mysql']['version'] }}</td>
                            <td
                                class="font-bold {{ $requirements['mysql']['current']['compatible'] ? 'text-green-500' : 'text-red-500' }}">
                                {{ $requirements['mysql']['current']['distrib'] . ' v' . $requirements['mysql']['current']['version'] }}
                            </td>
                        </tr>
                    </tbody>
                </table>

                <table class="min-w-full divide-y divide-gray-200 mt-5">
                    <thead>
                        <tr>
                            <th class="text-left">{{ __('PHP Extension') }}</th>
                            <th class="text-center">{{ __('Enabled') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $countEnable = 0;
                        @endphp
                        @foreach ($requirements['php_extensions'] as $name => $enabled)
                            @php
                                $requirementsEnable = $loop->index + 1;
                            @endphp
                            <tr>
                                <td class="text-left">{{ ucfirst($name) }}</td>
                                <td class="text-center">
                                    @if ($enabled)
                                        @php
                                            $countEnable++;
                                        @endphp
                                        <flux:icon.check-circle class="inline w-5 h-5 text-green-500" />
                                    @else
                                        <flux:icon.x-circle class="inline w-5 h-5 text-red-500" />
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <flux:separator class="mt-2 mb-2" />
                <div class="flex items-center justify-end gap-2">
                    <flux:button variant="primary" wire:click="firstStepSubmit" icon="arrow-right"
                        :disabled="$countEnable == $requirementsEnable ? false : true">{{ __('Next') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </div>

    <div class="row setup-content {{ $currentStep != 2 ? 'displayNone' : '' }}" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-2">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Database settings') }} </h3>
                <flux:separator class="mt-2 mb-2" />
                <div>
                    <flux:field>
                        <flux:label>Database Host</flux:label>
                        <flux:input type="text" wire:model="input.database_host" autocomplete="database_host" />
                        <flux:error name="database_host" />
                    </flux:field>

                </div>
                <div class="flex flex-col gap-4">
                    <flux:field>
                        <flux:label>Database Username</flux:label>
                        <flux:input type="text" wire:model="input.database_username"
                            autocomplete="database_username" />
                        <flux:error name="database_username" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Database Password</flux:label>
                        <flux:input type="text" wire:model="input.database_password"
                            autocomplete="database_password" />
                        <flux:error name="database_password" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Database Name</flux:label>
                        <flux:input type="text" wire:model.live.debounce.500ms="input.database_name"
                            autocomplete="database_name" />
                        <flux:error name="database_name" />
                    </flux:field>
                </div>

                @if ($existingDatabase)
                    <flux:text>
                        <flux:badge icon="bell-alert" color="amber">
                            Warning
                        </flux:badge>
                        Database dengan nama ini sudah ada. Jika kamu lanjutkan, database lama akan dihapus dan ini
                        tidak dapat
                        dikembalikan.
                    </flux:text>
                @endif
                <flux:separator class="mt-2 mb-2" />
                <div class="flex items-center justify-end gap-2">
                    <flux:button variant="primary" wire:click="back(1)" icon="arrow-left">{{ __('Back') }}
                    </flux:button>

                    @if ($existingDatabase)
                        <flux:button variant="primary" wire:loading.attr="disabled" wire:loading.class="opacity-50"
                            wire:click="replaceExistingDatabase">
                            <div class="flex gap-2">
                                <div wire:loading.remove>
                                    <flux:icon.arrow-right variant="micro" />
                                </div>
                                <div wire:loading>
                                    <flux:icon.loading variant="micro" />
                                </div>
                                {{ __('Delete Database and Create New') }}
                            </div>
                        </flux:button>
                    @else
                        <flux:button class="flex gap-2" variant="primary" wire:click="secondStepSubmit"
                            icon="arrow-right">
                            {{ __('Creating database') }}
                        </flux:button>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <div class="row setup-content {{ $currentStep != 3 ? 'displayNone' : '' }}" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-2">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Install Application') }} </h3>
                <flux:separator class="mt-2 mb-2" />
                <div class="text-gray-800">
                    <div class="text-sm font-bold">
                        Congrulation!! Create database successfully
                    </div>
                    <flux:text class="mt-2">
                        Klik Install to continue.
                    </flux:text>
                </div>
                <flux:separator class="mt-2 mb-2" />
                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="back(3)" icon="arrow-left">{{ __('Back') }}</flux:button>
                    <flux:button variant="primary" wire:click="installApp" icon="arrow-right">
                        {{ __('Install') }}</flux:button>
                </div>
            </div>
        </div>
    </div>

    <div class="row setup-content {{ $currentStep != 4 ? 'displayNone' : '' }}" id="step-4">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-4">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Create Admin User') }} </h3>
                <flux:separator class="mt-2 mb-2" />
                <flux:input type="text" wire:model="input.name" autocomplete="name" name="name"
                    label="Name" placeholder="Name" />
                <flux:input type="text" wire:model="input.email" autocomplete="email" name="email"
                    label="Email" placeholder="Email" />
                <flux:input type="password" wire:model="input.password" autocomplete="password" name="password"
                    label="Password" placeholder="Password" />
                <!-- Confirm Password -->
                <flux:input wire:model="input.password_confirmation" id="password_confirmation"
                    :label="__('Confirm password')" type="password" name="password_confirmation"
                    autocomplete="new-password" placeholder="Confirm password" />
                <flux:separator class="mt-2 mb-2" />

                <div class="flex items-center justify-end gap-2">
                    <flux:button variant="primary" wire:click="createAdmin" icon="arrow-right">
                        {{ __('Create Admin') }}</flux:button>
                </div>
            </div>
        </div>
    </div>

    <div class="row setup-content {{ $currentStep != 5 ? 'displayNone' : '' }}" id="step-5">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-4">
                <h3 class="text-lg font-bold text-gray-800">{{ __('Company Detail') }} </h3>
                <flux:separator class="mt-2 mb-2" />
                <form wire:submit="updateCompany" class="max-w-md mt-3">
                    <div class="flex flex-col gap-4">
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.company') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.title" type="text" name="title" autocomplete="title"
                                    placeholder="{{ __('websystem.label.company') }}" />
                                
                            </flux:input.group>
                            <flux:error name="title"/>
                        </div>

                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.app-url') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.app_url" type="text" name="app_url"
                                    autocomplete="app_url" placeholder="{{ __('websystem.placeholder.app-url') }}" />
                            </flux:input.group>
                            <flux:error name="app_url"/>
                        </div>
                        
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.email') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.email" type="text" name="email" autocomplete="email"
                                    placeholder="{{ __('websystem.placeholder.email') }}" />
                            </flux:input.group>
                            <flux:error name="email"/>
                        </div>
                        
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.address') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.address" type="text" name="address"
                                    autocomplete="address" placeholder="{{ __('websystem.placeholder.address') }}" />
                            </flux:input.group>
                            <flux:error name="address"/>
                        </div>
                        
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.city') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.city" type="text" name="city" autocomplete="city"
                                    placeholder="{{ __('websystem.placeholder.city') }}" />
                            </flux:input.group>
                            <flux:error name="city"/>
                        </div>
                        
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.postal_code') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.postal_code" type="text" name="postal_code"
                                    autocomplete="postal_code"
                                    placeholder="{{ __('websystem.placeholder.postal_code') }}" />
                            </flux:input.group>
                            <flux:error name="postal_code"/>
                        </div>
                        
                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.phone') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.phone" type="text" name="phone" autocomplete="phone"
                                    placeholder="{{ __('websystem.placeholder.phone') }}" />
                            </flux:input.group>
                            <flux:error name="phone"/>
                        </div>
                        
              

                        <div>
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.tax-rate') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.tax_rate" type="text" name="tax_rate"
                                    autocomplete="tax_rate"
                                    placeholder="{{ __('websystem.placeholder.tax-rate') }}" />
                                <flux:input.group.suffix>%</flux:input.group.suffix>
                            </flux:input.group>
                            <span class="text-gray-500">{{ __('websystem.info.tax-rate') }}</span>
                        </div>

                        <div class="flex items-center justify-end gap-2">
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                {{ __('customer.button.save') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="row setup-content {{ $currentStep != 6 ? 'displayNone' : '' }}" id="step-6">
        <div class="col-xs-12">
            <div class="col-md-12 flex flex-col gap-4">
                <h3 class="text-lg font-bold text-gray-800">
                    {{ __('Congrulation, install Customer Management successfully') }} </h3>
                <flux:separator class="mt-2 mb-2" />
                <!--
                <flux:text>You can login with:</flux:text>
                <flux:input wire:model="input.email" name="email" label="Email" type="text" readonly />
                <flux:input wire:password="input.password" name="password" label="Password" type="password" viewable
                    readonly />
                    -->
                <flux:separator class="mt-2 mb-2" />
                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="finish" variant="primary" iconTrailing="arrow-right">
                        {{ __('Finish') }}
                    </flux:button>
                </div>

            </div>
        </div>
    </div>
</div>
