@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Paginación" class="flex flex-wrap items-center justify-center gap-1.5 text-sm">
        @if ($paginator->onFirstPage())
            <span class="cursor-not-allowed rounded-full border border-black/5 bg-white px-3.5 py-2 text-zinc-300">&lsaquo;</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="rounded-full border border-black/10 bg-white px-3.5 py-2 text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">&lsaquo;</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 text-zinc-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="rounded-full bg-brand-600 px-4 py-2 font-bold text-white shadow-sm shadow-brand-600/25">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="rounded-full border border-black/10 bg-white px-4 py-2 text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="rounded-full border border-black/10 bg-white px-3.5 py-2 text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">&rsaquo;</a>
        @else
            <span class="cursor-not-allowed rounded-full border border-black/5 bg-white px-3.5 py-2 text-zinc-300">&rsaquo;</span>
        @endif
    </nav>
@endif
