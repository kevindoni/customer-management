<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.payment-gateways') }}</flux:heading>
        <flux:text size="sm" class="mb-6">{!! __('system.sub-title.payment-gateways') !!}</flux:text>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>
        <div class="grid md:grid-cols-2 md:gap-6">
            <div>
                <livewire:admin.settings.payment-gateway.midtrans-form />
            </div>
            <div>
                <livewire:admin.settings.payment-gateway.tripay-form />
            </div>
        </div>

    </x-layouts.general-setting>
</section>
