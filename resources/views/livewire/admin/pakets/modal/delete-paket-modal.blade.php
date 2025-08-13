<div>
    @if ($deletePaketModal)

    <flux:modal class="md:w-120" wire:model="deletePaketModal" :dismissible="false" @close="$dispatch('close-modal')">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">
                    {{ trans('paket.alert.header-delete', ['paket' => $paketSelect->name]) }}
                </flux:heading>
                <flux:subheading>
                    {{ trans('paket.alert.content-delete', ['paket' => $paketSelect->name, 'mikrotik' =>
                    $paketSelect->mikrotik->name]) }}
                </flux:subheading>
            </div>

            @if($paketSelect->customer_pakets->count())
            <flux:field>
                <flux:select wire:model.change="input.selectedPaket" :label="__('paket.ph.select-action')">
                    <flux:select.option value=""> {{ trans('paket.ph.select-action') }}
                    </flux:select.option>
                    <flux:select.option value="{{ $paketSelect->id }}">
                        {{ trans('paket.ph.delete-all-client', [
                        'paket' => $paketSelect->name,
                        'count_client' => count($paketSelect->customer_pakets),
                        ]) }}
                    </flux:select.option>
                    @foreach (\App\Models\Pakets\Paket::whereNot('id', $paketSelect->id)->where('disabled',
                    false)->orderBy('name', 'asc')->get() as $paket)
                    <flux:select.option value="{{ $paket->id }}">
                        {{ trans('paket.ph.move-to', [
                        'count_client' => count($paketSelect->customer_pakets),
                        ]) }}
                        {{ $paket->name }}
                    </flux:select.option>
                    @endforeach
                </flux:select>
                <flux:error name="selectedPaket" />

            </flux:field>
            @endif

            <flux:input wire:model="input.current_password" :label="__('user.label.confirm-password')" type="password"
                name="current_password" autofocus autocomplete="current_password" placeholder="{{ __('Password') }}" />

            <div class="flex items-center justify-end">
                <flux:button wire:click="$set('deletePaketModal', false)" class="me-2">
                    {{ trans('user.button.nevermind') }}
                </flux:button>
                @if($input['showForceDeleteButton'])
                <flux:button wire:click="forceDeletePaket" variant="danger" icon="trash">
                    {{ trans('user.button.force-delete') }}
                </flux:button>
                @else
                <flux:button wire:click="deletePaket" variant="danger" icon="trash">
                    {{ trans('user.button.delete') }}
                </flux:button>
                @endif
            </div>
             @if($input['showForceDeleteButton'])
            <flux:text class="text-red-500" size="sm">
                If you force delete, The package may be deleted without deleting the data on your MikroTik. Please delete it manually from your MikroTik.
            </flux:text>
            @endif

        </div>

    </flux:modal>
    @endif
</div>
