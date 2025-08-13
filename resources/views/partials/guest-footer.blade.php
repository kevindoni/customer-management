<div
    class="flex flex-col gap-2 items-center justify-center w-full text-[13px] leading-[20px] flex-1 text-[#706f6c] dark:text-[#A1A09A]">
    <div class="mt-4">
        <a href="{{ route('tos') }}">Ketentuan Layanan</a> &bull; <a href="{{ route('contact') }}">Contact</a> &bull; <a
            href="{{ route('privacy') }}">Kebijakan Privasi</a>
    </div>
    <div>
        Customer Management V.{{ env('APP_VERSION') }} - &copy{{ \Carbon\Carbon::now()->format('Y') }} by
        {{ \App\Models\Websystem::first()->title ?? env('APP_NAME') }}
    </div>
</div>
