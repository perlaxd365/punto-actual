@extends('layouts.app')

@section('title', 'Punto Actual | Últimas noticias del Perú, empleos y cursos')
@section('description', 'Noticias actuales del Perú y el mundo: política, economía, deportes, espectáculos, tecnología, regiones, convocatorias de empleo verificadas y cursos recomendados para mejorar el aprendizaje y la empleabilidad en el Perú.')

@php
    $pool = $coverNews->isNotEmpty() ? $coverNews : $featured->merge($latest);
    $lead = $pool->first();
    $heroSide = $pool->skip(1)->take(4);
    $topStories = $featured->merge($latest)->unique('id');
    if ($lead) {
        $topStories = $topStories->reject(fn ($item) => $item->id === $lead->id);
    }
    $topStories = $topStories->take(6);
    $ticker = $latest->take(6);
    $homeUrl = route('home');
    $itemList = $topStories->take(10)->values()->map(fn ($item, $index) => [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'url' => route('news.show', $item->slug),
        'name' => $item->title,
    ]);
@endphp

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => 'Punto Actual: últimas noticias del Perú y el mundo',
            'url' => $homeUrl,
            'inLanguage' => 'es-PE',
            'isPartOf' => ['@type' => 'WebSite', 'url' => $homeUrl, 'name' => config('puntoactual.site.name')],
            'mainEntity' => ['@type' => 'ItemList', 'itemListElement' => $itemList],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
<h1 class="sr-only">Últimas noticias del Perú y el mundo | Punto Actual</h1>
{{-- Ticker de última hora --}}
@if($ticker->isNotEmpty())
<div class="border-b border-black/10 bg-brand-600 text-white">
    <div class="container-page flex items-center gap-3 py-2">
        <span class="flex shrink-0 items-center gap-1.5 rounded bg-white/15 px-2.5 py-1 text-[10px] font-black uppercase tracking-wider">
            <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span> Última hora
        </span>
        <div class="ticker-viewport min-w-0 flex-1" data-ticker>
            <div class="ticker-stack">
                @foreach($ticker as $item)
                    <a href="{{ route('news.show', $item) }}" data-ticker-item class="ticker-item text-[13px] font-semibold transition hover:text-white/80 {{ $loop->first ? 'is-active' : '' }}">{{ $item->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif

<div class="container-page relative overflow-hidden py-6 lg:py-8">
    <div class="hero-float pointer-events-none absolute -right-16 top-4 h-56 w-56 rounded-full bg-brand-500/10 blur-3xl"></div>
    <div class="hero-float pointer-events-none absolute -left-20 bottom-0 h-64 w-64 rounded-full bg-brand-600/10 blur-3xl" style="animation-delay:-7s"></div>

    {{-- Hero: titular + últimas --}}
    <div class="relative grid gap-6 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <div class="min-w-0" data-reveal>
            @if($lead)
                @include('partials.news-card', ['item' => $lead, 'variant' => 'lead'])
                @if($heroSide->isNotEmpty())
                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach($heroSide as $item)
                            @include('partials.news-card', ['item' => $item, 'variant' => 'horizontal'])
                        @endforeach
                    </div>
                @endif
            @else
                <div class="flex aspect-[16/9] items-center justify-center rounded-2xl bg-gradient-to-br from-ink-950 via-black to-brand-800 text-white">
                    <div class="p-8 text-center">
                        <p class="text-xs font-bold uppercase tracking-[0.3em] text-brand-300">Punto Actual</p>
                        <p class="mt-3 text-3xl font-black">Lo que pasa. Lo que importa.</p>
                    </div>
                </div>
            @endif
        </div>

        <aside class="min-w-0" data-reveal style="--reveal-delay:140ms">
            <div class="rounded-2xl border border-black/[0.08] bg-zinc-50/80 p-4">
                @include('partials.section-heading', ['title' => 'Lo último', 'link' => route('news.index'), 'linkLabel' => 'Ver más'])
                <div class="mt-1">
                    @foreach($latest->take(6) as $item)
                        @include('partials.news-card', ['item' => $item, 'variant' => 'list'])
                    @endforeach
                </div>
            </div>
            <a href="{{ route('vacancies.index') }}" class="mt-4 flex items-center justify-between rounded-2xl bg-brand-600 px-5 py-4 text-white transition hover:bg-brand-500">
                <span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-white/80">Empleos</span>
                    <span class="block text-lg font-black">Convocatorias vigentes</span>
                </span>
                <span class="text-2xl">→</span>
            </a>
        </aside>
    </div>
</div>

@include('partials.ads', ['adSlot' => 'home'])

{{-- Actualidad --}}
@if($topStories->isNotEmpty())
<div class="container-page py-6" data-reveal>
    @include('partials.section-heading', ['title' => 'Actualidad', 'link' => route('news.index'), 'linkLabel' => 'Ver todas'])
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($topStories->take(6) as $item)
            @include('partials.news-card', ['item' => $item, 'variant' => 'grid'])
        @endforeach
    </div>
</div>
@endif

{{-- Secciones por categoría (estilo RPP) --}}
@foreach($sections as $section)
    <section class="border-t border-black/10">
        <div class="container-page py-6" data-reveal>
            @include('partials.section-heading', [
                'title' => $section->category->name,
                'link' => route('categories.show', $section->category),
                'linkLabel' => 'Ver sección',
            ])
            @php($first = $section->items->first())
            @php($rest = $section->items->skip(1)->take(3))
            <div class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
                <div class="min-w-0">
                    @include('partials.news-card', ['item' => $first, 'variant' => 'horizontal'])
                </div>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                    @foreach($rest as $item)
                        @include('partials.news-card', ['item' => $item, 'variant' => 'horizontal'])
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endforeach

{{-- Convocatorias --}}
<section class="border-t border-black/10 bg-zinc-50">
    <div class="container-page py-8" data-reveal>
        @include('partials.section-heading', ['title' => 'Empleos y convocatorias', 'link' => route('vacancies.index'), 'linkLabel' => 'Ver todas'])
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($vacancies as $job)
                <a href="{{ route('vacancies.show', $job) }}" data-reveal style="--reveal-delay: {{ $loop->index * 70 }}ms" class="group flex flex-col rounded-xl border border-black/[0.07] bg-white p-4 transition duration-300 hover:-translate-y-0.5 hover:border-brand-500/40">
                    <div class="flex min-w-0 items-start gap-3">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                            @if($job->entity?->logo)
                                <img src="{{ $job->entity->logo }}" alt="{{ $job->entity->name }}" loading="lazy" class="h-full w-full object-cover">
                            @else
                                {{ strtoupper(substr($job->entity?->name ?? 'PA', 0, 2)) }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                            <h3 class="mt-0.5 line-clamp-2 text-sm font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h3>
                            <p class="mt-1 truncate text-xs text-zinc-500">{{ $job->entity?->name }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        <span class="badge-soft !px-2 !py-0.5 !text-[10px]">{{ $job->salary_label }}</span>
                        <span class="badge-red !px-2 !py-0.5 !text-[10px]">{{ $job->department ?: 'Perú' }}</span>
                    </div>
                    <div class="mt-auto flex items-center justify-between gap-2 border-t border-black/5 pt-2.5 text-[11px] text-zinc-500">
                        <span>{{ $job->vacancies_count ?? '—' }} plaza(s)</span>
                        <span>Cierra: {{ $job->application_end_at?->format('d/m/Y') ?? 'Ver bases' }}</span>
                    </div>
                </a>
            @empty
                <div class="rounded-xl border border-black/10 bg-white p-8 text-center text-zinc-500 md:col-span-2 lg:col-span-3">No hay convocatorias publicadas en este momento.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Cursos recomendados --}}
@if($courses->isNotEmpty())
<section class="border-t border-black/10">
    <div class="container-page py-8" data-reveal>
        @include('partials.section-heading', ['title' => 'Cursos recomendados', 'link' => route('courses.index'), 'linkLabel' => 'Ver todos'])
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <p class="max-w-2xl text-sm leading-relaxed text-zinc-600">
                Recomendamos cursos para mejorar el aprendizaje en el Perú y cerrar brechas de habilidades.
                Explora formación en Excel, inglés, programación, marketing digital, oficios y más.
            </p>
            <a href="{{ route('courses.index') }}" class="btn-ghost !px-4 !py-2">Explorar directorio de cursos</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($courses as $course)
                @include('partials.course-card', ['course' => $course, 'delay' => ($loop->index % 3) * 80])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Ciudades y temas --}}
<section id="regiones" class="container-page py-8" data-reveal>
    <div class="grid gap-6 lg:grid-cols-2">
        <div>
            <p class="section-label">Noticias por ciudad</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight">Encuentra lo que pasa en tu región</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($cities->take(18) as $city)
                    <a href="{{ route('news.city', \Illuminate\Support\Str::slug($city)) }}" class="rounded-full border border-black/10 bg-black/[0.02] px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:bg-brand-500/10 hover:text-brand-700">{{ $city }}</a>
                @endforeach
                <a href="{{ route('news.index') }}" class="rounded-full bg-brand-600 px-3 py-1.5 text-sm font-bold text-white transition hover:bg-brand-500">Ver todas</a>
            </div>
        </div>
        <div>
            <p class="section-label">Explora por tema</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight">Secciones y categorías</h2>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category) }}" class="rounded-full border border-black/10 bg-black/[0.02] px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:bg-brand-500/10 hover:text-brand-700">{{ $category->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="border-t border-black/10 bg-zinc-50">
    <div class="container-page grid gap-6 py-8 lg:grid-cols-[minmax(0,1.5fr)_minmax(0,1fr)]" data-reveal>
        <div>
            <h2 class="text-xl font-black tracking-tight sm:text-2xl">Noticias actuales del Perú y el mundo</h2>
            <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                Punto Actual reúne las últimas noticias del Perú y el mundo: política, economía, sociedad, deportes,
                espectáculos, tecnología y cobertura regional. Además publicamos convocatorias de empleo verificadas
                (CAS, 728, 276, Servicio Civil y prácticas) con enlaces a las bases oficiales. También recomendamos
                cursos y programas de formación para mejorar el aprendizaje en el Perú y ampliar las oportunidades
                laborales. Sigue la actualidad de
                @foreach($cities->take(6) as $city)
                    <a href="{{ route('news.city', \Illuminate\Support\Str::slug($city)) }}" class="font-semibold text-brand-600 hover:text-brand-700">{{ $city }}</a>{{ ! $loop->last ? ', ' : '.' }}
                @endforeach
                Información actualizada al minuto por nuestra redacción.
            </p>
        </div>
        <div class="flex flex-wrap content-start gap-2">
            <a href="{{ route('news.index') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Últimas noticias</a>
            <a href="{{ route('categories.show', 'politica') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Política</a>
            <a href="{{ route('categories.show', 'economia') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Economía</a>
            <a href="{{ route('categories.show', 'deportes') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Deportes</a>
            <a href="{{ route('news.international') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Internacional</a>
            <a href="{{ route('vacancies.index') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Empleos</a>
            <a href="{{ route('courses.index') }}" class="rounded-full border border-black/10 bg-white px-3 py-1.5 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">Cursos</a>
        </div>
    </div>
</section>

<section class="container-page pb-12 pt-8" data-reveal>
    <div class="relative overflow-hidden rounded-2xl border border-brand-500/20 bg-gradient-to-br from-brand-50 via-white to-white p-7 sm:p-10">
        <div class="pointer-events-none absolute -right-16 -top-16 h-56 w-56 rounded-full bg-brand-600/20 blur-3xl"></div>
        <div class="relative max-w-2xl">
            <p class="section-label">Comunidad</p>
            <h2 class="mt-1 text-2xl font-black tracking-tight sm:text-3xl">Síguenos y no te pierdas nada</h2>
            <p class="mt-2 text-zinc-600">Noticias y convocatorias apenas se publiquen.</p>
            <div class="mt-5 flex flex-wrap items-center gap-3">
                <a href="{{ route('news.index') }}" class="btn-primary">Ver noticias</a>
                <div class="rounded-xl border border-black/10 bg-white px-3 py-1.5">@include('partials.social')</div>
            </div>
        </div>
    </div>
</section>
@endsection
