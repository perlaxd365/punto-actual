<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('puntoactual.site.name').' | '.config('puntoactual.site.tagline'))</title>
    <meta name="description" content="@yield('description', 'Noticias del Perú y convocatorias laborales verificadas.')">
    <meta name="theme-color" content="#ffffff">
    <link rel="canonical" href="{{ url()->current() }}">
    @if(config('analytics.google_site_verification'))
        <meta name="google-site-verification" content="{{ config('analytics.google_site_verification') }}">
    @endif
    @if(config('analytics.bing_site_verification'))
        <meta name="msvalidate.01" content="{{ config('analytics.bing_site_verification') }}">
    @endif

    <meta property="og:site_name" content="{{ config('puntoactual.site.name') }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="@yield('og_title', trim($__env->yieldContent('title', config('puntoactual.site.name'))))">
    <meta property="og:description" content="@yield('og_description', trim($__env->yieldContent('description', 'Noticias del Perú y convocatorias laborales verificadas.')))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('og_image', config('puntoactual.site.default_image') ?: asset('favicon.ico'))">
    <meta property="og:locale" content="es_PE">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title', config('puntoactual.site.name'))))">
    <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('description', 'Noticias del Perú y convocatorias laborales verificadas.')))">
    <meta name="twitter:image" content="@yield('og_image', config('puntoactual.site.default_image') ?: asset('favicon.ico'))">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    @include('partials.analytics-head')
</head>
<body class="min-h-screen bg-white text-ink-950 antialiased">
    @include('partials.analytics-body')
    <header class="sticky top-0 z-50 border-b border-black/10 bg-white/85 backdrop-blur-xl">
        <div class="h-0.5 w-full bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700"></div>
        <div class="container-page flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-baseline gap-1 text-xl font-black tracking-tight sm:text-2xl">
                <span class="text-ink-950">PUNTO</span><span class="text-brand-500">ACTUAL</span>
            </a>

            <nav class="hidden items-center gap-7 text-sm font-semibold text-zinc-600 md:flex">
                <a href="{{ route('news.index') }}" class="transition hover:text-brand-700">Noticias</a>
                <a href="{{ route('news.international') }}" class="transition hover:text-brand-700">Internacional</a>
                <a href="{{ route('vacancies.index') }}" class="transition hover:text-brand-700">Empleos</a>
                <a href="{{ route('home') }}#regiones" class="transition hover:text-brand-700">Regiones</a>
                <a href="{{ route('home') }}#actualidad" class="transition hover:text-brand-700">Actualidad</a>
            </nav>

            <div class="flex items-center gap-2 sm:gap-4">
                <div class="hidden sm:block">
                    @include('partials.social')
                </div>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hidden text-sm font-semibold text-brand-600 sm:inline">Panel</a>
                    <form method="post" action="{{ route('logout') }}" class="hidden sm:block">
                        @csrf
                        <button class="text-sm font-semibold text-zinc-600 transition hover:text-brand-700">Salir</button>
                    </form>
                @endauth
                <a href="{{ route('vacancies.index') }}" class="btn-primary !px-4 !py-2">Buscar empleo</a>
            </div>
        </div>

        <div class="border-t border-black/5 bg-white">
            <div class="container-page flex items-center gap-5 overflow-x-auto py-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-600">
                <span class="whitespace-nowrap text-brand-600">Actualidad</span>
                @foreach(['Política', 'Economía', 'Sociedad', 'Educación', 'Salud', 'Tecnología', 'Regiones', 'Deportes', 'Internacional'] as $topic)
                    <span class="whitespace-nowrap transition hover:text-brand-700">{{ $topic }}</span>
                @endforeach
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-black/10 bg-white">
        <div class="container-page grid gap-10 py-14 md:grid-cols-[1.4fr_1fr_1fr]">
            <div>
                <div class="flex items-baseline gap-1 text-2xl font-black tracking-tight">
                    <span class="text-ink-950">PUNTO</span><span class="text-brand-500">ACTUAL</span>
                </div>
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-zinc-600">Noticias y oportunidades laborales de todo el Perú, en un solo lugar. Información verificada y enlaces a las fuentes oficiales.</p>
                <div class="mt-5">@include('partials.social')</div>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-ink-950">Explora</h3>
                <div class="mt-4 grid gap-2 text-sm text-zinc-600">
                    <a href="{{ route('news.index') }}" class="transition hover:text-brand-700">Noticias</a>
                    <a href="{{ route('news.international') }}" class="transition hover:text-brand-700">Internacional</a>
                    <a href="{{ route('vacancies.index') }}" class="transition hover:text-brand-700">Convocatorias</a>
                    <a href="{{ route('home') }}#regiones" class="transition hover:text-brand-700">Regiones</a>
                    <a href="{{ route('sitemap') }}" class="transition hover:text-brand-700">Sitemap</a>
                    <a href="{{ route('feed') }}" class="transition hover:text-brand-700">RSS</a>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-ink-950">Transparencia</h3>
                <p class="mt-4 text-sm leading-relaxed text-zinc-600">Punto Actual es un portal informativo independiente. Verifica siempre las bases y enlaces de cada convocatoria en la entidad convocante.</p>
            </div>
        </div>
        <div class="border-t border-black/5 py-5 text-center text-xs text-zinc-500">
            © {{ date('Y') }} {{ config('puntoactual.site.name') }}. Todos los derechos reservados.
        </div>
    </footer>

    @livewireScripts
</body>
</html>
