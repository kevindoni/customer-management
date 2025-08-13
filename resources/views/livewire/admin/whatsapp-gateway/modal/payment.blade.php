<div>
    @if ($paymentModal)
        <flux:modal class="md:w-160" wire:model="paymentModal" :dismissible="false" @close="$js.refreshData">
            <div class="space-y-6">
                @if ($currentStep == 1)
                    <div>
                        <flux:heading size="lg" class="mb-4">
                            Rincian Pembayaran
                        </flux:heading>
                    </div>

                    <form wire:submit="processOrder">
                        <div class="flex flex-col gap-2">
                            <!--Company-->
                            <div class="flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Perusahaan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $company }}</flux:text>
                                </div>
                            </div>

                            <!--Invoice Number-->
                            <div class="flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Nomor Invoice</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $invoice['invoice_number'] }}</flux:text>
                                </div>
                            </div>

                            <!--Total Amount-->
                            <div class="flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Jumlah Tagihan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>@moneyIDR($invoice['total_amount'])</flux:text>
                                </div>
                            </div>
                            <flux:select wire:model.change="input.payment_method" name="payment_method"
                                :label="__('whatsapp-gateway.label.payment-method')">
                                <flux:select.option value="">
                                    {{ trans('whatsapp-gateway.ph.select-bank') }}
                                </flux:select.option>
                                @if ($paymentMethods)
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <flux:select.option value="{{ $paymentMethod['code'] }}">
                                            {{ $paymentMethod['name'] }}
                                        </flux:select.option>
                                    @endforeach
                                @endif
                            </flux:select>

                            <flux:separator class="mt-2 mb-2" />
                            <div class="flex items-center justify-end gap-2">
                                <flux:button wire:click="closeModal" variant="ghost" style="cursor: pointer">Batal
                                </flux:button>
                                <flux:button type="submit" variant="success" style="cursor:pointer">
                                    {{ __('Bayar') }}
                                </flux:button>
                            </div>
                        </div>

                    </form>
                @endif

                <!--Waiting Payment-->
                @if ($currentStep == 2)
                    <flux:heading size="lg" class="mb-4">
                        Menunggu Pembayaran
                    </flux:heading>
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Nama Pelanggan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ $order['customer_name'] }}</flux:text>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Email Pelanggan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ $order['customer_email'] }}</flux:text>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Telepon Pelanggan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ $order['customer_phone'] }}</flux:text>
                            </div>
                        </div>

                        <flux:separator class="my-2" />

                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Referensi</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ $order['reference'] }}</flux:text>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Nomor Tagihan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>
                                    {{ $order['merchant_ref'] }}
                                </flux:text>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Metode Pembayaran</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>{{ $order['payment_name'] }}</flux:text>
                            </div>
                        </div>

                        <div class="flex justify-between">
                            <div class="flex justify-start">
                                <flux:heading>Jumlah Tagihan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>
                                    @moneyIDR($order['amount'])
                                </flux:text>
                            </div>
                        </div>


                        <flux:input value="{{ $order['pay_code'] }}" readonly copyable :label="__('Kode Bayar')"
                            class="mt-4"/>


                        <flux:separator class="mt-2 mb-2" />

                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="paymentInstructions" variant="success" style="cursor: pointer">
                                Lihat Cara Bayar
                            </flux:button>
                            <flux:button wire:click="closeModal" variant="primary" style="cursor: pointer">Tutup
                            </flux:button>
                        </div>
                    </div>
                @endif

                <!--Payment Instruction-->
                @if ($currentStep == 3)
                    <flux:heading size="lg" class="mb-4">
                        Cara Pembayaran
                    </flux:heading>
                    <!-- Instruction -->
                    <div class="flex flex-col gap-4">
                        @foreach ($instructions as $instruction)
                            <div>
                                <flux:heading>{{ $loop->index + 1 }}. {{ $instruction['title'] }}
                                </flux:heading>
                                @foreach ($instruction['steps'] as $step)
                                    <flux:text>
                                        {{ $loop->index + 1 }}. {!! $step !!}
                                    </flux:text>
                                @endforeach
                            </div>
                        @endforeach
                        <flux:separator class="mt-2 mb-2" />
                        <div class="flex items-center justify-end gap-2">
                            <flux:button wire:click="back(2)" variant="success" style="cursor: pointer">
                                Kembali
                            </flux:button>
                            <flux:button wire:click="closeModal" variant="primary" style="cursor: pointer">Tutup
                            </flux:button>
                        </div>
                    </div>
                @endif
            </div>
        </flux:modal>
        @script
            <script wire-navigate-once>
                $js('refreshData', () => {
                    $wire.dispatch('refresh-invoice-list');
                })
            </script>
        @endscript
    @endif
</div>
