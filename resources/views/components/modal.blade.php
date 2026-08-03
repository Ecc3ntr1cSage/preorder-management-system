@props(['id', 'maxWidth'])

@php
    $id = $id ?? md5($attributes->wire('model'));

    $maxWidth = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
    ][$maxWidth ?? '2xl'];
@endphp

<div x-data="{ show: @entangle($attributes->wire('model')) }" x-on:close.stop="show = false" x-on:keydown.escape.window="show = false" x-show="show"
    id="{{ $id }}" class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 jetstream-modal sm:px-0"
    style="display: none;">
    <div x-show="show" class="fixed inset-0 transition-opacity duration-700 ease-[cubic-bezier(.32,.72,0,1)]" x-on:click="show = false"
        x-transition:enter="transition duration-700 ease-[cubic-bezier(.32,.72,0,1)]" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition duration-500 ease-[cubic-bezier(.32,.72,0,1)]"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-ink/45 backdrop-blur-sm"></div>
    </div>
    <div x-show="show"
        class="relative my-4 overflow-hidden rounded-[2rem] bg-ink/5 p-1.5 shadow-[0_32px_100px_rgba(46,26,71,.18)] ring-1 ring-ink/10 transform transition-all sm:my-6 sm:w-full {{ $maxWidth }} sm:mx-auto"
        x-trap.inert.noscroll="show" x-transition:enter="transition duration-700 ease-[cubic-bezier(.32,.72,0,1)]"
        x-transition:enter-start="opacity-0 translate-y-5 scale-[.98]"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition duration-500 ease-[cubic-bezier(.32,.72,0,1)]"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>
