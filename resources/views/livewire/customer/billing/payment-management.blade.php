<div class=" md:w-120">
    <div class="flex flex-col gap-4">
        @forelse ($invoices as $invoice)
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">

                <div class="px-4 py-4">
                    @if ($invoice->order)
                        <flux:badge icon="bell-alert" color="{{ $invoice->order->status == 'paid' ? 'teal' : 'amber' }}">
                            {{ $invoice->order->status == 'paid' ? $invoice->order->status : 'Kadaluarsa pada ' . \Carbon\Carbon::parse($invoice->order->expired_time)->format('d M Y H:i') }}
                        </flux:badge>
                        <flux:separator class="mt-2 mb-2" />
                    @endif


                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between md:flex-row flex-col">
                            <div class="flex justify-start">
                                <flux:heading>{{ $invoice->customer_paket->paket->name }}</flux:heading>
                            </div>
                        </div>
                        <flux:separator/>
                        <div class="flex justify-between md:flex-row flex-col">
                            <div class="flex justify-start">
                                <flux:heading>Periode</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                <flux:text>
                                {{ \Carbon\Carbon::parse($invoice->start_periode)->format('d M Y') }} - {{ \Carbon\Carbon::parse($invoice->end_periode)->format('d M Y') }}
                                </flux:text>
                            </div>
                        </div>
                        <div class="flex justify-between md:flex-row flex-col">
                            <div class="flex justify-start">
                                <flux:heading>Total Tagihan</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                @php
                                    $totalInvoice = $invoice->amount - $invoice->discount + $invoice->tax;
                                @endphp
                                <flux:text>@moneyIDR($totalInvoice)</flux:text>
                            </div>
                        </div>
                        <div class="flex justify-between md:flex-row flex-col">
                            <div class="flex justify-start">
                                <flux:heading>Total Harus Bayar</flux:heading>
                            </div>
                            <div class="flex justify-between">
                                @php
                                    $totalPaid = $invoice->payments->sum('amount');
                                    $totalRefunded = $invoice->payments->sum('refunded_amount');
                                    $netPaid = $totalPaid - $totalRefunded;
                                    $paid = $invoice->amount - $netPaid;
                                @endphp
                                <flux:heading>@moneyIDR($paid)</flux:heading>
                            </div>
                        </div>
                    </div>

                    <flux:separator class="mt-2 mb-2" />
                    <div class="flex items-center justify-end gap-2">
                        @if ($invoice->status != 'paid')
                            @if ($activePaymentGateway->count())
                                <flux:button size='sm'
                                    wire:click="$dispatch('show-create-order-modal', {invoice: '{{ $invoice->id }}'})"
                                    variant="primary" style="cursor: pointer">Bayar
                                </flux:button>
                            @else
                                <flux:text>Saat ini belum tersedia pembayaran online, namun anda masih bisa melakukan
                                    pembayaran melalui direct transfer atau datang ke outlet terdekat kami.</flux:text>
                            @endif
                        @else
                            @if ($invoice->order)
                                <flux:button size='sm'
                                    wire:click="$dispatch('show-detail-order-paid-modal', {order: '{{ $invoice->order->id }}'})"
                                    variant="primary" style="cursor: pointer">Lihat Detail Pembayaran
                                </flux:button>
                            @endif
                        @endif

                    </div>

                </div>
            </div>
        @empty
        @endforelse
    </div>
    @if ($invoices->hasPages())
        <div class="p-3">
            {{ $invoices->links() }}
        </div>
    @endif
    <livewire:customer.billing.modal.create-order />
    <livewire:customer.billing.modal.view-detail-order-paid />
</div>
