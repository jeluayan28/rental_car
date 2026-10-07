@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'rounded-xl border border-electric/40 bg-electric/10 px-4 py-3 text-sm font-medium text-electric']) }} role="status">
        {{ $status }}
    </div>
@endif
