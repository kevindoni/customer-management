<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                Memasukkan Customer dari PPP Secret Mikrotik
            </flux:heading>
            <div class="flex flex-col gap-2">
                <flux:text>
                    Untuk memasukkan customer dari mikrotik, silahkan pergi ke halaman <flux:badge icon="server"><a target="_blank" href="{{route('managements.mikrotiks')}}"> {{trans('menu.servers')}}</a></flux:badge>.
                </flux:text>

                 <flux:text>
                    Kemudian pilih tombol View <flux:button size="xs" variant="primary" icon="eye"/>. Halaman ini digunakan untuk mengimport dan mengexport cutomer dan paket ke mikrotik.
                </flux:text>
                <flux:text class="mt-6">
                <flux:badge size="sm" color="orange" icon="bell-alert">Format PPP Profile di Mikrotik</flux:badge><br>
                Patikan format profile di mikrotik sudah sesuai. Lihat pada menu <flux:badge size="sm"><a href="{{ route('helps.servers.importProfile') }}" target="_blank">Import Profile</a></flux:badge> untuk lebih lanjut.<br>
                <br>
                <br>
                Untuk mulai memasukkan customer dari mikrotik, tekan tombol <flux:button variant="primary"
                        size="sm" iconTrailing="arrow-down-tray" style="cursor:pointer">
                        {{ __('mikrotik.button.import-customer-from-mikrotik',['mikrotik' => 'Nama Mikrotik']) }}
                    </flux:button> dan ikuti petunjuk selanjutnya.

                    <br><br><br>
                    <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                    <flux:badge color="orange" icon="bell-alert" class="mb-2">!Perhatian</flux:badge><br>
                    Jika pada <flux:badge color="sky" size="sm" class="mb-2"><a href="{{ route('managements.websystem') }}" target="_blank">General Setting</a></flux:badge> Customer Management menggunakan <flux:badge color="sky" size="sm" class="mb-2">Queue Connection: sync</flux:badge>, anda hanya bisa mengimport profile sebanyak <flux:badge color="sky" size="sm" class="mb-2">Max. Process</flux:badge>. Hal ini dilakukan untuk mengurangi server anda bekerja terlalu berat.
                </div>
                </flux:text>
            </div>

        </div>
    </x-layouts.help>
</section>
