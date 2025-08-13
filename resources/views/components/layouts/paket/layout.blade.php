<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('pakets.management')" wire:navigate>
                {{ trans('paket.menu.pakets') }}
            </flux:navlist.item>

            <flux:navlist.item :href="route('pakets.profile.management')" wire:navigate>
                {{ trans('paket.menu.profiles') }}
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
