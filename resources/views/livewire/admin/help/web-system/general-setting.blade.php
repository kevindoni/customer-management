<section class="w-full">
    <x-layouts.help>
        <flux:heading size="lg" class="underline md:underline-offset-8 flex">
            <flux:icon.cog /> General Setting
        </flux:heading>
        <div class="grid  mt-6">
            <flux:text>
                Halaman ini digunakan untuk mengkonfigurasi Customer Management secara menyeluruh.<br>
                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <div
                        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                        <div class="flex flex-col gap-2">
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.company') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.title" type="text" name="title" autocomplete="title"
                                    placeholder="{{ __('websystem.label.company') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.app-url') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.app_url" type="text" name="app_url"
                                    autocomplete="app_url" placeholder="{{ __('websystem.placeholder.app-url') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.email') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.email" type="text" name="email" autocomplete="email"
                                    placeholder="{{ __('websystem.placeholder.email') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.address') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.address" type="text" name="address"
                                    autocomplete="address" placeholder="{{ __('websystem.placeholder.address') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.city') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.city" type="text" name="city" autocomplete="city"
                                    placeholder="{{ __('websystem.placeholder.city') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.postal_code') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.postal_code" type="text" name="postal_code"
                                    autocomplete="postal_code"
                                    placeholder="{{ __('websystem.placeholder.postal_code') }}" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.phone') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.phone" type="text" name="phone" autocomplete="phone"
                                    placeholder="{{ __('websystem.placeholder.phone') }}" />
                            </flux:input.group>

                            <div>
                                <flux:input.group>
                                    <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.tax-rate') }}
                                    </flux:input.group.prefix>
                                    <flux:input wire:model="input.tax_rate" type="text" name="tax_rate"
                                        autocomplete="tax_rate"
                                        placeholder="{{ __('websystem.placeholder.tax-rate') }}" />
                                    <flux:input.group.suffix>%</flux:input.group.suffix>
                                </flux:input.group>
                                <span class="text-gray-500">{{ __('websystem.info.tax-rate') }}</span>
                            </div>

                            <div class="flex items-center justify-end gap-2">
                                <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                    {{ __('customer.button.save') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <flux:badge color="sky">Company:</flux:badge> Isi nama perusahaan atau perorangan, ini
                        diperlukan juga untuk pendaftaran Whatsapp Gateway dan Payment Gateway.
                        <flux:badge color="sky">App Url:</flux:badge> Isi url website aplikasi Customer Management,
                        ini diperlukan juga untuk pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        Pengisian url yang salah dapat mengakibatkan pemrosesan pembayaran dari payment gateway gagal
                        atau permintaan Whatsapp dari customer tidak dapat diterima.
                        <flux:badge color="sky">Email:</flux:badge> Isi dengan email aktif, ini diperlukan juga untuk
                        pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        <flux:badge color="sky">Address:</flux:badge> Isi dengan alamat tempat anda membuka usaha,
                        ini diperlukan juga untuk pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        <flux:badge color="sky">City:</flux:badge> Isi dengan kota tempat anda membuka usaha, ini
                        diperlukan juga untuk pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        <flux:badge color="sky">Postal Code:</flux:badge> Isi dengan kodepos tempat anda membuka
                        usaha, ini diperlukan juga untuk pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        <flux:badge color="sky">Phone:</flux:badge> Isi dengan nomor telephone/handphone anda, ini
                        diperlukan juga untuk pendaftaran Whatsapp Gateway dan Transaksi Payment Gateway.
                        <flux:badge color="sky">Tax Rate:</flux:badge> Saat ini di Indonesia menerapkan tarif 12%,
                        Jika anda pengusaha kena pajak, isikan cukup dengan angka 12. Namun ini masih dalam percobaan,
                        dan belum menggunakan rumus terbarukan dari Dirjen Pajak.
                    </div>
                </div>

            </flux:text>
        </div>

        <div class="grid  mt-6">
            <flux:text>
                <div class="grid md:grid-cols-2 gap-4 mt-4">
                    <div
                        class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                        <div class="flex flex-col gap-2">
                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.app-env') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.app_env">
                                    <flux:select.option value="local">Local</flux:select.option>
                                    <flux:select.option value="production">Production</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.app-debug') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.app_debug">
                                    <flux:select.option value="true">True</flux:select.option>
                                    <flux:select.option value="false">False</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.session-driver') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.session_driver">
                                    <flux:select.option value="database">Database</flux:select.option>
                                    <flux:select.option value="file">File</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.cache-store') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.cache_store">
                                    <flux:select.option value="database">Database</flux:select.option>
                                    <flux:select.option value="file">File</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.queue-connection') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.queue_connection">
                                    <flux:select.option value="database">Database</flux:select.option>
                                    <flux:select.option value="sync">Sync</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.app-timezone') }}
                                </flux:input.group.prefix>
                                <flux:input wire:model="input.app_timezone" type="text" name="app_timezone"
                                    autocomplete="app_timezone" placeholder="ex: Asia/Jakarta" />
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.isolir-driver') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.isolir_driver" name="isolir_driver">
                                    <flux:select.option value="mikrotik">Mikrotik</flux:select.option>
                                    <flux:select.option value="server">Server</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <flux:input.group>
                                <flux:input.group.prefix class="w-1/2">{{ __('websystem.label.subscription-type') }}
                                </flux:input.group.prefix>
                                <flux:select wire:model="input.subscription_type" name="subscription_type">
                                    <flux:select.option value="prabayar">Pra Bayar</flux:select.option>
                                    <flux:select.option value="pascabayar">Pasca Bayar</flux:select.option>
                                </flux:select>
                            </flux:input.group>

                            <div class="flex items-center justify-end gap-2">
                                <flux:button wire:click="link_storage" variant="primary" iconTrailing="folder">
                                    {{ __('websystem.button.link-storage') }}
                                </flux:button>
                                <flux:button wire:click="optimize" variant="primary" iconTrailing="rocket-launch">
                                    {{ __('websystem.button.optimize') }}
                                </flux:button>
                                <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                    {{ __('customer.button.save') }}
                                </flux:button>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <flux:badge color="sky">App Env:</flux:badge> Gunakan mode Local untuk pengembangan, dan
                        mode Production saat website anda sudah siap digunakan.
                        <flux:badge color="sky">App Debug:</flux:badge> Pada mode Local, aktifkan fungsi ini dengan
                        mengubah ke True.Hal ini dapat memudahkan anda untuk melacak kesalahan kode.
                        <flux:badge color="sky">Session Driver:</flux:badge> Local untuk penyimpanan sesi dalam
                        bentuk file dan database untuk penyimpanan sesi ke database.
                        <flux:badge color="sky">Cache Store:</flux:badge> Local untuk penyimpanan cache dalam bentuk
                        file dan database untuk penyimpanan cache ke database.
                        <flux:badge color="sky">Queue Connection:</flux:badge> Ini adalah fitur untuk meningkatkan
                        kinerja website anda, jika anda menggunakan VPS Server atau sejenisnya, anda dapat mengaturnya
                        ke database. Namun anda perlu melakukan pengaturan Queue Service di dalam VPS anda atau aplikasi
                        customer management ini tidak dapat bekerja. Namun bagi anda pengguna server local yang tidak
                        memiliki fungsi Queue Service, anda dapat mengaturnya ke mode sync.
                        <flux:badge color="sky">App Time Zone:</flux:badge> Default di Indonesia GMT+7 adalah
                        Asia/Jakarta
                        <flux:badge color="sky">Isolir Driver:</flux:badge> Anda dapat menentukan pemrosesan isolir
                        akan menggunakan mikrotik atau server. Jika anda memiliki user yang banyak dan menggunakan
                        Server yang memiliki fitur Cronjob, saya menyarankan anda memilih mode Server. Namun dengan
                        memilih mode Server, anda juga harus melakukan konfigurasi Cronjob pada server anda. Namun jika
                        anda memilih menggunakan Mikrotik, ini juga akan memakan resource di Mikrotik.
                        <flux:badge color="sky">Subscription Type:</flux:badge> Jika anda memberlakukan pembayaran
                        terlebih dahulu kepada customer, anda dapat memilih PraBayar. Dan Jika anda memberlakukan system
                        pemakaian terlebih dahulu kemudian baru membayar, silahkan pilih Pasca Bayar. Untuk mode Pasca
                        Bayar, saat ini (8 Mei 2025) masih dalam proses pengembangan.
                    </div>
                </div>

            </flux:text>
        </div>
    </x-layouts.help>
</section>
