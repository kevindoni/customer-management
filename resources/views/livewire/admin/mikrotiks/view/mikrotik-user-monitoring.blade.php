<section class="w-full">
    @include('partials.show-mikrotik-heading')

    <x-layouts.mikrotik-view title="{{ trans('mikrotik.title.server-name', ['mikrotik' => $mikrotik->name]) }}"
        :mikrotik="$mikrotik" :heading="__('mikrotik.title.user-monitoring')" :subheading="__('mikrotik.subtitles.user-monitoring')">
        <div class="flex flex-col gap-6">
            <form wire:submit="activation" class="flex flex-col gap-6 md:w-120">
                <flux:input wire:model="input.apikey" :label="__('mikrotik.label.api-key')" type="text" name="apikey"
                    readonly placeholder="{{ __('mikrotik.helper.apikey') }}" />

                <flux:textarea wire:model="input.header_secret" :label="__('mikrotik.label.header-secret')" type="password"
                readonly name="header_secret" placeholder="{{ __('mikrotik.helper.header-secret') }}"/>

                <div class="flex items-center justify-end">
                    <flux:button type="submit" variant="primary">
                        {{ __('Activation') }}
                    </flux:button>

                </div>
            </form>

        </div>
    </x-layouts.mikrotik-view>
</section>
