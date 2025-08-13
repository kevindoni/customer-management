<div>
    @if ($viewOrderPaidModal)
        <flux:modal class="md:w-120" wire:model="viewOrderPaidModal" :dismissible="false" @close="$js.refreshData">
            <div class="space-y-4 pt-6">
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
                        <flux:heading>Nomor Invoice</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>{{ $order->merchant_ref }}</flux:text>
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
                        <flux:heading>Kode Pembayaran</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>{{ $order->pay_code }}</flux:text>
                    </div>
                </div>
                <div class="md:flex justify-between">
                    <div class="flex justify-start">
                        <flux:heading>Waktu Pembayaran</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>{{ \Carbon\Carbon::parse($order->invoice->paid_at)->format('d F Y, H:i:s') }}</flux:text>
                    </div>
                </div>
                <div class="md:flex justify-between">
                    <div class="flex justify-start">
                        <flux:heading>Jumlah Pembayaran</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:text>@moneyIDR($order->amount)</flux:text>
                    </div>
                </div>

                <flux:separator class="mt-2 mb-2" />
                <div class="flex items-center justify-end gap-2">
                    <flux:button wire:click="$set('viewOrderPaidModal', false)" variant="ghost" size="sm">
                        Tutup
                    </flux:button>

                </div>
            </div>
        </flux:modal>
    @endif
</div>
