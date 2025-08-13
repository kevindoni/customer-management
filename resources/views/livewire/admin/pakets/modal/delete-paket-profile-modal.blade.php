<div>
    @if ($deletePaketProfileModal)
    <flux:modal class="md:w-120" wire:model="deletePaketProfileModal" :dismissible="false"
    @close="$dispatch('close-modal')">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">
                {{ trans('paket.alert.header-delete-profile', ['profile' => $paketProfileSelect->profile_name]) }}
            </flux:heading>
            <flux:subheading>
                {{ trans('paket.alert.content-delete-profile', ['profile' => $paketProfileSelect->profile_name]) }}
            </flux:subheading>
        </div>

        <flux:checkbox wire:model.live="input.checkbox_delete_on_mikrotik" disabled
        label="{{ trans('paket.helper.delete-on-mikrotik') }}" />

        <flux:checkbox wire:model.live="input.checkbox_permanent_delete"
        label="{{ $input['checkbox_permanent_delete'] ? trans('paket.helper.if-check-delete-permanent'):trans('paket.helper.if-uncheck-delete-permanent') }}" />

        <flux:field>
            <flux:select wire:model="input.selectedPaketProfile" :label="__('paket.ph.select-action')">
                <flux:select.option value="">{{ trans('paket.ph.select-action') }}
                </flux:select.option>
                <flux:select.option value="{{ $paketProfileSelect->id }}">{{ trans('paket.ph.delete-all-profile', [
                    'profile' => $paketProfileSelect->profile_name,
                    'count_paket' => count($paketProfileSelect->pakets),
                    'count_client' => count($paketProfileSelect->customer_pakets),
                ]) }}
                </flux:select.option>
                @foreach (\App\Models\Pakets\PaketProfile::whereNot('id', $paketProfileSelect->id)->where('disabled', false)->orderBy('profile_name', 'asc')->get() as $paketProfile)
                    <flux:select.option value="{{ $paketProfile->id }}"> {{ trans('paket.ph.profile-move-to', [
                        'count_paket' => count($paketProfileSelect->pakets),
                        'count_client' => count($paketProfileSelect->customer_pakets),
                    ]) }}
                    {{ $paketProfile->profile_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>
            <flux:error name="selectedPaketProfile" />
        </flux:field>

        <p class="ms-auto mt-2 text-xs text-gray-500 dark:text-gray-400">
            {!! trans('paket.helper.option-delete-profile-permanent', [
                'profile' => $paketProfileSelect->name,
                'count_client' => count($paketProfileSelect->customer_pakets),
                'count_paket' => count($paketProfileSelect->pakets),
            ]) !!}</span>
        </p>

        <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" type="password"
        name="current_password" autofocus autocomplete="current_password"
        placeholder="{{ __('Password') }}" />

        <div class="flex items-center justify-end">
            <flux:button wire:click="$set('deletePaketProfileModal', false)" class="me-2">
                {{ trans('user.button.nevermind') }}
            </flux:button>
            <flux:button wire:click="deletePaketProfile" variant="danger" icon="trash">
                {{ trans('user.button.delete') }}
            </flux:button>
        </div>

    </div>

    </flux:modal>

    @endif
</div>
