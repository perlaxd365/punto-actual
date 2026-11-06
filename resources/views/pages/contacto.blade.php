@extends('layouts.app')

@section('title', 'Contacto | Punto Actual')
@section('description', 'Escríbenos para consultas, correcciones, sugerencias o alianzas. Canales oficiales de contacto de Punto Actual.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-[#f4f1ee]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.14),transparent_65%)]"></div>
    <div class="container-page relative py-14 sm:py-20">
        <p class="section-label">Institucional</p>
        <h1 class="mt-2 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Contacto</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">Consultas, correcciones, sugerencias o propuestas de colaboración. Respondemos por nuestros canales oficiales.</p>
    </div>
</div>

<section class="container-page py-14 sm:py-16">
    <div class="grid gap-8 lg:grid-cols-[minmax(0,7fr)_minmax(0,3fr)]">
        <div class="space-y-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <a href="mailto:{{ config('puntoactual.site.email') }}" class="card p-6 transition hover:border-brand-500/40 hover:-translate-y-0.5">
                    <p class="section-label">Correo</p>
                    <p class="mt-2 break-all text-base font-bold text-ink-950">{{ config('puntoactual.site.email') }}</p>
                    <p class="mt-1 text-sm text-zinc-600">Consultas, correcciones y prensa.</p>
                </a>
                <a href="https://wa.me/{{ ltrim(config('puntoactual.site.phone'), '+') }}" target="_blank" rel="noopener" class="card p-6 transition hover:border-brand-500/40 hover:-translate-y-0.5">
                    <p class="section-label">WhatsApp</p>
                    <p class="mt-2 text-base font-bold text-ink-950">{{ config('puntoactual.site.phone_display') }}</p>
                    <p class="mt-1 text-sm text-zinc-600">Atención directa en horario laboral.</p>
                </a>
            </div>

            <div class="card p-6">
                <p class="section-label">Oficina</p>
                <p class="mt-2 text-base font-semibold text-ink-950">{{ config('puntoactual.site.address') }}</p>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600">Si deseas enviar documentación, comunicados o propuestas, escríbenos primero por correo para coordinar la recepción.</p>
            </div>

            <div class="card p-6">
                <p class="section-label">Antes de escribir</p>
                <div class="mt-3 grid gap-3 text-sm leading-relaxed text-zinc-600">
                    <p><strong class="text-ink-950">Correcciones:</strong> indica el enlace de la nota, el dato a corregir y la fuente que lo sustenta.</p>
                    <p><strong class="text-ink-950">Convocatorias:</strong> si detectas que una convocatoria venció o cambió, compártenos el enlace oficial para actualizarla.</p>
                    <p><strong class="text-ink-950">Postulaciones:</strong> Punto Actual no gestiona postulaciones; debes aplicar en el portal oficial de la entidad convocante.</p>
                </div>
            </div>
        </div>

        <aside class="space-y-6 self-start lg:sticky lg:top-24">
            <div class="card p-5">
                <p class="section-label">Institucional</p>
                <nav class="mt-3 grid gap-2 text-sm font-semibold text-zinc-600">
                    <a href="{{ route('pages.nosotros') }}" class="transition hover:text-brand-700">Sobre nosotros</a>
                    <a href="{{ route('pages.editorial') }}" class="transition hover:text-brand-700">Política editorial</a>
                    <a href="{{ route('pages.privacidad') }}" class="transition hover:text-brand-700">Política y privacidad</a>
                    <a href="{{ route('pages.terminos') }}" class="transition hover:text-brand-700">Términos y condiciones</a>
                </nav>
            </div>
        </aside>
    </div>
</section>
@endsection
