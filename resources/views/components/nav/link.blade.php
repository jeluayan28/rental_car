@props(['href', 'active' => false])

<a href="{{ $href }}"
   {{ $attributes->class([
       'text-sm font-medium transition-colors hover:text-sun',
       'text-sun' => $active,
       'text-cream/80' => ! $active,
   ]) }}>
    {{ $slot }}
</a>
