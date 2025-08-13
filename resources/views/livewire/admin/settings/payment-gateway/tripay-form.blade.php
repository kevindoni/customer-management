<div>
    @if (!env('TRIPAY_MERCHANT_CODE'))
    <flux:badge variant="solid" color="cyan" style="cursor: pointer">
        <a target="_blank" href="https://tripay.co.id/?ref=TP38753">Daftar TriPay</a>
</flux:badge>
   @endif
    <form wire:submit="update_tripay" class="max-w-md mt-3">
        <div class="flex flex-col gap-2">
            <div class="flex justify-between gap-2">
                <div class="flex justify-start gap-2">
                    <flux:heading size="xl" class="font-bold">TriPay</flux:heading>
                </div>
                <div class="flex justify-between gap-2">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model.live="enable" />
                        @if ($enable)
                            <flux:label>
                                <flux:badge color="lime">{{ trans('Enable') }}
                                </flux:badge>
                            </flux:label>
                        @else
                            <flux:label>
                                <flux:badge color="rose">{{ trans('Disable') }}
                                </flux:badge>
                            </flux:label>
                        @endif
                    </flux:field>


                </div>
            </div>

            <flux:separator class="my-2" />

            <div class="flex flex-col gap-2 {{ $enable ? 'opacity-100' : 'opacity-75'}}">
                <flux:badge class="mt-4 font-semibold" color="{{ $enable ? 'sky' : 'zync'}}" size="lg">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model.live="production" />
                        <flux:label>Production</flux:label>
                    </flux:field>
                </flux:badge>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('Merchant Code') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.merchant_code" type="text" name="merchant_code"
                            autocomplete="merchant_code" placeholder="{{ __('Merchant Code') }}" />
                    </flux:input.group>
                    <flux:error name="merchant_code" />
                </flux:field>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.production_api_key" type="password" viewable
                            name="production_api_key" autocomplete="production_api_key"
                            placeholder="{{ __('API Key') }}" />
                    </flux:input.group>
                    <flux:error name="production_api_key" />
                </flux:field>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.production_secret_key" type="password"
                            name="production_secret_key" autocomplete="production_secret_key" viewable
                            placeholder="{{ __('Secret Key') }}" />
                    </flux:input.group>
                    <flux:error name="production_secret_key" />
                </flux:field>

                <flux:badge class="mt-4 font-semibold" color="{{ $enable ? 'yellow' : 'zync'}}" size="lg">
                    <flux:field variant="inline">
                        <flux:checkbox wire:model.live="development" />
                        <flux:label>Development</flux:label>
                    </flux:field>
                </flux:badge>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('Merchant Code') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.development_merchant_code" type="text" name="development_merchant_code"
                            autocomplete="development_merchant_code" placeholder="{{ __('Merchant Code') }}" />
                    </flux:input.group>
                    <flux:error name="development_merchant_code" />
                </flux:field>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.development_api_key" type="password" viewable name="development_api_key"
                            autocomplete="development_api_key" placeholder="{{ __('API Key') }}" />
                    </flux:input.group>
                    <flux:error name="development_api_key" />
                </flux:field>

                <flux:field>
                    <flux:input.group>
                        <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}</flux:input.group.prefix>
                        <flux:input wire:model="input.development_secret_key" type="password" name="development_secret_key"
                            autocomplete="sdevelopment_secret_key" viewable placeholder="{{ __('Secret Key') }}" />

                    </flux:input.group>
                    <flux:error name="development_secret_key" />
                </flux:field>

                <div class="flex items-center justify-end gap-2">
                    <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                        {{ __('customer.button.save') }}
                    </flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
