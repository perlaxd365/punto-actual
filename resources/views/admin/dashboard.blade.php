@extends('layouts.app')

@section('title', 'Panel de control | Punto Actual')
@section('description', 'Panel editorial de Punto Actual.')

@section('content')
<div class="container-page py-10 sm:py-14">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="section-label">Administración</p>
            <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">Panel de control</h1>
        </div>
        <a href="{{ route('home') }}" class="btn-ghost !px-4 !py-2">Ver portal ↗</a>
    </div>

    <div class="mt-8">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="section-label">Audiencia</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight">Visitas del portal</h2>
            </div>
            <p class="text-xs text-zinc-500">Páginas vistas por visitantes reales (sin bots ni sesiones del panel)</p>
        </div>

        <div class="mt-4 grid gap-4 sm:grid-cols-3">
            @foreach([
                ['Hoy', $todayVisits, 'Visitantes desde las 00:00'],
                ['Ayer', $yesterdayVisits, 'Jornada anterior'],
                ['Total', $totalVisits, 'Acumulado histórico'],
            ] as [$label, $value, $hint])
                <div class="card p-6">
                    <p class="text-sm font-semibold text-zinc-600">{{ $label }}</p>
                    <p class="mt-2 text-4xl font-black text-ink-950">{{ number_format($value, 0, ',', '.') }}</p>
                    <p class="mt-2 text-xs text-zinc-600">{{ $hint }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        @foreach([
            ['Noticias', $newsCount, 'Noticias publicadas y borradores'],
            ['Convocatorias', $vacancyCount, 'Ofertas registradas'],
            ['Entidades', $entityCount, 'Instituciones en el directorio'],
            ['Borradores laborales', $pending, 'Convocatorias por publicar'],
            ['Suscriptores', $subscriberCount ?? 0, 'Boletín activo'],
        ] as [$label, $value, $hint])
            <div class="card p-6">
                <p class="text-sm font-semibold text-zinc-600">{{ $label }}</p>
                <p class="mt-2 text-4xl font-black text-ink-950">{{ $value }}</p>
                <p class="mt-2 text-xs text-zinc-600">{{ $hint }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 grid gap-4 md:grid-cols-3">
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.news.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Gestionar noticias</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.vacancies.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Gestionar convocatorias</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.entities.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Gestionar entidades</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.subscribers.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Suscriptores</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.newsletter.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Enviar boletín</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.team.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Equipo y roles</span>
            <span class="text-brand-500">→</span>
        </a>
        <a class="group card flex items-center justify-between p-6 transition hover:border-brand-500/40" href="{{ route('admin.security.index') }}">
            <span class="font-bold text-ink-950 transition group-hover:text-brand-600">Seguridad y 2FA</span>
            <span class="text-brand-500">→</span>
        </a>
    </div>

    <div class="mt-8 rounded-2xl border border-black/10 bg-white p-5 text-sm text-zinc-600">
        Las noticias publicadas se envían automáticamente a Google mediante la Indexing API. Configura
        <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-brand-600">GOOGLE_INDEXING_ENABLED</code> y
        <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-brand-600">GOOGLE_APPLICATION_CREDENTIALS</code> para activarlo.
    </div>

    <div class="mt-10">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="section-label">Analítica</p>
                <h2 class="mt-1 text-2xl font-black tracking-tight">Herramientas de medición</h2>
            </div>
            <p class="text-xs text-zinc-500">Configura los IDs en <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-brand-600">.env</code></p>
        </div>

        <div class="mt-4 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['Google Analytics 4', 'https://analytics.google.com/', (bool) config('analytics.ga4_id'), 'Tráfico, audiencia y eventos.'],
                ['Google Search Console', 'https://search.google.com/search-console', (bool) config('analytics.google_site_verification'), 'Indexación y consultas de búsqueda.'],
                ['Google Tag Manager', 'https://tagmanager.google.com/', (bool) config('analytics.gtm_id'), 'Etiquetas y conversiones centralizadas.'],
                ['Microsoft Clarity', 'https://clarity.microsoft.com/', (bool) config('analytics.clarity_id'), 'Mapas de calor y grabaciones.'],
                ['Meta Business', 'https://business.facebook.com/', (bool) config('analytics.meta_pixel_id'), 'Pixel de Facebook e Instagram.'],
                ['TikTok Ads', 'https://ads.tiktok.com/', (bool) config('analytics.tiktok_pixel_id'), 'Pixel y campañas de TikTok.'],
            ] as [$name, $url, $configured, $description])
                <a href="{{ $url }}" target="_blank" rel="noopener" class="group card flex flex-col p-5 transition hover:border-brand-500/40">
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-bold text-ink-950 transition group-hover:text-brand-600">{{ $name }}</span>
                        <span class="{{ $configured ? 'badge-red' : 'badge-soft' }}">{{ $configured ? 'Configurado' : 'Sin configurar' }}</span>
                    </div>
                    <p class="mt-2 text-xs leading-relaxed text-zinc-500">{{ $description }}</p>
                    <span class="mt-3 text-xs font-bold text-brand-600 transition group-hover:text-brand-700">Abrir panel ↗</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
