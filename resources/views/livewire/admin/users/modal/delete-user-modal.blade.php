<div>
    <!-- Delete Confirmation Modal -->
    @if ($deleteUserModal)
    <flux:modal class="md:w-120" wire:model="deleteUserModal" :dismissible="false"
    @close="$dispatch('close-modal')">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ trans('user.alert.header-delete', ['user' => $userSelect->full_name]) }}
            </flux:heading>
            <flux:subheading>
                {{ trans('user.alert.content-delete', ['user' => $userSelect->full_name]) }}
            </flux:subheading>
        </div>

        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" type="password"
        name="current_password" autofocus autocomplete="current_password"
        placeholder="{{ __('Password') }}" />

        <div class="flex items-center justify-end">
            <flux:button wire:click="$set('deleteUserModal', false)" class="me-2">
                {{ trans('user.button.nevermind') }}
            </flux:button>
            <flux:button wire:click="delete" variant="danger" icon="trash">
                {{ trans('user.button.delete') }}
            </flux:button>
        </div>

    </div>

    </flux:modal>
    @endif
</div>
