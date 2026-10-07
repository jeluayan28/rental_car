@props(['car', 'imgClass' => 'h-full w-full object-cover'])

@php
    // Real photos render as-is. Cars without a photo (or with the dev placeholder tile) get an on-brand
    // "studio" scene instead, tinted per category, so the site never shows an empty box.
    $url = $car->image_url;
    $real = $url && ! str_contains($url, 'placehold.co');

    $tints = [
        'Sport' => '',
        'SUV' => 'hue-rotate(181deg)',
        'Sedan' => 'hue-rotate(26deg) saturate(1.1)',
        'Luxury' => 'saturate(.12) brightness(1.15)',
        'Family' => 'hue-rotate(160deg) saturate(.8)',
        'Adventure' => 'hue-rotate(-10deg) saturate(1.25)',
    ];
    $glows = ['Sport' => '#FF6B35', 'SUV' => '#2EC4FF', 'Sedan' => '#FFC857', 'Luxury' => '#F4F1DE', 'Family' => '#2EC4FF', 'Adventure' => '#FF9F45'];
@endphp

@if ($real)
    <img src="{{ $url }}" alt="{{ $car->brand }} {{ $car->model }}" loading="lazy" class="{{ $imgClass }}">
@else
    <div class="relative flex h-full w-full items-end justify-center overflow-hidden bg-gradient-to-br from-graphite via-[#111830] to-midnight" role="img" aria-label="{{ $car->brand }} {{ $car->model }}">
        <div class="absolute left-1/2 top-1/2 h-3/4 w-3/4 -translate-x-1/2 -translate-y-1/3 rounded-full opacity-25 blur-[60px]" style="background: {{ $glows[$car->category] ?? '#FF6B35' }}"></div>
        <span class="pointer-events-none absolute inset-x-0 top-[12%] select-none truncate px-4 text-center text-[clamp(1.75rem,4.2vw,3.25rem)] font-extrabold uppercase leading-none tracking-tight text-transparent [-webkit-text-stroke:1px_rgb(244_241_222/.1)]">{{ $car->brand }}</span>
        <div class="absolute inset-x-0 bottom-0 h-[18%] border-t border-cream/10 bg-gradient-to-b from-graphite/80 to-midnight/90"></div>
        <x-landing.hero-car class="relative mb-[7%] w-[88%] transition duration-700 ease-out group-hover:scale-105" style="filter: {{ $tints[$car->category] ?? '' }}" />
    </div>
@endif
