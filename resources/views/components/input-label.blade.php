@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-[11px] font-semibold uppercase tracking-widest text-cream/60']) }}>
    {{ $value ?? $slot }}
</label>
