@php
    $variant = $variant ?? 'grid';
    $href = route('courses.show', $course);
    $provider = $course->entity;
    $initials = mb_strtoupper(mb_substr($provider?->name ?? 'PA', 0, 2));
    $image = $course->display_image;
    $isFallback = ! $course->image;
    $delay = $delay ?? 0;
@endphp

@if($variant === 'list')
    <a href="{{ $href }}" data-reveal style="--reveal-delay: {{ $delay }}ms" class="group flex items-start gap-3 border-b border-black/[0.06] py-3 last:border-0">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-brand-500/10 text-xs font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
            @if($provider?->logo)
                <img src="{{ $provider->logo }}" alt="{{ $provider->name }}" loading="lazy" class="h-full w-full object-cover">
            @else
                {{ $initials }}
            @endif
        </div>
        <div class="min-w-0">
            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $course->area }}</span>
            <h3 class="mt-0.5 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $course->title }}</h3>
            <p class="mt-1 text-[11px] text-zinc-500">
                {{ $course->modality ?: 'Formación' }}{{ $course->duration ? ' · '.$course->duration : '' }}
            </p>
        </div>
    </a>
@else
    <a href="{{ $href }}" data-reveal style="--reveal-delay: {{ $delay }}ms" class="group flex flex-col overflow-hidden rounded-xl border border-black/[0.07] bg-white transition duration-300 hover:-translate-y-0.5 hover:border-brand-500/40 hover:shadow-[0_18px_40px_-28px_rgba(0,0,0,0.5)]">
        <div class="relative aspect-[16/10] overflow-hidden bg-gradient-to-br from-brand-600 via-brand-700 to-ink-950">
            @if($image)
                <img src="{{ $image }}" alt="{{ $course->title }}" loading="lazy" class="h-full w-full transition duration-500 group-hover:scale-[1.04] {{ $isFallback ? 'object-contain bg-white p-8' : 'object-cover' }}">
            @else
                <div class="flex h-full flex-col items-center justify-center gap-2 text-white">
                    <span class="text-5xl font-black text-white/20">{{ $initials }}</span>
                    <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-white/60">{{ $course->area }}</span>
                </div>
            @endif
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-brand-700">{{ $course->area }}</span>
        </div>
        <div class="flex flex-1 flex-col p-4">
            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-[10px] font-bold uppercase tracking-wider text-zinc-500">
                @if($course->modality)<span>{{ $course->modality }}</span>@endif
                @if($course->modality && $course->duration)<span class="text-zinc-300">·</span>@endif
                @if($course->duration)<span>{{ $course->duration }}</span>@endif
            </div>
            <h3 class="mt-1.5 line-clamp-3 text-base font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $course->title }}</h3>
            @if($course->excerpt)
                <p class="mt-1.5 line-clamp-2 text-sm text-zinc-500">{{ $course->excerpt }}</p>
            @endif
            <div class="mt-auto flex items-center gap-2 border-t border-black/5 pt-3">
                <span class="flex h-7 w-7 shrink-0 items-center justify-center overflow-hidden rounded-md bg-brand-500/10 text-[10px] font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                    @if($provider?->logo)
                        <img src="{{ $provider->logo }}" alt="{{ $provider->name }}" loading="lazy" class="h-full w-full object-cover">
                    @else
                        {{ $initials }}
                    @endif
                </span>
                <span class="truncate text-xs font-semibold text-zinc-600">{{ $provider?->name ?? 'Institución por confirmar' }}</span>
                <span class="ml-auto text-brand-600 transition group-hover:translate-x-0.5">→</span>
            </div>
        </div>
    </a>
@endif
