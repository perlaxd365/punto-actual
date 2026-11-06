@php
    $href = route('news.show', $item);
    $categoryName = $item->category?->name ?? 'Actualidad';
    $place = $item->place_label;
    $date = $item->published_at?->format('d/m/Y');
@endphp

<a href="{{ $href }}" class="group relative flex h-full min-h-[320px] flex-col overflow-hidden rounded-2xl border border-black/[0.07] bg-ink-950">
    @if($item->image)
        <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="absolute inset-0 h-full w-full object-cover opacity-90 transition duration-700 group-hover:scale-[1.04]">
    @else
        <div class="absolute inset-0 bg-gradient-to-br from-ink-950 via-black to-brand-800"></div>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

    <div class="relative mt-auto p-5 sm:p-6">
        <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-wider">
            <span class="rounded bg-brand-600 px-2 py-0.5 text-white">{{ $categoryName }}</span>
            @if($place)<span class="text-white/80">{{ $place }}</span>@endif
        </div>
        <h3 class="mt-2 line-clamp-3 text-xl font-black leading-snug text-white sm:text-2xl">{{ $item->title }}</h3>
        @if($item->excerpt)
            <p class="mt-2 line-clamp-2 text-sm text-white/80">{{ $item->excerpt }}</p>
        @endif
        <div class="mt-3 flex items-center justify-between gap-3">
            <p class="text-xs font-semibold text-white/70">{{ $date }}</p>
            <span class="inline-flex items-center gap-2 text-sm font-bold text-white">Leer nota <span class="cta-arrow">→</span></span>
        </div>
    </div>
</a>
