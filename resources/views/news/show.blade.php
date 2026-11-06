@extends('layouts.app')

@section('title', ($news->seo_title ?: $news->title).' | Punto Actual')
@section('description', $news->seo_description ?: $news->excerpt)
@section('og_type', 'article')
@section('og_image', route('og.news', $news))
@section('og_image_width', '1200')
@section('og_image_height', '630')

@php
    $plainBody = trim(strip_tags($news->body ?? ''));
    $wordCount = $plainBody === '' ? 0 : count(preg_split('/\s+/u', $plainBody, -1, PREG_SPLIT_NO_EMPTY));
    $readingTime = max(1, (int) ceil($wordCount / 200));
    $shareUrl = route('news.show', $news->slug);
    $breadcrumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Inicio', 'item' => route('home')]];
    if ($news->category) {
        $breadcrumbs[] = ['@type' => 'ListItem', 'position' => count($breadcrumbs) + 1, 'name' => $news->category->name, 'item' => route('categories.show', $news->category)];
    }
    $breadcrumbs[] = ['@type' => 'ListItem', 'position' => count($breadcrumbs) + 1, 'name' => $news->title, 'item' => route('news.show', $news->slug)];
@endphp

@push('head')
    <meta property="article:published_time" content="{{ $news->published_at?->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $news->updated_at?->toIso8601String() }}">
    <meta property="article:section" content="{{ $news->category?->name }}">
    <meta property="article:author" content="{{ $news->author?->name ?? 'Punto Actual' }}">
    <meta name="news_keywords" content="{{ collect([$news->category?->name, $news->place_label, 'Perú'])->filter()->implode(', ') }}">
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsArticle',
            'headline' => $news->title,
            'description' => $news->seo_description ?: $news->excerpt,
            'image' => $news->image ? [$news->image] : null,
            'datePublished' => $news->published_at?->toIso8601String(),
            'dateModified' => $news->updated_at?->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => $news->author?->name ?? 'Punto Actual'],
            'publisher' => [
                '@type' => 'NewsMediaOrganization',
                'name' => config('puntoactual.site.name'),
                'logo' => ['@type' => 'ImageObject', 'url' => asset('favicon-512.png'), 'width' => 512, 'height' => 512],
            ],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('news.show', $news->slug)],
            'url' => route('news.show', $news->slug),
            'articleSection' => $news->category?->name,
            'inLanguage' => 'es-PE',
            'isAccessibleForFree' => true,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>window.PA_PAGE = { type: 'news', id: {{ $news->id }}, title: @json($news->title), category: @json($news->category?->name), city: @json($news->city), country: @json($news->country) };</script>
@endpush

@section('content')
<div class="container-page py-10 sm:py-14">
    <nav class="flex flex-wrap items-center gap-1.5 text-xs font-semibold text-zinc-500" aria-label="Navegación">
        <a href="{{ route('home') }}" class="transition hover:text-brand-700">Inicio</a>
        <span aria-hidden="true">/</span>
        @if($news->category)
            <a href="{{ route('categories.show', $news->category) }}" class="transition hover:text-brand-700">{{ $news->category->name }}</a>
            <span aria-hidden="true">/</span>
        @endif
        <span class="max-w-[18rem] truncate text-zinc-600 sm:max-w-md">{{ $news->title }}</span>
    </nav>

    <div class="mt-5 grid gap-10 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <article class="min-w-0">
            <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
                <span class="badge-red">{{ $news->category?->name ?? 'Actualidad' }}</span>
                @if($news->place_label)
                    <span class="badge-soft">{{ $news->place_label }}</span>
                @endif
                <span class="text-xs text-zinc-500">{{ $news->published_at?->format('d/m/Y H:i') }}</span>
                @if($news->author)
                    <span class="text-xs text-zinc-600">· Por {{ $news->author->name }}</span>
                @endif
                <span class="text-xs text-zinc-500">· {{ $readingTime }} min de lectura</span>
            </div>

            <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight sm:text-5xl">{{ $news->title }}</h1>

            @if($news->excerpt)
                <p class="mt-5 border-l-2 border-brand-500 pl-4 text-lg leading-relaxed text-zinc-600 sm:text-xl">{{ $news->excerpt }}</p>
            @endif

            @if($news->image)
                <figure class="mt-8">
                    <img src="{{ $news->image }}" alt="{{ $news->title }}" class="max-h-[560px] w-full rounded-3xl border border-black/10 object-cover">
                    <figcaption class="mt-2 flex flex-wrap items-center justify-between gap-2 px-1 text-xs text-zinc-500">
                        <span>{{ $news->place_label ?: $news->category?->name }}</span>
                        @if($news->source_name)
                            <span>Imagen referencial · {{ $news->source_name }}</span>
                        @endif
                    </figcaption>
                </figure>
            @else
                <figure class="mt-8">
                    <div class="flex aspect-[16/9] w-full items-center justify-center rounded-3xl border border-black/10 bg-gradient-to-br from-ink-950 via-black to-brand-800">
                        <img src="{{ asset('images/logo-punto-actual-watermark.png') }}" alt="Punto Actual" class="h-10 w-auto opacity-90">
                    </div>
                </figure>
            @endif

            <div class="mt-8 grid grid-cols-2 gap-x-5 gap-y-4 rounded-2xl border border-black/10 bg-[#faf8f6] p-5 text-sm sm:grid-cols-4">
                @foreach([
                    'Categoría' => $news->category?->name ?? 'Actualidad',
                    'Ubicación' => $news->place_label ?: 'Perú',
                    'Publicado' => $news->published_at?->format('d/m/Y') ?? '—',
                    'Lectura' => $readingTime.' min',
                ] as $label => $value)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">{{ $label }}</p>
                        <p class="mt-1 font-semibold text-ink-950">{{ $value }}</p>
                    </div>
                @endforeach
            </div>

            <div class="article-body mt-10">{!! $news->body !!}</div>

            @include('partials.ads', ['adSlot' => 'article'])

            @if($news->source_url)
                <div class="mt-10 flex flex-col gap-3 rounded-2xl border border-black/10 bg-white p-5 text-sm text-zinc-600 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Fuente de la información</p>
                        <p class="mt-1 font-semibold text-ink-950">{{ $news->source_name ?: 'Consultar fuente original' }}</p>
                    </div>
                    <a class="btn-ghost !px-4 !py-2" href="{{ $news->source_url }}" target="_blank" rel="noopener nofollow">Ver fuente original ↗</a>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-2">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Temas:</span>
                @if($news->category)
                    <a href="{{ route('categories.show', $news->category) }}" class="badge-soft transition hover:border-brand-500/40 hover:text-brand-700">{{ $news->category->name }}</a>
                @endif
                @if($news->place_label)
                    <span class="badge-soft">{{ $news->place_label }}</span>
                @endif
                <span class="badge-soft">{{ $readingTime }} min de lectura</span>
            </div>

            <div class="mt-8 flex flex-wrap items-center gap-3 border-t border-black/10 pt-6">
                <span class="text-sm font-semibold text-zinc-600">Compartir:</span>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}">Facebook</a>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="x" href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($news->title) }}">X</a>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode($news->title.' '.$shareUrl) }}">WhatsApp</a>
            </div>
        </article>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @include('partials.ads', ['adSlot' => 'sidebar'])
            <div class="card p-5">
                <p class="section-label">Ficha de la nota</p>
                <h2 class="mt-1 text-lg font-black tracking-tight">Datos de la publicación</h2>
                <dl class="mt-4 grid gap-3 text-sm">
                    @foreach([
                        'Categoría' => $news->category?->name ?? 'Actualidad',
                        'Ubicación' => $news->place_label ?: 'Perú',
                        'Publicado' => $news->published_at?->format('d/m/Y H:i') ?? '—',
                        'Actualizado' => $news->updated_at?->format('d/m/Y H:i') ?? '—',
                        'Autor' => $news->author?->name ?? 'Punto Actual',
                        'Fuente' => $news->source_name ?: 'Redacción Punto Actual',
                        'Lectura' => $readingTime.' min',
                    ] as $label => $value)
                        <div class="flex items-start justify-between gap-4 border-b border-black/5 pb-2 last:border-0 last:pb-0">
                            <dt class="text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ $label }}</dt>
                            <dd class="text-right font-semibold text-ink-950">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>

            @include('news.partials.sidebar-slider', ['items' => $recommended, 'label' => 'Recomendado', 'title' => 'Te puede interesar'])
            @include('news.partials.sidebar-slider', ['items' => $similar, 'label' => 'Sigue leyendo', 'title' => 'Noticias similares'])

            @if($vacancies->isNotEmpty())
                <div class="card min-w-0 overflow-hidden p-4">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <p class="section-label">Empleos</p>
                            <h2 class="mt-0.5 text-lg font-black tracking-tight">Convocatorias</h2>
                        </div>
                    </div>
                    <div class="mt-3 grid min-w-0 gap-2">
                        @foreach($vacancies as $job)
                            <a href="{{ route('vacancies.show', $job) }}" class="group block overflow-hidden rounded-xl border border-black/5 p-3 transition hover:border-brand-500/40 hover:bg-black/[0.02]">
                                <span class="block text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                                <h3 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h3>
                                <p class="mt-1 truncate text-xs text-zinc-500">{{ $job->entity?->name }}</p>
                                <p class="mt-1 truncate text-[11px] text-zinc-500" title="{{ $job->professional_career }}"><span class="font-semibold text-zinc-700">Carrera:</span> {{ $job->professional_career ?: 'Revisar bases' }}</p>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('vacancies.index') }}" class="mt-3 inline-block text-xs font-bold text-brand-600 transition hover:text-brand-700">Ver todas →</a>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
