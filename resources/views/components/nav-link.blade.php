@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-md'
            : 'px-3 py-2 text-sm font-medium text-gray-300 rounded-md hover:bg-gray-700 hover:text-white';
@endphp

<a wire:navigate {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
