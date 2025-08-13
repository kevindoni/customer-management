<div>
    <flux:heading>Update to Version {{ \App\Livewire\Update::NEW_VERSION }}</flux:heading>
    <flux:text>Version application installed <flux:badge color="orange">{{ env('APP_VERSION') }}</flux:badge>
    </flux:text>
    <flux:text class="mt-2">
        <div class="font-semibold">News in version 2.0.6</div>
        <ul class="list-disc">
            <li>Perbaikan Bugs Add Invoice</li>
            <li>Tombol hapus invoice</li>
            <li>Kirim pesan ke semua pelanggan</li>
            <li>Catan Pembayaran</li>
        </ul>
    </flux:text>
	
	<flux:separator class="mt-2 mb-2" />
    <div class="flex items-center justify-end gap-2">
        @if(version_compare(env('APP_VERSION'), self::NEW_VERSION, '<'))
        <flux:button wire:click="update" variant="primary" icon="arrow-up">
            {{ __('Update') }}
        </flux:button>
        @endif
    </div>

</div>
