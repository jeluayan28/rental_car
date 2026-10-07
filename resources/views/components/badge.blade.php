@props(['value'])

@php
    $value = $value instanceof \BackedEnum ? $value->value : (string) $value;
    $style = match ($value) {
        'available', 'confirmed', 'paid' => 'border-electric/50 text-electric',
        'pending', 'rented', 'admin' => 'border-sun/50 text-sun',
        'maintenance', 'cancelled', 'failed' => 'border-tangerine/50 text-tangerine',
        default => 'border-cream/30 text-cream/70', // completed, refunded, customer
    };
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 whitespace-nowrap rounded-full border px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider', $style]) }}>
    <span class="h-1.5 w-1.5 rounded-full bg-current"></span>{{ $value }}
</span>
