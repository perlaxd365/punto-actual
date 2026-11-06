@extends('layouts.app')

@section('title', 'Política editorial y de correcciones | Punto Actual')
@section('description', 'Principios editoriales de Punto Actual: verificación, fuentes, correcciones, uso de inteligencia artificial y transparencia.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-[#f4f1ee]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.14),transparent_65%)]"></div>
    <div class="container-page relative py-14 sm:py-20">
        <p class="section-label">Institucional</p>
        <h1 class="mt-2 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Política editorial y de correcciones</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">Los criterios con los que seleccionamos, verificamos y corregimos la información que publicamos.</p>
        <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-zinc-500">Última actualización: {{ date('d/m/Y') }}</p>
    </div>
</div>

<section class="container-page py-14 sm:py-16">
    <div class="mx-auto max-w-3xl space-y-10 text-base leading-relaxed text-zinc-600">
        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">1. Nuestra misión</h2>
            <p class="mt-3">Punto Actual informa sobre la actualidad nacional e internacional y sobre convocatorias laborales, con utilidad práctica para el lector. Buscamos información clara, atribuida y verificable, sin sensacionalismo.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">2. Fuentes y verificación</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5">
                <li>Priorizamos fuentes oficiales (entidades públicas, documentos, portales de Estado) y medios de referencia.</li>
                <li>Cada noticia indica su fuente y enlaza al material original cuando corresponde.</li>
                <li>Las convocatorias se contrastan con el portal oficial de la entidad convocante antes de publicarse.</li>
                <li>No presentamos una convocatoria vencida como vigente.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">3. Autoría y responsabilidad</h2>
            <p class="mt-3">Los contenidos se publican bajo la responsabilidad editorial de Punto Actual. Cuando una nota tiene autor identificado, su nombre aparece en la ficha. Las notas de redacción se atribuyen a “Punto Actual”.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">4. Correcciones</h2>
            <p class="mt-3">Si detectamos un error, lo corregimos y dejamos constancia de la actualización mediante la fecha de modificación. Si el error es sustancial, añadimos una nota de corrección visible en la pieza. Para reportar un error puedes escribirnos por nuestros <a href="{{ route('pages.contacto') }}" class="font-semibold text-brand-600 underline underline-offset-4">canales de contacto</a>.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">5. Uso de inteligencia artificial</h2>
            <p class="mt-3">Podemos usar herramientas automatizadas para apoyar tareas de recopilación, resumen o formato. La selección, verificación y decisión de publicación es responsabilidad humana y sigue esta política. No publicamos contenido generado automáticamente sin revisión editorial.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">6. Publicidad y contenido patrocinado</h2>
            <p class="mt-3">La publicidad y el contenido patrocinado, cuando exista, se identifica de forma clara y se mantiene separado de la información editorial. Los anunciantes no intervienen en la cobertura informativa.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">7. Independencia y transparencia</h2>
            <p class="mt-3">Punto Actual es un portal informativo independiente. No recibimos pagos de entidades públicas ni de empresas para publicar convocatorias. Cualquier vínculo comercial se declara cuando corresponda.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">8. Derechos de autor</h2>
            <p class="mt-3">Respetamos los derechos de autor. Cuando usamos material de terceros, citamos la fuente y enlazamos al original. Si consideras que un contenido vulnera tus derechos, contáctanos para revisarlo.</p>
        </div>

        <div class="rounded-2xl border border-brand-500/20 bg-brand-500/[0.06] p-5 text-sm leading-relaxed text-brand-700">
            Esta política complementa nuestra <a href="{{ route('pages.privacidad') }}" class="font-bold underline underline-offset-4">política y privacidad</a> y nuestros <a href="{{ route('pages.terminos') }}" class="font-bold underline underline-offset-4">términos y condiciones</a>.
        </div>
    </div>
</section>
@endsection
