<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.websystem') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.websystem') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <x-layouts.general-setting >

        <div class="grid md:grid-cols-2 md:gap-8">
            <div class="mb-5">
                <livewire:admin.settings.general.update-general-form />
            </div>

            <div class="mb-5">
                <livewire:admin.settings.general.queue-session-form />
            </div>
        </div>

    </x-layouts.general-setting>
</section>
