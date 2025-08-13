@props(['billing'])
<div class="flex gap-2 justify-end flex-col">
    <div class="flex justify-end">
        <flux:button.group>
            @if ($billing->payments->where('reconciliation_status', '!=', 'discrepancy')->count())
                <flux:tooltip content="{{ __('billing.button.unpay') }}">
                    <flux:button size="xs"
                        wire:click="$dispatch('billing-unpayment-modal', {invoice: '{{ $billing->id }}'})"
                        variant="primary" color="red" class="cursor-pointer"
                        icon="x-circle">
                        {{ __('billing.button.unpay') }}
                    </flux:button>
                </flux:tooltip>
            @endif

            @if ($billing->status != 'paid' && $billing->periode === $billing->customer_paket->invoices()->where('status', '!=','paid')->oldest()->first()->periode)
                <flux:tooltip content="{{ __('billing.button.pay') }}">
                    <flux:button size="xs" wire:click="$dispatch('billing-payment-modal', {invoice: '{{ $billing->id }}'})"
                        variant="primary" color="green" class="cursor-pointer" icon="check">
                        {{ __('billing.button.pay') }}
                    </flux:button>
                </flux:tooltip>
            @endif
        </flux:button.group>
    </div>
    <div class="flex justify-end">
        <flux:button.group>
            @if ($billing->status != 'paid')

                <flux:tooltip content="{{ __('billing.button.download-invoice') }}">
                    <flux:button size="xs" wire:click="download_customer_invoice('{{ $billing->id }}')"
                        style="cursor: pointer;" variant="primary" icon="printer" />
                </flux:tooltip>

                <flux:tooltip content="{{ __('billing.button.add-discount') }}">
                    <flux:button size="xs"
                        wire:click="$dispatch('add-discount-modal', {invoice: '{{ $billing->id }}'})"
                        style="cursor: pointer;" variant="primary" icon="plus-circle" />
                </flux:tooltip>

                @if (!$billing->payments->count() && $billing->status != 'paid' && $billing->periode === $billing->customer_paket->invoices()->where('status', '!=','paid')->latest('periode')->first()->periode)
                    <flux:tooltip content="{{ __('billing.button.delete-billing') }}">
                        <flux:button size="xs"
                            wire:click="$dispatch('show-delete-invoice-modal', {invoice: '{{ $billing->id }}'})"
                            style="cursor: pointer;" variant="danger" icon="trash"/>
                    </flux:tooltip>
                @endif
            @else
                @if ($billing->amount - $billing->discount > 0)
                    <flux:button size="xs"
                        wire:click="$dispatch('download-billing-modal', {billing: '{{ $billing->id }}'})"
                        title=" {{ __('billing.button.receipt') }}" style="cursor: pointer;" variant="primary"
                        icon="printer" disabled />
                @endif
            @endif
        </flux:button.group>
    </div>
</div>
