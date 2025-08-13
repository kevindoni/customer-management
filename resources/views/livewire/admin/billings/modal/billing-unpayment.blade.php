<div>
    @if ($unpaymentModal)
        <flux:modal class="md:w-120" wire:model="unpaymentModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        @if ($invoice->status == 'paylater')
                            {{ trans('billing.payment.cancel-paylater', ['paket' => $invoice->customer_paket->paket->name, 'customer' => $invoice->customer_paket->user->first_name]) }}
                        @else
                            {{ trans('billing.payment.unpayment', ['paket' => $invoice->customer_paket->paket->name, 'customer' => $invoice->customer_paket->user->first_name]) }}
                        @endif
                    </flux:heading>

                    <flux:subheading>
                        @if ($invoice->status == 'paylater')
                            {!! trans('billing.content.cancel-paylater', [
                                'deadline' => \Carbon\Carbon::parse($invoice->customer_paket->activation_date)->format('d'),
                            ]) !!}
                        @else
                            {!! trans('billing.content.cancel-payment') !!}
                        @endif
                    </flux:subheading>
                </div>

                <form wire:submit="unpayment" class="flex flex-col gap-4">
                    <flux:textarea wire:model="input.note" :label="__('billing.label.note')" type="text" name="note"
                        autofocus autocomplete="note" placeholder="{{ __('billing.ph.write-note') }}" />
                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('Input your password') }}" />


                    <div class="flex items-center justify-end gap-2">

                        <flux:button wire:click="$set('unpaymentModal', false)" variant="primary">
                            {{ trans('billing.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                            @if ($invoice->status == 'paylater')
                                {{ __('billing.button.cancel-paylater') }}
                            @else
                                {{ __('billing.button.unpayment') }}
                            @endif
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
