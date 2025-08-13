<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist variant="outline">
            <flux:navlist.item icon="home-modern" wire:navigate href="{{ route('helps.home') }}"
                :current="request()->routeIs('helps.home')">Home
            </flux:navlist.item>
            <flux:navlist.item icon="cog" wire:navigate href="{{ route('helps.generalSetting') }}"
                :current="request()->routeIs('helps.generalSetting')">General Setting
            </flux:navlist.item>
            <flux:navlist.group-with-icon icon="server" expandable :expanded="request()->routeIs('helps.servers*') ? true : false" heading="Servers" class="grid">
                <flux:navlist.item :current="request()->routeIs('helps.servers.mikrotik')" wire:navigate href="{{ route('helps.servers.mikrotik') }}">Mikrotik</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.servers.addMikrotik')" wire:navigate href="{{ route('helps.servers.addMikrotik') }}">Add Mikrotik</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.servers.editMikrotik')" wire:navigate href="{{ route('helps.servers.editMikrotik') }}">Edit Mikrotik</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.servers.deleteMikrotik')" wire:navigate href="{{ route('helps.servers.deleteMikrotik') }}">Delete Mikrotik</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.servers.importProfile')" wire:navigate href="{{ route('helps.servers.importProfile') }}">Import Profile</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.servers.importCustomer')" wire:navigate href="{{ route('helps.servers.importCustomer') }}">Import Customer</flux:navlist.item>
            </flux:navlist.group-with-icon>

            <flux:navlist.group-with-icon icon="users" expandable :expanded="request()->routeIs('helps.customers.*') ? true : false" heading="Customers" class="grid">
                <flux:navlist.item :current="request()->routeIs('helps.customers.customer')" wire:navigate href="{{ route('helps.customers.customer') }}">Customer</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.customers.add')" wire:navigate href="{{ route('helps.customers.add') }}">Add Customer</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.customers.delete')" wire:navigate href="{{ route('helps.customers.delete') }}">Delete Customer</flux:navlist.item>
            </flux:navlist.group-with-icon>
            <flux:navlist.group-with-icon icon="device-tablet" expandable :expanded="request()->routeIs('helps.customers.pakets.*') ? true : false" heading="Customers Paket" class="grid">
                <flux:navlist.item :current="request()->routeIs('helps.customers.pakets.paket')" wire:navigate href="{{ route('helps.customers.pakets.paket') }}">Customer Paket</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.customers.pakets.add')" wire:navigate href="{{ route('helps.customers.pakets.add') }}">Add Customer Paket</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.customers.pakets.edit')" wire:navigate href="{{ route('helps.customers.pakets.edit') }}">Upgrade Customer Paket</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.customers.pakets.delete')" wire:navigate href="{{ route('helps.customers.pakets.delete') }}">Delete Customer Paket</flux:navlist.item>
            </flux:navlist.group-with-icon>

            <flux:navlist.group-with-icon icon="envelope" expandable :expanded="request()->routeIs('helps.whatsapps*') ? true : false" heading="Whatsapp Gateway" class="grid">
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.subscription')" wire:navigate href="{{ route('helps.whatsapps.subscription') }}">Subscription</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.generalConfig')" wire:navigate href="{{ route('helps.whatsapps.generalConfig') }}">General Config</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.addDevice')" wire:navigate href="{{ route('helps.whatsapps.addDevice') }}">Add Device</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.deleteDevice')" wire:navigate href="{{ route('helps.whatsapps.deleteDevice') }}">Delete Device</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.scanDevice')" wire:navigate href="{{ route('helps.whatsapps.scanDevice') }}">Activation Whatsapp</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.notificationMessage')" wire:navigate href="{{ route('helps.whatsapps.notificationMessage') }}">Customer Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.editNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.editNotificationMessage') }}">Edit Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.keywordMessage')" wire:navigate href="{{ route('helps.whatsapps.keywordMessage') }}">Keyword Message</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.paymentNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.paymentNotificationMessage') }}">Payment Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.unpaymentNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.unpaymentNotificationMessage') }}">Unpayment Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.paylaterNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.paylaterNotificationMessage') }}">Paylater Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.warningBillNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.warningBillNotificationMessage') }}">Warning Bill Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.deadlineBillNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.deadlineBillNotificationMessage') }}">Deadline Bill Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.isolirNotificationMessage')" wire:navigate href="{{ route('helps.whatsapps.isolirNotificationMessage') }}">Isolir Notification</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.whatsapps.payment')" wire:navigate href="{{ route('helps.whatsapps.payment') }}">Payment</flux:navlist.item>
           </flux:navlist.group-with-icon>

           <flux:navlist.group-with-icon icon="wallet" expandable :expanded="request()->routeIs('helps.paymentgateways*') ? true : false" heading="Payment Gateway" class="grid">
                <flux:navlist.item :current="request()->routeIs('helps.paymentgateways.tripay')" wire:navigate href="{{ route('helps.paymentgateways.tripay') }}">Tripay</flux:navlist.item>
                <flux:navlist.item :current="request()->routeIs('helps.paymentgateways.midtrans')" wire:navigate href="{{ route('helps.paymentgateways.midtrans') }}">Midtrans</flux:navlist.item>
            </flux:navlist.group-with-icon>

            <flux:navlist.item icon="wifi" wire:navigate href="{{ route('helps.development') }}"
                :current="request()->routeIs('helps.development')">Hotspot
            </flux:navlist.item>
        </flux:navlist>

    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-screen">
            {{ $slot }}
        </div>
    </div>
</div>
