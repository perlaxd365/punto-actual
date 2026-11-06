<!doctype html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('puntoactual.site.name').' | '.config('puntoactual.site.tagline'))</title>
    <meta name="description" content="@yield('description', 'Noticias del Perú y convocatorias laborales verificadas.')">
    <meta name="robots" content="@yield('robots', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1')">
    <meta name="theme-color" content="#ffffff">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96.png') }}">
    <link rel="icon" type="image/png" sizes="144x144" href="{{ asset('favicon-144.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" sizes="any" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="es-pe" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="es" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
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
    <meta property="og:image" content="@yield('og_image', config('puntoactual.site.default_image') ?: asset('favicon-512.png'))">
    @hasSection('og_image_width')
        <meta property="og:image:width" content="@yield('og_image_width')">
        <meta property="og:image:height" content="@yield('og_image_height')">
        <meta property="og:image:type" content="image/jpeg">
    @endif
    <meta property="og:locale" content="es_PE">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', trim($__env->yieldContent('title', config('puntoactual.site.name'))))">
    <meta name="twitter:description" content="@yield('og_description', trim($__env->yieldContent('description', 'Noticias del Perú y convocatorias laborales verificadas.')))">
    <meta name="twitter:image" content="@yield('og_image', config('puntoactual.site.default_image') ?: asset('favicon-512.png'))">

    @php($socialLinks = array_values(array_filter(config('puntoactual.social'))))
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'NewsMediaOrganization',
            'name' => config('puntoactual.site.name'),
            'url' => url('/'),
            'logo' => ['@type' => 'ImageObject', 'url' => asset('images/logo-punto-actual.png'), 'width' => 1000, 'height' => 212],
            'sameAs' => $socialLinks,
            'founder' => [
                ['@type' => 'Person', 'name' => 'Raúl Baca'],
                ['@type' => 'Person', 'name' => 'Perla García'],
            ],
            'ethicsPolicy' => route('pages.editorial'),
            'publishingPrinciples' => route('pages.editorial'),
            'contactPoint' => [[
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'email' => config('puntoactual.site.email'),
                'telephone' => config('puntoactual.site.phone'),
                'areaServed' => 'PE',
                'availableLanguage' => ['es'],
            ]],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => config('puntoactual.site.name'),
            'url' => url('/'),
            'inLanguage' => 'es-PE',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('news.index').'?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://res.cloudinary.com" crossorigin>
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('head')
    @include('partials.analytics-head')
    @include('partials.adsense-head')
</head>
<body class="min-h-screen bg-white text-ink-950 antialiased">
    @include('partials.analytics-body')
    <header class="sticky top-0 z-50 border-b border-black/10 bg-white/85 backdrop-blur-xl">
        <div class="h-0.5 w-full bg-gradient-to-r from-brand-700 via-brand-500 to-brand-700"></div>
        <div class="container-page flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center">
                <img src="{{ asset('images/logo-punto-actual.png') }}" alt="Punto Actual" class="h-7 w-auto sm:h-9" width="1668" height="353">
            </a>

            <nav class="hidden items-center gap-7 text-sm font-semibold text-zinc-600 md:flex">
                <a href="{{ route('news.index') }}" class="transition hover:text-brand-700">Noticias</a>
                <a href="{{ route('news.international') }}" class="transition hover:text-brand-700">Internacional</a>
                <a href="{{ route('vacancies.index') }}" class="transition hover:text-brand-700">Empleos</a>
                <a href="{{ route('home') }}#regiones" class="transition hover:text-brand-700">Ciudades</a>
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
                <a href="{{ route('news.index') }}" class="whitespace-nowrap text-brand-600 transition hover:text-brand-700">Actualidad</a>
                @foreach([
                    'Política' => 'politica',
                    'Economía' => 'economia',
                    'Sociedad' => 'sociedad',
                    'Educación' => 'educacion',
                    'Salud' => 'salud',
                    'Tecnología' => 'tecnologia',
                    'Regiones' => 'regiones',
                    'Deportes' => 'deportes',
                    'Internacional' => 'internacional',
                ] as $topic => $slug)
                    <a href="{{ route('categories.show', $slug) }}" class="whitespace-nowrap transition hover:text-brand-700">{{ $topic }}</a>
                @endforeach
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-black/10 bg-white">
        <div class="container-page grid gap-10 py-14 md:grid-cols-2 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
            <div>
                <img src="{{ asset('images/logo-punto-actual.png') }}" alt="Punto Actual" class="h-9 w-auto" width="1668" height="353">
                <p class="mt-4 max-w-sm text-sm leading-relaxed text-zinc-600">Noticias y oportunidades laborales de todo el Perú, en un solo lugar. Información verificada y enlaces a las fuentes oficiales.</p>
                <div class="mt-5">@include('partials.social')</div>

                <form method="post" action="{{ route('newsletter.store') }}" class="mt-6 max-w-sm">
                    @csrf
                    <label for="newsletter-email" class="text-xs font-bold uppercase tracking-wider text-zinc-500">Recibe lo más importante</label>
                    <div class="mt-2 flex gap-2">
                        <input id="newsletter-email" type="email" name="email" required placeholder="tu@correo.com" class="input !py-2.5">
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                        <button class="btn-primary !px-4 !py-2.5">Suscribirme</button>
                    </div>
                    @if(session('newsletter_status'))
                        <p class="mt-2 text-xs font-semibold text-emerald-600">{{ session('newsletter_status') }}</p>
                    @endif
                    @error('email')<p class="mt-2 text-xs font-semibold text-brand-600">{{ $message }}</p>@enderror
                    <p class="mt-2 text-[11px] leading-relaxed text-zinc-500">Al suscribirte aceptas nuestra <a href="{{ route('pages.privacidad') }}" class="font-semibold text-brand-600 underline underline-offset-4">política de privacidad</a>.</p>
                </form>
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
                <div class="mt-4 border-t border-black/5 pt-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-zinc-400">Ciudades</p>
                    <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-xs text-zinc-600">
                        @foreach(\App\Models\News::published()->whereNotNull('city')->where('city', '!=', '')->select('city')->distinct()->orderBy('city')->pluck('city')->take(8) as $footerCity)
                            <a href="{{ route('news.city', \Illuminate\Support\Str::slug($footerCity)) }}" class="transition hover:text-brand-700">{{ $footerCity }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-ink-950">Institucional</h3>
                <div class="mt-4 grid gap-2 text-sm text-zinc-600">
                    <a href="{{ route('pages.nosotros') }}" class="transition hover:text-brand-700">Sobre nosotros</a>
                    <a href="{{ route('pages.nosotros') }}#fundadores" class="transition hover:text-brand-700">Fundadores</a>
                    <a href="{{ route('pages.editorial') }}" class="transition hover:text-brand-700">Política editorial</a>
                    <a href="{{ route('pages.contacto') }}" class="transition hover:text-brand-700">Contacto</a>
                    <a href="{{ route('pages.privacidad') }}" class="transition hover:text-brand-700">Política y privacidad</a>
                    <a href="{{ route('pages.terminos') }}" class="transition hover:text-brand-700">Términos y condiciones</a>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-ink-950">Transparencia</h3>
                <p class="mt-4 text-sm leading-relaxed text-zinc-600">Punto Actual es un portal informativo independiente. Verifica siempre las bases y enlaces de cada convocatoria en la entidad convocante.</p>
            </div>
        </div>
        <div class="border-t border-black/5 py-5 text-center text-xs text-zinc-500">
            © {{ date('Y') }} {{ config('puntoactual.site.name') }}. Todos los derechos reservados.
            <span class="mx-1 text-zinc-300">·</span>
            <a href="{{ route('pages.privacidad') }}" class="font-semibold transition hover:text-brand-700">Privacidad</a>
            <span class="mx-1 text-zinc-300">·</span>
            <a href="#" onclick="document.cookie='pa_consent=; path=/; max-age=0; SameSite=Lax'; window.location.reload(); return false;" class="font-semibold transition hover:text-brand-700">Preferencias de cookies</a>
        </div>
    </footer>

    @include('partials.cookie-consent')

    @livewireScripts
</body>
</html>
