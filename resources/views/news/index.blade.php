@extends('layouts.app')

@php
    $isCategory = isset($category) && $category;
    $pageTitle = $isCategory ? $category->name.' | Noticias del Perú' : 'Últimas noticias del Perú y el mundo';
    $pageDescription = $isCategory
        ? 'Últimas noticias de '.$category->name.'. Cobertura verificada de Punto Actual con contexto y fuentes.'
        : 'Últimas noticias nacionales e internacionales de política, economía, sociedad, deportes, tecnología y regiones. Actualizado al minuto.';
    $latest = $latest ?? collect();
    $categories = $categories ?? collect();
    $crumbs = [['name' => 'Inicio', 'url' => route('home')]];
    if ($isCategory) {
        $crumbs[] = ['name' => 'Noticias', 'url' => route('news.index')];
        $crumbs[] = ['name' => $category->name];
    } else {
        $crumbs[] = ['name' => 'Noticias'];
    }
    $crumbSchema = collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['name'],
        'item' => $c['url'] ?? null,
    ]))->all();
    $itemList = $news->getCollection()->values()->map(fn ($item, $i) => [
        '@type' => 'ListItem',
        'position' => (($news->currentPage() - 1) * $news->perPage()) + $i + 1,
        'url' => route('news.show', $item->slug),
        'name' => $item->title,
    ])->all();
@endphp

@section('title', $pageTitle.' | Punto Actual')
@section('description', $pageDescription)

@push('head')
    @if($news->previousPageUrl())
        <link rel="prev" href="{{ $news->previousPageUrl() }}">
    @endif
    @if($news->nextPageUrl())
        <link rel="next" href="{{ $news->nextPageUrl() }}">
    @endif
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $pageTitle,
            'description' => $pageDescription,
            'url' => url()->current(),
            'inLanguage' => 'es-PE',
            'isPartOf' => ['@type' => 'WebSite', 'name' => config('puntoactual.site.name'), 'url' => route('home')],
            'mainEntity' => ['@type' => 'ItemList', 'itemListElement' => $itemList],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbSchema,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
<div class="border-b border-black/10 bg-zinc-50">
    <div class="container-page py-5 sm:py-7">
        @include('partials.breadcrumbs', ['items' => $crumbs])
        <div class="mt-2 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="section-label">{{ $isCategory ? 'Sección' : 'Información nacional e internacional' }}</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-5xl">{{ $isCategory ? $category->name : 'Últimas noticias' }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-zinc-600 sm:text-base">{{ $pageDescription }}</p>
            </div>
            <form class="flex w-full max-w-sm gap-2 sm:w-auto" data-track="search">
                <input name="q" value="{{ request('q') }}" placeholder="Buscar noticias..." class="input min-w-0 !py-2.5">
                <button class="btn-primary shrink-0 !px-4 !py-2.5">Buscar</button>
            </form>
        </div>
        <div class="mt-4 flex gap-1.5 overflow-x-auto pb-1 text-[11px] font-bold uppercase tracking-wider [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <a href="{{ route('news.index') }}" class="whitespace-nowrap rounded-full px-3 py-1.5 {{ ! $isCategory ? 'bg-brand-600 text-white' : 'bg-white text-zinc-600 ring-1 ring-black/10 hover:text-brand-700' }}">Últimas</a>
            @foreach($categories as $item)
                <a href="{{ route('categories.show', $item) }}" class="whitespace-nowrap rounded-full px-3 py-1.5 {{ $isCategory && $category->id === $item->id ? 'bg-brand-600 text-white' : 'bg-white text-zinc-600 ring-1 ring-black/10 hover:text-brand-700' }}">{{ $item->name }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="container-page py-6">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0">
            @if($news->isEmpty())
                <div class="rounded-xl border border-black/10 bg-white p-10 text-center text-zinc-500">No encontramos noticias.</div>
            @else
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($news as $item)
                        @include('partials.news-card', ['item' => $item, 'variant' => 'grid'])
                    @endforeach
                </div>
                <div class="mt-8">{{ $news->links() }}</div>
            @endif
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            <div class="rounded-2xl border border-black/[0.08] bg-zinc-50/80 p-4">
                @include('partials.section-heading', ['title' => 'Lo último', 'link' => route('news.index')])
                @foreach($latest as $item)
                    @include('partials.news-card', ['item' => $item, 'variant' => 'list'])
                @endforeach
            </div>
            <a href="{{ route('vacancies.index') }}" class="flex items-center justify-between rounded-2xl bg-brand-600 px-5 py-4 text-white transition hover:bg-brand-500">
                <span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-white/80">Empleos</span>
                    <span class="block text-lg font-black">Ver convocatorias</span>
                </span>
                <span class="text-2xl">→</span>
            </a>
        </aside>
    </div>
</div>
@endsection
