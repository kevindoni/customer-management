<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.webhook-monitorings') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.webhook-monitorings') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting>
        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
            <form wire:submit="generateSigningSecret" class="flex flex-col gap-6 md:w-120">
                <flux:input wire:model="input.apikey" :label="__('mikrotik.label.api-key')" type="text" name="apikey"
                    readonly placeholder="{{ __('mikrotik.helper.apikey') }}" />


                <flux:textarea wire:model="input.header_secret" :label="__('mikrotik.label.header-secret')" type="password"
                readonly name="header_secret" placeholder="{{ __('mikrotik.helper.header-secret') }}" copyable/>


                <div class="flex items-center justify-end">
                    <flux:button type="submit" variant="primary">
                        {{ __('Generate') }}
                    </flux:button>

                </div>
            </form>
            <div class="p-4 flex-1">
                <flux:badge color="rose" icon="fire" class="text-wrap">
                Caution!
                </flux:badge>
                <flux:text class="mt-2">Dont forget to update your script in mikrotik if you generate new signing secret!</flux:text>
            </div>
        </div>

    </x-layouts.general-setting>
</section>
