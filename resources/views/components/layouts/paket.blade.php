<x-layouts.app.header :title="$title ?? null" :mikrotik="$mikrotik">
    <flux:main container>
        <div class="flex max-md:flex-col items-start">
            <div class="w-full md:w-[220px] pb-4 mr-10">
                <flux:navlist>
                    <flux:navlist.item wire:navigate
                        href="{{ route('managements.mikrotik.dashboard', $mikrotik->slug) }}"
                        :current="request()->routeIs('managements.mikrotik.dashboard')">Dashboard
                    </flux:navlist.item>
                    <flux:navlist.item wire:navigate
                        href="{{ route('managements.mikrotik.dashboard', $mikrotik->slug) }}"
                        :current="request()->routeIs('managements.mikrotik.dashboard')">Dashboard
                    </flux:navlist.item>
                </flux:navlist>
            </div>
            <flux:separator class="md:hidden" />
            {{ $slot }}
        </div>
    </flux:main>
    <!--Custom Script-->
    @livewireChartsScripts
    @stack('scripts')
</x-layouts.app.header>
