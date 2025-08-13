<section class="w-full">
    <x-layouts.help>
        <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Ubah Pesan Notifikasi
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Kunjungi halaman <flux:button variant="success" icon="wa" size="xs"
                            href="{{ route('managements.whatsapp_gateway') }}" target="_blank">Whatsapp Gateway

                        </flux:button>.
                        <br>Tekan tombol
                        <flux:tooltip content="{{ trans('whatsapp-gateway.button.edit-message') }}">
                            <flux:button size="xs" variant="primary" icon="pencil" />
                        </flux:tooltip> untuk mengubah pesan.
                    </flux:text>

                    <flux:text>
                        <div
                            class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <flux:input :label="__('whatsapp-gateway.label.name')" type="text" disabled
                                        filled iconTrailing="lock-closed" />
                                </div>
                                <flux:field>
                                    <flux:textarea rows="8" :label="__('whatsapp-gateway.label.message')"
                                        type="text" />
                                </flux:field>
                                <flux:field>
                                    <flux:textarea :label="__('whatsapp-gateway.label.description')" type="text" />
                                    <flux:error name="description" />
                                </flux:field>

                                <div class="flex items-center justify-end">
                                    <flux:button variant="ghost" class="me-2" style="cursor:pointer">
                                        {{ __('device.button.cancel') }}
                                    </flux:button>
                                    <flux:button variant="primary" style="cursor:pointer">
                                        {{ __('device.button.update') }}
                                    </flux:button>
                                </div>

                            </div>
                        </div>

                    </flux:text>
                </div>



            </div>

        </div>

    </x-layouts.help>
</section>
