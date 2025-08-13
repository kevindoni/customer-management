<div>
    <flux:select wire:model.change="input.changePaket" size="sm">
        @if ($customerPaket->paket->disabled)
            <flux:select.option value="{{ $customerPaket->paket_id }}"> {{ $customerPaket->paket->name }} - @if ($customerPaket->paket->price == 0)
                    {{ trans('paket.free') }}
                @else
                    @moneyIDR($customerPaket->paket->price)
                @endif
            </flux:select.option>
        @endif
        @foreach (\App\Models\Pakets\Paket::where('disabled', false)->where('mikrotik_id', $customerPaket->paket->mikrotik_id)->orderBy('price', 'ASC')->orderBy('name', 'ASC')->get() as $paket)
            <flux:select.option value="{{ $paket->id }}">
                {{ $paket->name }} - @if ($paket->price == 0)
                    {{ trans('paket.free') }}
                @else
                    @moneyIDR($paket->price)
                @endif
            </flux:select.option>
        @endforeach
    </flux:select>

    <flux:modal :name="'confirm-change-paket-'.$customerPaket->id">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">{{ trans('customer.alert.change-paket') }}</flux:heading>
                <flux:text class="mt-2">
                    <p>{{ $alert1 }}</p>
                    <p>{{ trans('customer.alert.change-this-paket-action') }}</p>
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">{{ trans('customer.button.cancel') }}</flux:button>
                </flux:modal.close>
                <flux:button wire:click="$dispatch('update-customer-paket',{customerPaket: '{{ $customerPaket->slug }}'})" variant="primary" style="cursor: pointer;">{{ trans('customer.button.yes-change-paket') }}</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
