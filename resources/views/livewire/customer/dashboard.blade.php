<div>

    <div class="flex flex-col gap-2">
        @forelse (auth()->user()->customer_pakets as $customerPaket)
            @if (!$customerPaket->online && !is_null($customerPaket->activation_date))
                <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                    <div class="px-4 py-4">
                        <flux:badge icon="bell-alert" color="red">
                            Koneksi terputus
                        </flux:badge>
                        <flux:text>Saat ini koneksi internet di
                            {{ $customerPaket->customer_installation_address->address }} sedang terputus. Silahkan
                            hubungi petugas kami untuk meperbaiki.
                        </flux:text>
                    </div>
                </div>
            @endif
        @empty
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                <div class="px-4 py-4">
                    <flux:badge icon="bell-alert" color="amber">
                        Informasi
                    </flux:badge>
                    <flux:text>
                        Anda belum memiliki paket internet. Silahkan hubungi petugas kami untuk mendaftar.
                    </flux:text>

                </div>
            </div>
        @endforelse


        @if (auth()->user()->invoices->where('status', '!=', 'paid'))
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                <div class="px-4 py-4" href="{{ route('customer.paymentmanagement') }}" wire:navigate
                    style="cursor: pointer;">
                    <flux:badge icon="bell-alert" color="amber">
                        Tagihan
                    </flux:badge>
                    <flux:text>
                        Anda memiliki {{ auth()->user()->invoices->where('status', '!=', 'paid')->count() }} tagihan
                        belum dibayar. Segera lakukan pembayaran untuk menghindari pemblokiran oleh system.
                    </flux:text>
                    <flux:button size='xs' variant="primary" style="cursor: pointer" icon="eye">
                        <a href="{{ route('customer.paymentmanagement') }}" wire:navigate>Lihat</a>
                    </flux:button>
                </div>
            </div>
        @endif

        @if ($notCompleteProfile)
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                <div class="px-4 py-4" href="{{ route('settings.profile') }}" wire:navigate style="cursor: pointer;">
                    <flux:badge icon="user-circle" color="amber">
                        Lengkapi Profile
                    </flux:badge>
                    <flux:text>
                        Lengkapi profile anda untuk mendapatkan pelayanan maksimal dari kami.
                    </flux:text>
                </div>
            </div>
        @endif

        @if ($notCompleteAddress)
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">
                <div class="px-4 py-4" href="{{ route('settings.address') }}" wire:navigate style="cursor: pointer;">
                    <flux:badge icon="home" color="amber">
                        Lengkapi Alamat
                    </flux:badge>
                    <flux:text>
                        Lengkapi anda anda agar kami lebih mudah menemukan rumah anda saat terjadi gangguan.
                    </flux:text>
                </div>
            </div>
        @endif

        <div class="flex flex-col gap-4">
        <flux:heading>Selamat datang kembali, {{ auth()->user()->full_name }}</flux:heading>
        <flux:heading>Saat ini kamu sedang berlangganan paket internet di bawah ini:</flux:heading>
        @forelse (auth()->user()->customer_pakets as $customerPaket)
        <div class="relative overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700 p-6 mt-2">
            <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">

            <div class="px-4 py-4">
                <div class="gap-2 flex md:flex-row flex-col">
                    Status Koneksi:
                    @if (is_null($customerPaket->activation_date))
                    <flux:badge icon="bell-alert" color="sky">Menunggu Aktivasi</flux:badge>
                    @else
                    <flux:badge icon="bell-alert" color="{{ $customerPaket->online ? 'teal' : 'red' }}">
                        {{ $customerPaket->online ? 'Terhubung' : 'Terputus' }}</flux:badge>
                    @endif

                    @php
                    $nowDate = new DateTime(\Carbon\Carbon::now()->startOfDay());
                    $deadline = new DateTime($customerPaket->expired_date);
                    $interval_day = $nowDate->diff($deadline)->format('%a');

                    @endphp
                    @if (\Carbon\Carbon::now()->lt($customerPaket->expired_date) && $interval_day > 0 && $interval_day <
                        6) <flux:badge icon="bell-alert" color="orange">
                        {{ $interval_day }} hari lagi terblokir.
                        </flux:badge>
                        @endif
                </div>

                <flux:separator class="mt-2 mb-2" />

                <div class="flex justify-between mb-2 mt-2">
                    <div class="flex justify-start">
                        <flux:heading>{{ $customerPaket->paket->name }}</flux:heading>
                    </div>
                </div>

                @if (!is_null($customerPaket->activation_date))
                <div class="flex justify-between mb-2 mt-2 md:flex-row flex-col">
                    <div class="flex justify-start">
                        <flux:heading>Masa Aktif</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:heading>
                            {{ \Carbon\Carbon::parse($customerPaket->start_date)->format('d M Y') }} -
                            {{ \Carbon\Carbon::parse($customerPaket->expired_date)->format('d M Y') }}
                        </flux:heading>
                    </div>
                </div>
                @endif
                <div class="flex justify-between mb-2 mt-2 md:flex-row flex-col">
                    <div class="flex justify-start">
                        <flux:heading>Periode Pembayaran</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:heading>{{ Str::apa($customerPaket->renewal_period) }}</flux:heading>
                    </div>
                </div>
                <div class="flex justify-between mb-2 mt-2 md:flex-row flex-col">
                    <div class="flex justify-start">
                        <flux:heading>Biaya Layanan (Belum termasuk pajak)</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:heading>@moneyIDR($customerPaket->price) </flux:heading>
                    </div>
                </div>




            </div>
        </div>
        </div>
        @empty
            <flux:header>Ups, kamu belum berlangganan internet kepada kami. Rugi lho kalo tidak menggunakan layanan internet dari kami karena banyak fitur dan promo menarik. <br>
        Untuk mulai berlangganan, kamu bisa menghubungi kami ya. Informasi lebih lanjut untuk menghubungi kami, kamu bisa lihat di halaman <a href="{{ route('contact') }}" wire:navigate>kontak</a>.</flux:header>
        @endforelse
        </div>
    </div>



</div>
