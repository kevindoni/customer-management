<div>
    @if ($createInvoicesModal)
        <flux:modal class="md:w-120" wire:model="createInvoicesModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {!! trans('billing.modal.create-invoices') !!}
                    </flux:heading>
                </div>

                <div class="md:flex gap-6 flex-col">
                    <flux:input wire:model.live.debounce.500ms="search_address" type="text"
                        placeholder="{{ trans('billing.ph.search-with-address') }}" />

                    <flux:select wire:model.change="search_with_periode">
                        <flux:select.option value="all-periode">{{ __('billing.ph.all-periode') }}</flux:select.option>
                        @foreach ($periodes as $periode)
                            <flux:select.option value="{{ $periode->periode }}">
                                {{ \Carbon\Carbon::parse($periode->periode)->format('F Y') }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>

                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('createInvoicesModal', false)" variant="primary">
                            {{ trans('billing.button.cancel') }}
                        </flux:button>

                        @if ($fileExist)
                            <flux:button wire:click="download_invoices" variant="primary" iconTrailing="arrow-right">
                                {{ __('billing.button.download-invoice') }}
                            </flux:button>
                        @else
                            <flux:button wire:click="exportInvoices" variant="primary" iconTrailing="arrow-right">
                                {{ __('billing.button.create-invoices', ['count'=> $invoiceCount]) }}
                            </flux:button>
                        @endif
                    </div>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
