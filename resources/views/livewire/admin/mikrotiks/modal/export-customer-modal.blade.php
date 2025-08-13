<div>
    @if ($exportCustomerModal)
    <flux:modal class="md:w-120" wire:model="exportCustomerModal" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('mikrotik.export-customers') }}
                </flux:heading>
                <flux:subheading>{{ trans('mikrotik.subtitles.add-mikrotik') }}</flux:subheading>
            </div>

            <form wire:submit="exportCustomer">
                <div class="flex flex-col gap-4">
                    <div>
                        <flux:select wire:model.change="input.selectedServer" :label="trans('mikrotik.ph.export-to')" name="selectedServer">
                            <flux:select.option value="">{{ trans('paket.ph.select-mikrotik') }}</flux:select.option>
                            @foreach (\App\Models\Servers\Mikrotik::where('disabled', false)->orderBy('name', 'asc')->get() as $optionMikrotik)
                            <flux:select.option value="{{ $optionMikrotik->id }}">
                                {{ $optionMikrotik->name }}
                            </flux:select.option>
                            @endforeach
                        </flux:select>
                        <p class="ms-auto mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ trans('paket.helper.mikrotik') }}
                        </p>
                    </div>

                    <flux:text class="mt-2">{{ __('mikrotik.helper.export-customer-to-mikrotik', [
                        'maxSecret' => $maxSecret,
                        'countSecret' => $countDifferentUserSecret,
                        'fromMikrotik' => $fromMikrotik->name,
                        'toMikrotik' => $toMikrotik? $toMikrotik->name : '(Select Mikrotik)'
                    ]) }}</flux:text>


                    <div class="flex items-center justify-end gap-2">
                        <flux:button size="sm"  wire:click="$set('exportCustomerModal', false)" variant="ghost" style="cursor:pointer">
                            {{ trans('paket.button.cancel') }}
                        </flux:button>

                        <flux:button type="submit" size="sm" iconTrailing="arrow-up-tray" style="cursor:pointer">
                            {{ __('mikrotik.button.export-customer-to-mikrotik') }}
                        </flux:button>
                    </div>
                </div>
            </form>
        </div>
    </flux:modal>

    @endif
</div>
