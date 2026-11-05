@extends('layouts.app')

@section('title', $entity->name.' | Punto Actual')
@section('description', 'Convocatorias vigentes de '.$entity->name.($entity->region ? ' en '.$entity->region : '').'.')

@section('content')
<div class="container-page max-w-6xl py-10 sm:py-14">
    @if($entity->cover_image)
        <div class="mb-6 overflow-hidden rounded-3xl border border-black/10 bg-zinc-100">
            <img src="{{ $entity->cover_image }}" alt="{{ $entity->name }}" class="aspect-[21/8] w-full object-cover">
        </div>
    @endif

    <div class="card p-7 sm:p-9">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
            <div class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-brand-500/10 text-2xl font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                @if($entity->logo)
                    <img src="{{ $entity->logo }}" alt="{{ $entity->name }}" class="h-full w-full object-cover">
                @else
                    {{ strtoupper(substr($entity->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <p class="section-label">{{ $entity->entity_type }}</p>
                <h1 class="mt-1 text-3xl font-black tracking-tight">{{ $entity->name }}</h1>
                @if($entity->region)
                    <p class="text-zinc-500">{{ $entity->region }}</p>
                @endif
            </div>
        </div>

        @if($entity->description)
            <p class="mt-6 leading-7 text-zinc-600">{{ $entity->description }}</p>
        @endif

        <div class="mt-5 flex flex-wrap gap-4 text-sm">
            @if($entity->website)
                <a class="link-red" href="{{ $entity->website }}" target="_blank" rel="noopener">Sitio web oficial</a>
            @endif
            @if($entity->official_jobs_url)
                <a class="link-red" href="{{ $entity->official_jobs_url }}" target="_blank" rel="noopener">Portal de convocatorias</a>
            @endif
        </div>
    </div>

    <div class="mt-10 flex items-end justify-between">
        <h2 class="text-2xl font-black tracking-tight">Convocatorias de esta entidad</h2>
        <a href="{{ route('vacancies.index') }}" class="text-sm font-bold text-brand-600 transition hover:text-brand-700">Ver todas →</a>
    </div>

    <div class="mt-4 grid gap-3">
        @forelse($vacancies as $job)
            <a class="group card flex items-center justify-between gap-4 p-5 transition hover:border-brand-500/40" href="{{ route('vacancies.show', $job) }}">
                <div class="min-w-0">
                    <p class="truncate font-bold text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</p>
                    <p class="mt-1 text-xs text-zinc-500">{{ $job->employment_regime }} · {{ $job->department ?: 'Perú' }}</p>
                </div>
                <span class="shrink-0 text-sm font-semibold text-zinc-600">{{ $job->salary_label }}</span>
            </a>
        @empty
            <div class="card p-8 text-center text-zinc-600">No hay convocatorias vigentes.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $vacancies->links() }}</div>
</div>
@endsection
