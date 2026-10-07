@props(['href', 'active' => false])

<a href="{{ $href }}" @if ($active) aria-current="page" @endif
   {{ $attributes->class([
       'nav-underline text-sm font-medium transition-colors hover:text-sun',
       'text-sun' => $active,
       'text-cream/80' => ! $active,
   ]) }}>
    {{ $slot }}
</a>
