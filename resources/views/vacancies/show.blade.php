@extends('layouts.app')

@section('title', $vacancy->title.' | Punto Actual')
@section('description', 'Convocatoria: '.$vacancy->title.'. Entidad: '.$vacancy->entity->name.'. Régimen: '.$vacancy->employment_regime.'.')

@push('head')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'JobPosting',
            'title' => $vacancy->title,
            'description' => strip_tags($vacancy->description),
            'datePosted' => $vacancy->published_at?->toIso8601String(),
            'validThrough' => $vacancy->application_end_at?->toIso8601String(),
            'employmentType' => $vacancy->employment_type ?: $vacancy->employment_regime,
            'hiringOrganization' => ['@type' => 'Organization', 'name' => $vacancy->entity->name],
            'jobLocation' => [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressCountry' => 'PE',
                    'addressRegion' => $vacancy->department,
                    'addressLocality' => $vacancy->province ?: $vacancy->department,
                ],
            ],
            'baseSalary' => $vacancy->salary_min ? [
                '@type' => 'MonetaryAmount',
                'currency' => $vacancy->salary_currency,
                'value' => ['@type' => 'QuantitativeValue', 'minValue' => (float) $vacancy->salary_min, 'maxValue' => (float) ($vacancy->salary_max ?: $vacancy->salary_min), 'unitText' => 'MONTH'],
            ] : null,
            'url' => route('vacancies.show', $vacancy->slug),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script>window.PA_PAGE = { type: 'job', id: {{ $vacancy->id }}, title: @json($vacancy->title), regime: @json($vacancy->employment_regime), department: @json($vacancy->department) };</script>
@endpush

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('vacancies.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver a empleos</a>

    <div class="mt-5 grid gap-10 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <article class="min-w-0">
            <div class="card p-6 sm:p-9">
                @if($vacancy->image)
                    <div class="mb-6 overflow-hidden rounded-2xl border border-black/10 bg-zinc-100">
                        <img src="{{ $vacancy->image }}" alt="{{ $vacancy->title }}" class="aspect-[16/7] w-full object-cover">
                    </div>
                @endif

                <div class="flex flex-col gap-5 md:flex-row md:items-start">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-500/10 text-xl font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                        {{ strtoupper(substr($vacancy->entity->name, 0, 2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold uppercase tracking-wider text-brand-600">{{ $vacancy->employment_regime }} · {{ $vacancy->reference_code }}</p>
                        <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">{{ $vacancy->title }}</h1>
                        <a href="{{ route('entities.show', $vacancy->entity) }}" class="mt-2 inline-block font-semibold text-zinc-600 transition hover:text-brand-700">{{ $vacancy->entity->name }}</a>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="badge-soft">{{ $vacancy->salary_label }}</span>
                            <span class="badge-red">{{ $vacancy->department ?: 'Perú' }}</span>
                        </div>
                    </div>
                    <a href="{{ $vacancy->official_url }}" target="_blank" rel="noopener" class="btn-primary shrink-0" data-track="apply_click" data-track-id="{{ $vacancy->id }}" data-track-type="vacancy" data-track-label="{{ $vacancy->title }}">Ir a postular ↗</a>
                </div>

                <div class="mt-8 grid gap-5 border-y border-black/10 py-6 text-sm sm:grid-cols-3">
                    @foreach([
                        'Plazas' => $vacancy->vacancies_count ?? 'No indicado',
                        'Fecha de publicación' => $vacancy->published_at?->format('d/m/Y') ?? 'No indicada',
                        'Fecha límite' => $vacancy->application_end_at?->format('d/m/Y H:i') ?? 'Consultar bases',
                        'Formación académica' => $vacancy->education_level ?: 'Consultar bases',
                        'Carrera / especialidad' => $vacancy->professional_career ?: 'Consultar bases',
                        'Modalidad' => $vacancy->work_mode ?: 'No indicada',
                    ] as $label => $value)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-zinc-500">{{ $label }}</p>
                            <p class="mt-1 text-ink-900">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                @foreach(['Descripción' => 'description', 'Funciones' => 'functions', 'Requisitos' => 'requirements', 'Experiencia general' => 'general_experience', 'Experiencia específica' => 'specific_experience', 'Cursos y programas de especialización' => 'courses_training', 'Conocimientos' => 'knowledge', 'Cronograma' => 'schedule'] as $label => $field)
                    @if($vacancy->$field)
                        <section class="mt-8">
                            <h2 class="text-xl font-black text-ink-950">{{ $label }}</h2>
                            <div class="mt-3 whitespace-pre-line leading-7 text-zinc-600">{{ $vacancy->$field }}</div>
                        </section>
                    @endif
                @endforeach

                @if($vacancy->bases_url)
                    <a href="{{ $vacancy->bases_url }}" target="_blank" rel="noopener" class="link-red mt-8 inline-block">Descargar bases de la convocatoria</a>
                @endif

                <p class="mt-8 rounded-2xl border border-brand-500/20 bg-brand-500/5 p-4 text-sm leading-relaxed text-brand-700">
                    Verifica requisitos, fechas y condiciones en el portal oficial de la entidad convocante. Punto Actual es un portal independiente y no recibe postulaciones para esta convocatoria.
                </p>
            </div>
        </article>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @include('vacancies.partials.sidebar-slider', ['items' => $recommended, 'label' => 'Recomendado', 'title' => 'Convocatorias recomendadas'])
            @include('vacancies.partials.sidebar-slider', ['items' => $similar, 'label' => 'Sigue explorando', 'title' => 'Convocatorias similares'])

            <div class="card p-4">
                <p class="section-label">Entidad convocante</p>
                <a href="{{ route('entities.show', $vacancy->entity) }}" class="mt-3 flex items-center gap-3 rounded-xl border border-black/5 p-3 transition hover:border-brand-500/40 hover:bg-black/[0.02]">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
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
