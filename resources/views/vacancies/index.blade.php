@extends('layouts.app')

@section('title', 'Convocatorias y empleos en Perú | Punto Actual')
@section('description', 'Busca convocatorias CAS, 728, 276, Servicio Civil y prácticas en todo el Perú. Filtra por región y régimen laboral.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-zinc-50">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.20),transparent_65%)]"></div>
    <div class="container-page relative py-12 sm:py-16">
        <p class="section-label">Oportunidades en todo el país</p>
        <h1 class="mt-2 text-4xl font-black tracking-tight sm:text-5xl">Empleos y convocatorias</h1>
        <p class="mt-3 max-w-2xl text-zinc-600">Encuentra oportunidades públicas y privadas. Revisa siempre las bases oficiales antes de postular.</p>
    </div>
</div>

<div class="container-page py-10">
    <form class="card grid min-w-0 gap-3 p-4 md:grid-cols-4" data-track="search">
        <input name="q" value="{{ request('q') }}" placeholder="Cargo, carrera o especialidad (ej. computación, ingeniería)" class="input min-w-0">
        <select name="region" class="input min-w-0">
            <option value="">Todas las regiones</option>
            @foreach($regions as $region)
                <option @selected(request('region') === $region)>{{ $region }}</option>
            @endforeach
        </select>
        <select name="regime" class="input min-w-0">
            <option value="">Todos los regímenes</option>
            @foreach(['CAS', '728', '276', 'Servicio Civil', 'Prácticas', 'Privado'] as $regime)
                <option @selected(request('regime') === $regime)>{{ $regime }}</option>
            @endforeach
        </select>
        <button class="btn-primary min-w-0">Filtrar convocatorias</button>
    </form>

    <div class="mt-8 grid gap-4 md:grid-cols-2">
        @forelse($vacancies as $job)
            <a href="{{ route('vacancies.show', $job) }}" class="group card flex min-w-0 flex-col overflow-hidden p-4 transition duration-300 hover:-translate-y-0.5 hover:border-brand-500/40 sm:p-5">
                @if($job->image)
                    <div class="mb-4 aspect-[16/8] overflow-hidden rounded-xl border border-black/5 bg-zinc-100">
                        <img src="{{ $job->image }}" alt="{{ $job->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    </div>
                @endif
                <div class="flex min-w-0 gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-brand-500/10 text-base font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                        {{ strtoupper(substr($job->entity?->name ?? 'PA', 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">{{ $job->employment_regime }}</span>
                        <h2 class="mt-1 line-clamp-2 text-lg font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h2>
                        <p class="truncate text-sm text-zinc-500">{{ $job->entity?->name }}</p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap gap-2">
                    <span class="badge-soft">{{ $job->salary_label }}</span>
                    <span class="badge-red">{{ $job->department ?: 'Perú' }}</span>
                </div>
                <p class="mt-3 line-clamp-2 text-xs leading-relaxed text-zinc-600" title="{{ $job->professional_career }}">
                    <span class="font-semibold uppercase tracking-wider text-ink-900">Carrera:</span>
                    {{ $job->professional_career ?: 'Revisar carreras y especialidades en las bases oficiales.' }}
                </p>
                <div class="mt-auto flex flex-col gap-1 border-t border-black/5 pt-3 text-xs text-zinc-500 sm:flex-row sm:items-center sm:justify-between sm:gap-3">
                    <span class="min-w-0">{{ $job->vacancies_count ?? '—' }} plaza(s)</span>
                    <span class="min-w-0 sm:text-right">Postulación hasta: {{ $job->application_end_at?->format('d/m/Y') ?? 'Ver bases' }}</span>
                </div>
            </a>
        @empty
            <div class="card p-10 text-center text-zinc-600 md:col-span-2">No hay convocatorias que coincidan con tu búsqueda.</div>
        @endforelse
    </div>

    <div class="mt-10">{{ $vacancies->links() }}</div>
</div>
@endsection
