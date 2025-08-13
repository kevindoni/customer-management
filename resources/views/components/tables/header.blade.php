@props([
    'sortable' => null,
    'direction' => null,
])

<th
    {{ $attributes->merge(['class' => 'border-r border-zinc-200 border-b-zinc-300/80 dark:border-white/10 px-6 py-3 text-center text-xs whitespace-nowrap tracking-wider font-bold text-zinc-800 dark:text-white'])->only('class') }}>

    @unless ($sortable)
        <span class="leading-4 tracking-wider">
            {{ $slot }}
        </span>
    @else
        <button {{ $attributes->except('class') }} class="space-x-1 leading-4">
            <span>
                {{ $slot }}
            </span>
            <span>
                @if ($direction === 'asc')
                    <i class="fas fa-sort-up"></i> ↑
                @elseif ($direction === 'desc')
                    <i class="fas fa-sort-down"></i> ↓
                @else
                    <i class="text-muted fas fa-sort"></i> ↑↓
                @endif
            </span>
        </button>
    @endunless
</th>
