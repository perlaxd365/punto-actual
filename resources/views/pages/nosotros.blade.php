@extends('layouts.app')

@section('title', 'Sobre nosotros y fundadores | Punto Actual')
@section('description', 'Conoce a Punto Actual, el portal peruano de noticias y convocatorias laborales. Fundado por Raúl Baca y Perla García.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-[#f4f1ee]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.16),transparent_65%)]"></div>
    <div class="container-page relative py-14 sm:py-20">
        <p class="section-label">Institucional</p>
        <h1 class="mt-2 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Sobre Punto Actual</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">Somos un portal peruano independiente que reúne noticias verificadas y convocatorias laborales de todo el país, con contexto y enlaces a las fuentes oficiales.</p>
    </div>
</div>

<section class="container-page py-14 sm:py-16">
    <div class="grid gap-10 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <div class="space-y-12">
            <div>
                <p class="section-label">Quiénes somos</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">Información útil, clara y verificable</h2>
                <div class="mt-4 space-y-4 text-base leading-relaxed text-zinc-600">
                    <p>Punto Actual nació con el objetivo de acercar la actualidad nacional y las oportunidades laborales a todos los peruanos, sin ruido y con información contrastada. Publicamos noticias de política, economía, sociedad, regiones, deportes e internacional, además de convocatorias del Estado y del sector privado.</p>
                    <p>Cada convocatoria incluye la carrera o especialidad requerida, requisitos, remuneración, ubicación y fecha de cierre, y siempre enlaza a la fuente oficial para que el postulante verifique las bases antes de aplicar.</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                @foreach([
                    ['Noticias verificadas', 'Contenido con contexto, autoría y enlace a la fuente original.'],
                    ['Convocatorias claras', 'Carrera, requisitos, salario y fecha límite visibles en cada ficha.'],
                    ['Cobertura nacional', 'Noticias y empleos de costa, sierra y selva, actualizados a diario.'],
                ] as [$title, $text])
                    <div class="card p-5">
                        <div class="h-1 w-10 rounded-full bg-brand-500"></div>
                        <h3 class="mt-3 text-base font-bold text-ink-950">{{ $title }}</h3>
                        <p class="mt-1.5 text-sm leading-relaxed text-zinc-600">{{ $text }}</p>
                    </div>
                @endforeach
            </div>

            <div id="fundadores">
                <p class="section-label">Fundadores</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl">Las personas detrás del portal</h2>
                <div class="mt-6 grid gap-5 sm:grid-cols-2">
                    <article class="card p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-500/10 text-xl font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">RB</div>
                            <div>
                                <h3 class="text-lg font-black text-ink-950">Ing. Raúl Baca</h3>
                                <p class="text-sm font-semibold text-brand-600">Fundador · Tecnología y Producto</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-zinc-600">Ingeniero responsable de la plataforma, la experiencia de usuario y la integración de datos del portal. Impulsa el desarrollo tecnológico de Punto Actual.</p>
                    </article>

                    <article class="card p-6">
                        <div class="flex items-center gap-4">
                            <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-brand-500/10 text-xl font-black text-brand-600 ring-1 ring-inset ring-brand-600/15">PG</div>
                            <div>
                                <h3 class="text-lg font-black text-ink-950">Adm. Perla García</h3>
                                <p class="text-sm font-semibold text-brand-600">Fundadora · Administración y Finanzas</p>
                            </div>
                        </div>
                        <p class="mt-4 text-sm leading-relaxed text-zinc-600">Administradora a cargo de la gestión operativa, las finanzas y la sostenibilidad del proyecto, con enfoque en la transparencia y el buen servicio a los lectores.</p>
                    </article>
                </div>
            </div>

            <div class="rounded-3xl border border-brand-500/20 bg-gradient-to-br from-brand-50 via-white to-white p-6 sm:p-8">
                <p class="section-label">Nuestro compromiso</p>
                <h2 class="mt-2 text-2xl font-black tracking-tight">Periodismo útil, sin sensacionalismo</h2>
                <p class="mt-3 text-base leading-relaxed text-zinc-600">Creemos en la información que ayuda a tomar decisiones: entender lo que pasa en el país y encontrar una oportunidad laboral con datos completos. No mostramos convocatorias vencidas como vigentes y siempre atribuimos la fuente.</p>
            </div>
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            <div class="card p-5">
                <p class="section-label">En esta sección</p>
                <nav class="mt-3 grid gap-2 text-sm font-semibold text-zinc-600">
                    <a href="#fundadores" class="transition hover:text-brand-700">Fundadores</a>
                    <a href="{{ route('pages.privacidad') }}" class="transition hover:text-brand-700">Política y privacidad</a>
                    <a href="{{ route('pages.terminos') }}" class="transition hover:text-brand-700">Términos y condiciones</a>
                    <a href="{{ route('news.index') }}" class="transition hover:text-brand-700">Noticias</a>
                    <a href="{{ route('vacancies.index') }}" class="transition hover:text-brand-700">Convocatorias</a>
                </nav>
            </div>
        </aside>
    </div>
</section>
@endsection
