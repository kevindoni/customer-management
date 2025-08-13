<section class="w-full">

    <x-layouts.help>
        <div class="flex flex-col gap-6">

            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Konfigurasi Dasar
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Setelah anda berhasil berlangganan, anda dapat mengkonfigurasi Whatsapp Gateway di halaman
                        <flux:button variant="success" icon="wa" size="xs"
                            href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway
                        </flux:button>
                        <div
                            class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                            <flux:badge color="orange" icon="bell-alert" class="mb-2">!Perhatian</flux:badge><br>
                            Pastikan anda sudah berlangganan dan menambahkan nomor.
                        </div>


                        <div
                            class="md:mt-4 mt-2 flex flex-col  bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">

                                <div class="object-cover flex p-4 items-center w-full rounded-t-lg h-auto md:h-auto md:w-48 md:rounded-none md:rounded-s-lg bg-green-500">
                                    <img src="http://{{ config('wa-griyanet.server_url') }}/assets/images/logo_black.png" height="50px" alt="griyanet whatsapp gateway">
                                </div>


                            <div class="flex flex-col justify-between p-4 leading-normal w-full ">
                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ trans('whatsapp-gateway.label.email') }}
                                            <flux:tooltip content="Email yang digunakan untuk mendaftar di WA gateway Griyanet">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>

                                    <flux:subheading>{{ auth()->user()->email }}

                                        </flux:subheading>

                                </div>
                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ trans('whatsapp-gateway.label.username') }}
                                            <flux:tooltip content="Username yang diperoleh dari WA gateway Griyanet">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                    <flux:subheading>{{ auth()->user()->username }}


                                    </flux:subheading>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ trans('whatsapp-gateway.label.subscription-status') }}
                                            <flux:tooltip content="Status berlangganan">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                     <flux:badge color="emerald">
                                        Active
                                   </flux:badge>

                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ trans('whatsapp-gateway.label.subscription') }}
                                            <flux:tooltip content="Product aktif">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>

                                    <div class="flex flex-row gap-2">
                                            <flux:subheading>
                                                Starter
                                            </flux:subheading>

                                            <flux:button variant="primary" size="xs" icon="pencil" style="cursor: pointer;">
                                                Upgrade
                                            </flux:button>

                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ trans('whatsapp-gateway.label.subscription-expired') }}
                                            <flux:tooltip content="Masa berakhir langganan">
                                                <flux:badge size="sm" color="sky">?</flux:badge>
                                            </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                        <flux:subheading>{{ \Carbon\Carbon::parse(\Carbon\Carbon::now()->addDays(14))->diffForHumans() }}

                                    </flux:subheading>

                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.remaining-day') }}
                                            <flux:tooltip content="Kurang dari berapa hari pelanggan akan mendapatkan notifikasi WA">
                                                <flux:badge size="sm" color="sky">?</flux:badge>
                                            </flux:tooltip>
                                    </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:input.group>
                                            <flux:input type="text" name="remaining_day" placeholder="{{ __('whatsapp-gateway.helper.remaining-day') }}" />
                                            <flux:input.group.suffix>{{ __('whatsapp-gateway.label.day') }}

                                            </flux:input.group.suffix>

                                        </flux:input.group>
                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.schedule-time') }}
                                            <flux:tooltip content="Waktu kapan pelanggan akan menerima pesan pemberitahuan">
                                                <flux:badge size="sm" color="sky">?</flux:badge>
                                            </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:input.group>
                                            <flux:input type="text" placeholder="{{ __('whatsapp-gateway.helper.schedule-time') }}" />
                                            <flux:input.group.suffix>H</flux:input.group.suffix>

                                        </flux:input.group>


                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-boot') }}
                                            <flux:tooltip content="Pilih nomor untuk menjawab otomatis">
                                                <flux:badge size="sm" color="sky">?</flux:badge>
                                            </flux:tooltip>
                                    </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70 flex gap-2">
                                        <flux:field>
                                            <flux:select>
                                                <flux:select.option >
                                                    62xxxxxxx
                                                </flux:select.option>

                                            </flux:select>
                                        </flux:field>


                                        <flux:button size='xs' variant="primary" icon="plus-circle" style="cursor: pointer;">
                                            Add Number
                                        </flux:button>

                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.whatsapp-number-notification') }}
                                            <flux:tooltip content="Pilih nomor untuk pengiriman pesan pemberitahuan">
                                                <flux:badge size="sm" color="sky">?</flux:badge>
                                            </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:field class="flex">
                                            <flux:select>
                                                <flux:select.option>
                                                   62xxxxxxxx
                                                </flux:select.option>
                                            </flux:select>
                                        </flux:field>
                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.enable') }}
                                        <flux:tooltip content="Jika Enable, artinya WA gateway sedang digunakan">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:field variant="inline">
                                            <flux:checkbox />
                                                <flux:label>
                                                    <flux:badge color="lime">
                                                        {{ trans('whatsapp-gateway.label.whatsapp-gateway-enable') }}
                                                    </flux:badge>
                                                </flux:label>

                                        </flux:field>

                                    </div>
                                </div>

                                <div class="flex items-start max-md:flex-col mb-2">
                                    <div class="mr-10 md:pb-4 md:w-[220px]">
                                        <flux:heading>{{ __('whatsapp-gateway.label.notif-admin') }}
                                            <flux:tooltip content="Kirim pemberitahuan ke Admin">
                                            <flux:badge size="sm" color="sky">?</flux:badge>
                                        </flux:tooltip>
                                        </flux:heading>
                                    </div>
                                    <div class="text-sm text-zinc-500 dark:text-white/70">
                                        <flux:field variant="inline">
                                            <flux:checkbox/>
                                                <flux:label>
                                                    <flux:badge color="lime">{{ trans('whatsapp-gateway.label.yes') }}
                                                    </flux:badge>
                                                </flux:label>

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
