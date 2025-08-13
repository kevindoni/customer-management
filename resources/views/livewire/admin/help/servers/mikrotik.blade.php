<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Pengaturan Mikrotik
                </flux:heading>
                <div class="flex flex-col gap-2">
                    <flux:text>
                        Sebelum anda dapat menggunakan apilkasi Customer Management ini, lakukan konfigurasi pada
                        mikrotik anda.<br>
                        <br>
                        Buka Winbox dan login.<br>
                        Pada menu kiri, pilih <flux:badge color="lime">IP -> Service</flux:badge> dan aktifkan service
                        berikut.
                        <br>
                        <br>
                        <flux:badge size="sm" color="orange" icon="bell-alert">IP Service List (tanpa SSL)
                        </flux:badge>
                        <ul>
                            <li>* api : enable (Wajib untuk semua versi ROS)</li>
                            <li>* www : enable (Wajib untuk ROS mikrotik diatas atau sama dengan 7.9)</li>
                        </ul>
                        <br>
                        <br>

                        Jika anda menggunakan koneksi SSL, pastikan bahwa sertifikat ssl mikrotik anda masih berlaku dan
                        sudah terpasang di service. Aktifkan service berikut:<br>
                        <br>
                        <flux:badge size="sm" color="orange" icon="bell-alert">IP Service List (tanpa SSL)
                        </flux:badge>
                        <ul>
                            <li>* api-ssl : enable (Wajib untuk semua versi ROS)</li>
                            <li>* www-ssl : enable (Wajib untuk ROS mikrotik diatas atau sama dengan 7.9)</li>
                        </ul>
                        <br><br>
                    </flux:text>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Membuat Sertifikat SSL
                </flux:heading>
                <div class="flex flex-col gap-2">
                    <flux:text>
                        Sebelum anda dapat menggunakan koneksi SSL, anda dapat membuatnya terlebih dahulu jika belum
                        mempunyai.<br>
                        <br>
                        Buka Winbox dan login.<br>

                        Mikrotik Versi 7.9 keatas (mungkin dapat digunakan untuk versi di bawahnya juga)<br><br>
                        Pada menu kiri, pilih <flux:badge color="lime">System -> Certificates</flux:badge><br>
                        1. Tekan tombol
                        <flux:badge icon="plus" /> pada bagian kiri atas.<br>
                        2. Tab <flux:badge>General</flux:badge><br>
                        <ul class="ms-2">
                            <li><span class="font-bold mt-2">Name: </span>Isi nama sertifikat sesuai keinginan.</li>
                            <li><span class="font-bold  mt-2">Country:</span> ID</li>
                            <li><span class="font-bold  mt-2">Subject Alt. Name:</span> Pilih <flux:badge
                                    color="sky">IP</flux:badge> jika anda menggunakan IP dan <flux:badge
                                    color="sky">DNS</flux:badge> jika anda menggunakan DNS, kemudian pada kolom
                                disampingnya, hapus <flux:badge color="sky">::</flux:badge> dan masukkan IP Address
                                mikrotik atau DNS.<br>
                                Contoh:<br>
                                Ip Mikrotik anda 192.168.1.1 sehingga pengisiannya sebagai berikut.<br>
                                Subject Alt. Name: <flux:badge color="sky">IP : 192.168.1.1</flux:badge>
                            </li>
                            <li>Untuk lainnya biarkan default.</li>
                        </ul>
                        3. Tab <flux:badge>Key Usage</flux:badge><br>
                        <flux:badge color="sky" icon="check" size="sm">digital signature</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">key encipherment</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">data encipherment</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">key cert. sign</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">crl sign</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">tls client</flux:badge>
                        <flux:badge color="sky" icon="check" size="sm">tls server</flux:badge><br>
                        4. Setelah semua selesai, tekan tombol  <flux:badge>Sign</flux:badge> pada bagian kiri dan tunggu sampai proses berubah menjadi Done.<br>
                        5. Tekan tombol <flux:badge>Ok</flux:badge>.
                    </flux:text>
                </div>
            </div>

            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Pengaturan Sertifikat SSL pada Service
                </flux:heading>
                <div class="flex flex-col gap-2">
                    <flux:text>
                       Pastikan SSL yang anda buat diatas sudah berhasil, kemudian lakukan konfigurasi Service api-ssl dan www-ssl pada mikrotik.<br>
                        <br>
                        Buka Winbox dan login.<br>
                        Pada menu kiri, pilih <flux:badge color="lime">IP -> Service</flux:badge>.<br>
                        Klik 2x pada <flux:badge>api-ssl</flux:badge>, pada colom certificate arahkan ke Certificate yang sudah anda buat diatas.<br>
                        Klik 2x pada <flux:badge>www-ssl</flux:badge>, pada colom certificate arahkan ke Certificate yang sudah anda buat diatas.<br>
                    </flux:text>
                </div>
            </div>
        </div>
    </x-layouts.help>
</section>
