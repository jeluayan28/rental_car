@props(['step', 'label', 'accent' => 'text-sun'])

<div class="max-w-4xl">
    <p data-reveal class="flex items-center gap-3 text-xs font-semibold uppercase tracking-[0.3em] {{ $accent }}">
        <span>{{ $step }}</span>
        <span class="h-px w-8 bg-current opacity-60"></span>
        <span>{{ $label }}</span>
    </p>
    <h2 data-reveal style="--d:.08s"
        class="mt-5 text-[clamp(2.5rem,7.2vw,5.75rem)] font-extrabold uppercase leading-[0.92] tracking-tight text-cream">
        {{ $slot }}
    </h2>
    @isset($intro)
        <p data-reveal style="--d:.16s" class="mt-5 max-w-xl text-lg text-cream/65">{{ $intro }}</p>
    @endisset
</div>
