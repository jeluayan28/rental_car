@props(['title', 'text' => null])

{{-- Friendly empty state: a car parked on a dashed road, a headline, an optional action in the slot. --}}
<div {{ $attributes->class(['rounded-[2rem] border border-dashed border-cream/20 px-6 py-12 text-center sm:py-16']) }}>
    <svg viewBox="0 0 120 56" class="mx-auto h-14 w-auto text-cream/40" fill="none" aria-hidden="true">
        <path d="M10 48h100" stroke="currentColor" stroke-width="2" stroke-dasharray="6 6" stroke-linecap="round"/>
        <path d="M22 34l6-12c1-2 3-3 5-3h40c2 0 4 1 5 3l9 12" stroke="#FF6B35" stroke-width="3" stroke-linejoin="round" stroke-linecap="round"/>
        <path d="M16 34h88v6H16z" stroke="#FF6B35" stroke-width="3" stroke-linejoin="round"/>
        <circle cx="38" cy="42" r="6" fill="#0B1020" stroke="#F4F1DE" stroke-width="2.5"/>
        <circle cx="86" cy="42" r="6" fill="#0B1020" stroke="#F4F1DE" stroke-width="2.5"/>
    </svg>
    <p class="mt-6 text-2xl font-extrabold text-cream">{{ $title }}</p>
    @if ($text)<p class="mx-auto mt-2 max-w-md text-cream/60">{{ $text }}</p>@endif
    @if (trim($slot))<div class="mt-6 flex flex-wrap justify-center gap-3">{{ $slot }}</div>@endif
</div>
