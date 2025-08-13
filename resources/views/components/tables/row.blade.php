<tr
    {{ $attributes->merge(['class' => 'odd:bg-gray-100 odd:dark:bg-white/10 dark:bg-gray-200 bg-white even:bg-white/10 even:dark:bg-white/20 border-b border-zinc-200 border-b-zinc-300/80 dark:border-white/10']) }}>
    {{ $slot }}
</tr>
