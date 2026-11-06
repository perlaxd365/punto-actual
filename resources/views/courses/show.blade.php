@extends('layouts.app')

@php
    $provider = $course->entity;
    $initials = mb_strtoupper(mb_substr($provider?->name ?? 'PA', 0, 2));
    $displayImage = $course->display_image;
    $pageTitle = $course->seo_title ?: $course->title;
    $pageDescription = $course->seo_description ?: ($course->excerpt ?: 'Curso recomendado por Punto Actual para mejorar el aprendizaje y la empleabilidad en el Perú.');
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Cursos', 'url' => route('courses.index')],
        ['name' => $course->title],
    ];
    $crumbSchema = collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['name'],
        'item' => $c['url'] ?? null,
    ]))->all();
    $courseSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Course',
        'name' => $course->title,
        'description' => strip_tags((string) ($course->seo_description ?: ($course->excerpt ?: $course->description))),
        'url' => route('courses.show', $course->slug),
        'inLanguage' => 'es-PE',
        'educationalLevel' => $course->level,
        'teaches' => $course->excerpt,
        'provider' => [
            '@type' => 'EducationalOrganization',
            'name' => $provider?->name ?? config('puntoactual.site.name'),
            'url' => $provider?->website ?: $course->official_url,
        ],
        'hasCourseInstance' => [
            '@type' => 'CourseInstance',
            'courseMode' => $course->modality,
            'courseWorkload' => $course->duration,
            'inLanguage' => 'es-PE',
        ],
    ], fn ($value) => ! is_null($value) && $value !== '');
@endphp

@section('title', $pageTitle.' | Cursos | Punto Actual')
@section('description', $pageDescription)
@section('og_type', 'article')
@section('og_title', $pageTitle)
@section('og_description', $pageDescription)

@push('head')
    <script type="application/ld+json">
        {!! json_encode($courseSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
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
<div class="relative overflow-hidden border-b border-black/10 bg-gradient-to-br from-brand-50 via-white to-white">
    <div class="hero-float pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-brand-500/15 blur-3xl"></div>
    <div class="hero-float pointer-events-none absolute -left-24 bottom-0 h-64 w-64 rounded-full bg-brand-600/10 blur-3xl" style="animation-delay:-8s"></div>

    <div class="container-page relative py-6 sm:py-10">
        @include('partials.breadcrumbs', ['items' => $crumbs])

        <div class="mt-4 max-w-4xl" data-reveal>
            <div class="flex flex-wrap items-center gap-2">
                <span class="badge-red">{{ $course->area }}</span>
                @if($course->is_featured)
                    <span class="badge-soft">Destacado</span>
                @endif
            </div>

            <h1 class="mt-4 text-4xl font-black leading-[1.05] tracking-tight sm:text-5xl">{{ $course->title }}</h1>

            @if($course->excerpt)
                <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">{{ $course->excerpt }}</p>
            @endif

            <dl class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-xl border border-black/10 bg-white/80 p-3 backdrop-blur">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Modalidad</dt>
                    <dd class="mt-1 text-sm font-black text-ink-950">{{ $course->modality ?: 'Consultar' }}</dd>
                </div>
                <div class="rounded-xl border border-black/10 bg-white/80 p-3 backdrop-blur">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Duración</dt>
                    <dd class="mt-1 text-sm font-black text-ink-950">{{ $course->duration ?: 'Consultar' }}</dd>
                </div>
                <div class="rounded-xl border border-black/10 bg-white/80 p-3 backdrop-blur">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Nivel</dt>
                    <dd class="mt-1 text-sm font-black text-ink-950">{{ $course->level ?: 'Consultar' }}</dd>
                </div>
                <div class="rounded-xl border border-black/10 bg-white/80 p-3 backdrop-blur">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Institución</dt>
                    <dd class="mt-1 line-clamp-1 text-sm font-black text-ink-950">{{ $provider?->name ?? 'Punto Actual' }}</dd>
                </div>
            </dl>

            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ $course->official_url }}" target="_blank" rel="noopener" class="btn-primary">Ver información oficial ↗</a>
                @if($provider)
                    <a href="{{ route('courses.provider', $provider->slug) }}" class="btn-ghost">Más cursos de {{ $provider->name }}</a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="container-page py-8">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0 space-y-6">
            <article class="card overflow-hidden" data-reveal>
                @if($displayImage)
                    <div class="relative bg-zinc-100">
                        <img src="{{ $displayImage }}" alt="{{ $course->title }}" class="aspect-[16/9] max-h-[360px] w-full {{ $course->image ? 'object-cover' : 'object-contain bg-white p-8' }}">
                    </div>
                @endif

                <div class="p-6 sm:p-8">
                    <p class="section-label">Contenido del curso</p>
                    <h2 class="mt-1 text-2xl font-black tracking-tight sm:text-3xl">Sobre el curso</h2>
                    <div class="article-body mt-4 text-zinc-700">
                        @if($course->description)
                            {!! nl2br(e($course->description)) !!}
                        @else
                            <p>{{ $course->excerpt }}</p>
                        @endif
                    </div>
                </div>
            </article>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach([
                    ['Aprovecha el formato', $course->modality ? 'La modalidad '.$course->modality.' te permite organizar tu tiempo según tu rutina.' : 'Revisa la modalidad y los horarios antes de inscribirte.'],
                    ['Verifica antes de pagar', 'Confirma costos, fechas de inicio, requisitos y formas de pago en el sitio oficial.'],
                    ['Piensa en tu salida', 'Evalúa si el curso entrega certificado y cómo se conecta con el empleo que buscas.'],
                ] as [$title, $text])
                    <div class="rounded-2xl border border-black/[0.08] bg-zinc-50 p-5" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-brand-500/10 text-sm font-black text-brand-600">{{ $loop->iteration }}</span>
                        <h3 class="mt-3 text-sm font-black text-ink-950">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-6 text-white sm:p-8" data-reveal>
                <div class="pointer-events-none absolute -right-12 -top-12 h-44 w-44 rounded-full bg-white/10 blur-2xl"></div>
                <div class="relative">
                    <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-white/70">Siguiente paso</p>
                    <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">Revisa la información oficial</h2>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-white/80">
                        Fechas, costos y requisitos se confirman directamente con {{ $provider?->name ?? 'la institución' }}.
                        Punto Actual no gestiona inscripciones ni cobros.
                    </p>
                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <a href="{{ $course->official_url }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-5 py-3 text-sm font-bold text-brand-700 transition hover:bg-brand-50">
                            Ir al sitio oficial <span class="cta-arrow">↗</span>
                        </a>
                        <a href="{{ route('courses.index') }}" class="inline-flex items-center justify-center gap-2 rounded-full border border-white/30 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                            Ver más cursos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @if($provider)
                <div class="overflow-hidden rounded-2xl border border-black/[0.08] bg-white" data-reveal>
                    <div class="bg-gradient-to-br from-brand-600 to-brand-800 p-5 text-white">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-white/70">Institución</p>
                        <div class="mt-3 flex items-center gap-3">
                            <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-white p-1.5">
                                @if($provider->logo)
                                    <img src="{{ $provider->logo }}" alt="{{ $provider->name }}" class="h-full w-full object-contain">
                                @else
                                    <span class="text-sm font-black text-brand-600">{{ $initials }}</span>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-black">{{ $provider->name }}</p>
                                <p class="text-xs text-white/70">{{ $provider->entity_type }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        @if($provider->description)
                            <p class="text-sm leading-relaxed text-zinc-600">{{ \Illuminate\Support\Str::limit($provider->description, 150) }}</p>
                        @endif
                        <div class="mt-4 grid gap-2">
                            @if($provider->website)
                                <a href="{{ $provider->website }}" target="_blank" rel="noopener" class="link-red text-sm">Sitio oficial ↗</a>
                            @endif
                            <a href="{{ route('courses.provider', $provider->slug) }}" class="link-red text-sm">Ver todos sus cursos →</a>
                        </div>
                    </div>
                </div>
            @endif

            @if($similar->isNotEmpty())
                <div class="rounded-2xl border border-black/[0.08] bg-zinc-50/80 p-4" data-reveal>
                    @include('partials.section-heading', ['title' => 'Cursos de '.$course->area, 'link' => route('courses.index', ['area' => $course->area])])
                    @foreach($similar as $item)
                        @include('partials.course-card', ['course' => $item, 'variant' => 'list', 'delay' => ($loop->index % 4) * 60])
                    @endforeach
                </div>
            @endif

            <a href="{{ route('vacancies.index') }}" class="group flex items-center justify-between rounded-2xl bg-brand-600 px-5 py-4 text-white transition hover:bg-brand-500" data-reveal>
                <span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-white/80">Empleos</span>
                    <span class="block text-lg font-black">Mejora tu perfil</span>
                </span>
                <span class="cta-arrow text-2xl">→</span>
            </a>
        </aside>
    </div>
</div>

@if($recommended->isNotEmpty())
<section class="border-t border-black/10 bg-zinc-50">
    <div class="container-page py-8" data-reveal>
        @include('partials.section-heading', ['title' => 'Otros cursos recomendados', 'link' => route('courses.index'), 'linkLabel' => 'Ver todos'])
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($recommended->take(6) as $item)
                @include('partials.course-card', ['course' => $item, 'delay' => ($loop->index % 3) * 80])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
