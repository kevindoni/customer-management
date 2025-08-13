<div>
    @if ($permanentlyDeletePaketModal)

    <flux:modal class="md:w-120" wire:model="permanentlyDeletePaketModal" :dismissible="false" @close="$dispatch('close-modal')">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('paket.alert.delete-paket-permanently', ['paket' => $paket->name]) }}
                </flux:heading>
                <flux:subheading>
                    {{ trans('paket.alert.delete-paket-permanently-content', ['paket' => $paket->name, 'mikrotik' =>
                    $paket->mikrotik->name]) }}
                </flux:subheading>
            </div>

            <flux:checkbox wire:model.live="input.checkbox_delete_on_mikrotik"
                label="{{ trans('paket.helper.delete-on-mikrotik') }}" />

                @if ($input['checkbox_delete_on_mikrotik'])
                <p class="ms-auto text-xs text-red-500 dark:text-red-400">
                    {!! trans('paket.helper.if-check-delete-on-mikrotik') !!}</span>
                </p>

                @endif

            <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" type="password"
                name="current_password" autofocus autocomplete="current_password" placeholder="{{ __('Password') }}" />

            <div class="flex items-center justify-end">
                <flux:button wire:click="$set('permanentlyDeletePaketModal', false)" class="me-2">
                    {{ trans('paket.button.cancel') }}
                </flux:button>
                <flux:button wire:click="permanentlyDeletePaket" variant="danger" icon="trash">
                    {{ trans('paket.button.delete-permanent') }}
                </flux:button>
            </div>

        </div>

    </flux:modal>
    @endif
</div>
