@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-night/80']) }}>
    {{ $value ?? $slot }}
</label>
