<section class="w-full">
    <x-layouts.help>
        <flux:heading size="lg" class="underline md:underline-offset-8">
            Menghapus Perangkat/Nomor dari Whatsapp Gateway Griyanet
        </flux:heading>
        <div class="flex flex-col gap-2 mt-6">
            <flux:text>
                Anda tidak dapat mengubah nomor yang sudah ditambahkan. Jika anda ingin mengubah nomor, silahkan hapus dan tambahkan nomor baru.
                Untuk menghapus perangkat atau nomor, silahkan ke halaman <flux:button size="xs"
                    href="{{ route('managements.whatsapp.number') }}" target="_blank">Devices</flux:button>
                <br> Tekan tombol <flux:button size="xs" variant="danger" icon="trash"
                style="cursor: pointer;" />. Kemudian ikuti petunjuk selanjutnya.
            </flux:text>
            <flux:text>
                <div
                    class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                    <div class="flex flex-col gap-6">
                        <div>
                            <flux:heading size="lg">
                                {{ trans('device.heading.delete-number',['number' => '+62xxxx']) }}
                            </flux:heading>
                            <flux:subheading>{{ trans('device.heading.subtitle-delete-number',['number' => '+62xxxx']) }}
                            </flux:subheading>
                        </div>

                        <div class="flex flex-col gap-6">
                            <flux:input :label="__('user.label.confirm-password')" value="12345678"/>
                            <div class="flex items-center justify-end">
                                <flux:button size="xs" variant="ghost" class="me-2">
                                    {{ __('device.button.cancel') }}
                                </flux:button>
                                <flux:button size="xs" icon="trash" variant="danger">
                                    {{ __('device.button.delete') }}
                                </flux:button>
                            </div>

                        </div>

                    </div>

                </div>
            </flux:text>
            <flux:text>
                Masukkan sandi login Customer Management anda dan Tekan tombol Delete untuk mulai menghapus nomor.
                <br>

                <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                    <flux:badge color="orange" icon="bell-alert" class="mb-2">!Perhatian</flux:badge><br>
                    Jika anda menghapus nomor, pastikan Nomor Notifikasi atau Pesan Otomatis di pengaturan Whatsapp gateway anda telah diganti dengan nomor yang baru.
                </div>
            </flux:text>


        </div>
    </x-layouts.help>
</section>
