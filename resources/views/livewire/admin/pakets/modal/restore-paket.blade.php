<div>
    @if ($restorePaketModal)
        <flux:modal class="md:w-120" wire:model="restorePaketModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('paket.alert.restore-paket', ['paket' => $paket->name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('paket.alert.restore-paket-content', ['paket' => $paket->name, 'mikrotik' => $paket->mikrotik->name]) }}
                    </flux:subheading>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="$set('restorePaketModal', false)" variant="ghost" icon="x-circle"
                        style="cursor: pointer">
                        {{ trans('paket.button.cancel') }}
                    </flux:button>
                    <flux:button wire:click="restoredPaket" variant="success" iconTrailing="arrow-uturn-left"
                        style="cursor: pointer">
                        {{ trans('paket.button.restore-paket') }}
                    </flux:button>
                </div>


            </div>
        </flux:modal>
    @endif
</div>
