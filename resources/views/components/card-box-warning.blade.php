<div {{ $attributes->merge(['class' => 'flex items-start gap-4 rounded-lg p-6 text-orange-700 [&_button]:text-orange-700! dark:text-orange-200 dark:[&_button]:text-orange-200! bg-orange-400/20 dark:bg-orange-400/40 [&:is(button)]:hover:bg-orange-400/30 dark:[button]:hover:bg-orange-400/50'])->only('class') }}>
    <div class="flex size-12 shrink-0 items-center justify-center rounded-full sm:size-16">
        {{ $icon }}

    </div>

    <div class="pt-3 sm:pt-5">
        <flux:heading size="lg" class="font-semi-bold text-orange-700 dark:text-orange-200">
            {{ $title }}
        </flux:heading>
        <p class="mt-2 text-sm/relaxed">
            <flux:text>
                {{ $content }}
            </flux:text>
        </p>
    </div>
</div>
