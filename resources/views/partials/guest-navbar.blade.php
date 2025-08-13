
    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="home" :href="route('home')"
            :current="request()->routeIs('home')" wire:navigate>
            {{ trans('menu.customer.home') }}
        </flux:navbar.item>
        <flux:navbar.item icon="device-phone-mobile" :href="route('tos')"
            :current="request()->routeIs('tos')" wire:navigate>
            {{ trans('menu.guest.term-of-service') }}
        </flux:navbar.item>
        <flux:navbar.item icon="document-currency-dollar" :href="route('privacy')"
            :current="request()->routeIs('privacy')" wire:navigate>
            {{ trans('menu.guest.privacy') }}
        </flux:navbar.item>
        <flux:navbar.item icon="document-currency-dollar" :href="route('contact')"
            :current="request()->routeIs('contact')" wire:navigate>
            {{ trans('menu.guest.contact') }}
        </flux:navbar.item>
    </flux:navbar>

