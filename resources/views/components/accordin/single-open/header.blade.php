@props(['defaultOpen'])

<div x-data="{ selectedAccordionItem: '{{ $defaultOpen }}' }"
    class="w-full divide-y divide-neutral-300 overflow-hidden rounded-md border border-neutral-300 dark:divide-neutral-700 dark:border-neutral-700 bg-white dark:bg-white/10 text-zinc-800 dark:text-white">
    {{ $slot }}
</div>
