@props(['href', 'variant' => 'ghost'])

<a href="{{ $href }}"
   {{ $attributes->class([
       'inline-flex items-center justify-center rounded-full px-5 py-2 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-electric',
       'bg-tangerine text-midnight hover:bg-sun' => $variant === 'primary',
       'text-cream hover:text-sun' => $variant === 'ghost',
   ]) }}>
    {{ $slot }}
</a>
