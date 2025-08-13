<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Menambahkan Mikrotik
                </flux:heading>
                <div class="flex flex-col gap-2">
                    <flux:text>
                        Pilih menu <flux:badge color="lime">Settings</flux:badge> -> <flux:badge color="lime"><a href="managements/general">Server</a>General Settings</flux:badge> kemudian pilih Menu <flux:badge color="lime"><a href="/managements/mikrotiks">Server</a></flux:badge>.
                    </flux:text>

                     <flux:text>
                        Untuk mulai menambahkan mikrotik baru, klik tombol <flux:button size="xs" variant="primary" icon="plus-circle">{{ __('mikrotik.button.create') }}</flux:button>
                    </flux:text>

                    <flux:text class="mt-6">
                    <flux:badge size="sm" color="orange" icon="bell-alert">Gunakan user mikrotik yang memiliki akses penuh.</flux:badge>
                    <br> User mikrotik yang digunakan harus memiliki akses penuh ke mikrotik.
                </flux:text>

                    <flux:text class="mt-6">
                    <flux:badge size="sm" color="orange" icon="bell-alert">Service api mikrotik harus aktif.</flux:badge><br>
                    Untuk semua versi mikrotik.
                    </flux:text>

                    <flux:text class="mt-6">
                    <flux:badge size="sm" color="orange" icon="bell-alert">Service www/www-ssl aktif (untuk ROS >= 7.9)</flux:badge><br>
                    Untuk ROS versi diatas atau sama dengan 7.9.<br>
                    Jika mikrotik anda diatas atau sama dengan versi 7.9, anda harus mengaktifkan service www atau www-ssl (pilih salah satu) pada mikrotik untuk koneksi aman.
                    Certificate diperlukan jika service ini diaktifkan, namun jika kalian tidak ingin menggunakan service www-ssl, anda bisa merubah dari pengaturan di halaman <flux:badge icon="server"><a target="_blank" href="{{route('managements.mikrotiks')}}"> {{trans('menu.servers')}}</a></flux:badge>.
                    </flux:text>

                </div>
            </div>
        </div>
    </x-layouts.help>
</section>
