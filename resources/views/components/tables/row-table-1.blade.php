<tr
    {{ $attributes->merge(['class' => 'odd:bg-white odd:dark:bg-gray-600 even:bg-gray-100 even:dark:bg-gray-700 border-b dark:border-gray-800 border-gray-300']) }}>
    {{ $slot }}
</tr>
