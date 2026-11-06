@extends('layouts.app')

@php
    $initials = mb_strtoupper(mb_substr($entity->name, 0, 2));
    $pageTitle = 'Cursos de '.$entity->name;
    $pageDescription = 'Cursos y programas recomendados de '.$entity->name.'. Formación para mejorar el aprendizaje y la empleabilidad en el Perú.';
    $crumbs = [
        ['name' => 'Inicio', 'url' => route('home')],
        ['name' => 'Cursos', 'url' => route('courses.index')],
        ['name' => $entity->name],
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
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $pageTitle,
            'description' => $pageDescription,
            'url' => url()->current(),
            'inLanguage' => 'es-PE',
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
        <div class="mt-3 flex flex-wrap items-start justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-brand-500/10 text-lg font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                    @if($entity->logo)
                        <img src="{{ $entity->logo }}" alt="{{ $entity->name }}" class="h-full w-full object-cover">
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <div>
                    <p class="section-label">Institución educativa</p>
                    <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">{{ $entity->name }}</h1>
                    <p class="mt-1 text-sm text-zinc-600">{{ $entity->entity_type }}{{ $entity->region ? ' · '.$entity->region : '' }}</p>
                </div>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($entity->website)
                    <a href="{{ $entity->website }}" target="_blank" rel="noopener" class="btn-ghost">Sitio oficial ↗</a>
                @endif
                <a href="{{ route('courses.index') }}" class="btn-primary">Ver todos los cursos</a>
            </div>
        </div>
        @if($entity->description)
            <p class="mt-4 max-w-3xl text-sm leading-relaxed text-zinc-600">{{ $entity->description }}</p>
        @endif
    </div>
</div>

<div class="container-page py-6">
    <p class="mb-4 text-sm font-semibold text-zinc-500">{{ $courses->total() }} {{ $courses->total() === 1 ? 'curso' : 'cursos' }} disponibles</p>

    @if($courses->isEmpty())
        <div class="rounded-xl border border-black/10 bg-white p-10 text-center text-zinc-500">
            Esta institución aún no tiene cursos publicados.
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($courses as $course)
                @include('partials.course-card', ['course' => $course])
            @endforeach
        </div>
        <div class="mt-8">{{ $courses->links() }}</div>
    @endif
</div>
@endsection
