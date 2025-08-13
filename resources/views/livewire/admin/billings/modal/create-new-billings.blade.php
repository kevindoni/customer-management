<div>
    @if ($createNewBillingsModal)
        <flux:modal class="md:min-w-sm" wire:model="createNewBillingsModal" :dismissible="false" :closable="false">
            <div class="space-y-6">

                <div class="grid auto-rows-min px-3 py-4">
                    <div
                        class="items-center justify-center flex">
                        <div class="py-15">
                            <flux:icon.loading class="size-20" />
                        </div>
                    </div>
                </div>
                <div class="items-center justify-center flex">
                    <flux:heading size="lg">
                        Please wait!
                    </flux:heading>
                </div>
            </div>
        </flux:modal>
    @endif
</div>
