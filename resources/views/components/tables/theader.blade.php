<thead {{ $attributes->merge(['class' => 'bg-gray-300 dark:bg-white/10'])->only('class') }}>
    <tr>
        {{ $slot }}
    </tr>
</thead>
