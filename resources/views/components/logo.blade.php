@props(['size' => 'text-2xl'])

<a href="{{ route('home') }}" {{ $attributes->class(['inline-flex items-center font-extrabold tracking-[0.2em] text-cream', $size]) }}>
    ROAM<span class="text-tangerine">R</span>
</a>
