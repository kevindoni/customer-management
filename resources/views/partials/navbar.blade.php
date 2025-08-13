@role('admin')
    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
            wire:navigate>
            {{ trans('menu.dashboard') }}
        </flux:navbar.item>

        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon="users" icon-trailing="chevron-down" :current="request()->routeIs('customers.*')">
                {{ trans('menu.customers') }}
            </flux:navbar.item>
            <flux:navmenu>
                <flux:navmenu.item icon="users" :href="route('customers.management')"
                    :current="request()->routeIs('customers.management')" wire:navigate>
                    {{ trans('menu.customers') }}
                </flux:navmenu.item>
                <flux:navmenu.item icon="users" :href="route('customers.paket.management')"
                    :current="request()->routeIs('customers.paket.management')" wire:navigate>
                    {{ trans('menu.customers-paket') }}
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>


        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon="currency-dollar" icon-trailing="chevron-down" :current="request()->routeIs('billings.*')">
                 {{ trans('menu.billings') }}
            </flux:navbar.item>
            <flux:navmenu>
                <flux:navmenu.item icon="currency-dollar" :href="route('billings.management')"
                    :current="request()->routeIs('billings.management')" wire:navigate>
                     {{ trans('menu.billings') }}
                </flux:navmenu.item>
                <flux:navmenu.item icon="credit-card" :href="route('billings.management.payments')"
                    :current="request()->routeIs('billings.management.payments')" wire:navigate>
                    {{ trans('menu.payments') }}
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>


        <flux:navbar.item icon="layout-grid" :href="route('pakets.management')"
            :current="request()->routeIs('pakets.management')" wire:navigate>
            {{ trans('menu.pakets') }}
        </flux:navbar.item>

        <flux:separator vertical variant="subtle" class="my-2" />

        <flux:dropdown class="max-lg:hidden">
            <flux:navbar.item icon="cog" icon-trailing="chevron-down" :current="request()->routeIs('managements.*')">
                {{ trans('menu.settings') }}
            </flux:navbar.item>
            <flux:navmenu>
                <flux:navmenu.item icon="wifi" :href="route('managements.hotspots')"
                    :current="request()->routeIs('managements.hotspot*')" wire:navigate>
                    {{ trans('menu.hotspots') }}
                </flux:navmenu.item>

                <flux:navmenu.item icon="wa" :href="route('managements.whatsapp_gateway')"
                    :current="request()->routeIs('managements.whatsapp_gateway')" wire:navigate>
                    {{ trans('menu.whatsapp-gateway') }}
                </flux:navmenu.item>

                <flux:navmenu.item icon="cog" :href="route('managements.websystem')"
                    :current="request()->routeIs('managements.websystem')" wire:navigate>
                    {{ trans('menu.general') }}
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>
    </flux:navbar>
@else
    <flux:navbar class="-mb-px max-lg:hidden">
        <flux:navbar.item icon="home" :href="route('customer.dashboard')"
            :current="request()->routeIs('customer.dashboard')" wire:navigate>
            {{ trans('menu.customer.home') }}
        </flux:navbar.item>
        <flux:navbar.item :href="route('customer.subscriptionmanagement')"
            :current="request()->routeIs('customer.subscriptionmanagement')" wire:navigate>
            {{ trans('menu.customer.subscription') }}
        </flux:navbar.item>
        <flux:navbar.item :href="route('customer.paymentmanagement')"
            :current="request()->routeIs('customer.paymentmanagement')" wire:navigate>
            {{ trans('menu.customer.bill') }}
        </flux:navbar.item>
    </flux:navbar>
@endif
