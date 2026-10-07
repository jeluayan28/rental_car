@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex flex-wrap items-center justify-center gap-2">
        @php
            $base = 'inline-flex h-11 min-w-[2.75rem] items-center justify-center rounded-full border px-4 text-sm font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-electric';
        @endphp

        @if ($paginator->onFirstPage())
            <span class="{{ $base }} cursor-not-allowed border-cream/10 text-cream/30" aria-disabled="true">&larr; Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $base }} border-cream/25 text-cream hover:border-tangerine hover:text-tangerine">&larr; Prev</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1 text-cream/40">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="{{ $base }} border-tangerine bg-tangerine text-midnight" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="{{ $base }} border-cream/25 text-cream hover:border-tangerine hover:text-tangerine" aria-label="Go to page {{ $page }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $base }} border-cream/25 text-cream hover:border-tangerine hover:text-tangerine">Next &rarr;</a>
        @else
            <span class="{{ $base }} cursor-not-allowed border-cream/10 text-cream/30" aria-disabled="true">Next &rarr;</span>
        @endif
    </nav>
@endif
