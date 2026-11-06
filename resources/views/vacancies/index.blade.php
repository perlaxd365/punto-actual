@extends('layouts.app')

@php
    $pageTitle = 'Convocatorias y empleos en Perú';
    $pageDescription = 'Busca convocatorias CAS, 728, 276, Servicio Civil y prácticas en todo el Perú. Filtra por región y régimen laboral.';
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Empleos'],
    ];
    $crumbSchema = collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['name'],
        'item' => $c['url'] ?? null,
    ]))->all();
    $itemList = $vacancies->getCollection()->values()->map(fn ($job, $i) => [
        '@type' => 'ListItem',
        'position' => (($vacancies->currentPage() - 1) * $vacancies->perPage()) + $i + 1,
        'url' => route('vacancies.show', $job->slug),
        'name' => $job->title,
    ])->all();
@endphp

@section('title', $pageTitle.' | Punto Actual')
@section('description', $pageDescription)

@push('head')
    @if($vacancies->previousPageUrl())
        <link rel="prev" href="{{ $vacancies->previousPageUrl() }}">
    @endif
    @if($vacancies->nextPageUrl())
        <link rel="next" href="{{ $vacancies->nextPageUrl() }}">
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
                <p class="section-label">Oportunidades en todo el país</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-5xl">Empleos y convocatorias</h1>
                <p class="mt-2 max-w-2xl text-sm text-zinc-600 sm:text-base">Oportunidades públicas y privadas. Revisa siempre las bases oficiales antes de postular.</p>
            </div>
        </div>

        <form class="mt-5 grid gap-2 rounded-xl border border-black/[0.08] bg-white p-3 md:grid-cols-[1.6fr_1fr_1fr_auto]" data-track="search">
            <input name="q" value="{{ request('q') }}" placeholder="Cargo, carrera o especialidad" class="input min-w-0 !py-2.5">
            <select name="region" class="input min-w-0 !py-2.5">
                <option value="">Todas las regiones</option>
                @foreach($regions as $region)
                    <option @selected(request('region') === $region)>{{ $region }}</option>
                @endforeach
            </select>
            <select name="regime" class="input min-w-0 !py-2.5">
                <option value="">Todos los regímenes</option>
                @foreach(['CAS', '728', '276', 'Servicio Civil', 'Prácticas', 'Privado'] as $regime)
                    <option @selected(request('regime') === $regime)>{{ $regime }}</option>
                @endforeach
            </select>
            <button class="btn-primary min-w-0 !px-5 !py-2.5">Filtrar</button>
        </form>
    </div>
</div>

<div class="container-page py-6">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0">
            @if($vacancies->isEmpty())
                <div class="rounded-xl border border-black/10 bg-white p-10 text-center text-zinc-500">No hay convocatorias que coincidan con tu búsqueda.</div>
            @else
                <div class="grid gap-3">
                    @foreach($vacancies as $job)
                        <a href="{{ route('vacancies.show', $job) }}" class="group flex gap-4 rounded-xl border border-black/[0.07] bg-white p-4 transition hover:border-brand-500/40 hover:shadow-[0_16px_36px_-30px_rgba(0,0,0,0.5)]">
                            <div class="hidden h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15 sm:flex">
                                @if($job->entity?->logo)
                                    <img src="{{ $job->entity->logo }}" alt="{{ $job->entity->name }}" loading="lazy" class="h-full w-full object-cover">
                                @else
                                    {{ strtoupper(substr($job->entity?->name ?? 'PA', 0, 2)) }}
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                                    <span class="text-[10px] font-semibold text-zinc-400">·</span>
                                    <span class="truncate text-[11px] font-semibold text-zinc-600">{{ $job->entity?->name }}</span>
                                </div>
                                <h2 class="mt-1 line-clamp-2 text-base font-bold leading-snug text-ink-950 transition group-hover:text-brand-600 sm:text-lg">{{ $job->title }}</h2>
                                <p class="mt-1 line-clamp-2 text-xs leading-relaxed text-zinc-500">
                                    <span class="font-semibold text-zinc-700">Carrera:</span> {{ $job->professional_career ?: 'Revisar carreras y especialidades en las bases oficiales.' }}
                                </p>
                                <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                    <span class="badge-soft !px-2 !py-0.5 !text-[10px]">{{ $job->department ?: 'Perú' }}</span>
                                    <span class="badge-soft !px-2 !py-0.5 !text-[10px]">{{ $job->salary_label }}</span>
                                    <span class="badge-red !px-2 !py-0.5 !text-[10px]">{{ $job->vacancies_count ?? '—' }} plaza(s)</span>
                                </div>
                            </div>
                            <div class="hidden shrink-0 flex-col items-end justify-between text-right sm:flex">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Cierre</span>
                                <span class="text-xs font-bold text-ink-950">{{ $job->application_end_at?->format('d/m/Y') ?? 'Ver bases' }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">{{ $vacancies->links() }}</div>
            @endif
        </div>

        <aside class="space-y-5 self-start lg:sticky lg:top-24">
            <div class="rounded-xl border border-black/[0.08] bg-zinc-50/80 p-4">
                @include('partials.section-heading', ['title' => 'Por región'])
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach($regions as $region)
                        <a href="{{ route('vacancies.index', ['region' => $region]) }}" class="rounded-full border border-black/10 bg-white px-2.5 py-1 text-xs font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">{{ $region }}</a>
                    @endforeach
                </div>
            </div>
            <div class="rounded-xl border border-black/[0.08] bg-white p-4">
                @include('partials.section-heading', ['title' => 'Por régimen'])
                <div class="mt-2 flex flex-wrap gap-1.5">
                    @foreach(['CAS', '728', '276', 'Servicio Civil', 'Prácticas', 'Privado'] as $regime)
                        <a href="{{ route('vacancies.index', ['regime' => $regime]) }}" class="rounded-full border border-black/10 px-2.5 py-1 text-xs font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:text-brand-700">{{ $regime }}</a>
                    @endforeach
                </div>
            </div>
            <div class="rounded-xl bg-brand-600 p-4 text-white">
                <p class="text-[10px] font-bold uppercase tracking-wider text-white/80">Consejo</p>
                <p class="mt-1 text-sm font-semibold leading-relaxed">Postula solo a convocatorias vigentes y verifica siempre en el portal oficial de la entidad.</p>
            </div>
        </aside>
    </div>
</div>
@endsection
