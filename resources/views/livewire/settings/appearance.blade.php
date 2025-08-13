<div class="flex flex-col items-start">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Appearance')" :subheading=" __('Update the appearance settings for your account')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('system.appearance.light') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('system.appearance.dark') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('system.appearance.system') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>
</div>
