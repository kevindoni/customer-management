<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Payment Gateway Tripay
                </flux:heading>
                <div class="flex flex-col gap-6">
                    <flux:text>
                        Sebelum anda melakukan konfigurasi tripay, pastikan anda sudah memiliki akun tripay.
                        <br>Jika anda belum memiliki akun tripay, anda bisa melakukan pendaftaran melalui <a
                            target="_blank" href="https://tripay.co.id/?ref=TP38753">
                            <flux:badge color="sky">Daftar Tripay</flux:badge>.
                        </a>
                    </flux:text>

                    <flux:text>
                        Jika anda sudah memiliki akun tripay, lakukan konfigurasi sebagai berikut:<br>

                        <div
                            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                            <flux:badge color="sky" class="mb-2">Konfigurasi Tripay di Website Tripay</flux:badge>
                            <br>
                            Biasanya Tripay akan meminta URL Callback untuk mengirimkan status pembayaran.<br>
                            URL Callback:
                            <flux:input value="http://nama-domain/tripay/response" copyable /><br>
                            atau jika anda menggunakan koneksi SSL<br>
                            <flux:input value="https://nama-domain/tripay/response" copyable />
                            <br><br>

                            <div
                                class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                                <flux:badge color="orange" icon="bell-alert" class="mb-2">!Penting</flux:badge><br>
                                <ul>
                                    <li>* Ganti <flux:badge size="sm" color="lime">nama-domain</flux:badge>
                                        dengan domain atau ip publik yang mengarah ke server customer management.</li>
                                    <li>* Pastikan server customer management anda dapat diakses secara publik.</li>
                                    <li>* Untuk memulai uji coba silahkan ikuti panduan pada website tripay.</li>
                                </ul>
                            </div>
                        </div>
                    </flux:text>

                    <flux:text>
                        <div
                            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                            <flux:badge color="sky" class="mb-2">Konfigurasi Tripay di Customer Management
                            </flux:badge>
                            <br><br>
                            Merchant ID: Diisi dengan Kode Merchant Tripay<br>
                            <br>
                            <flux:badge class="mt-4 font-semibold" color="sky" size="lg">
                                Production
                            </flux:badge><br>
                            API Key: Diisi dengan API Key Production Tripay<br>
                            Secret Key: Diisi dengan Private Key Production Tripay<br>
                            <br>
                            <flux:badge class="mt-4 font-semibold" color="yellow" size="lg">
                                Development
                            </flux:badge><br>
                            API Key: Diisi dengan API Key Sandbox Tripay<br>
                            Secret Key: Diisi dengan Private Key Sandbox Tripay<br>

                        </div>
                    </flux:text>
                    <div class="flex md:flex-row flex-col gap-6">
                        <flux:text>
                            <flux:heading size="lg" class="underline md:underline-offset-8">
                                Mode Produksi
                            </flux:heading>
                            <div
                                class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-between gap-2">
                                        <div class="flex justify-start gap-2">
                                            <flux:heading size="xl" class="font-bold">TriPay</flux:heading>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <flux:field variant="inline">
                                                <flux:checkbox checked />
                                                <flux:label>
                                                    <flux:badge color="lime">{{ trans('Enable') }}
                                                    </flux:badge>
                                                </flux:label>

                                            </flux:field>


                                        </div>
                                    </div>

                                    <flux:separator class="my-2" />

                                    <div class="flex flex-col gap-2 opacity-100">
                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Merchant Code') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="Txxxx"
                                                    placeholder="{{ __('Merchant Code') }}" />
                                            </flux:input.group>
                                            <flux:error name="merchant_code" />
                                        </flux:field>


                                        <flux:badge class="mt-4 font-semibold" color="sky" size="lg">
                                            <flux:field variant="inline">
                                                <flux:checkbox checked />
                                                <flux:label>Production</flux:label>
                                            </flux:field>
                                        </flux:badge>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('API Key') }}" />
                                            </flux:input.group>
                                            <flux:error name="production_api_key" />
                                        </flux:field>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('Secret Key') }}" />
                                            </flux:input.group>
                                            <flux:error name="production_secret_key" />
                                        </flux:field>

                                        <flux:badge class="mt-4 font-semibold" color="yellow" size="lg">
                                            <flux:field variant="inline">
                                                <flux:checkbox disabled />
                                                <flux:label>Development</flux:label>
                                            </flux:field>
                                        </flux:badge>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('API Key') }}" />
                                            </flux:input.group>
                                        </flux:field>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('Secret Key') }}" />

                                            </flux:input.group>
                                            <flux:error name="development_secret_key" />
                                        </flux:field>

                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                                {{ __('customer.button.save') }}
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </flux:text>

                        <flux:text>
                            <flux:heading size="lg" class="underline md:underline-offset-8">
                                Mode Sandbox / Uji Coba
                            </flux:heading>

                            <div
                                class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                                <div class="flex flex-col gap-2">
                                    <div class="flex justify-between gap-2">
                                        <div class="flex justify-start gap-2">
                                            <flux:heading size="xl" class="font-bold">TriPay</flux:heading>
                                        </div>
                                        <div class="flex justify-between gap-2">
                                            <flux:field variant="inline">
                                                <flux:checkbox checked />
                                                <flux:label>
                                                    <flux:badge color="lime">{{ trans('Enable') }}
                                                    </flux:badge>
                                                </flux:label>

                                            </flux:field>


                                        </div>
                                    </div>

                                    <flux:separator class="my-2" />

                                    <div class="flex flex-col gap-2 opacity-100">
                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Merchant Code') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="Txxxx"
                                                    placeholder="{{ __('Merchant Code') }}" />
                                            </flux:input.group>
                                            <flux:error name="merchant_code" />
                                        </flux:field>


                                        <flux:badge class="mt-4 font-semibold" color="sky" size="lg">
                                            <flux:field variant="inline">
                                                <flux:checkbox disabled />
                                                <flux:label>Production</flux:label>
                                            </flux:field>
                                        </flux:badge>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('API Key') }}" />
                                            </flux:input.group>
                                            <flux:error name="production_api_key" />
                                        </flux:field>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('Secret Key') }}" />
                                            </flux:input.group>
                                            <flux:error name="production_secret_key" />
                                        </flux:field>

                                        <flux:badge class="mt-4 font-semibold" color="yellow" size="lg">
                                            <flux:field variant="inline">
                                                <flux:checkbox checked />
                                                <flux:label>Development</flux:label>
                                            </flux:field>
                                        </flux:badge>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('API Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('API Key') }}" />
                                            </flux:input.group>
                                        </flux:field>

                                        <flux:field>
                                            <flux:input.group>
                                                <flux:input.group.prefix class="w-1/2">{{ __('Secret Key') }}
                                                </flux:input.group.prefix>
                                                <flux:input type="text" value="XXXXXXXXXXXXXX"
                                                    placeholder="{{ __('Secret Key') }}" />

                                            </flux:input.group>
                                            <flux:error name="development_secret_key" />
                                        </flux:field>

                                        <div class="flex items-center justify-end gap-2">
                                            <flux:button type="submit" variant="primary" iconTrailing="arrow-right">
                                                {{ __('customer.button.save') }}
                                            </flux:button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
    </x-layouts.help>
</section>
