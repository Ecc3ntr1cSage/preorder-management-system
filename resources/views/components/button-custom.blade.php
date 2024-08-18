<button
    {{ $attributes->merge([
        'class' =>
            'relative flex items-center justify-center p-4 px-5 py-2 overflow-hidden font-medium text-indigo-600 rounded-md shadow-2xl min-w-[120px] group',
        'wire:loading.attr' => 'disabled',
    ]) }}>
    <span
        class="absolute top-0 left-0 w-40 h-40 -mt-10 -ml-3 transition-all duration-700 bg-indigo-500 rounded-full blur-md ease"></span>
    <span class="absolute inset-0 w-full h-full transition duration-700 group-hover:rotate-180 ease">
        <span class="absolute bottom-0 left-0 w-24 h-24 -ml-10 rounded-full bg-sky-500 blur-md"></span>
        <span class="absolute bottom-0 right-0 w-24 h-24 -mr-10 bg-purple-500 rounded-full blur-md"></span>
    </span>
    <span class="relative font-semibold tracking-widest text-white uppercase">{{ $slot }}</span>
</button>
