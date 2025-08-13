<div class="relative mb-6 w-full">
    @php
        $province = json_decode($user->user_address->province, true);
        $city = json_decode($user->user_address->city, true);
        $district = json_decode($user->user_address->district, true);
        $subdistrict = json_decode($user->user_address->subdistrict, true);
    @endphp
    <flux:heading size="xl" level="1">{{ $user->full_name }}
        <flux:tooltip content="{{ trans('customer.button.edit-customer') }}">
            <flux:button size="sm" icon="pencil" variant="ghost" style="cursor: pointer;" wire:click="$dispatch('edit-customer-modal',{user: '{{ $user->username }}'})"/>
        </flux:tooltip>
    </flux:heading>
    <flux:text>
        {{ trans('customer.label.address') }}: {{ $user->user_address->address ?? '-' }}, {{ $subdistrict['text'] ?? '-' }}, {{ $district['text'] ?? '-' }}, {{ $city['text'] ?? '-' }}, {{ $province['text'] ?? '-' }}
    </flux:text>
    <flux:text>
        {{ trans('customer.label.email') }}: {{ $user->email ?? '-' }}
    </flux:text>
     <flux:text class="mb-3">
        {{ trans('customer.label.phone') }}: {{ $user->user_address->phone ?? '-' }}
    </flux:text>
    <flux:separator variant="subtle" />
</div>
