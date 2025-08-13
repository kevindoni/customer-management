@props(['disabled' => false])

<select data-flux-select-native {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'bg-white dark:bg-white/10 dark:disabled:bg-white/[9%] w-full pl-3 pr-10 block h-10 py-2 text-base sm:text-sm leading-none rounded-lg appearance-none shadow-xs border text-gray-600',
]) !!}>
    {{ $slot }}
</select>
