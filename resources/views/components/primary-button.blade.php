<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-tangerine px-6 py-3 text-sm font-bold text-midnight shadow-[0_10px_30px_-10px_rgba(255,107,53,.8)] transition hover:bg-sun focus:outline-none focus-visible:ring-2 focus-visible:ring-electric focus-visible:ring-offset-2 focus-visible:ring-offset-midnight disabled:opacity-50']) }}>
    {{ $slot }}
</button>
