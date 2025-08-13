<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
        <a href="{{ route('home') }}" class="ml-2 mr-5 flex items-center space-x-2 lg:ml-0" wire:navigate>
            <x-app-logo />
        </a>
        @include('partials.guest-navbar')
        <flux:spacer />
        <flux:navbar class="mr-1.5 space-x-0.5 py-0!">
            <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
                aria-label="Toggle dark mode" />
            <flux:tooltip :content="__('menu.guest.user-login')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" wire:navigate
                    href="{{ route('login') }}"  label="Login" :current="request()->routeIs('login')">
                     {{trans('menu.guest.user-login')}}
                </flux:navbar.item>

            </flux:tooltip>
            <flux:tooltip :content="__('menu.guest.user-register')" position="bottom">
                <flux:navbar.item class="h-10 max-lg:hidden [&>div>svg]:size-5" wire:navigate
                    href="{{ route('register') }}"  label="Register" :current="request()->routeIs('register')">
                     {{trans('menu.guest.user-register')}}
                </flux:navbar.item>
            </flux:tooltip>

        </flux:navbar>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky
        class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
        <a href="{{ route('home') }}" class="ml-1 flex items-center space-x-2" wire:navigate>
            <x-app-logo />
        </a>
        <flux:navlist variant="outline">
           <!-- Mobile Menu -->
           <flux:navlist.group :heading="__('menu.guest.menu')">
                <flux:navlist.item icon="home" :href="route('home')" :current="request()->routeIs('home')" wire:navigate>
                    {{trans('menu.customer.home')}}
                </flux:navlist.item>
                <flux:navlist.item icon="device-phone-mobile" :href="route('tos')" :current="request()->routeIs('tos')" wire:navigate>
                   {{ trans('menu.guest.term-of-service') }}
                </flux:navlist.item>
                <flux:navlist.item icon="document-currency-dollar" :href="route('privacy')" :current="request()->routeIs('privacy')" wire:navigate>
                   {{ trans('menu.guest.privacy') }}
                </flux:navlist.item>
                <flux:navlist.item icon="document-currency-dollar" :href="route('contact')" :current="request()->routeIs('contact')" wire:navigate>
                   {{ trans('menu.guest.contact') }}
                </flux:navlist.item>
            </flux:navlist.group>
            <flux:navlist.group :heading="__('menu.guest.user')">
                <flux:navlist.item :href="route('login')" :current="request()->routeIs('login')" wire:navigate>
                    {{trans('menu.guest.user-login')}}
                </flux:navlist.item>
                <flux:navlist.item :href="route('register')" :current="request()->routeIs('register')" wire:navigate>
                   {{ trans('menu.guest.user-register') }}
                </flux:navlist.item>

            </flux:navlist.group>
        </flux:navlist>
        <flux:spacer />
        <flux:navlist variant="outline">

        </flux:navlist>
    </flux:sidebar>
    {{ $slot }}

    @include('partials.footer')

</body>
</html>
