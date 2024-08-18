@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'caret-indigo-600 border-2 border-zinc-400 focus:border-violet-500 focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500 rounded-md bg-white/20 placeholder:italic placeholder:text-gray-400 disabled:border-none disabled:bg-transparent transition']) !!}>
