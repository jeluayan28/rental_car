<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full border border-tangerine/60 bg-tangerine/10 px-6 py-3 text-sm font-bold text-tangerine transition hover:bg-tangerine hover:text-midnight focus:outline-none focus-visible:ring-2 focus-visible:ring-tangerine focus-visible:ring-offset-2 focus-visible:ring-offset-midnight']) }}>
    {{ $slot }}
</button>
