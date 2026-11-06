@extends('layouts.app')

@php
    $pageTitle = 'Noticias de '.$match;
    $pageDescription = 'Últimas noticias de '.$match.': política, economía, sociedad, seguridad y más. Cobertura actualizada de Punto Actual.';
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Noticias', 'url' => route('news.index')],
        ['name' => $match],
    ];
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
            'about' => ['@type' => 'Place', 'name' => $match, 'address' => ['@type' => 'PostalAddress', 'addressCountry' => 'PE', 'addressLocality' => $match]],
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
        <p class="mt-2 section-label">Cobertura regional</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-5xl">Noticias de {{ $match }}</h1>
        <p class="mt-2 max-w-2xl text-sm text-zinc-600 sm:text-base">{{ $pageDescription }}</p>
    </div>
</div>

<div class="container-page py-6">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0">
            @if($news->isEmpty())
                <div class="rounded-xl border border-black/10 bg-white p-10 text-center text-zinc-500">Aún no hay noticias publicadas de {{ $match }}.</div>
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
                @include('partials.section-heading', ['title' => 'Otras noticias', 'link' => route('news.index')])
                @foreach($latest as $item)
                    @include('partials.news-card', ['item' => $item, 'variant' => 'list'])
                @endforeach
            </div>
            <div class="rounded-2xl border border-black/[0.08] bg-white p-4">
                @include('partials.section-heading', ['title' => 'Ciudades'])
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach(\App\Models\News::published()->whereNotNull('city')->where('city', '!=', '')->select('city')->distinct()->orderBy('city')->pluck('city')->take(18) as $city)
                        <a href="{{ route('news.city', \Illuminate\Support\Str::slug($city)) }}" class="rounded-full border border-black/10 px-2.5 py-1 text-xs font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">{{ $city }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
