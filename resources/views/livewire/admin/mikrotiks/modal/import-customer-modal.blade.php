<div>
    @if ($importCustomerModal)
        <flux:modal class="md:w-120" wire:model="importCustomerModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('mikrotik.import-customers') }}
                    </flux:heading>
                </div>

                <form wire:submit="importCustomer">
                    <div class="flex flex-col gap-4">
                        <flux:text class="mt-2">
                            {{ __('mikrotik.helper.import-customer-to-mikrotik', [
                                'maxSecret' => $maxSecret,
                                'countSecret' => $countDifferentUserSecret,
                                'mikrotik' => $mikrotik->name,
                            ]) }}
                        </flux:text>

                        <flux:input wire:model="input.activation_date" type="date" name='activation_date'
                            label="{{ trans('mikrotik.label.activation-date') }}"
                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button size="sm" wire:click="$set('importCustomerModal', false)" variant="ghost"
                                style="cursor:pointer">
                                {{ trans('paket.button.cancel') }}
                            </flux:button>

                            <flux:button type="submit" size="sm" iconTrailing="arrow-down-tray"
                                style="cursor:pointer">
                                {{ __('mikrotik.button.import-user-secrets', [
                                    'maxSecret' => $maxSecret,
                                    'countSecret' => $countDifferentUserSecret,
                                ]) }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
