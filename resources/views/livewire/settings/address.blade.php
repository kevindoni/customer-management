<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('address.address')" :subheading="__('Update your general address')">
        <flux:separator class="md:hidden" />

        <div class="flex items-start max-md:flex-col md:mt-6 mt-2 mb-2">
            <div class="mr-10 w-full md:pb-4 md:w-[220px]">
                <flux:subheading>{{ trans('address.general-address') }}</flux:subheading>
                
            </div>
        
            <div class="flex-1 self-stretch md:pt-6">
                <flux:heading>{{ $user->user_address->address }}</flux:heading>
                <flux:heading>{{ $user->user_address->subdistrict }}</flux:heading>
                <flux:heading>{{ $user->user_address->district }}</flux:heading>
                <flux:heading>{{ $user->user_address->city }}</flux:heading>
                <flux:heading>{{ $user->user_address->province }}</flux:heading>
                <flux:heading>{{ $user->user_address->country }}</flux:heading>
            </div>
        </div>

        <flux:separator class="md:hidden" />

        <div class="flex items-start max-md:flex-col md:mt-6 mt-2">
            <div class="mr-10 w-full md:pb-4 md:w-[220px]">
                <flux:subheading>{{ trans('address.phone') }}</flux:subheading>
            </div>
            <div class="flex-1 self-stretch md:pt-6">
                <flux:heading>{{ $user->user_address->phone }}</flux:heading>
            </div>
        </div>

        <div class="flex items-start max-md:flex-col md:mt-6 mt-2">
            <div class="mr-10 w-full md:pb-4 md:w-[220px]">
                <flux:subheading>{{ trans('address.wa-notification') }}</flux:subheading>
            </div>
            <div class="flex-1 self-stretch md:pt-6">

                <flux:heading>
                    @if ($user->user_address->wa_notification)
                        {{ trans('address.yes') }}
                    @else
                        {{ trans('address.no') }}
                    @endif
                </flux:heading>
            </div>
        </div>
        
        
    </x-settings.layout>
</section>
