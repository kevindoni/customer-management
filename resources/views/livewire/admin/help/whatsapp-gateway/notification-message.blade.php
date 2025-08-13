<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Pesan Notifikasi
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Kunjungi halaman <flux:button variant="success" icon="wa" size="xs"
                        href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway
                    </flux:button> untuk mulai mengelola pesan notifikasi anda.
                    </flux:text>


                </div>
            </div>

        </div>

    </x-layouts.help>
</section>
