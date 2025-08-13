<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Aktivasi perangkat melalui QR Code
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Tekan tombol
                        <flux:tooltip content="Scan with QR Code">
                            <flux:button variant="primary" size="xs" icon="qr-code" />
                        </flux:tooltip> dan tunggu hingga QR Code ditampilkan dan siap untuk discan.
                    </flux:text>
                    <flux:text>
                        Buka aplikasi whatsapp anda di smartphone, pilih menu <flux:icon.ellipsis-vertical
                            size="4" class="flex inline" /> -> Tautkan perangkat
                        <br>Saat QR Code sudah ditampilkan, arahkan camera smartphone anda ke QR Code untuk mulai
                        aktivasi
                        device.
                        <br>Tunggu hinga device terkoneksi ke Whatsapp. Saat berhasil terkoneksi, status device akan
                        berubah
                        menjadi <flux:badge size="sm" color="green" variant="solid">Connected.
                        </flux:badge>

                    </flux:text>

                    <flux:text>
                        Untuk memastikan bahwa device anda berhasil terkoneksi, tekan tombol <flux:tooltip
                            content="Test Send Message">
                            <flux:button size="xs" variant="primary" icon="envelope" style="cursor: pointer;" />
                        </flux:tooltip>, jika berhasil, anda akan menerima pesan
                        <br>
                        <p class="text-blue-700">Congrulation, whatsapp gateway successfully connected.</p>
                    </flux:text>
                </div>
            </div>

            <div class="flex flex-col gap-2 mt-6">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Aktivasi Pesan Notifikasi
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Kunjungi halaman <flux:button variant="success" icon="wa" size="xs"
                            href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway
                        </flux:button> dan pilih kolom berikut untuk mulai menentukan device yang akan digunakan.

                        <div
                            class="md:w-124 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            <div class="flex flex-col gap-2">
                                <div class="flex items-start max-md:flex-col">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-boot') }}
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70 flex gap-2">
                                        <flux:field>
                                            <flux:select disabled>
                                                <flux:select.option value="">
                                                    {{ trans('whatsapp-gateway.ph.select-wa-number') }}
                                                </flux:select.option>
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                </div>

                                <div
                                    class="flex items-start max-md:flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-2 bg-green-200">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-notification') }}
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:field>
                                            <flux:select>
                                                <flux:select.option>
                                                    62xxxxxxxx
                                                </flux:select.option>
                                            </flux:select>
                                        </flux:field>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </flux:text>

                </div>
            </div>

            <div class="flex flex-col gap-2 mt-6">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Aktivasi Pesan Otomatis
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Kunjungi halaman <flux:button variant="success" icon="wa" size="xs"
                            href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway
                        </flux:button> dan pilih kolom berikut untuk mulai menentukan device yang akan digunakan.

                        <div
                            class="md:w-124 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            <div class="flex flex-col gap-2">
                                <div
                                    class="flex items-start max-md:flex-col rounded-xl border border-neutral-200 dark:border-neutral-700 p-2 bg-green-200">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-boot') }}
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70 flex gap-2">
                                        <flux:field>
                                            <flux:select>
                                                <flux:select.option>
                                                    62xxxxxxxx
                                                </flux:select.option>
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-notification') }}
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:field>
                                            <flux:select disabled>
                                                <flux:select.option value="">
                                                    {{ trans('whatsapp-gateway.ph.select-wa-number') }}
                                                </flux:select.option>
                                            </flux:select>
                                        </flux:field>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </flux:text>

                </div>
            </div>
        </div>

    </x-layouts.help>
</section>
