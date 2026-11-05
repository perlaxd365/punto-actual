@extends('layouts.app')

@section('title', ($news->seo_title ?: $news->title).' | Punto Actual')
@section('description', $news->seo_description ?: $news->excerpt)
@section('og_type', 'article')
@section('og_image', $news->image ?: (config('puntoactual.site.default_image') ?: asset('favicon.ico')))

@push('head')
    <meta property="article:published_time" content="{{ $news->published_at?->toIso8601String() }}">
    <meta property="article:section" content="{{ $news->category?->name }}">
    <meta property="article:author" content="{{ $news->author?->name ?? 'Punto Actual' }}">
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
            'publisher' => ['@type' => 'Organization', 'name' => config('puntoactual.site.name')],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('news.show', $news->slug)],
            'articleSection' => $news->category?->name,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>window.PA_PAGE = { type: 'news', id: {{ $news->id }}, title: @json($news->title), category: @json($news->category?->name), city: @json($news->city), country: @json($news->country) };</script>
@endpush

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('news.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver a noticias</a>

    <div class="mt-5 grid gap-10 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <article class="min-w-0">
            <div class="flex flex-wrap items-center gap-3">
                <span class="badge-red">{{ $news->category?->name ?? 'Actualidad' }}</span>
                @if($news->place_label)
                    <span class="badge-soft">{{ $news->place_label }}</span>
                @endif
                <span class="text-xs text-zinc-500">{{ $news->published_at?->format('d/m/Y H:i') }}</span>
                @if($news->author)
                    <span class="text-xs text-zinc-600">· {{ $news->author->name }}</span>
                @endif
            </div>

            <h1 class="mt-4 text-3xl font-black leading-tight tracking-tight sm:text-5xl">{{ $news->title }}</h1>

            @if($news->excerpt)
                <p class="mt-5 text-lg leading-relaxed text-zinc-600">{{ $news->excerpt }}</p>
            @endif

            @if($news->image)
                <img src="{{ $news->image }}" alt="{{ $news->title }}" class="mt-8 max-h-[560px] w-full rounded-3xl border border-black/10 object-cover">
            @endif

            <div class="article-body mt-10">{!! $news->body !!}</div>

            @if($news->source_url)
                <p class="mt-10 rounded-2xl border border-black/10 bg-white p-5 text-sm text-zinc-600">
                    Fuente: <a class="link-red" href="{{ $news->source_url }}" target="_blank" rel="noopener nofollow">{{ $news->source_name ?: 'Consultar fuente' }}</a>
                </p>
            @endif

            <div class="mt-10 flex flex-wrap items-center gap-3 border-t border-black/10 pt-6">
                <span class="text-sm font-semibold text-zinc-600">Compartir:</span>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('news.show', $news->slug)) }}">Facebook</a>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="x" href="https://twitter.com/intent/tweet?url={{ urlencode(route('news.show', $news->slug)) }}&text={{ urlencode($news->title) }}">X</a>
                <a class="btn-ghost !px-4 !py-2" target="_blank" rel="noopener" data-track="share" data-track-network="whatsapp" href="https://api.whatsapp.com/send?text={{ urlencode($news->title.' '.route('news.show', $news->slug)) }}">WhatsApp</a>
            </div>
        </article>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @include('news.partials.sidebar-slider', ['items' => $recommended, 'label' => 'Recomendado', 'title' => 'Te puede interesar'])
            @include('news.partials.sidebar-slider', ['items' => $similar, 'label' => 'Sigue leyendo', 'title' => 'Noticias similares'])

            @if($vacancies->isNotEmpty())
                <div class="card p-4">
                    <div class="flex items-center justify-between gap-2">
                        <div>
                            <p class="section-label">Empleos</p>
                            <h2 class="mt-0.5 text-lg font-black tracking-tight">Convocatorias</h2>
                        </div>
                    </div>
                    <div class="mt-3 grid gap-2">
                        @foreach($vacancies as $job)
                            <a href="{{ route('vacancies.show', $job) }}" class="group rounded-xl border border-black/5 p-3 transition hover:border-brand-500/40 hover:bg-black/[0.02]">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                                <h3 class="mt-1 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h3>
                                <p class="mt-1 truncate text-xs text-zinc-500">{{ $job->entity?->name }}</p>
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
