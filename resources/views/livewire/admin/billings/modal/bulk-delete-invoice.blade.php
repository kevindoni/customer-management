<div>
    @if ($bulkDeleteInvoiceModal)
        <flux:modal class="md:w-120" wire:model="bulkDeleteInvoiceModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('Delete Invoice') }}
                    </flux:heading>

                    <flux:text class="mt-2">
                        {!!trans('Are you sure to delete invoice?') !!}
                    </flux:text>


                </div>
                <form wire:submit='deleteSelectedInvoice' class="flex flex-col gap-6">

                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" viewable autofocus
                        type="password" name="current_password" placeholder="{{ __('customer.ph.input-your-password') }}" />

                    <div class="flex gap-2">
                        <flux:spacer />
                        <flux:modal.close>
                            <flux:button style="cursor: pointer;" variant="ghost"
                                wire:click="$set('bulkDeleteInvoiceModal', false)">
                                {{ trans('user.button.cancel') }}
                            </flux:button>
                        </flux:modal.close>
                        <flux:button type="submit" variant="danger" icon="trash">
                           {!! trans_choice('billing.button.bulk-delete-invoice', collect($invoices)->count(), [
                                                'count' => collect($invoices)->count(),]) !!}
                        </flux:button>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
