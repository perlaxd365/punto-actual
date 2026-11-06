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
<div class="border-b border-black/10 bg-zinc-50">
    <div class="container-page py-5 sm:py-7">
        @include('partials.breadcrumbs', ['items' => $crumbs])
        <div class="mt-3 grid gap-6 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
            <div>
                <p class="section-label">{{ $course->area }}</p>
                <h1 class="mt-1 text-3xl font-black leading-tight tracking-tight sm:text-4xl">{{ $course->title }}</h1>
                @if($course->excerpt)
                    <p class="mt-3 max-w-3xl text-base leading-relaxed text-zinc-600">{{ $course->excerpt }}</p>
                @endif

                @if($displayImage)
                    <div class="mt-4 overflow-hidden rounded-2xl border border-black/10 bg-zinc-100">
                        <img src="{{ $displayImage }}" alt="{{ $course->title }}" class="aspect-[16/9] w-full object-cover">
                    </div>
                @endif

                <div class="mt-4 flex flex-wrap gap-2">
                    @if($course->modality)<span class="badge-soft">{{ $course->modality }}</span>@endif
                    @if($course->level)<span class="badge-soft">{{ $course->level }}</span>@endif
                    @if($course->duration)<span class="badge-soft">{{ $course->duration }}</span>@endif
                </div>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <a href="{{ $course->official_url }}" target="_blank" rel="noopener" class="btn-primary">Ver información oficial ↗</a>
                    @if($provider)
                        <a href="{{ route('courses.provider', $provider->slug) }}" class="btn-ghost">Más cursos de {{ $provider->name }}</a>
                    @endif
                </div>
            </div>

            @if($provider)
                <div class="rounded-2xl border border-black/[0.08] bg-white p-5">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-zinc-500">Institución</p>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                            @if($provider->logo)
                                <img src="{{ $provider->logo }}" alt="{{ $provider->name }}" class="h-full w-full object-cover">
                            @else
                                {{ $initials }}
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-ink-950">{{ $provider->name }}</p>
                            <p class="text-xs text-zinc-500">{{ $provider->entity_type }}</p>
                        </div>
                    </div>
                    @if($provider->website)
                        <a href="{{ $provider->website }}" target="_blank" rel="noopener" class="mt-4 inline-flex text-xs font-bold text-brand-600 hover:text-brand-700">Sitio oficial ↗</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<div class="container-page py-6">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0">
            <article class="card p-6 sm:p-8">
                <h2 class="text-2xl font-black tracking-tight">Sobre el curso</h2>
                <div class="article-body mt-4 text-zinc-700">
                    @if($course->description)
                        {!! nl2br(e($course->description)) !!}
                    @else
                        <p>{{ $course->excerpt }}</p>
                    @endif
                </div>

                <div class="mt-6 rounded-xl border border-black/[0.08] bg-zinc-50 p-5">
                    <h3 class="text-sm font-black uppercase tracking-wider text-ink-950">Antes de inscribirte</h3>
                    <ul class="mt-3 grid gap-2 text-sm text-zinc-600">
                        <li>Verifica costos, fechas de inicio y requisitos en el sitio oficial de la institución.</li>
                        <li>Confirma la modalidad (online, presencial o híbrida) y los horarios disponibles.</li>
                        <li>Revisa si el curso entrega certificado y si es reconocido por el sector al que apuntas.</li>
                    </ul>
                </div>

                <div class="mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ $course->official_url }}" target="_blank" rel="noopener" class="btn-primary">Ir al sitio oficial del curso ↗</a>
                    <a href="{{ route('courses.index') }}" class="btn-ghost">Ver más cursos</a>
                </div>
                <p class="mt-4 text-xs leading-relaxed text-zinc-500">
                    Punto Actual recomienda este curso como parte de su directorio de formación. No gestionamos
                    inscripciones ni cobros; toda la información oficial corresponde a la institución.
                </p>
            </article>
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @if($similar->isNotEmpty())
                <div class="rounded-2xl border border-black/[0.08] bg-zinc-50/80 p-4">
                    @include('partials.section-heading', ['title' => 'Cursos de '.$course->area, 'link' => route('courses.index', ['area' => $course->area])])
                    @foreach($similar as $item)
                        @include('partials.course-card', ['course' => $item, 'variant' => 'list'])
                    @endforeach
                </div>
            @endif

            <a href="{{ route('vacancies.index') }}" class="flex items-center justify-between rounded-2xl bg-brand-600 px-5 py-4 text-white transition hover:bg-brand-500">
                <span>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-white/80">Empleos</span>
                    <span class="block text-lg font-black">Mejora tu perfil</span>
                </span>
                <span class="text-2xl">→</span>
            </a>
        </aside>
    </div>
</div>

@if($recommended->isNotEmpty())
<section class="border-t border-black/10 bg-zinc-50">
    <div class="container-page py-8">
        @include('partials.section-heading', ['title' => 'Otros cursos recomendados', 'link' => route('courses.index'), 'linkLabel' => 'Ver todos'])
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($recommended->take(6) as $item)
                @include('partials.course-card', ['course' => $item])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
