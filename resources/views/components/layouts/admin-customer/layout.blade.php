
<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('customer.show', $user->username)" wire:navigate>
                {{ trans('customer.menu.paket') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('customer.billing', $user->username)" wire:navigate>
                {{ trans('customer.menu.billing') }}
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
