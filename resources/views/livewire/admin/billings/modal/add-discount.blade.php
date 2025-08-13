<div>
    @if ($addDiscountModal)
    <flux:modal class="md:w-120" wire:model="addDiscountModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('billing.label.title-add-discount', [
                    'paket' => $invoice->customer_paket->paket->name,
                    'customer' => $invoice->customer_paket->user->full_name,
                    'periode' => \Carbon\Carbon::parse($invoice->periode)->format('F Y'),
                    ]) }}
                </flux:heading>
            </div>

            <form wire:submit="add_discount" class="flex flex-col gap-4">

                <flux:input wire:model="input.discount" :label="trans('billing.label.add-discount')" type="text"
                    name="discount" autofocus autocomplete="discount"
                    placeholder="{{ trans('billing.label.add-discount') }}" />

                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="$set('addDiscountModal', false)" variant="primary">
                        {{ trans('billing.button.cancel') }}
                    </flux:button>

                    <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                        {{ __('billing.button.add-discount') }}
                    </flux:button>
                </div>
            </form>
        </div>
    </flux:modal>

    @endif
</div>
