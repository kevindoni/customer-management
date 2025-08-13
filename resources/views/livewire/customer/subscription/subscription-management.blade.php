<div class=" md:w-120">
    <div class="flex flex-col gap-4">
        @forelse ($customerPakets as $customerPaket)
        <div class="rounded-xl border bg-white dark:bg-stone-950 dark:border-stone-800 text-stone-800 shadow-xs">

            <div class="px-4 py-4">
                <div class="gap-2 flex md:flex-row flex-col">
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
                        <flux:heading>Biaya Layanan (Belum termasuk pajak)</flux:heading>
                    </div>
                    <div class="flex justify-between">
                        <flux:heading>@moneyIDR($customerPaket->price) </flux:heading>
                    </div>
                </div>




            </div>
        </div>
        @empty
        <flux:header>Ups, kamu belum berlangganan internet kepada kami. Rugi lho kalo tidak menggunakan layanan internet dari kami karena banyak fitur dan promo menarik. <br>
        Untuk mulai berlangganan, kamu bisa menghubungi kami ya. Informasi lebih lanjut untuk menghubungi kami, kamu bisa lihat di halaman <a href="{{ route('contact') }}" wire:navigate>kontak</a>.</flux:header>
        @endforelse
    </div>
    @if ($customerPakets->hasPages())
    <div class="p-3">
        {{ $customerPakets->links() }}
    </div>
    @endif

</div>
