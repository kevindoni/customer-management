<div>
    @if ($importPaketModal)
        <flux:modal class="md:w-120" wire:model="importPaketModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('mikrotik.import-pakets') }}
                    </flux:heading>
                </div>

                <form wire:submit="importPaket">
                    <div class="flex flex-col gap-4">
                        <flux:text class="mt-2">{{ __('mikrotik.helper.import-paket-from-profile-mikrotik', [
                        'maxProfile' => $maxProfile,
                        'countProfile' => $countDifferentProfile,
                        'mikrotik' => $mikrotik->name
                    ]) }}</flux:text>

                        <div class="flex items-center justify-end gap-2">
                            <flux:button size="sm"  wire:click="$set('importPaketModal', false)" variant="ghost" style="cursor:pointer">
                                {{ trans('paket.button.cancel') }}
                            </flux:button>

                            <flux:button type="submit" size="sm" iconTrailing="arrow-down-tray" style="cursor:pointer">
                                {{ __('mikrotik.button.import-pakets-to-customer-management') }}
                            </flux:button>
                        </div>
                    </div>
                </form>
            </div>
        </flux:modal>
    @endif
</div>
