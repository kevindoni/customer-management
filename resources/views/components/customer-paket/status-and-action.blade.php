@props(['customerPaket', 'user', 'size' => 'xs'])

<flux:tooltip content="Edit">
    <flux:button :size="$size" style="cursor: pointer;" variant="primary" icon="map-pin"
        wire:click="$dispatch('edit-customer-paket-address-modal',{customerPaket: '{{ $customerPaket->slug }}'})"/>
</flux:tooltip>
@if ($customerPaket->status == 'pending' || $customerPaket->status == 'cancelled')
    @if ($customerPaket->internet_service->value == 'ppp')
        <flux:button.group>
            <flux:button :size="$size">
                {{ $customerPaket->internet_service->name }}
            </flux:button>
            <flux:tooltip :content="__('Activation')" position="bottom">
            <flux:button variant="danger" iconTrailing="check" :size="$size" style="cursor: pointer;"
                wire:click="$dispatch('activation-customer-paket-modal',{customerPaket: '{{ $customerPaket->slug }}'})">
                {{ \Illuminate\Support\Str::apa($customerPaket->status) }}
            </flux:button>
            </flux:tooltip>
        </flux:button.group>
    @endif
    @if ($customerPaket->internet_service->value == 'ip_static')
        @if (is_null($customerPaket->customer_static_paket->mac_address) ||
                is_null($customerPaket->customer_static_paket->interface))
            @if (is_null($customerPaket->customer_static_paket->mac_address))
                <flux:button :size="$size" title="Add MAC Address" style="cursor: pointer;"
                    wire:click="$dispatch('edit-mac-address-modal',{customerPaket: '{{ $customerPaket->slug }}'})">
                    <span class="text-red-500"> [Mac Address Required]</span>
                </flux:button>
            @endif
            @if (is_null($customerPaket->customer_static_paket->interface))
                <flux:button :size="$size" title="Update Interface" style="cursor: pointer;" wire:navigate
                    href="{{ route('customer.show', $user->username) }}">
                    <span class="text-red-500"> [Interface Required]</span>
                </flux:button>
            @endif
        @else
            <flux:button :size="$size">
                {{ $customerPaket->internet_service->name }}
            </flux:button>
            <flux:tooltip :content="__('Activation')" position="bottom">
            <flux:button variant="danger" iconTrailing="check" :size="$size" style="cursor: pointer;"
                wire:click="$dispatch('activation-customer-paket-modal',{customerPaket: '{{ $customerPaket->slug }}'})">
                {{ \Illuminate\Support\Str::apa($customerPaket->status) }}
            </flux:button>
            </flux:tooltip>
        @endif
    @endif
@else

<flux:tooltip :content="$customerPaket->status == 'active' ? 'Disable' : 'Enable'" position="bottom">
    <flux:button :size="$size" style="cursor: pointer;"
        wire:click="$dispatch('disable-customer-paket-modal',{customerPaket: '{{ $customerPaket->slug }}'})"
        icon="power" variant="primary" color="{{ $customerPaket->status === 'active' ? 'green' : 'red' }}">
        {{ \Illuminate\Support\Str::apa($customerPaket->status) }}
    </flux:button>
</flux:tooltip>



    @if ($customerPaket->status == 'active')
        @if (!is_null($customerPaket->customer_installation_address->phone) && !is_null($customerPaket->customer_billing_address->phone))
        <flux:button :size="$size" style="cursor: pointer;"
            wire:click="$dispatch('disable-wa-notification-installation-address-modal',{customerPaket: '{{ $customerPaket->slug }}'})">
            <flux:icon.wa variant="mini"
                class="{{ $customerPaket->customer_installation_address->wa_notification ? 'text-green-500' : 'text-red-500' }}" />
        </flux:button>
        @endif


            <flux:button.group>
                <flux:button :size="$size">
                    {{ $customerPaket->customer_static_paket ? 'Static' : 'PPP' }}
                </flux:button>
                <flux:tooltip content="{{ $customerPaket->online ? 'Up' : 'Down' }}">
                <flux:button :size="$size"
                    icon="{{ $customerPaket->online ? 'arrow-up-circle' : 'arrow-down-circle' }}"
                    variant="{{ $customerPaket->online ? 'success' : 'danger' }}" />
                </flux:tooltip>
            </flux:button.group>

    @endif
@endif
