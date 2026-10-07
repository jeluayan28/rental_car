@props(['status'])

@php
    $style = match ($status->value) {
        'pending' => 'border-sun/50 text-sun',
        'confirmed' => 'border-electric/50 text-electric',
        'completed' => 'border-cream/30 text-cream/70',
        'cancelled' => 'border-tangerine/50 text-tangerine',
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[11px] font-bold uppercase tracking-widest', $style]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $status->value }}
</span>
