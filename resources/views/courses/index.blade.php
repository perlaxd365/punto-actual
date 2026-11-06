@extends('layouts.app')

@php
    $pageTitle = 'Cursos más buscados del Perú';
    $pageDescription = 'Directorio de los cursos más buscados del Perú: Excel, inglés, programación, marketing digital, diseño, oficios y más. Recomendamos formación para mejorar el aprendizaje y la empleabilidad en el Perú.';
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Cursos'],
    ];
    $crumbSchema = collect($crumbs)->values()->map(fn ($c, $i) => array_filter([
        '@type' => 'ListItem',
        'position' => $i + 1,
        'name' => $c['name'],
        'item' => $c['url'] ?? null,
    ]))->all();
    $itemList = $courses->getCollection()->values()->map(fn ($course, $i) => [
        '@type' => 'ListItem',
        'position' => (($courses->currentPage() - 1) * $courses->perPage()) + $i + 1,
        'url' => route('courses.show', $course->slug),
        'name' => $course->title,
    ])->all();
@endphp

@section('title', $pageTitle.' | Punto Actual')
@section('description', $pageDescription)

@push('head')
    @if($courses->previousPageUrl())
        <link rel="prev" href="{{ $courses->previousPageUrl() }}">
    @endif
    @if($courses->nextPageUrl())
        <link rel="next" href="{{ $courses->nextPageUrl() }}">
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
            <div class="max-w-3xl">
                <p class="section-label">Formación y empleabilidad</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-5xl">{{ $pageTitle }}</h1>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600 sm:text-base">
                    Reunimos los cursos más buscados por los peruanos para mejorar sus habilidades y acceder a
                    mejores oportunidades. Recomendamos formación continua para fortalecer el aprendizaje en el Perú.
                </p>
            </div>
        </div>

        <form method="get" action="{{ route('courses.index') }}" class="mt-5 grid gap-2 sm:grid-cols-[minmax(0,1fr)_auto_auto_auto]" data-track="search">
            <input name="q" value="{{ request('q') }}" placeholder="Buscar por curso, área o habilidad..." class="input min-w-0 !py-2.5">
            <select name="area" class="input !py-2.5 sm:w-44">
                <option value="">Todas las áreas</option>
                @foreach($areas as $area)
                    <option value="{{ $area }}" @selected(request('area') === $area)>{{ $area }}</option>
                @endforeach
            </select>
            <select name="modality" class="input !py-2.5 sm:w-44">
                <option value="">Toda modalidad</option>
                @foreach($modalities as $modality)
                    <option value="{{ $modality }}" @selected(request('modality') === $modality)>{{ $modality }}</option>
                @endforeach
            </select>
            <button class="btn-primary shrink-0 !px-5 !py-2.5">Buscar cursos</button>
        </form>

        <div class="mt-4 flex gap-1.5 overflow-x-auto pb-1 text-[11px] font-bold uppercase tracking-wider [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
            <a href="{{ route('courses.index', array_filter(['q' => request('q'), 'modality' => request('modality')])) }}" class="whitespace-nowrap rounded-full px-3 py-1.5 {{ ! request('area') ? 'bg-brand-600 text-white' : 'bg-white text-zinc-600 ring-1 ring-black/10 hover:text-brand-700' }}">Todas</a>
            @foreach($areas as $area)
                <a href="{{ route('courses.index', array_filter(['area' => $area, 'q' => request('q'), 'modality' => request('modality')])) }}" class="whitespace-nowrap rounded-full px-3 py-1.5 {{ request('area') === $area ? 'bg-brand-600 text-white' : 'bg-white text-zinc-600 ring-1 ring-black/10 hover:text-brand-700' }}">{{ $area }}</a>
            @endforeach
        </div>
    </div>
</div>

<div class="container-page py-6">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,3fr)_minmax(0,1fr)]">
        <div class="min-w-0">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                <p class="text-sm font-semibold text-zinc-500">
                    {{ $courses->total() }} {{ $courses->total() === 1 ? 'curso encontrado' : 'cursos encontrados' }}
                </p>
                @if(request()->filled('q') || request()->filled('area') || request()->filled('modality'))
                    <a href="{{ route('courses.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">Limpiar filtros</a>
                @endif
            </div>

            @if($courses->isEmpty())
                <div class="rounded-xl border border-black/10 bg-white p-10 text-center text-zinc-500">
                    No encontramos cursos con esos filtros. Prueba con otra área o palabra clave.
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($courses as $course)
                        @include('partials.course-card', ['course' => $course, 'delay' => ($loop->index % 3) * 80])
                    @endforeach
                </div>
                <div class="mt-8">{{ $courses->links() }}</div>
            @endif

            <div class="mt-8 rounded-xl border border-black/[0.08] bg-zinc-50 p-5 text-sm leading-relaxed text-zinc-600">
                <p class="font-bold text-ink-950">Sobre esta selección</p>
                <p class="mt-1">
                    Los cursos se agrupan por área y se enlazan al sitio oficial de cada institución. Costos, fechas,
                    requisitos y modalidades se confirman directamente con la institución educativa.
                </p>
            </div>
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            @if($featured->isNotEmpty())
                <div class="rounded-2xl border border-black/[0.08] bg-zinc-50/80 p-4">
                    @include('partials.section-heading', ['title' => 'Cursos destacados', 'link' => route('courses.index')])
                    @foreach($featured as $course)
                        @include('partials.course-card', ['course' => $course, 'variant' => 'list', 'delay' => ($loop->index % 4) * 60])
                    @endforeach
                </div>
            @endif

            <div class="rounded-2xl border border-brand-500/20 bg-gradient-to-br from-brand-50 via-white to-white p-5">
                <p class="section-label">Aprender para crecer</p>
                <h2 class="mt-1 text-lg font-black tracking-tight">¿Por qué recomendamos cursos?</h2>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600">
                    La formación continua mejora la empleabilidad, impulsa mejores salarios y cierra brechas de
                    habilidades en el Perú. Un curso bien elegido puede abrir nuevas oportunidades laborales.
                </p>
                <a href="{{ route('vacancies.index') }}" class="mt-4 inline-flex text-sm font-bold text-brand-600 hover:text-brand-700">Ver convocatorias de empleo →</a>
            </div>

            <div class="rounded-2xl border border-black/[0.08] bg-white p-5">
                <p class="text-sm font-bold text-ink-950">¿Buscas un curso específico?</p>
                <p class="mt-1 text-sm text-zinc-600">Escríbenos y ayúdanos a ampliar este directorio con las áreas que más te interesan.</p>
                <a href="{{ route('pages.contacto') }}" class="mt-3 inline-flex text-sm font-bold text-brand-600 hover:text-brand-700">Contactar a Punto Actual →</a>
            </div>
        </aside>
    </div>
</div>
@endsection
