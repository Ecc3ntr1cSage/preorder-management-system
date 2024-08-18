@props(['disabled' => false])

<textarea id="message" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'transition caret-indigo-600 border-2 border-zinc-400 focus:border-violet-400 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 rounded-md bg-white/20 placeholder:italic placeholder:text-indigo-400 placeholder:text-sm']) !!}>{{ $slot }}</textarea>
