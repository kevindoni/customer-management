<section class="w-full">
    <x-layouts.help>
        <flux:heading size="lg" class="underline md:underline-offset-8">
            Sekarang anda dapat berlangganan Whatsapp Gateway Griyanet
        </flux:heading>
        <div class="flex flex-col gap-2 mt-6">
            <flux:text>
                Sebelum anda memulai untuk berlangganan, anda harus mengatur nama perusahaan, alamat email dan lainnya di halaman <flux:button variant="ghost" icon="cog" size="xs"
                    href="{{ route('managements.websystem') }}" target="_blank">General Settings</flux:button>.<br><br>
                Untuk mulai berlangganan, silahkan ke halaman <flux:button variant="success" icon="wa" size="xs"
                    href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway</flux:button>
                <br> Cukup dengan memasukkan sandi, dan anda sudah mulai bisa berlangganan. Kami menyediakan beberapa
                variant product dengan harga yang sangat terjangkau.



                <div class="w-full p-4 text-center sm:p-8">
                    <div
                        class="items-center justify-center space-y-4 sm:flex sm:space-y-0 sm:space-x-4 rtl:space-x-reverse">
                        <div
                            class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">
                                <div class="space-y-6" action="#">

                                        <h5 class="text-xl font-medium text-gray-900 dark:text-white">Register</h5>
                                        <p class="mb-5 text-base text-gray-500 sm:text-sm dark:text-gray-400">
                                            You dont have account on Griyanet Whatsapp Gateway or data corrupt. Please
                                            syncronize
                                            now.</p>


                                    <div>
                                        <flux:input placeholder="{{ __('whatsapp-gateway.helper.password') }}" />
                                        <flux:error class="mt-1" name="password" />
                                    </div>

                                    <flux:button variant="primary" size="sm" icon="arrow-right-circle">
                                        {{ trans('whatsapp-gateway.button.register')}}
                                    </flux:button>


                                </div>


                        </div>
                    </div>
                </div>
            </flux:text>
                <flux:text>
                Anda akan mendapatkan jumlah pesan yang tidak terbatas, numun yang perlu diperhatikan adalah gunakan
                nomor yang bukan nomor penting. Karena jika nomor anda terdeteksi
                sebagai spam, whatsapp akan memblokir nomor anda sehingga tidak dapat digunakan kembali.
            </flux:text>


        </div>
    </x-layouts.help>
</section>
