<nav class="flex flex-wrap items-center gap-1.5 text-xs font-semibold text-zinc-500" aria-label="Ruta de navegación">
    @foreach($items as $crumb)
        @if(! $loop->first)
            <span aria-hidden="true" class="text-zinc-300">/</span>
        @endif
        @if(! empty($crumb['url']) && ! $loop->last)
            <a href="{{ $crumb['url'] }}" class="transition hover:text-brand-700">{{ $crumb['name'] }}</a>
        @else
            <span class="max-w-[20rem] truncate text-zinc-600">{{ $crumb['name'] }}</span>
        @endif
    @endforeach
</nav>
