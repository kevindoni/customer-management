<div>
    @if ($restoreDeletedMikrotikModal)
        <flux:modal class="md:w-120" wire:model="restoreDeletedMikrotikModal" :dismissible="false"
            @close="$dispatch('close-modal')">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('mikrotik.alert.restore-mikrotik', ['mikrotik' => $mikrotik->name]) }}
                    </flux:heading>
                    <flux:subheading>
                        {{ trans('mikrotik.alert.restore-mikrotik-content', ['mikrotik' => $mikrotik->name]) }}
                    </flux:subheading>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="$set('restoreDeletedMikrotikModal', false)" variant="ghost" icon="x-circle"
                        style="cursor: pointer">
                        {{ trans('mikrotik.button.cancel') }}
                    </flux:button>
                    <flux:button wire:click="restoredMikrotik" variant="success" iconTrailing="arrow-uturn-left"
                        style="cursor: pointer">
                        {{ trans('mikrotik.button.restore-mikrotik') }}
                    </flux:button>
                </div>


            </div>
        </flux:modal>
    @endif
</div>
