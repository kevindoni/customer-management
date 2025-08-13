<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <a href="@role('admin') {{ route('dashboard') }} @else {{ route('customer.dashboard') }} @endif" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
            <x-app-logo />
        </a>
        @include('partials.navbar')
        <flux:spacer />
         @role('admin')
        <flux:navbar class="mr-1.5 space-x-0.5 py-0!">

            <flux:tooltip :content="__('Donate')" position="bottom">
                    <flux:navbar.item label="Donate" class="h-10 max-lg:hidden [&>div>svg]:size-5" icon="currency-dollar"
                    href="https://www.nihbuatjajan.com/griyanet" target="_blank"/>
            </flux:tooltip>

            <flux:tooltip :content="__('Documentation')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" icon="book-open-text"
                    href="{{ route('helps.home') }}" target="_blank" label="Documentation" :current="request()->routeIs('helps.*')"/>
            </flux:tooltip>
            <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                aria-label="Toggle dark mode" />
        </flux:navbar>
        @endrole

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-left text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->fullname }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item href="/settings/profile" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky
        class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        @role('admin')
        <a href="{{ route('dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
            <x-app-logo />
        </a>
        @else
        <a href="{{ route('customer.dashboard') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
            <x-app-logo />
        </a>
        @endif
        <flux:navlist variant="outline">
            @include('partials.navbar-mobile')
        </flux:navlist>
        <flux:spacer />
         @role('admin')
        <flux:navlist variant="outline">
            <flux:navlist.item icon="book-open-text" href="{{ route('helps.home') }}" target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>
        @endrole
    </flux:sidebar>
    {{ $slot }}
    @include('partials.footer')
</body>
</html>
