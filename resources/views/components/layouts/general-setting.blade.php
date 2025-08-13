<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px] hidden md:grid">
        <flux:navlist>
            <flux:navlist.item icon="cog-6-tooth" :href="route('managements.websystem')" :current="request()->routeIs('managements.websystem')" wire:navigate>
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

        <flux:navlist.item icon="no-symbol" :href="route('managements.autoisolirs')" :current="request()->routeIs('managements.autoisolirs')" wire:navigate>
            {{trans('menu.autoisolirs')}}
        </flux:navlist.item>

        <flux:navlist.item icon="play" :href="route('managements.wanMonitorings')" :current="request()->routeIs('managements.wanMonitorings')" wire:navigate>
            {{trans('menu.wan-monitorings')}}
        </flux:navlist.item>

        <flux:navlist.item icon="rocket-launch" :href="route('managements.webhookMonitorings')" :current="request()->routeIs('managements.webhookMonitorings')" wire:navigate>
            {{trans('menu.webhook-monitorings')}}
        </flux:navlist.item>

        <flux:navlist.item icon="building-library" :href="route('managements.banks')" :current="request()->routeIs('managements.banks')" wire:navigate>
            {{trans('menu.banks')}}
        </flux:navlist.item>

        <flux:navlist.group-with-icon expandable icon="wa" :heading="__('menu.whatsapp-gateway')" :expanded="request()->routeIs('managements.whatsapp*') ? true : false" class="grid">
            <flux:navlist.item :href="route('managements.whatsapp_gateway')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.general') }}</flux:navlist.item>

            <flux:navlist.item :href="route('managements.whatsapp.number')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.number') }}</flux:navlist.item>
            <flux:navlist.item :href="route('managements.whatsapp.messageHistories')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.message-histories') }}</flux:navlist.item>
            <flux:navlist.item :href="route('managements.whatsapp.notificationMessage')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.notification-message') }}</flux:navlist.item>

            <flux:navlist.item :href="route('managements.whatsapp.bootMessage')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.boot-message') }}</flux:navlist.item>

            <flux:navlist.item :href="route('managements.whatsapp.invoice')" wire:navigate>
                {{ trans('whatsapp-gateway.menu.invoices') }}</flux:navlist.item>
        </flux:navlist.group-with-icon>

        <flux:navlist.item icon="wallet" :href="route('managements.paymentgateways')" :current="request()->routeIs('managements.paymentgateways')" wire:navigate>
            {{trans('menu.payment-gateways')}}
        </flux:navlist.item>
        </flux:navlist>
    </div>

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-screen">
            {{ $slot }}
        </div>
    </div>
</div>
