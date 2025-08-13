<div class="mt-4">
    @php
        $company = App\Models\Websystem::first();
    @endphp
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    @php
                        $company = \App\Models\Websystem::first();
                    @endphp
                    <h1 class="mb-1 font-semibold">{{ $company->title ?? env('APP_NAME') }}</h1>
                    <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">
                        {{ $company->address ?? 'Company Address (Change from setting page)' }}
                        <br>{{ $company->city ?? 'City' }} - {{ $company->postal_code ?? 'Postal Code' }}</p>
                    <p class="text-[13px] mb-2 text-[#706f6c] dark:text-[#A1A09A]">Layanan Pelanggan:
                        <br>Email: {{ $company->email ?? 'Change from setting page' }}
                        <br>Phone: {{ $company->phone ?? 'Change from setting page' }}</p>
                </div>

                <div class="bg-[#fff2f2] dark:bg-[#6b6b6b] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                    {{-- Customer Management Logo --}}
                    <x-app-logo-icon class="w-full text-[#000000] dark:text-[#FFFFFF] transition-all translate-y-0 opacity-100 max-w-none duration-750 starting:opacity-0 starting:translate-y-6" />

                    {{-- Light Mode 12 SVG --}}


                    {{-- Dark Mode 12 SVG --}}


                    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
                </div>

            </main>
        </div>
</div>
