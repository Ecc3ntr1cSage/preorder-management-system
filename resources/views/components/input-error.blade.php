@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'text-sm text-rose-500']) }}>{{ $message }}</p>
@enderror
