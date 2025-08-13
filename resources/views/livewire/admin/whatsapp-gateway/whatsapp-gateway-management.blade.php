<section class="w-full">
    <x-layouts.general-setting :heading="__('Whatsapp Gateway')" :subheading="__('Connect your aplication to whatsapp gateway')">
        <x-layouts.whatsapp-gateway.nav-mobile/>

        <div class="flex flex-col gap-4">
            @foreach ($notifications as $notification)
                <div class="bg-orange-400/20 dark:bg-orange-400/40 rounded-xl text-orange-700 [&_button]:text-orange-700! dark:text-orange-200 dark:[&_button]:text-orange-200! shadow-xs">
                    <div class="px-4 py-4 flex items-start gap-4">
                        <flux:icon.bell-alert />
                        <div>
                            <div>
                                <flux:heading size="lg" class="font-semi-bold text-orange-700 dark:text-orange-200">
                                    {{ $notification['title'] }}
                                </flux:heading>
                                <p class="text-sm/relaxed">
                                    <flux:text>
                                        <flux:badge size="xs">{{ \Carbon\Carbon::parse($notification['updated_at'])->format('d-m-Y, H:i') }}</flux:badge>
                                    </flux:text>
                                </p>
                            </div>

                            <flux:text class="mt-2">
                                {{ $notification['message'] }}
                            </flux:text>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>



        @if ($isLogin)
            <div
                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                <flux:badge color="sky" class="mb-2">Document</flux:badge><br>
                Read this <flux:button size="sm" icon="book-open-text"><a href="{{ route('helps.whatsapps.generalConfig') }}" target="_blank">document</a></flux:button> to configuration.
            </div>
            <div
                class="md:mt-4 mt-2 flex flex-col  bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                @if ($subscription)
                    <div class="object-cover flex p-4 items-center w-full rounded-t-lg h-auto md:h-auto md:w-48 md:rounded-none md:rounded-s-lg {{ $subscription['status'] == 'active' ? 'bg-green-500' : 'bg-red-600' }}">
                @else
                    <div class="object-cover flex p-4 items-center w-full rounded-t-lg h-auto md:h-auto md:w-48 md:rounded-none md:rounded-s-lg bg-red-600">
                @endif
                        <img src="http://{{ config('wa-griyanet.server_url') }}/assets/images/logo_black.png" height="50px" alt="griyanet whatsapp gateway">
                    </div>

                <div class="flex flex-col justify-between p-4 leading-normal w-full ">
                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ trans('whatsapp-gateway.label.email') }}</flux:heading>
                        </div>
                        <flux:subheading>{{ $user['email'] }}</flux:subheading>
                    </div>
                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ trans('whatsapp-gateway.label.username') }}</flux:heading>
                        </div>
                        <flux:subheading>{{ $user['username'] }}</flux:subheading>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ trans('whatsapp-gateway.label.subscription-status') }}</flux:heading>
                        </div>
                         <flux:badge
                            color="{{ $subscription ? ($subscription['status'] == 'active' ? 'emerald' : 'rose') : 'red' }}">
                            {{\Illuminate\Support\Str::apa($subscription['status'] ?? 'In Active')}}
                       </flux:badge>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ trans('whatsapp-gateway.label.subscription') }}</flux:heading>
                        </div>

                        <div class="flex flex-row gap-2">
                            @if ($subscription)
                                <flux:subheading>
                                    {{\Illuminate\Support\Str::apa($subscription['product_name'].' - '.$subscription['renewal_period'] ?? 'Unsubscribe')}}
                                </flux:subheading>

                                <flux:button variant="primary" size="xs"
                                icon="pencil" style="cursor: pointer;"
                                wire:click="$dispatch('show-edit-subscription-modal', {subscription: '{{ $subscription['slug'] }}'})">
                                    Upgrade
                                </flux:button>

                            @else
                            <flux:button variant="primary" size="xs"
                                icon="plus-circle" style="cursor: pointer;"
                                wire:click="$dispatch('show-add-subscription-modal')">
                                Add
                            </flux:button>
                            @endif

                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ trans('whatsapp-gateway.label.subscription-expired') }}</flux:heading>
                        </div>
                        @if ($subscription)
                            <flux:subheading>{{ \Carbon\Carbon::parse($subscription['end_date'])->diffForHumans() }}
                            </flux:subheading>
                        @endif
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.remaining-day') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70">
                            <div class="flex gap-2">
                                <flux:input.group>
                                    <flux:input wire:model.blur="input.remaining_day" type="text" name="remaining_day"
                                        autofocus autocomplete="remaining_day"
                                        placeholder="{{ __('whatsapp-gateway.helper.remaining-day') }}" />
                                    <flux:input.group.suffix>{{ __('whatsapp-gateway.label.day') }}

                                    </flux:input.group.suffix>
                                </flux:input.group>
                                <x-action-message class="me-3 p-1" on="remaining-day-updated">
                                    <flux:badge color="teal">Saved</flux:badge>
                                </x-action-message>
                            </div>
                            <flux:error name="remaining_day" />
                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.schedule-time') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70">
                            <div class="flex gap-2">
                                <flux:input type="time" wire:model.blur="input.schedule_time"/>
                                <x-action-message class="me-3 p-1" on="schedule-time-updated">
                                    <flux:badge color="teal">Saved</flux:badge>
                                </x-action-message>
                            </div>
                            <flux:error name="schedule_time" />
                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-boot') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70 flex gap-2">
                            <flux:field>
                                <flux:select wire:model.change="input.whatsapp_number_boot" name="whatsapp_number_boot">
                                    <flux:select.option value="">
                                        {{ trans('whatsapp-gateway.ph.select-wa-number') }}
                                    </flux:select.option>
                                    @foreach ($devices as $device)
                                        <flux:select.option value="{{ $device['body'] }}">{{ $device['body'] }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="whatsapp_number_boot" />
                            </flux:field>
                            <x-action-message class="me-3 p-1" on="boot-number-updated">
                                <flux:badge color="teal">Saved</flux:badge>
                            </x-action-message>
                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-notification') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70 flex gap-2">
                            <flux:field>
                                <flux:select wire:model.change="input.whatsapp_number_notification"
                                    name="whatsapp_number_notification">
                                    <flux:select.option value="">
                                        {{ trans('whatsapp-gateway.ph.select-wa-number') }}
                                    </flux:select.option>
                                    @foreach ($devices as $device)
                                        <flux:select.option value="{{ $device['body'] }}">{{ $device['body'] }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="whatsapp_number_notification" />
                            </flux:field>
                            <x-action-message class="me-3 p-1" on="notification-number-updated">
                                <flux:badge color="teal">Saved</flux:badge>
                            </x-action-message>

                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.enable') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="enable" />
                                @if ($enable)
                                    <flux:label>
                                        <flux:badge color="lime">
                                            {{ trans('whatsapp-gateway.label.whatsapp-gateway-enable') }}</flux:badge>
                                    </flux:label>
                                @else
                                    <flux:label>
                                        <flux:badge color="rose">
                                            {{ trans('whatsapp-gateway.label.whatsapp-gateway-disable') }}</flux:badge>
                                    </flux:label>
                                @endif
                            </flux:field>
                        </div>
                    </div>

                    <div class="flex items-start max-md:flex-col mb-2">
                        <div class="mr-10 md:pb-4 md:w-[220px]">
                            <flux:heading>{{ __('whatsapp-gateway.label.notif-admin') }}
                            </flux:heading>
                        </div>
                        <div class="text-sm text-zinc-500 dark:text-white/70">
                            <flux:field variant="inline">
                                <flux:checkbox wire:model.live="notifAdmin" />
                                @if ($notifAdmin)
                                    <flux:label>
                                        <flux:badge color="lime">{{ trans('whatsapp-gateway.label.yes') }}
                                        </flux:badge>
                                    </flux:label>
                                @else
                                    <flux:label>
                                        <flux:badge color="rose">{{ trans('whatsapp-gateway.label.no') }}
                                        </flux:badge>
                                    </flux:label>
                                @endif
                            </flux:field>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="w-full p-4 text-center sm:p-8">
                <div
                    class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                    <div
                        class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">

                        <form wire:submit="initialize">
                            <div class="space-y-6" action="#">
                                @if (empty(env('API_USERNAME')) || empty(env('API_CLIENT_SECRET')) || empty(env('API_KEY')))
                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Register</h5>
                                    <p class="mb-5 text-base text-gray-500 sm:text-sm dark:text-gray-400">
                                        You dont have account on Griyanet Whatsapp Gateway or data corrupt. Please
                                        syncronize
                                        now.</p>
                                @else
                                    <h5 class="text-xl font-medium text-gray-900 dark:text-white">Login</h5>
                                    <p class="mb-5 text-base text-gray-500 sm:text-sm dark:text-gray-400">
                                        You dont have account on Griyanet Whatsapp Gateway or data corrupt. Please
                                        syncronize
                                        now.</p>
                                @endif
                                <flux:error name="email"/>

                                <div>
                                    <flux:input wire:model="input.password" type="password" name="passsword"
                                        autocomplete="password"
                                        placeholder="{{ __('whatsapp-gateway.helper.password') }}" />
                                    <flux:error class="mt-1" name="password" />
                                </div>

                                <flux:button variant="primary" size="sm" icon="arrow-right-circle"
                                    style="cursor: pointer;" type="submit">
                                    {{ empty(env('API_USERNAME')) || empty(env('API_CLIENT_SECRET')) || empty(env('API_KEY'))
                                        ? trans('whatsapp-gateway.button.register')
                                        : trans('whatsapp-gateway.button.login') }}
                                </flux:button>

                                <div>
                                    <x-action-message class="me-3" on="login-success">
                                        {{ __('Login successfully') }}
                                    </x-action-message>
                                </div>
                                <flux:text>
                                Read this <flux:button size="sm" variant="ghost" icon="book-open-text"><a href="{{ route('helps.whatsapps.subscription') }}" target="_blank">document</a></flux:button> to registration.
                                </flux:text>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        @endif
    </x-layouts.whatsapp-gateway.layout>
    <livewire:admin.whatsapp-gateway.modal.edit-subscription />
</section>
