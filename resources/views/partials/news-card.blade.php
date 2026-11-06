@php
    $variant = $variant ?? 'grid';
    $href = route('news.show', $item);
    $categoryName = $item->category?->name ?? 'Actualidad';
    $place = $item->place_label;
    $date = $item->published_at?->format('d/m/Y');
    $time = $item->published_at?->format('H:i');
@endphp

@if($variant === 'lead')
    <a href="{{ $href }}" class="group relative block overflow-hidden rounded-2xl bg-ink-950" data-track="cover_click" data-track-label="{{ $item->title }}">
        @if($item->image)
            <img src="{{ $item->image }}" alt="{{ $item->title }}" fetchpriority="high" class="aspect-[16/9] w-full object-cover opacity-90 transition duration-700 group-hover:scale-[1.03]">
        @else
            <div class="flex aspect-[16/9] w-full items-center justify-center bg-gradient-to-br from-ink-950 via-black to-brand-800 text-6xl font-black text-white/10">PA</div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/35 to-transparent"></div>
        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-7">
            <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-wider">
                <span class="rounded bg-brand-600 px-2 py-0.5 text-white">{{ $categoryName }}</span>
                @if($place)<span class="text-white/80">{{ $place }}</span>@endif
            </div>
            <h2 class="mt-2 line-clamp-3 text-2xl font-black leading-[1.1] text-white sm:text-4xl">{{ $item->title }}</h2>
            @if($item->excerpt)
                <p class="mt-2 line-clamp-2 max-w-2xl text-sm text-white/80 sm:text-base">{{ $item->excerpt }}</p>
            @endif
            <p class="mt-3 text-xs font-semibold text-white/70">{{ $date }} · {{ $time }}</p>
        </div>
    </a>
@elseif($variant === 'horizontal')
    <a href="{{ $href }}" class="group flex gap-3 rounded-xl border border-black/[0.06] bg-white p-2.5 transition duration-300 hover:-translate-y-0.5 hover:border-brand-500/40 hover:bg-brand-50/20 hover:shadow-[0_18px_40px_-32px_rgba(0,0,0,0.4)]">
        <div class="relative h-20 w-28 shrink-0 overflow-hidden rounded-lg bg-zinc-100 sm:h-24 sm:w-32">
            @if($item->image)
                <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-zinc-100 text-xl font-black text-black/10">PA</div>
            @endif
            @isset($rank)
                <span class="absolute left-1.5 top-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-brand-600 text-[10px] font-black text-white shadow-sm">{{ $rank }}</span>
            @endisset
        </div>
        <div class="min-w-0 py-0.5">
            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $categoryName }}</span>
            <h3 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
            <p class="mt-1.5 text-[11px] text-zinc-500">{{ $date }}{{ $place ? ' · '.$place : '' }}</p>
        </div>
    </a>
@elseif($variant === 'list')
    <a href="{{ $href }}" class="group flex items-start gap-3 border-b border-black/[0.06] py-3 last:border-0">
        @isset($position)
            <span class="mt-0.5 w-5 shrink-0 text-lg font-black leading-none text-brand-600/30">{{ $position }}</span>
        @endisset
        <div class="min-w-0">
            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $categoryName }}</span>
            <h3 class="mt-0.5 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
            <p class="mt-1 text-[11px] text-zinc-500">{{ $time }} · {{ $date }}</p>
        </div>
    </a>
@else
    <a href="{{ $href }}" class="group flex flex-col overflow-hidden rounded-xl border border-black/[0.07] bg-white transition duration-300 hover:-translate-y-1 hover:border-brand-500/40 hover:shadow-[0_24px_50px_-32px_rgba(0,0,0,0.45)]">
        <div class="aspect-[16/10] overflow-hidden bg-zinc-100">
            @if($item->image)
                <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]">
            @else
                <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-zinc-100 text-4xl font-black text-black/10">PA</div>
            @endif
        </div>
        <div class="flex flex-1 flex-col p-4">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $categoryName }}</span>
                @if($place)<span class="text-[10px] font-semibold text-zinc-500">· {{ $place }}</span>@endif
            </div>
            <h3 class="mt-1.5 line-clamp-3 text-base font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
            @if(($showExcerpt ?? true) && $item->excerpt)
                <p class="mt-1.5 line-clamp-2 text-sm text-zinc-500">{{ $item->excerpt }}</p>
            @endif
            <p class="mt-auto pt-3 text-[11px] font-semibold text-zinc-500">{{ $date }} · {{ $time }}</p>
        </div>
    </a>
@endif
