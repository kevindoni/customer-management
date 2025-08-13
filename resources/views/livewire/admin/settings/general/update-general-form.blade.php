<div>
    <form wire:submit="update_general" class="max-w-md mt-3  space-y-4">

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.company') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.title" type="text" name="title" autocomplete="title"
                    placeholder="{{ __('websystem.label.company') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.app-url') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.app_url" type="text" name="app_url" autocomplete="app_url"
                    placeholder="{{ __('websystem.placeholder.app-url') }}" />
            </div>
        </div>

         <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.different-days-create-invoice') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.diff_day" type="text" name="diff_day" autocomplete="diff_day"
                    placeholder="{{ __('websystem.placeholder.different-days-create-invoice') }}" />
            </div>
        </div>

        <!--Email-->
        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.email') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.email" type="text" name="email" autocomplete="email"
                    placeholder="{{ __('websystem.placeholder.email') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.address') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.address" type="text" name="address" autocomplete="address"
                    placeholder="{{ __('websystem.placeholder.address') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.city') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.city" type="text" name="city" autocomplete="city"
                    placeholder="{{ __('websystem.placeholder.city') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.postal_code') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.postal_code" type="text" name="postal_code" autocomplete="postal_code"
                    placeholder="{{ __('websystem.placeholder.postal_code') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.phone') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input wire:model="input.phone" type="text" name="phone" autocomplete="phone"
                    placeholder="{{ __('websystem.placeholder.phone') }}" />
            </div>
        </div>

        <div class="grid grid-cols-4 lg:gap-2 gap-1">
            <div class="place-content-center">
                <flux:label class="font-semibold">{{ __('websystem.label.tax-rate') }}</flux:label>
            </div>
            <div class="col-span-3">
                <flux:input.group>
                    <flux:input wire:model="input.tax_rate" type="text" name="tax_rate" autocomplete="tax_rate"
                        placeholder="{{ __('websystem.placeholder.tax-rate') }}" />
                    <flux:input.group.suffix>%</flux:input.group.suffix>
                </flux:input.group>
                <span class="text-gray-500">{{ __('websystem.info.tax-rate') }}</span>
            </div>
        </div>

        <div class="flex items-center justify-end gap-2 mt-6">
            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                {{ __('customer.button.save') }}
            </flux:button>
        </div>

    </form>
</div>
