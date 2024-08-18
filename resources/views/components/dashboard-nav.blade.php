@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-2 mx-1 my-1 text-white transition rounded-md bg-indigo-700/90'
            : 'flex items-center px-3 py-2 mx-1 my-1 text-gray-300 transition rounded-md hover:bg-indigo-700/50 hover:text-white';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
