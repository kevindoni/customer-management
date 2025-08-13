@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'p-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-gray-500 text-xs sm:text-sm dark:text-gray-300 focus:border-gray-400 dark:focus:border-gray-800 focus:ring-gray-400 dark:focus:ring-gray-800 rounded-md shadow-sm',
]) !!}>
