@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm my-1']) }}>
    {{ $value ?? $slot }}
</label>
