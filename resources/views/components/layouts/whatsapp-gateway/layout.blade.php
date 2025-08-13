<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
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
