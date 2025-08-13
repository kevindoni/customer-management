
<section class="w-full">

    <x-layouts.help>
        <div class="flex flex-col gap-6">

            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Format Standard Pesan Pembayaran Mundur
                </flux:heading>

                <div class="flex md:flex-row flex-col gap-2 mt-6">
                    <flux:text>
                        <div
                            class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            <div class="flex flex-col gap-6">
                                <div>
                                    <flux:input wire:model="input.namaPesan"
                                        :label="__('whatsapp-gateway.label.name')" type="text" disabled filled
                                        iconTrailing="lock-closed" />
                                </div>
                                <flux:field>
                                    <flux:textarea wire:model="input.pesan" rows="10" readonly
                                        :label="__('whatsapp-gateway.label.message')" type="text" />
                                </flux:field>
                                <flux:field>
                                    <flux:textarea wire:model="input.deskripsi"
                                        :label="__('whatsapp-gateway.label.description')" type="text" />
                                    <flux:error name="description" value="test" />
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

                    <flux:text>
                        <div
                            class="md:w-128 relative overflow rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            <div>
                                <x-whatsapp-preview :send_message="$input['pesan']" />
                            </div>
                        </div>
                    </flux:text>


                </div>


            </div>
        </div>
    </x-layouts.help>
</section>
