<div>
    @if ($deleteMikrotikModal)
        <flux:modal class="md:w-120" wire:model="deleteMikrotikModal" :dismissible="false">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ trans('mikrotik.delete-mikrotik', ['mikrotik' => $mikrotik->name]) }}
                    </flux:heading>
                </div>

                <form wire:submit="delete" class="flex flex-col gap-4">
                    <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')"
                    type="password" name="current_password" placeholder="{{ __('Input your password') }}" />

                    <div class="flex items-center justify-end">
                        <flux:button type="submit" variant="danger" icon="trash" style="cursor:pointer">
                            {{ __('mikrotik.button.delete') }}
                        </flux:button>
                    </div>

                </form>
            </div>
        </flux:modal>
    @endif
</div>
