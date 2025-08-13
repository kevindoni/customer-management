@role('admin')
<flux:navlist.group :heading="__('Platform')">
    <flux:navlist.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
        {{ __('Dashboard') }}
    </flux:navlist.item>
    <flux:navlist.group-with-icon expandable icon="users" :heading="__('menu.customers')" :expanded="request()->routeIs('customers.*') ? true : false" class="grid">
        <flux:navlist.item :href="route('customers.management')" :current="request()->routeIs('customers.management')" wire:navigate>
            {{trans('menu.customers')}}
        </flux:navlist.item>
        <flux:navlist.item :href="route('customers.paket.management')"
            :current="request()->routeIs('customers.paket.management')" wire:navigate>
            {{ trans('menu.customers-paket') }}
        </flux:navlist.item>
    </flux:navlist.group-with-icon>
    <flux:navlist.group-with-icon :heading="__('menu.billings')" expandable icon="currency-dollar" :expanded="request()->routeIs('billings.*')" wire:navigate>
        <flux:navlist.item :href="route('billings.management')" wire:navigate icon="currency-dollar">
            {{ trans('menu.billings') }}
        </flux:navlist.item>

        <flux:navlist.item :href="route('billings.management.payments')" wire:navigate icon="credit-card">
            {{ trans('menu.payments') }}
        </flux:navlist.item>

    </flux:navlist.group-with-icon>
    <flux:navlist.item icon="device-tablet" :href="route('pakets.management')" :current="request()->routeIs('pakets.management')" wire:navigate>
        {{ __('menu.pakets') }}
    </flux:navlist.item>
    <flux:navlist.group-with-icon expandable icon="cog" :heading="__('menu.settings')" :expanded="request()->routeIs('managements.*') ? true : false" class="grid">
        <flux:navlist.item icon="cog" :href="route('managements.websystem')" :current="request()->routeIs('managements.websystem')" wire:navigate>
            {{trans('menu.general')}}
        </flux:navlist.item>

        <flux:navlist.item icon="user-group" :href="route('managements.users')" :current="request()->routeIs('managements.users')" wire:navigate>
            {{trans('menu.users')}}
        </flux:navlist.item>

        <flux:navlist.item icon="shield-check" :href="route('managements.roles')" :current="request()->routeIs('managements.roles')" wire:navigate>
            {{trans('menu.roles')}}
        </flux:navlist.item>

        <flux:navlist.item icon="server" :href="route('managements.mikrotiks')" :current="request()->routeIs('managements.mikrotiks')" wire:navigate>
            {{trans('menu.servers')}}
        </flux:navlist.item>

        <flux:navlist.item icon="lock-closed" :href="route('managements.autoisolirs')" :current="request()->routeIs('managements.autoisolirs')" wire:navigate>
            {{trans('menu.autoisolirs')}}
        </flux:navlist.item>

        <flux:navlist.item icon="server" :href="route('managements.wanMonitorings')" :current="request()->routeIs('managements.wanMonitorings')" wire:navigate>
            {{trans('menu.wan-monitorings')}}
        </flux:navlist.item>

        <flux:navlist.item icon="rocket-launch" :href="route('managements.webhookMonitorings')" :current="request()->routeIs('managements.webhookMonitorings')" wire:navigate>
            {{trans('menu.webhook-monitorings')}}
        </flux:navlist.item>

        <flux:navlist.item icon="building-library" :href="route('managements.banks')" :current="request()->routeIs('managements.banks')" wire:navigate>
            {{trans('menu.banks')}}
        </flux:navlist.item>

        <flux:navlist.item icon="wa" :href="route('managements.whatsapp_gateway')" :current="request()->routeIs('managements.whatsapp_gateway')" wire:navigate>
            {{ __('menu.whatsapp-gateway') }}
        </flux:navlist.item>
        <flux:navlist.item icon="building-library" :href="route('managements.paymentgateways')" :current="request()->routeIs('managements.paymentgateways')" wire:navigate>
            {{trans('menu.payment-gateways')}}
        </flux:navlist.item>

    </flux:navlist.group-with-icon>


</flux:navlist.group>

@else
<flux:navlist.group :heading="__('Platform')">
    <flux:navlist.item icon="home" :href="route('customer.dashboard')" :current="request()->routeIs('customer.dashboard')" wire:navigate>
        {{trans('menu.customer.home')}}
    </flux:navlist.item>
    <flux:navlist.item :href="route('customer.subscriptionmanagement')" :current="request()->routeIs('customer.subscriptionmanagement')" wire:navigate>
        {{trans('menu.customer.subscription')}}
    </flux:navlist.item>
    <flux:navlist.item :href="route('customer.paymentmanagement')" :current="request()->routeIs('customer.paymentmanagement')" wire:navigate>
        {{trans('menu.customer.bill')}}
    </flux:navlist.item>

</flux:navlist.group>
@endif

