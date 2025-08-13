@props([
    'sortable' => null,
    'direction' => null,
])

<th
    {{ $attributes->merge(['class' => 'px-3 py-1 whitespace-no-wrap border border-gray-300 dark:border-gray-800 text-sm leading-5'])->only('class') }}>

    @unless ($sortable)
        <span class="text-left text-xs leading-4 font-medium text-gray-800 dark:text-gray-300 tracking-wider">
            {{ $slot }}
        </span>
    @else
        <button {{ $attributes->except('class') }}
            class="flex items-center space-x-1 text-left text-xs leading-4 font-medium">
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
