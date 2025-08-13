<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                Memasukkan Paket dari Profile Mikrotik
            </flux:heading>
            <div class="flex flex-col gap-2">
                <flux:text>
                    Untuk memasukkan paket dari mikrotik, silahkan pergi ke halaman <flux:badge icon="server"><a target="_blank" href="{{route('managements.mikrotiks')}}"> {{trans('menu.servers')}}</a></flux:badge>.
                </flux:text>

                 <flux:text>
                    Kemudian pilih tombol View <flux:button size="xs" variant="primary" icon="eye"/>. Halaman ini digunakan untuk mengimport dan mengexport cutomer dan paket ke mikrotik.
                </flux:text>
                <flux:text class="mt-6">
                <flux:badge size="sm" color="orange" icon="bell-alert">Format PPP Profile di Mikrotik</flux:badge><br>
                Patikan format profile di mikrotik sebagai berikut:<br>
                <flux:badge size="sm" color="sky">Comment</flux:badge> pada PPP->Profile mikrotik diisi harga paket dengan menggunakan nomor (tanpa spasi, titik, koma). <br>
                Contoh:<br>
                Jika paket akan dibuat harga Rp. 500.000,-, pada comment diisi angka <flux:badge>500000</flux:badge><br><br>
                Pada tab Limit->Rate Limit diisi format limitasi secara keseluruhan.<br>
                Contoh:<br>
                Max limit: <flux:badge size="sm" color="lime">5M/10M</flux:badge><br>
                Bursh limit: <flux:badge size="sm" color="Red">10M/20M</flux:badge><br>
                Burst threshold: <flux:badge size="sm" color="orange">8M/15M</flux:badge><br>
                Burst time: <flux:badge size="sm" color="emerald">8/8</flux:badge></br>
                Priority: <flux:badge size="sm" color="violet">1</flux:badge><br>
                Limit at: <flux:badge size="sm" color="fuchsia">2M/5M</flux:badge><br>
                Rate limit diisi <flux:badge>
                    <flux:badge size="sm" color="lime">5M/10M</flux:badge>
                    <flux:badge size="sm" color="Red">10M/20M</flux:badge>
                    <flux:badge size="sm" color="orange">8M/15M</flux:badge>
                    <flux:badge size="sm" color="emerald">8/8</flux:badge>
                    <flux:badge size="sm" color="violet">1</flux:badge>
                    <flux:badge size="sm" color="fuchsia">2M/5M</flux:badge>
                </flux:badge>
                </flux:text>

                <flux:text>
                    Tekan tombol <flux:button size="sm" iconTrailing="arrow-down-tray" style="cursor:pointer">{{ __('mikrotik.button.import-pakets-to-customer-management') }}</flux:button>
Kemudian ikuti petunjuk selanjutnya.
                </flux:text>
            </div>

        </div>
    </x-layouts.help>
</section>
