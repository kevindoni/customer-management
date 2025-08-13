
<div class="flex items-start max-md:flex-col">
    <div class="flex-1 self-stretch max-md:pt-6">
        @php
            $company = App\Models\Websystem::first();
        @endphp
        <div
            class="leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">

            <h1 class="mb-1 font-semibold">Kebijakan Privasi</h1>
            <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                {{ $company->title ?? env('APP_NAME') }} memiliki komitmen untuk menjamin privasi Anda. Kami tidak
                menjual,
                memperdagangkan atau menyewakan informasi pribadi Anda kepada pihak lain. Informasi yang kami kumpulkan
                hanya digunakan untuk memudahkan pelayanan kami kepada para pelanggan.
                {{ $company->title ?? env('APP_NAME') }} mengumpulkan informasi seperti: nama, tanggal lahir, NIK,
                alamat,
                email, password, kode pos dan nomor telepon dengan satu tujuan untuk data pelanggan dan memberitahu Anda
                tentang status layanan kami, mengirimkan informasi tagihan, mengirimkan informasi yang berkaitan dengan
                layanan internet anda.

                Dengan berlangganan layanan internet dari kami dan menggunakan aplikasi ini, kami menganggap Anda setuju
                dengan peraturan kebijakan privasi kami dan menyetujui syarat dan ketentuan yang kami buat.
            </p>
        </div>
    </div>
</div>
