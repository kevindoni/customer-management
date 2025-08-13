<div class="lg:hidden mr-10 w-full pb-4 md:w-[220px]">
    <flux:navlist>
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
    </flux:navlist>
    <flux:separator/>
</div>
