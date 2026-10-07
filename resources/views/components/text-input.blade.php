@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'rounded-xl border-cream/20 bg-midnight/70 text-cream placeholder-cream/35 shadow-none transition focus:border-tangerine focus:ring-tangerine disabled:opacity-50']) }}>
