@props(['customer_paket'])

<flux:button size="xs" style="cursor: pointer;"
x-on:click="$flux.modal('disable-wa-installation-customer-paket-{{ $customer_paket->id }}').show()">
    <flux:icon.wa variant="micro"
        class="{{ $customer_paket->customer_installation_address->wa_notification ? 'text-green-500' : 'text-red-500' }}" />
</flux:button>

@if ($customer_paket->customer_static_paket)
    <flux:button size="xs" title="Status Paket">
        Static <flux:icon.viewfinder-circle variant="micro"
            class="{{ $customer_paket->customer_static_paket->online ? 'text-green-500' : 'text-red-500' }}" />
    </flux:button>
@else
    <flux:button size="xs" title="Status PPP">
        PPP <flux:icon.viewfinder-circle variant="micro"
            class="{{ $customer_paket->customer_ppp_paket->online ? 'text-green-500' : 'text-red-500' }}" />
    </flux:button>
@endif
