@extends('layouts.app')

@section('title', 'Punto Actual | Noticias y empleos del Perú')
@section('description', 'Noticias nacionales y convocatorias laborales verificadas. Encuentra tu próxima oportunidad en todo el Perú.')

@php
    $slides = $coverNews->isNotEmpty()
        ? $coverNews->map(fn ($item) => (object) [
            'title' => $item->title,
            'subtitle' => $item->excerpt,
            'image' => $item->image,
            'link' => route('news.show', $item->slug),
            'place' => $item->place_label,
            'category' => $item->category?->name,
        ])
        : $featured->merge($latest)->filter(fn ($item) => $item->image)->unique('id')->take(4)->values()->map(fn ($item) => (object) [
            'title' => $item->title,
            'subtitle' => $item->excerpt,
            'image' => $item->image,
            'link' => route('news.show', $item->slug),
            'place' => $item->place_label,
            'category' => $item->category?->name,
        ]);

    if ($slides->isEmpty()) {
        $slides = collect([(object) [
            'title' => 'Lo que pasa. Lo que importa.',
            'subtitle' => 'Noticias nacionales y convocatorias laborales verificadas.',
            'image' => asset('images/samples/cover-1.jpg'),
            'link' => route('news.index'),
            'place' => null,
            'category' => null,
        ]]);
    }
@endphp

@section('content')
<section class="relative border-b border-black/10 bg-white">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(900px_500px_at_85%_-20%,rgba(225,29,46,0.14),transparent_62%)]"></div>
    <div class="container-page relative py-6 lg:py-10">
        <div class="relative overflow-hidden rounded-3xl border border-black/10 bg-zinc-100 shadow-[0_1px_2px_rgba(0,0,0,0.04),0_30px_70px_-40px_rgba(0,0,0,0.45)]" data-carousel data-interval="5000">
            <div class="relative h-[400px] sm:h-[480px] lg:h-[560px]">
                @foreach($slides as $index => $slide)
                    <div class="carousel-slide absolute inset-0 {{ $index === 0 ? 'is-active' : '' }}" data-carousel-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                        <img src="{{ $slide->image }}" alt="{{ $slide->title }}" class="carousel-image absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-white via-white/85 to-white/5"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-white/90 via-white/20 to-transparent"></div>
                        <div class="relative flex h-full items-end p-6 sm:p-10 lg:p-14">
                            <div class="max-w-xl">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="badge-red">Portada</span>
                                    @if($slide->category)
                                        <span class="badge-soft">{{ $slide->category }}</span>
                                    @endif
                                    @if($slide->place)
                                        <span class="badge-soft">{{ $slide->place }}</span>
                                    @endif
                                </div>
                                <h1 class="mt-3 text-3xl font-black leading-[1.05] tracking-tight text-ink-950 sm:text-5xl">{{ $slide->title }}</h1>
                                @if($slide->subtitle)
                                    <p class="mt-3 max-w-lg text-base leading-relaxed text-zinc-600 sm:text-lg">{{ $slide->subtitle }}</p>
                                @endif
                                <div class="mt-6 flex flex-wrap gap-3">
                                    <a href="{{ $slide->link }}" class="btn-primary" data-track="cover_click" data-track-label="{{ $slide->title }}">Leer más</a>
                                    <a href="{{ route('vacancies.index') }}" class="btn-ghost">Ver empleos</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($slides->count() > 1)
                <div class="absolute bottom-5 right-5 z-10 flex items-center gap-2">
                    @foreach($slides as $index => $slide)
                        <button type="button" data-carousel-dot aria-label="Portada {{ $index + 1 }}" class="carousel-dot {{ $index === 0 ? 'is-active' : '' }}"></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

@php($portada = $featured->merge($latest)->unique('id')->take(4))
<section id="actualidad" class="container-page py-16">
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="section-label">Selección editorial</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Noticias destacadas</h2>
        </div>
        <a href="{{ route('news.index') }}" class="hidden text-sm font-bold text-brand-600 transition hover:text-brand-700 sm:inline">Ver todas →</a>
    </div>

    @if($portada->isEmpty())
        <div class="card p-10 text-center text-zinc-600">Próximamente publicaremos noticias.</div>
    @else
        @php($lead = $portada->first())
        <div class="grid gap-5 lg:grid-cols-2">
            <a href="{{ route('news.show', $lead) }}" class="group overflow-hidden rounded-3xl border border-black/10 bg-white shadow-[0_1px_2px_rgba(0,0,0,0.04),0_28px_60px_-38px_rgba(0,0,0,0.4)]">
                <div class="aspect-[16/10] w-full overflow-hidden border-b border-black/10">
                    @if($lead->image)
                        <img src="{{ $lead->image }}" alt="{{ $lead->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    @else
                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-white text-7xl font-black text-black/10">PA</div>
                    @endif
                </div>
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="badge-red">{{ $lead->category?->name ?? 'Actualidad' }}</span>
                        @if($lead->place_label)
                            <span class="badge-soft">{{ $lead->place_label }}</span>
                        @endif
                    </div>
                    <h3 class="mt-3 text-2xl font-black leading-tight text-ink-950 transition group-hover:text-brand-600 sm:text-3xl">{{ $lead->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ $lead->excerpt }}</p>
                    <p class="mt-3 text-xs text-zinc-500">{{ $lead->published_at?->format('d/m/Y H:i') }}</p>
                </div>
            </a>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                @foreach($portada->skip(1)->take(3) as $item)
                    <a href="{{ route('news.show', $item) }}" class="group card flex gap-4 overflow-hidden p-3 transition duration-300 hover:-translate-y-0.5 hover:border-brand-500/40">
                        <div class="aspect-square w-24 shrink-0 overflow-hidden rounded-xl bg-zinc-100">
                            @if($item->image)
                                <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full items-center justify-center text-lg font-black text-black/10">PA</div>
                            @endif
                        </div>
                        <div class="min-w-0 py-1">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">{{ $item->category?->name ?? 'Actualidad' }}</span>
                            @if($item->place_label)
                                <span class="text-[11px] font-semibold text-zinc-500">· {{ $item->place_label }}</span>
                            @endif
                            <h3 class="mt-1 line-clamp-2 font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
                            <p class="mt-2 text-xs text-zinc-500">{{ $item->published_at?->format('d/m/Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</section>

@if($international->isNotEmpty())
<section id="internacional" class="container-page py-16">
    <div class="mb-8 flex items-end justify-between gap-4">
        <div>
            <p class="section-label">Mirada global</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Internacional</h2>
            <p class="mt-2 max-w-xl text-zinc-600">Solo las noticias más importantes del mundo, seleccionadas por nuestra redacción.</p>
        </div>
        <a href="{{ route('news.international') }}" class="hidden text-sm font-bold text-brand-600 transition hover:text-brand-700 sm:inline">Ver más →</a>
    </div>

    <div class="grid gap-5 md:grid-cols-3">
        @foreach($international as $item)
            <a href="{{ route('news.show', $item) }}" class="group card overflow-hidden transition duration-300 hover:-translate-y-1 hover:border-brand-500/40">
                <div class="aspect-[16/9] overflow-hidden border-b border-black/10 bg-zinc-100">
                    @if($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">
                    @else
                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-brand-100 to-zinc-100 text-4xl font-black text-black/10">PA</div>
                    @endif
                </div>
                <div class="p-5">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-brand-600">Internacional</span>
                        @if($item->place_label)
                            <span class="badge-soft">{{ $item->place_label }}</span>
                        @endif
                    </div>
                    <h3 class="mt-2 line-clamp-2 text-lg font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $item->title }}</h3>
                    <p class="mt-2 line-clamp-2 text-sm text-zinc-500">{{ $item->excerpt }}</p>
                    <p class="mt-4 text-xs text-zinc-600">{{ $item->published_at?->format('d/m/Y') }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

<section class="border-y border-black/10 bg-zinc-50">
    <div class="container-page py-16">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="section-label">Oportunidades laborales</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Convocatorias recientes</h2>
            </div>
            <a href="{{ route('vacancies.index') }}" class="hidden text-sm font-bold text-brand-600 transition hover:text-brand-700 sm:inline">Ver empleos →</a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @forelse($vacancies as $job)
                <a href="{{ route('vacancies.show', $job) }}" class="group card p-5 transition duration-300 hover:-translate-y-1 hover:border-brand-500/40">
                    @if($job->image)
                        <div class="mb-4 aspect-[16/8] overflow-hidden rounded-xl border border-black/5 bg-zinc-100">
                            <img src="{{ $job->image }}" alt="{{ $job->title }}" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                    @endif
                    <div class="flex items-start gap-3">
                        <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-brand-500/10 text-sm font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">
                            {{ strtoupper(substr($job->entity?->name ?? 'PA', 0, 2)) }}
                        </div>
                        <div class="min-w-0">
                            <h3 class="line-clamp-2 font-bold leading-snug text-ink-950 transition group-hover:text-brand-600">{{ $job->title }}</h3>
                            <p class="mt-1 truncate text-sm text-zinc-500">{{ $job->entity?->name }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="badge-red">{{ $job->employment_regime }}</span>
                        <span class="badge-soft">{{ $job->salary_label }}</span>
                    </div>
                    <div class="mt-4 flex items-center justify-between border-t border-black/5 pt-3 text-xs text-zinc-500">
                        <span>{{ $job->department ?: 'Perú' }}</span>
                        <span>Cierre: {{ $job->application_end_at?->format('d/m/Y') ?? 'Ver bases' }}</span>
                    </div>
                </a>
            @empty
                <div class="card p-10 text-center text-zinc-600 md:col-span-2 lg:col-span-3">No hay convocatorias publicadas en este momento.</div>
            @endforelse
        </div>
    </div>
</section>

<section id="regiones" class="container-page py-16">
    <div class="grid gap-8 lg:grid-cols-[.9fr_1.1fr] lg:items-center">
        <div>
            <p class="section-label">Cobertura nacional</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Explora por tema</h2>
            <p class="mt-4 max-w-md text-zinc-600">Sigue las secciones que te interesan y encuentra oportunidades por región, entidad y régimen laboral.</p>
            <a href="{{ route('news.index') }}" class="btn-ghost mt-7">Ver todas las noticias</a>
        </div>
        <div class="flex flex-wrap gap-2.5">
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" class="rounded-full border border-black/10 bg-black/[0.02] px-4 py-2 text-sm font-semibold text-zinc-600 transition hover:border-brand-500/40 hover:bg-brand-500/10 hover:text-brand-700">{{ $category->name }}</a>
            @endforeach
        </div>
    </div>
</section>

<section class="container-page pb-16">
    <div class="relative overflow-hidden rounded-3xl border border-brand-500/20 bg-gradient-to-br from-brand-50 via-white to-white p-8 sm:p-12">
        <div class="pointer-events-none absolute -right-16 -top-16 h-64 w-64 rounded-full bg-brand-600/20 blur-3xl"></div>
        <div class="relative max-w-2xl">
            <p class="section-label">Comunidad</p>
            <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Síguenos y no te pierdas nada</h2>
            <p class="mt-3 text-zinc-600">Únete a nuestra comunidad en redes sociales para recibir noticias y convocatorias apenas se publiquen.</p>
            <div class="mt-6 flex flex-wrap items-center gap-3">
                <a href="{{ route('news.index') }}" class="btn-primary">Ver noticias</a>
                <div class="rounded-2xl border border-black/10 bg-zinc-50 px-4 py-2">@include('partials.social')</div>
            </div>
        </div>
    </div>
</section>
@endsection
