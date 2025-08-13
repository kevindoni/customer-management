<div>
    @if ($bulkEditActivationModal)
        <flux:modal wire:model="bulkEditActivationModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('customer.paket.alert.header-bulk-edit-activation-paket', [
                            'count' => $customerPakets->count()
                        ]) }}
                    </flux:heading>
                </div>
                <form wire:submit='edit_bulk_activation_paket'>
                    <div class="flex flex-col gap-6">
                        <flux:input wire:model="input.activation_date" type="date" name='activation_date'
                            label="{{ trans('billing.label.activation-date') }}" max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"/>
                        <div class="flex gap-2">
                            <flux:spacer />
                            <flux:button style="cursor: pointer;" variant="ghost" size="sm"
                                wire:click="$set('bulkEditActivationModal', false)">
                                {{ trans('customer.button.cancel') }}
                            </flux:button>
                            <flux:button type="submit" variant="primary" icon="check" style="cursor: pointer;"
                                size="sm">
                                {{ trans('customer.paket.button.update') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
