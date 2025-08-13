<div>
    @if ($permanentlyDeleteMikrotikModal)
        <flux:modal class="md:w-120" wire:model="permanentlyDeleteMikrotikModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('mikrotik.delete-mikrotik', ['mikrotik' => $mikrotik->name]) }}
                    </flux:heading>
                </div>

                <form wire:submit="delete" class="flex flex-col gap-4">


                    <flux:checkbox wire:model.live="input.checkbox_delete_secret_on_mikrotik"
                    label="{{ $input['checkbox_delete_secret_on_mikrotik'] ? trans('mikrotik.label.yes-delete-secret-on-mikrotik'):trans('mikrotik.label.delete-secret-on-mikrotik') }}" />

                    @if($input['checkbox_delete_secret_on_mikrotik'])
                    <flux:text class="mt-2">{!! __('mikrotik.alert.check-delete-secret-on-mikrotik', [
                        'count_secret' => $countSecret,
                        'mikrotik' => $mikrotik->name,
                    ]) !!}
                    </flux:text>
                    @endif

                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                    type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                    <div class="flex items-center justify-end gap-2">
                        <flux:button wire:click="$set('permanentlyDeleteMikrotikModal', false)">
                            {{ trans('mikrotik.button.cancel') }}
                        </flux:button>
                        <flux:button type="submit" variant="danger" icon="trash" style="cursor:pointer">
                            {{ __('mikrotik.button.delete') }}
                        </flux:button>
                    </div>

                </form>
            </div>
        </flux:modal>
    @endif
</div>
