<div>
    @if ($restoreCustomerModal)
        <flux:modal class="md:w-120" wire:model="restoreCustomerModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('user.title.restore-customer', ['user' => $user->full_name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('user.title.restore-customer-content', ['user' => $user->full_name]) }}
                    </flux:subheading>
                </div>

                <div class="flex items-center justify-end gap-2">
                    @if($user->customer_pakets()->whereNotNull('activation_date')->withTrashed()->count())
                        <flux:field variant="inline">
                            <flux:checkbox wire:model.live="input.restoreOnMikrotik" />
                            <flux:label>
                                {{ trans('customer.label.restore-on-mikrotik') }}
                            </flux:label>
                        </flux:field>
                        @endif

                    <flux:button wire:click="$set('restoreCustomerModal', false)" variant="ghost" icon="x-circle"
                        style="cursor: pointer">
                        {{ trans('user.button.cancel') }}
                    </flux:button>
                    <flux:button wire:click="restoredCustomer" variant="success" iconTrailing="arrow-uturn-left"
                        style="cursor: pointer">
                        {{ __('Restore') }}
                    </flux:button>
                </div>


            </div>
        </flux:modal>
    @endif
</div>
