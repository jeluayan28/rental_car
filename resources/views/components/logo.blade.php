@props(['class' => ''])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => 'inline-flex items-center text-2xl font-extrabold tracking-[0.2em] text-cream']) }}>
    ROAM<span class="text-tangerine">R</span>
</a>
