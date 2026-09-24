@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-4 py-2 rounded-full text-base font-semibold leading-5 bg-teal-600 text-white transition duration-150 ease-in-out'
            : 'inline-flex items-center px-4 py-2 rounded-full text-base font-medium leading-5 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>