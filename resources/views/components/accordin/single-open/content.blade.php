@props(['name'])

<div class="divide-y divide-neutral-300 dark:divide-neutral-700">
    <button id="controlsAccordionItem{{ $name }}" type="button"
        class="flex w-full items-center justify-between gap-4 p-4 text-left underline-offset-2 bg-white dark:bg-white/10 hover:bg-neutral-50/75 focus-visible:bg-neutral-50/75 focus-visible:underline focus-visible:outline-none dark:hover:bg-neutral-900/75 dark:focus-visible:bg-neutral-900/75"
        aria-controls="accordionItem{{ $name }}" @click="selectedAccordionItem = '{{ $name }}'"
        :class="selectedAccordionItem === '{{ $name }}' ?
            'text-onSurfaceStrong dark:text-onSurfaceDarkStrong font-bold' :
            'text-onSurface dark:text-onSurfaceDark font-medium'"
        :aria-expanded="selectedAccordionItem === '{{ $name }}' ? 'true' : 'false'">
        {{ $title }}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor"
            class="size-5 shrink-0 transition" aria-hidden="true"
            :class="selectedAccordionItem === '{{ $name }}' ? 'rotate-180' : ''">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <div x-cloak x-show="selectedAccordionItem === '{{ $name }}'" id="accordionItem{{ $name }}"
        role="region" aria-labelledby="controlsAccordionItem{{ $name }}" x-collapse>
        <div class="p-4 text-sm sm:text-base text-pretty">
            {{ $content }}
        </div>
    </div>
</div>
