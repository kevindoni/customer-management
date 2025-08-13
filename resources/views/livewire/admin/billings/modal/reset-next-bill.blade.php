<div>
    @if ($showResetNextBillModal)
        <flux:modal class="md:w-120" wire:model="showResetNextBillModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('billing.label.reset-next-bill') }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!!trans('billing.content.reset-next-bill') !!}
                    </flux:text>


                </div>
                <form wire:submit='reset_next_bill' class="flex flex-col gap-6">

                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                        type="password" name="current_password" placeholder="{{ __('customer.ph.input-your-password') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('showResetNextBillModal', false)">
                                {{ trans('user.button.cancel') }}</flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="danger" icon="trash">
                            {{ trans('billing.button.reset-next-bill')}}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
