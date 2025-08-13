<div>
    @if ($createOrderModal)
        <flux:modal class="md:w-120" wire:model="createOrderModal" :dismissible="false" @close="$js.refreshData">
            @php
                $tripay = \App\Models\PaymentGateway::whereValue('tripay')->first();
            @endphp
            <div class="space-y-4">
                @if ($tripay->mode === 'development')
                    <flux:badge icon="bell-alert" color="amber">
                        Demo
                    </flux:badge>
                @endif
                <div class="{{ $currentStep != 1 ? 'hidden' : '' }}">
                    @if ($currentStep == 1)
                        <flux:heading size="lg" class="mb-4">
                            Rincian Pembayaran
                        </flux:heading>
                        <form wire:submit="processOrder">
                            <div class="flex flex-col gap-2">
                                <div class="md:flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Nama Pelanggan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $invoice->customer_paket->user->full_name }}</flux:text>
                                    </div>
                                </div>

                                <div class="md:flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Nomor Invoice</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>{{ $invoice->invoice_number }}</flux:text>
                                    </div>
                                </div>
                                <div class="md:flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Periode</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text>
                                            {{ \Carbon\Carbon::parse($invoice->start_periode)->format('d F Y') }} -
                                            {{ \Carbon\Carbon::parse($invoice->end_periode)->format('d F Y') }}
                                        </flux:text>
                                    </div>
                                </div>

                                @php
                                    $totalPaid = $invoice->payments->sum('amount');
                                    $totalRefunded = $invoice->payments->sum('refunded_amount');
                                    $netPaid = $totalPaid - $totalRefunded;
                                    $totalBill = $invoice->amount - $invoice->discount + $invoice->tax - $netPaid;
                                @endphp
                                @if ($invoice->tax > 0)
                                    <div class="md:flex justify-between">
                                        <div class="flex justify-start">
                                            <flux:heading>PPN</flux:heading>
                                        </div>
                                        <div class="flex justify-between">
                                            <flux:text>@moneyIDR($invoice->tax)</flux:text>
                                        </div>
                                    </div>
                                @endif

                                <div class="md:flex justify-between">
                                    <div class="flex justify-start">
                                        <flux:heading>Jumlah Tagihan</flux:heading>
                                    </div>
                                    <div class="flex justify-between">
                                        <flux:text> @moneyIDR($totalBill)</flux:text>
                                    </div>
                                </div>
                                <flux:field>
                                    <flux:select wire:model="input.method" label="Metode:">
                                        <flux:select.option value="">Pilih metode</flux:select.option>
                                        @foreach ($paymentChanels as $paymentChanel)
                                            <flux:select.option value="{{ $paymentChanel['code'] }}">
                                                {{ $paymentChanel['name'] }}
                                            </flux:select.option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="method" />
                                </flux:field>
                                <flux:separator class="mt-2 mb-2" />
                                <div class="flex items-center justify-end gap-2">
                                    <flux:button size='sm' wire:click="closeModal" variant="ghost"
                                        style="cursor: pointer">Batal
                                    </flux:button>
                                    <flux:button.group>
                                        @if ($invoice->order)
                                            <flux:button size="sm" wire:click="back(2)" variant="primary"
                                                style="cursor: pointer">
                                                Kembali
                                            </flux:button>
                                        @endif
                                        <flux:button size='sm' type="submit" variant="primary"
                                            style="cursor:pointer">
                                            {{ __('Bayar') }}
                                        </flux:button>
                                    </flux:button.group>

                                </div>

                            </div>
                        </form>
                    @endif
                </div>

                <div class="{{ $currentStep != 2 ? 'hidden' : '' }}">
                    @if ($currentStep == 2)
                        <flux:heading size="lg" class="mb-4">
                            Menunggu Pembayaran
                        </flux:heading>
                        <div class="flex flex-col gap-2">
                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Nama Pelanggan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $order->customer_name }}</flux:text>
                                </div>
                            </div>

                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Email Pelanggan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $order->customer_email }}</flux:text>
                                </div>
                            </div>

                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Telepon Pelanggan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $order->customer_phone }}</flux:text>
                                </div>
                            </div>

                            <flux:separator class="my-2" />

                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Referensi</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $order->reference }}</flux:text>
                                </div>
                            </div>
                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Nomor Tagihan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>
                                        {{ $order->merchant_ref }}
                                    </flux:text>
                                </div>
                            </div>

                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Metode Pembayaran</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>{{ $order->payment_name }}</flux:text>
                                </div>
                            </div>

                            <div class="md:flex justify-between">
                                <div class="flex justify-start">
                                    <flux:heading>Jumlah Tagihan</flux:heading>
                                </div>
                                <div class="flex justify-between">
                                    <flux:text>
                                        @moneyIDR($order->amount)
                                    </flux:text>
                                </div>
                            </div>

                            <flux:field>
                                <flux:label>Kode Bayar:</flux:label>
                                <flux:input wire:model="input.paymentCode" readonly>
                                    {{ $order->pay_code }}
                                </flux:input>
                            </flux:field>
                            <flux:separator class="mt-2 mb-2" />
                            <div class="flex items-center justify-end gap-2">
                                <flux:button size="sm" wire:click="closeModal" variant="ghost"
                                    style="cursor: pointer">Tutup
                                </flux:button>

                                <flux:button.group>
                                    <flux:button size="sm" wire:click='instructions' variant="primary"
                                        style="cursor: pointer">
                                        Lihat Cara Bayar
                                    </flux:button>
                                    <flux:button size="sm" wire:click="changeOrderModal('{{ $order->invoice->id }}')" variant="primary"
                                        style="cursor: pointer">Ganti
                                    </flux:button>
                                </flux:button.group>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="{{ $currentStep != 3 ? 'hidden' : '' }}">
                    @if ($currentStep == 3)
                        <flux:heading size="lg" class="mb-4">
                            Cara Pembayaran
                        </flux:heading>
                        <!-- Instruction -->
                        <div class="flex flex-col gap-4">
                            @foreach ($inctructions as $instruction)
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
                                <flux:button size='sm' wire:click="closeModal" variant="ghost"
                                    style="cursor: pointer">Tutup
                                </flux:button>

                                <flux:button size='sm' wire:click="back(2)" variant="primary"
                                    style="cursor: pointer">
                                    Kembali
                                </flux:button>


                            </div>
                        </div>
                    @endif
                </div>
                @if ($tripay->mode === 'development')
                    <flux:text>
                        <flux:badge icon="bell-alert" color="red">
                            Penting!!!
                        </flux:badge>
                    </flux:text>
                    <flux:text class="text-red-700">
                        Saat notifikasi <flux:badge icon="bell-alert" color="amber" size="sm">Demo
                        </flux:badge> terlihat, jangan melakukan pembayaran apapun!
                    </flux:text>
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
