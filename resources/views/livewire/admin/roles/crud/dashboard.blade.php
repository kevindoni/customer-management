<section class="w-full">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ __('system.title.roles') }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">{{ __('system.sub-title.roles') }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>
    <x-layouts.general-setting>

        <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
            <div class="grid auto-rows-min gap-4 md:grid-cols-2">
                @include('livewire.admin.roles.cards.roles-card', [
                    'items' => $sortedRolesWithPermissionsAndUsers,
                ])
                @include('livewire.admin.roles.cards.permissions-card', [
                    'items' => $sortedPermissionsRolesUsers,
                ])
            </div>
            @include('livewire.admin.roles.tables.roles-table')

            @include('livewire.admin.roles.tables.permissions-table')


        </div>

    </x-layouts.general-setting>
</section>
