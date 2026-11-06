<div class="mb-4 flex items-end justify-between gap-4 border-b border-black/10 pb-2">
    <div class="flex items-center gap-2.5">
        <span class="h-5 w-1.5 rounded-full bg-brand-600"></span>
        <h2 class="text-xl font-black tracking-tight text-ink-950 sm:text-2xl">{{ $title }}</h2>
    </div>
    @if(! empty($link))
        <a href="{{ $link }}" class="shrink-0 text-[11px] font-bold uppercase tracking-wider text-brand-600 transition hover:text-brand-700">{{ $linkLabel ?? 'Ver todo' }} →</a>
    @endif
</div>
