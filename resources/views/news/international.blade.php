@extends('layouts.app')

@section('title', 'Noticias internacionales | Punto Actual')
@section('description', 'Las noticias internacionales más importantes, seleccionadas por Punto Actual.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-zinc-50">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_88%_0%,rgba(225,29,46,0.16),transparent_65%)]"></div>
    <div class="container-page relative py-12 sm:py-16">
        <p class="section-label">Mirada global</p>
        <h1 class="mt-2 text-4xl font-black tracking-tight sm:text-5xl">Internacional</h1>
        <p class="mt-3 max-w-2xl text-zinc-600">Solo lo más importante del mundo, con contexto y enlaces a las fuentes originales.</p>
    </div>
</div>

<div class="container-page py-12">
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($news as $item)
            <a href="{{ route('news.show', $item) }}" class="group card overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-brand-500/40">
                <div class="aspect-[16/10] overflow-hidden border-b border-black/10 bg-zinc-100">
                    @if($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    @else
                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-zinc-100 text-4xl font-black text-black/10">PA</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Internacional</span>
                        @if($item->place_label)
                            <span class="badge-soft">{{ $item->place_label }}</span>
                        @endif
                    </div>
                    <h2 class="mt-2 line-clamp-3 text-lg font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h2>
                    <p class="mt-2 line-clamp-2 text-sm text-zinc-500">{{ $item->excerpt }}</p>
                    <p class="mt-4 text-xs text-zinc-600">{{ $item->published_at?->format('d/m/Y H:i') }}</p>
                </div>
            </a>
        @empty
            <div class="card p-10 text-center text-zinc-600 md:col-span-2 lg:col-span-3">Pronto publicaremos las noticias internacionales más relevantes.</div>
        @endforelse
    </div>

    <div class="mt-10">{{ $news->links() }}</div>
</div>
@endsection
