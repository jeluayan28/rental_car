@props(['tabs', 'current' => null])

{{-- Filter tabs. $tabs = [label => url]; the tab whose key equals $current is highlighted. --}}
<nav class="flex flex-wrap gap-2" aria-label="Filter">
    @foreach ($tabs as $key => $tab)
        <a href="{{ $tab['url'] }}" @if ($current === $key) aria-current="true" @endif
           class="rounded-full border px-4 py-1.5 text-sm font-semibold transition {{ $current === $key ? 'border-tangerine bg-tangerine text-midnight' : 'border-cream/20 text-cream/70 hover:border-cream/50 hover:text-cream' }}">
            {{ $tab['label'] }}@isset($tab['count']) <span class="opacity-60">{{ $tab['count'] }}</span>@endisset
        </a>
    @endforeach
</nav>
