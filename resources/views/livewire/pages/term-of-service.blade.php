<div class="flex flex-col gap-6">
    @php
        $company = App\Models\Websystem::first();
    @endphp
    <div
        class="leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">


        <h1 class="mb-1 font-semibold">Syarat dan Ketentuan</h1>
        <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
            Pelanggan dimohon untuk membaca syarat dan ketentuan yang berlaku bagi pengguna aplikasi ini.</p>
        <h1 class="mb-1 mt-4 font-semibold">Penggunaan Situs dan Pembatasan</h1>
        <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
            Situs ini beserta kontennya hanya dapat digunakan untuk keperluan pribadi yang tidak bersifat komersial.
            Dilarang keras menggunakan Situs ini atau Kontennya untuk keperluan lain, termasuk tanpa batasan,
            memodifikasi, menghilangkan, menghapus, mengirim, mempublikasikan, mendistribusikan, melakukan proxy
            caching, mengunggah, memposting, mendistribusi ulang, melakukan perizinan ulang, menjual, menggandakan,
            mempublikasikan ulang, atau menyebarkan dalam bentuk lain tanpa izin tertulis dari Grup atau pemiliknya.
            Anda tidak boleh menggunakan teknik framing untuk mendapatkan merek dagang atau logo-logo Grup atau
            menggunakan meta tag atau teks tersembunyi tanpa persetujuan tertulis terlebih dahulu. Anda tidak boleh
            membuat tautan ke Situs tanpa persetujuan tertulis sebelumnya dari kami. Dilarang keras menggunakan spider,
            robot, dan alat-alat pengumpulan atau pengambilan data sejenis lainnya.
        </p>

        <h1 class="mb-1 mt-4 font-semibold">Perilaku Penggunaan Internet</h1>
        <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
            Anda setuju untuk menggunakan layanan internet dari kami hanya untuk keperluan yang sesuai dengan ketentuan
            hukum.
            Anda dilarang menggunakan layanan internet untuk melakukan hal-hal yang bersifat melanggar hukum seperti
            melakukan penipuan
            melalui media internet, phising, hacking atau tindakan yang dapat merugikan orang lain, yang dapat
            menimbulkan tanggung jawab perdata maupun pidana sesuai hukum. Kami dapat mengungkapkan semua konten
            atau segala jenis komunikasi elektronik (termasuk nomor telepon, alamat rumah dan alamat email Anda, serta
            informasi lainnya) (1) untuk memenuhi tuntutan hukum, peraturan,
            atau pemerintah; (2) jika pengungkapan tersebut diperlukan atau sesuai untuk penggunaan internet; atau (3)
            Kami tidak bertanggung jawab atas apa yang
            anda lakukan yang berbentuk melawan hukum dengan menggunakan media layanan internet dari kami.

        </p>
        <h1 class="mb-1 mt-4 font-semibold">Pemberian Ganti Rugi</h1>
        <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
            Anda setuju untuk memberikan ganti rugi dan membebaskan {{ $company->title ?? env('APP_NAME') }} dari segala
            gugatan, tindakan, tuntutan, kerugian, atau kerusakan dari pihak ketiga, (termasuk imbalan jasa pengacara
            dan biaya-biaya lainnya) yang timbul dari atau berkaitan dengan pelanggaran Anda atas Syarat dan Ketentuan
            ini, penggunaan Anda atas Situs ini, atau pelanggaran Anda terhadap hak-hak pihak ketiga.
        </p>

        <h1 class="mb-1 mt-4 font-semibold">Detail Kepemilikan Situs</h1>
        <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
            Situs ini adalah milik:<br><br>
            {{ $company->title ?? env('APP_NAME') }}<br>
            {{ $company->address ?? 'Company Address (Change from setting page)' }}
            <br>{{ $company->city ?? 'City' }} - {{ $company->postal_code ?? 'Postal Code' }}
        </p>
        </p>
    </div>
</div>
