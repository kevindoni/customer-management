<div class="relative mb-6 w-full">
    <div class="md:flex justify-between mb-2">
        <div class="md:flex justify-start gap-2">
            <flux:heading size="xl" level="1"> {{ __('mikrotik.server-name', ['mikrotik' => $mikrotik->name]) }}
            </flux:heading>
            <flux:subheading size="lg" class="mb-6"></flux:subheading>
        </div>
        <div class="md:flex justify-between gap-2">
            <flux:button :href="route('managements.mikrotiks')" wire:navigate style="cursor: pointer;" variant="primary" size="sm"
                icon="server">
                {{trans('menu.servers')}}
            </flux:button>
        </div>
    </div>
        <flux:separator variant="subtle" />
    </div>
