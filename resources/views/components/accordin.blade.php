<div x-data="{ isExpanded: false }" class="divide-y divide-neutral-300 dark:divide-neutral-700">
    <button id="controlsAccordionItemOne" type="button"
        class="flex w-full items-center justify-between gap-4 p-2 text-xs text-left underline-offset-2 bg-white dark:bg-white/10 shadow-xs border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 rounded text-zinc-800 dark:text-white"
        aria-controls="accordionItemOne" @click="isExpanded = ! isExpanded"
        :class="isExpanded ? 'text-onSurfaceStrong dark:text-onSurfaceDarkStrong font-bold' :
            'text-onSurface dark:text-onSurfaceDark font-medium'"
        :aria-expanded="isExpanded ? 'true' : 'false'">
        {{ $slot }}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke="currentColor"
            class="size-5 shrink-0 transition" aria-hidden="true" :class="isExpanded ? 'rotate-180' : ''">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>
    <div x-cloak x-show="isExpanded" id="accordionItemOne" role="region" aria-labelledby="controlsAccordionItemOne"
        x-collapse>
        <div class="p-2 text-sm sm:text-base text-pretty">
            {{ $content }}
        </div>
    </div>
</div>
