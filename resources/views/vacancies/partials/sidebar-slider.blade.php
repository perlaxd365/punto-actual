@if($items->isNotEmpty())
    <div class="card p-4" data-slider data-interval="5000">
        <div class="flex items-start justify-between gap-2">
            <div>
                <p class="section-label">{{ $label }}</p>
                <h2 class="mt-0.5 text-lg font-black tracking-tight">{{ $title }}</h2>
            </div>
            @if($items->count() > 1)
                <div class="flex shrink-0 gap-1">
                    <button type="button" data-slider-prev aria-label="Anterior" class="flex h-8 w-8 items-center justify-center rounded-full border border-black/10 text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" data-slider-next aria-label="Siguiente" class="flex h-8 w-8 items-center justify-center rounded-full border border-black/10 text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif
        </div>

        <div class="mt-3 overflow-hidden rounded-xl">
            <div class="slider-track flex transition-transform duration-500 ease-out">
                @foreach($items as $item)
                    <div class="slider-slide w-full shrink-0">
                        <a href="{{ route('vacancies.show', $item) }}" class="group block">
                            <div class="aspect-[16/10] overflow-hidden rounded-xl border border-black/10 bg-zinc-100">
                                @if($item->image)
                                    <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                                @else
                                    <div class="flex h-full items-center justify-center text-3xl font-black text-black/10">PA</div>
                                @endif
                            </div>
                            <span class="mt-3 block text-[11px] font-bold uppercase tracking-wider text-brand-600">{{ $item->employment_regime }}</span>
                            <h3 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
                            <p class="mt-1 truncate text-xs text-zinc-500">{{ $item->entity?->name }}</p>
                            <div class="mt-2 flex flex-wrap gap-1.5">
                                <span class="rounded-full bg-black/[0.04] px-2 py-0.5 text-[10px] font-semibold text-ink-700">{{ $item->salary_label }}</span>
                                <span class="rounded-full bg-brand-50 px-2 py-0.5 text-[10px] font-semibold text-brand-700">{{ $item->department ?: 'Perú' }}</span>
                            </div>
                            <p class="mt-2 text-xs text-zinc-500">Cierre: {{ $item->application_end_at?->format('d/m/Y') ?? 'Ver bases' }}</p>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        @if($items->count() > 1)
            <div class="mt-3 flex justify-center gap-1.5">
                @foreach($items as $index => $item)
                    <button type="button" data-slider-dot aria-label="Recomendación {{ $index + 1 }}" class="slider-dot {{ $index === 0 ? 'is-active' : '' }}"></button>
                @endforeach
            </div>
        @endif
    </div>
@endif
