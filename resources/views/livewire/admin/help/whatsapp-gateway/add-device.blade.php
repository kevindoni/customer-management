<section class="w-full">
    <x-layouts.help>
        <flux:heading size="lg" class="underline md:underline-offset-8">
            Menambahkan Perangkat/Nomor ke Whatsapp Gateway Griyanet
        </flux:heading>
        <div class="flex flex-col gap-2 mt-6">
            <flux:text>
                Untuk mulai menambahkan nomor, silahkan ke halaman <flux:button size="xs"
                    href="{{ route('managements.whatsapp.number') }}" target="_blank">Devices</flux:button>
                <br> Dan tekan tombol <flux:button size="xs" variant="primary" icon="plus-circle">
                    {{ __('whatsapp-gateway.button.add-device') }}
                </flux:button>.
            </flux:text>
            <flux:text>
                Silahkan isi form yang tersedia.
                <br>
                <div
                    class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                    <div class="flex flex-col gap-6">
                        <div class="flex flex-col gap-4">
                            <flux:input :label="__('whatsapp-gateway.label.device-name')" type="text"
                                value="Notifikasi 1" readonly />
                            <flux:input :label="__('whatsapp-gateway.label.number')" type="text"
                                value="6285xxxxxxxxx" readonly />
                        </div>

                        <flux:textarea :label="__('whatsapp-gateway.label.description')" type="text"
                            value="Ini adalah nomor notifikasi customer" readonly />

                        <div class="flex items-center justify-end">
                            <flux:button variant="ghost" class="me-2" size="xs">
                                {{ __('whatsapp-gateway.button.cancel') }}
                            </flux:button>
                            <flux:button variant="primary" size="xs">
                                {{ __('whatsapp-gateway.button.add-device') }}
                            </flux:button>
                        </div>

                    </div>

                </div>
            </flux:text>
            <flux:text>
                Tekan tombol Add Device untuk mulai menambahkan.
            </flux:text>


        </div>
    </x-layouts.help>
</section>
