<button
    {{ $attributes->merge([
        'class' =>
            'flex items-center justify-center h-10 px-4 rounded-md min-w-[100px] shadow-md overflow-hidden group bg-gray-800 relative hover:bg-gradient-to-r hover:from-sky-500 hover:via-indigo-500 hover:to-purple-500 text-white hover:ring-2 hover:ring-offset-2 hover:ring-indigo-500 transition-all ease-out duration-300',
        'wire:loading.attr' => 'disabled',
    ]) }}
    wire:loading.class="bg-gradient-to-r from-sky-500 via-indigo-500 to-purple-500"
    @if (isset($target)) wire:target="{{ $target }}" @endif>
    <span
        class="absolute right-0 w-8 h-32 -mt-12 transition-all duration-[850ms] transform translate-x-12 bg-white opacity-10 rotate-12 lg:group-hover:-translate-x-[400px] ease"></span>
    <span class="relative flex items-center px-2 text-sm font-semibold tracking-widest uppercase" wire:loading.remove
        @if (isset($target)) wire:target="{{ $target }}" @endif>
        {{ $slot }}
    </span>
    <span wire:loading @if (isset($target)) wire:target="{{ $target }}" @endif>
        <svg class="w-6 h-6 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2">
            </circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0a12 12 0 00-12 12h2z"></path>
        </svg>
    </span>
</button>
