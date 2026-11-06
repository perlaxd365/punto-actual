@extends('layouts.app')

@section('title', 'Noticias del Perú | Punto Actual')
@section('description', 'Últimas noticias nacionales de política, economía, sociedad, educación, salud, tecnología y regiones.')

@section('content')
<div class="border-b border-black/10 bg-zinc-50">
    <div class="container-page py-12 sm:py-16">
        <p class="section-label">Información nacional</p>
        <h1 class="mt-2 text-4xl font-black tracking-tight sm:text-5xl">Noticias</h1>
        <p class="mt-3 max-w-2xl text-zinc-600">Actualidad verificada del Perú, con contexto y enlaces a las fuentes originales.</p>

        <form class="mt-8 flex max-w-xl gap-2" data-track="search">
            <input name="q" value="{{ request('q') }}" placeholder="Buscar noticias..." class="input">
            <button class="btn-primary shrink-0">Buscar</button>
        </form>
    </div>
</div>

<div class="container-page py-12">
    @if($category ?? null)
        <p class="mb-6 text-sm text-zinc-600">Mostrando la categoría <span class="font-semibold text-brand-600">{{ $category->name }}</span></p>
    @endif

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($news as $item)
            <a href="{{ route('news.show', $item) }}" class="group card overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-brand-500/40">
                <div class="aspect-[16/10] overflow-hidden bg-zinc-100">
                    @if($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    @else
                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-zinc-100 text-4xl font-black text-black/10">PA</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">{{ $item->category?->name ?? 'Actualidad' }}</span>
                        @if($item->place_label)
                            <span class="badge-soft">{{ $item->place_label }}</span>
                        @endif
                    </div>
                    <h2 class="mt-2 line-clamp-3 text-lg font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h2>
                    <p class="mt-2 line-clamp-2 text-sm text-zinc-500">{{ $item->excerpt }}</p>
                    <div class="mt-4 grid gap-1.5 border-t border-black/5 pt-3 text-xs text-zinc-500">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-semibold uppercase tracking-wider text-zinc-400">Publicado</span>
                            <span class="font-semibold text-zinc-600">{{ $item->published_at?->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-semibold uppercase tracking-wider text-zinc-400">Autor</span>
                            <span class="truncate font-semibold text-zinc-600">{{ $item->author?->name ?? 'Punto Actual' }}</span>
                        </div>
                        @if($item->source_name)
                            <div class="flex items-center justify-between gap-3">
                                <span class="font-semibold uppercase tracking-wider text-zinc-400">Fuente</span>
                                <span class="truncate font-semibold text-zinc-600">{{ $item->source_name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="card p-10 text-center text-zinc-600 md:col-span-2 lg:col-span-3">No encontramos noticias.</div>
        @endforelse
    </div>

    <div class="mt-10">{{ $news->links() }}</div>
</div>
@endsection
