<div>
    @if ($addBankModal)
        <flux:modal class="md:w-120" wire:model="addBankModal" :dismissible="false">
            <div class="space-y-6">

                <flux:heading size="lg">
                    @if ($bank->slug)
                        {{ trans('bank.edit-account', ['bank' => $bank->bank_name]) }}
                    @else
                        {{ trans('bank.add-account') }}
                    @endif
                </flux:heading>
                <flux:subheading>
                    @if ($bank->slug)
                        {{ $bank->bank_name . ' - ' . $bank->account_number . ' - ' . $bank->account_name }}
                    @endif
                </flux:subheading>

                <form wire:submit="{{ $bank->slug ? 'updateBank' : 'addBank' }}">
                    <div class="flex flex-col gap-4">
                        <flux:input wire:model="input.bank_name" :label="__('bank.table.bank')" type="text"
                            name="bank_name" autofocus autocomplete="bank_name"
                            placeholder="{{ __('bank.helper.bank') }}" />

                        <flux:input wire:model="input.account_number" :label="__('bank.table.account-number')"
                            type="text" name="account_number" autofocus autocomplete="account_number"
                            placeholder="{{ __('bank.helper.account-number') }}" />

                        <flux:input wire:model="input.account_name" :label="__('bank.table.account-name')"
                            type="text" name="account_name" autofocus autocomplete="account_name"
                            placeholder="{{ __('bank.helper.account-name') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="$set('addBankModal', false)" variant="ghost">
                                {{ trans('bank.button.cancel') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                @if ($bank->slug)
                                    {{ __('bank.button.update-bank') }}
                                @else
                                    {{ __('bank.button.add-bank') }}
                                @endif
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
