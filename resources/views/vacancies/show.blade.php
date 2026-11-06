@extends('layouts.app')

@php
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Empleos', 'url' => route('vacancies.index')],
        ['name' => $vacancy->title],
    ];
    $crumbSchema = collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['name'],
        'item' => $c['url'] ?? null,
    ]))->all();
@endphp

@section('title', $vacancy->title.' | Convocatoria '.$vacancy->entity->name.' | Punto Actual')
@section('description', 'Convocatoria vigente: '.$vacancy->title.'. Entidad: '.$vacancy->entity->name.'. Régimen: '.$vacancy->employment_regime.'. Revisa requisitos, plazas y fecha límite.')
@section('og_type', 'article')
@section('og_image', route('og.vacancy', $vacancy))
@section('og_image_width', '1200')
@section('og_image_height', '630')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $vacancy->title,
            'description' => strip_tags($vacancy->description ?: $vacancy->excerpt ?? ''),
            'datePosted' => $vacancy->published_at?->toIso8601String(),
            'validThrough' => $vacancy->application_end_at?->toIso8601String(),
            'employmentType' => $vacancy->employment_type ?: $vacancy->employment_regime,
            'hiringOrganization' => array_filter([
                '@type' => 'Organization',
                'name' => $vacancy->entity->name,
                'sameAs' => $vacancy->entity->website ?: null,
                'logo' => $vacancy->entity->logo ?: null,
            ]),
            'identifier' => array_filter([
                '@type' => 'PropertyValue',
                'name' => $vacancy->entity->name,
                'value' => $vacancy->reference_code ?: $vacancy->slug,
            ]),
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'PE',
                    'addressRegion' => $vacancy->department,
                    'addressLocality' => $vacancy->province ?: $vacancy->district ?: $vacancy->department,
                    'streetAddress' => $vacancy->workplace ?: null,
                ],
            ],
            'jobLocationType' => $vacancy->work_mode && stripos($vacancy->work_mode, 'remot') !== false ? 'TELECOMMUTE' : null,
            'directApply' => false,
            'educationRequirements' => $vacancy->education_level ? ['@type' => 'EducationalOccupationalCredential', 'credentialCategory' => $vacancy->education_level] : null,
            'experienceRequirements' => ($vacancy->general_experience || $vacancy->specific_experience) ? ['@type' => 'OccupationalExperienceRequirements', 'monthsOfExperience' => null, 'description' => trim(($vacancy->general_experience ?: '').' '.($vacancy->specific_experience ?: ''))] : null,
            'baseSalary' => $vacancy->salary_min ? [
                '@type' => 'MonetaryAmount',
                'currency' => $vacancy->salary_currency,
                'value' => ['@type' => 'QuantitativeValue', 'minValue' => (float) $vacancy->salary_min, 'maxValue' => (float) ($vacancy->salary_max ?: $vacancy->salary_min), 'unitText' => 'MONTH'],
            ] : null,
            'url' => route('vacancies.show', $vacancy->slug),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $crumbSchema,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>window.PA_PAGE = { type: 'job', id: {{ $vacancy->id }}, title: @json($vacancy->title), regime: @json($vacancy->employment_regime), department: @json($vacancy->department) };</script>
@endpush

@section('content')
<div class="border-b border-black/10 bg-zinc-50">
    <div class="container-page py-3">
        @include('partials.breadcrumbs', ['items' => $crumbs])
    </div>
</div>

<div class="container-page py-6 sm:py-9">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <article class="min-w-0">
            <div class="rounded-xl border border-black/[0.08] bg-white p-5 sm:p-7">
                @if($vacancy->image)
                    <div class="mb-5 overflow-hidden rounded-lg border border-black/10 bg-zinc-100">
                        <img src="{{ $vacancy->image }}" alt="{{ $vacancy->title }}" fetchpriority="high" class="aspect-[16/7] w-full object-cover">
                    </div>
                @endif

                <div class="flex flex-col gap-4 md:flex-row md:items-start">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-brand-500/10 text-lg font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                        @if($vacancy->entity->logo)
                            <img src="{{ $vacancy->entity->logo }}" alt="{{ $vacancy->entity->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr($vacancy->entity->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-[11px] font-bold uppercase tracking-wider text-brand-600">{{ $vacancy->employment_regime }}{{ $vacancy->reference_code ? ' · '.$vacancy->reference_code : '' }}</p>
                        <h1 class="mt-1.5 text-2xl font-black leading-tight tracking-tight sm:text-4xl">{{ $vacancy->title }}</h1>
                        <a href="{{ route('entities.show', $vacancy->entity) }}" class="mt-1.5 inline-block text-sm font-semibold text-zinc-600 transition hover:text-brand-700">{{ $vacancy->entity->name }}</a>
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            <span class="badge-soft">{{ $vacancy->salary_label }}</span>
                            <span class="badge-red">{{ $vacancy->department ?: 'Perú' }}</span>
                            @if($vacancy->work_mode)<span class="badge-soft">{{ $vacancy->work_mode }}</span>@endif
                        </div>
                    </div>
                    <a href="{{ $vacancy->official_url }}" target="_blank" rel="noopener" class="btn-primary shrink-0" data-track="apply_click" data-track-id="{{ $vacancy->id }}" data-track-type="vacancy" data-track-label="{{ $vacancy->title }}">Ir a postular ↗</a>
                </div>

                <div class="mt-6 rounded-lg border border-brand-500/20 bg-brand-500/[0.06] p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-brand-600">Carrera / especialidad</p>
                    <p class="mt-1 text-sm font-semibold leading-relaxed text-ink-950">{{ $vacancy->professional_career ?: 'Revisa las carreras y especialidades requeridas en las bases oficiales de la convocatoria.' }}</p>
                </div>

                <div class="mt-5 grid gap-4 border-y border-black/10 py-5 text-sm sm:grid-cols-3">
                    @foreach([
                        'Plazas' => $vacancy->vacancies_count ?? 'No indicado',
                        'Publicado' => $vacancy->published_at?->format('d/m/Y') ?? 'No indicada',
                        'Cierre' => $vacancy->application_end_at?->format('d/m/Y H:i') ?? 'Consultar bases',
                        'Formación' => $vacancy->education_level ?: 'Consultar bases',
                        'Experiencia' => $vacancy->general_experience ?: ($vacancy->specific_experience ?: 'Consultar bases'),
                        'Modalidad' => $vacancy->work_mode ?: 'No indicada',
                    ] as $label => $value)
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-zinc-500">{{ $label }}</p>
                            <p class="mt-1 text-ink-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                @foreach(['Descripción' => 'description', 'Funciones' => 'functions', 'Requisitos' => 'requirements', 'Experiencia general' => 'general_experience', 'Experiencia específica' => 'specific_experience', 'Cursos y programas de especialización' => 'courses_training', 'Conocimientos' => 'knowledge', 'Cronograma' => 'schedule'] as $label => $field)
                    @if($vacancy->$field)
                        <section class="mt-6">
                            <h2 class="text-lg font-black text-ink-950">{{ $label }}</h2>
                            <div class="mt-2 whitespace-pre-line text-sm leading-7 text-zinc-600">{{ $vacancy->$field }}</div>
                        </section>
                    @endif
                @endforeach

                <div class="mt-6 flex flex-wrap gap-2">
                    @if($vacancy->department)
                        <a href="{{ route('vacancies.index', ['region' => $vacancy->department]) }}" class="badge-soft transition hover:border-brand-500/40 hover:text-brand-700">Más empleos en {{ $vacancy->department }}</a>
                    @endif
                    @if($vacancy->employment_regime)
                        <a href="{{ route('vacancies.index', ['regime' => $vacancy->employment_regime]) }}" class="badge-soft transition hover:border-brand-500/40 hover:text-brand-700">Régimen {{ $vacancy->employment_regime }}</a>
                    @endif
                </div>

                @if($vacancy->bases_url)
                    <a href="{{ $vacancy->bases_url }}" target="_blank" rel="noopener" class="link-red mt-6 inline-block">Descargar bases de la convocatoria</a>
                @endif

                <p class="mt-6 rounded-lg border border-brand-500/20 bg-brand-500/5 p-3.5 text-xs leading-relaxed text-brand-700">
                    Verifica requisitos, fechas y condiciones en el portal oficial de la entidad convocante. Punto Actual es un portal independiente y no recibe postulaciones para esta convocatoria.
                </p>
            </div>
        </article>

        <aside class="space-y-5 self-start lg:sticky lg:top-24">
            @include('vacancies.partials.sidebar-slider', ['items' => $recommended, 'label' => 'Recomendado', 'title' => 'Convocatorias recomendadas'])
            @include('vacancies.partials.sidebar-slider', ['items' => $similar, 'label' => 'Sigue explorando', 'title' => 'Convocatorias similares'])

            <div class="rounded-xl border border-black/[0.08] bg-zinc-50/80 p-4">
                <p class="section-label">Entidad convocante</p>
                <a href="{{ route('entities.show', $vacancy->entity) }}" class="mt-2 flex items-center gap-3 rounded-lg border border-black/5 bg-white p-3 transition hover:border-brand-500/40">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                        @if($vacancy->entity->logo)
                            <img src="{{ $vacancy->entity->logo }}" alt="{{ $vacancy->entity->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr($vacancy->entity->name, 0, 2)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-bold text-ink-950">{{ $vacancy->entity->name }}</p>
                        <p class="truncate text-xs text-zinc-500">{{ $vacancy->entity->region ?: 'Perú' }}</p>
                    </div>
                </a>
            </div>
        </aside>
    </div>
</div>
@endsection
