<div>
    @if ($paymentModal)
        <flux:modal class="md:w-120" wire:model="paymentModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('billing.payment.payment', ['paket' => $invoice->customer_paket->paket->name, 'customer' => $invoice->customer_paket->user->full_name]) }}
                    </flux:heading>
                </div>

                <form wire:submit="payment" class="flex flex-col gap-4">
                    <flux:field>
                        <flux:label>{!! trans('billing.label.payment-methode') !!}</flux:label>
                        <flux:select wire:model.live="input.selectedPaymentMethode" name="selectedPaymentMethode">
                            <flux:select.option value="">{{ trans('billing.ph.select-payment-methode') }}
                            </flux:select.option>
                            <flux:select.option value="cash">{{ trans('billing.ph.cash') }}</flux:select.option>
                            @if (count(\App\Models\Bank::where('disabled', false)->get()))
                                <flux:select.option value="bank_transfer">{{ trans('billing.ph.bank-transfer') }}</flux:select.option>
                            @endif
                            <flux:select.option value="paylater">{{ trans('billing.ph.paylater') }}</flux:select.option>
                        </flux:select>
                        <flux:error name="selectedPaymentMethode"/>
                    </flux:field>



                    <div x-show="show" x-data="{ show: false }"
                        {{ '@' . 'input-amount' }}.window="show = event.detail.open">
                        <flux:input wire:model="input.amount" type="text" name="amount"
                        label="{{ trans('billing.label.amount') }}"/>
                    </div>
                    <div x-show="show" x-data="{ show: false }"
                        {{ '@' . 'select-paylater-date' }}.window="show = event.detail.open">

                        <flux:input wire:model="input.paylaterDate" type="date" name='paylaterDate'
                            label="{{ trans('billing.label.paylater') }}"
                            min="{{ \Carbon\Carbon::now()->addDay()->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::parse($invoice->end_periode)->format('Y-m-d') }}" />
                    </div>

                    <div class="relative z-0 w-3/4" x-show="show" x-data="{ show: false }"
                        {{ '@' . 'select-methode-transfer' }}.window="show = event.detail.open">
                         <flux:field>
                            <flux:label>{!! trans('billing.label.bank') !!}</flux:label>
                            <flux:select wire:model.live="input.selectedBankTransfer" name="selectedBankTransfer">
                                <flux:select.option value="">{{ trans('billing.ph.select-bank') }}
                                </flux:select.option>
                                @foreach (\App\Models\Bank::where('disabled', false)->orderBy('bank_name', 'asc')->get() as $account_bank)
                                    <flux:select.option value="{{ $account_bank->slug }}">{{ $account_bank->bank_name }} -
                                        {{ $account_bank->account_name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="selectedBankTransfer"/>
                        </flux:field>
                     </div>

                    <flux:textarea wire:model="input.note" :label="__('billing.label.note')" type="text" disabled
                        name="note" autofocus autocomplete="note" placeholder="{{ __('billing.ph.write-note') }}" />


                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('paymentModal', false)" variant="primary">
                            {{ trans('billing.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                            {{ __('billing.button.process') }}
                        </flux:button>
                    </div>

                </form>
            </div>
        </flux:modal>
    @endif
</div>
