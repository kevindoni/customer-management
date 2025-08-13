<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.dashboard', $mikrotik->slug) }}"
                :current="request()->routeIs('managements.mikrotik.dashboard')">Dashboard
            </flux:navlist.item>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.profiles', $mikrotik->slug) }}" iconTrailing="arrow-down-tray"
                :current="request()->routeIs('managements.mikrotik.profiles')">Profiles
            </flux:navlist.item>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.secrets', $mikrotik->slug) }}" iconTrailing="arrow-down-tray"
                :current="request()->routeIs('managements.mikrotik.secrets')">User Secrets
            </flux:navlist.item>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.pakets', $mikrotik->slug) }}" iconTrailing="arrow-up-tray"
                :current="request()->routeIs('managements.mikrotik.pakets')">Pakets
            </flux:navlist.item>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.customers', $mikrotik->slug) }}" iconTrailing="arrow-up-tray"
                :current="request()->routeIs('managements.mikrotik.customers')">Customers
            </flux:navlist.item>
            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.wanmonitoring', $mikrotik->slug) }}"
                :current="request()->routeIs('managements.mikrotik.wanmonitoring')">WAN Monitoring
            </flux:navlist.item>

            <flux:navlist.item wire:navigate
                href="{{ route('managements.mikrotik.usermonitoring', $mikrotik->slug) }}"
                :current="request()->routeIs('managements.mikrotik.usermonitoring')">User Monitoring
            </flux:navlist.item>
        </flux:navlist>

    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-2 w-full max-w-screen">
            {{ $slot }}
        </div>
    </div>
</div>
