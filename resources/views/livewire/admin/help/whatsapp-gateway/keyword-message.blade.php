<section class="w-full">

    <x-layouts.help>
        <div class="flex flex-col gap-6">

            <div class="flex flex-col gap-2">
                <flux:heading size="lg" class="underline md:underline-offset-8">
                    Keyword Pesan
                </flux:heading>

                <div class="flex flex-col gap-2 mt-6">
                    <flux:text>
                        Anda dapat menggunakan keyword berikut untuk mengubah pesan.
                        <div
                    class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
                    <flux:badge color="orange" icon="bell-alert" class="mb-2">!Perhatian</flux:badge><br>
                    Keyword tidak dapat diubah, atau pesan anda tidak akan terdekripsi.
                </div>
                    </flux:text>
                    <flux:text>
                        <div
                            class="md:w-96 relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-6">
                            Keyword yang tersedia:<br><br>
                            <flux:badge color="orange" class="mb-2">%company%</flux:badge> : {{ env('APP_NAME') }}<br>
                            <flux:badge color="orange" class="mb-2">%name%</flux:badge> : Nama Customer<br>
                            <flux:badge color="orange" class="mb-2">%customer_id%</flux:badge> : ID Customer<br>
                            <flux:badge color="orange" class="mb-2">%address%</flux:badge> : Alamat Customer<br>
                            <flux:badge color="orange" class="mb-2">%invoice_number%</flux:badge> : Nomor Invoice<br>
                            <flux:badge color="orange" class="mb-2">%transaction_id%</flux:badge> : ID Transaksi<br>
                            <flux:badge color="orange" class="mb-2">%paket%</flux:badge> : Paket Customer<br>
                            <flux:badge color="orange" class="mb-2">%periode%</flux:badge> : Periode Pembayaran<br>
                            <flux:badge color="orange" class="mb-2">%bill%</flux:badge> : Nominal Pembayaran<br>
                            <flux:badge color="orange" class="mb-2">%teller%</flux:badge> : Nama Teller Penerima<br>
                            <flux:badge color="orange" class="mb-2">%payment_time%</flux:badge> : Waktu Pembayaran<br>
                            <flux:badge color="orange" class="mb-2">%payment_methode%</flux:badge> : Metode Pembayaran<br>
                            <flux:badge color="orange" class="mb-2">%paylater%</flux:badge> : Jatuh Tempo Pembayaran Paylater<br>
                        </div>
                    </flux:text>
                </div>
            </div>
        </div>
    </x-layouts.help>
</section>
