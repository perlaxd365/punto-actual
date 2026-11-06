@extends('layouts.app')

@php
    $plainBody = trim(strip_tags($news->body ?? ''));
    $wordCount = $plainBody === '' ? 0 : count(preg_split('/\s+/u', $plainBody, -1, PREG_SPLIT_NO_EMPTY));
    $readingTime = max(1, (int) ceil($wordCount / 200));
    $shareUrl = route('news.show', $news->slug);
    $cityUrl = $news->city ? route('news.city', \Illuminate\Support\Str::slug($news->city)) : null;
    $crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => route('home')]];
    if ($news->category) {
        $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $news->category->name, 'item' => route('categories.show', $news->category)];
    }
    if ($cityUrl && $news->category?->slug !== 'internacional') {
        $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $news->city, 'item' => $cityUrl];
    }
    $crumbs[] = ['@type' => 'ListItem', 'position' => count($crumbs) + 1, 'name' => $news->title, 'item' => route('news.show', $news->slug)];
@endphp

@section('title', ($news->seo_title ?: $news->title).' | Punto Actual')
@section('description', $news->seo_description ?: $news->excerpt)
@section('og_type', 'article')
@section('og_image', route('og.news', $news))
@section('og_image_width', '1200')
@section('og_image_height', '630')

@push('head')
    <meta property="article:published_time" content="{{ $news->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $news->updated_at?->toIso8601String() }}">
    <meta property="article:section" content="{{ $news->category?->name }}">
    <meta property="article:author" content="{{ $news->author?->name ?? 'Punto Actual' }}">
    <meta name="news_keywords" content="{{ collect([$news->category?->name, $news->place_label, $news->city, 'Perú', 'noticias'])->filter()->unique()->implode(', ') }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'description' => $news->seo_description ?: $news->excerpt,
            'image' => $news->image ? [$news->image] : null,
            'thumbnailUrl' => $news->image,
            'datePublished' => $news->published_at?->toIso8601String(),
            'dateModified' => $news->updated_at?->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => $news->author?->name ?? 'Punto Actual'],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => config('puntoactual.site.name'),
                'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo-punto-actual.png'), 'width' => 1000, 'height' => 212],
            ],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('news.show', $news->slug)],
            'url' => route('news.show', $news->slug),
            'articleSection' => $news->category?->name,
            'keywords' => collect([$news->category?->name, $news->city, $news->country])->filter()->implode(', '),
            'wordCount' => $wordCount,
            'timeRequired' => 'PT'.$readingTime.'M',
            'inLanguage' => 'es-PE',
            'isAccessibleForFree' => true,
            'isPartOf' => ['@type' => 'WebSite', 'name' => config('puntoactual.site.name'), 'url' => route('home')],
            'about' => $news->city ? ['@type' => 'Place', 'name' => $news->city, 'address' => ['@type' => 'PostalAddress', 'addressLocality' => $news->city, 'addressCountry' => $news->country ?: 'PE']] : null,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbs,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>window.PA_PAGE = { type: 'news', id: {{ $news->id }}, title: @json($news->title), category: @json($news->category?->name), city: @json($news->city), country: @json($news->country) };</script>
@endpush

@section('content')
<div class="border-b border-black/10 bg-zinc-50">
    <div class="container-page py-3">
        @include('partials.breadcrumbs', ['items' => collect($crumbs)->map(fn ($c) => ['name' => $c['name'], 'url' => $c['item'] ?? null])->all()])
    </div>
</div>

<div class="container-page py-6 sm:py-9">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <article class="min-w-0">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                @if($news->category)
                    <a href="{{ route('categories.show', $news->category) }}" class="badge-red">{{ $news->category->name }}</a>
                @endif
                @if($news->city)
                    <a href="{{ $cityUrl }}" class="badge-soft transition hover:text-brand-700">{{ $news->city }}</a>
                @elseif($news->place_label)
                    <span class="badge-soft">{{ $news->place_label }}</span>
                @endif
                <span class="text-xs text-zinc-500">{{ $news->published_at?->format('d/m/Y H:i') }}</span>
                <span class="text-xs text-zinc-500">· {{ $readingTime }} min de lectura</span>
            </div>

            <h1 class="mt-3 text-3xl font-black leading-[1.08] tracking-tight sm:text-4xl lg:text-5xl">{{ $news->title }}</h1>

            @if($news->excerpt)
                <p class="mt-4 border-l-4 border-brand-600 pl-4 text-base leading-relaxed text-zinc-600 sm:text-xl">{{ $news->excerpt }}</p>
            @endif

            <div class="mt-4 flex flex-wrap items-center gap-3 text-xs text-zinc-500">
                <span class="flex items-center gap-2 font-semibold text-zinc-700">
                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-brand-600 text-[10px] font-black text-white">{{ strtoupper(substr($news->author?->name ?? 'PA', 0, 2)) }}</span>
                    {{ $news->author?->name ?? 'Redacción Punto Actual' }}
                </span>
                <span>·</span>
                <span>Actualizado {{ $news->updated_at?->format('d/m/Y H:i') }}</span>
            </div>

            <div class="mt-6 flex flex-wrap items-center gap-2 border-y border-black/10 py-2.5">
                <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-500">Compartir</span>
                <a class="rounded-full border border-black/10 px-3 py-1 text-xs font-bold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700" target="_blank" rel="noopener" data-track="share" data-track-network="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}">Facebook</a>
                <a class="rounded-full border border-black/10 px-3 py-1 text-xs font-bold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700" target="_blank" rel="noopener" data-track="share" data-track-network="x" href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($news->title) }}">X</a>
                <a class="rounded-full border border-black/10 px-3 py-1 text-xs font-bold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700" target="_blank" rel="noopener" data-track="share" data-track-network="whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode($news->title.' '.$shareUrl) }}">WhatsApp</a>
            </div>

            @if($news->image)
                <figure class="mt-5">
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" fetchpriority="high" width="1200" height="675" class="max-h-[560px] w-full rounded-xl border border-black/10 object-cover">
                    <figcaption class="mt-2 flex flex-wrap items-center justify-between gap-2 px-1 text-xs text-zinc-500">
                        <span>{{ $news->place_label ?: $news->category?->name }}</span>
                        @if($news->source_name)
                            <span>Imagen referencial · {{ $news->source_name }}</span>
                        @endif
                    </figcaption>
                </figure>
            @endif

            <div class="article-body mt-6">{!! $news->body !!}</div>

            @include('partials.ads', ['adSlot' => 'article'])

            @if($news->source_url)
                <div class="mt-8 flex flex-col gap-3 rounded-xl border border-black/10 bg-zinc-50 p-4 text-sm text-zinc-600 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Fuente de la información</p>
                        <p class="mt-0.5 font-semibold text-ink-950">{{ $news->source_name ?: 'Consultar fuente original' }}</p>
                    </div>
                    <a class="btn-ghost !px-4 !py-2" href="{{ $news->source_url }}" target="_blank" rel="noopener nofollow">Ver fuente original ↗</a>
                </div>
            @endif

            <div class="mt-6 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Temas:</span>
                @if($news->category)
                    <a href="{{ route('categories.show', $news->category) }}" class="badge-soft transition hover:border-brand-500/40 hover:text-brand-700">{{ $news->category->name }}</a>
                @endif
                @if($news->city)
                    <a href="{{ $cityUrl }}" class="badge-soft transition hover:border-brand-500/40 hover:text-brand-700">{{ $news->city }}</a>
                @endif
                <span class="badge-soft">{{ $readingTime }} min de lectura</span>
            </div>
        </article>

        <aside class="space-y-5 self-start lg:sticky lg:top-24">
            @include('partials.ads', ['adSlot' => 'sidebar'])
            <div class="rounded-xl border border-black/[0.08] bg-zinc-50/80 p-4">
                <p class="section-label">La nota</p>
                <h2 class="mt-1 text-base font-black tracking-tight">Datos de la publicación</h2>
                <dl class="mt-3 grid gap-2 text-sm">
                    @foreach([
                        'Categoría' => $news->category?->name ?? 'Actualidad',
                        'Ubicación' => $news->place_label ?: 'Perú',
                        'Publicado' => $news->published_at?->format('d/m/Y H:i') ?? '—',
                        'Actualizado' => $news->updated_at?->format('d/m/Y H:i') ?? '—',
                        'Autor' => $news->author?->name ?? 'Punto Actual',
                        'Fuente' => $news->source_name ?: 'Redacción Punto Actual',
                    ] as $label => $value)
                        <div class="flex items-start justify-between gap-3 border-b border-black/5 pb-1.5 last:border-0 last:pb-0">
                            <dt class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500">{{ $label }}</dt>
                            <dd class="text-right text-xs font-semibold text-ink-950">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            @include('news.partials.sidebar-slider', ['items' => $recommended, 'label' => 'Recomendado', 'title' => 'Te puede interesar'])
            @include('news.partials.sidebar-slider', ['items' => $similar, 'label' => 'Sigue leyendo', 'title' => 'Noticias similares'])

            @if($vacancies->isNotEmpty())
                <div class="rounded-xl border border-black/[0.08] bg-white p-4">
                    @include('partials.section-heading', ['title' => 'Empleos', 'link' => route('vacancies.index')])
                    <div class="mt-1 grid gap-1">
                        @foreach($vacancies as $job)
                            <a href="{{ route('vacancies.show', $job) }}" class="group block border-b border-black/5 py-2 last:border-0">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                                <h3 class="mt-0.5 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h3>
                                <p class="mt-0.5 truncate text-xs text-zinc-500">{{ $job->entity?->name }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
