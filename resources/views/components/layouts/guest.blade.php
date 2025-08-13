<x-layouts.app.guest-header :title="$title ?? null">
    <flux:main container>
        {{ $slot }}
         @include('partials.guest-footer')
    </flux:main>
</x-layouts.app.guest-header>
