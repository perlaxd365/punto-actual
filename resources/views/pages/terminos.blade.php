@extends('layouts.app')

@section('title', 'Términos y condiciones | Punto Actual')
@section('description', 'Términos y condiciones de uso del portal Punto Actual: contenido informativo, convocatorias, propiedad intelectual y responsabilidad.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-[#f4f1ee]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.14),transparent_65%)]"></div>
    <div class="container-page relative py-14 sm:py-20">
        <p class="section-label">Institucional</p>
        <h1 class="mt-2 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Términos y condiciones</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">Las reglas de uso del portal y el alcance de la información que publicamos.</p>
        <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-zinc-500">Última actualización: {{ date('d/m/Y') }}</p>
    </div>
</div>

<section class="container-page py-14 sm:py-16">
    <div class="mx-auto max-w-3xl space-y-10 text-base leading-relaxed text-zinc-600">
        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">1. Aceptación</h2>
            <p class="mt-3">Al acceder y usar Punto Actual aceptas estos términos. Si no estás de acuerdo, te pedimos no utilizar el sitio.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">2. Contenido informativo</h2>
            <p class="mt-3">Las noticias y convocatorias publicadas tienen carácter informativo. Punto Actual no es una entidad pública ni una agencia de empleo: no gestiona postulaciones, no recibe documentos y no interviene en los procesos de selección.</p>
            <p class="mt-3">La información de cada convocatoria (requisitos, remuneración, fechas y bases) puede cambiar por decisión de la entidad convocante. Verifica siempre los datos en el portal oficial antes de postular.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">3. Exactitud y vigencia</h2>
            <p class="mt-3">Procuramos publicar información actualizada y atribuida a su fuente. Sin embargo, no garantizamos que todo el contenido esté libre de errores ni que las convocatorias sigan vigentes después de su fecha de cierre. Las convocatorias vencidas no se presentan como vigentes.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">4. Propiedad intelectual</h2>
            <p class="mt-3">La marca, el diseño y los contenidos originales de Punto Actual están protegidos por la normativa de propiedad intelectual. Los resúmenes de noticias se elaboran con fines informativos y citan la fuente. No está permitido reproducir el contenido del portal con fines comerciales sin autorización.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">5. Enlaces a terceros</h2>
            <p class="mt-3">El sitio incluye enlaces a portales oficiales y medios externos. No controlamos esos sitios ni respondemos por su contenido, disponibilidad o políticas. El acceso a ellos es responsabilidad del usuario.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">6. Uso permitido</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5">
                <li>No usar el sitio para actividades ilícitas o que afecten a terceros.</li>
                <li>No intentar vulnerar la seguridad, disponibilidad o integridad de la plataforma.</li>
                <li>No extraer contenido de forma masiva sin autorización.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">7. Limitación de responsabilidad</h2>
            <p class="mt-3">En la medida permitida por ley, Punto Actual no será responsable por decisiones tomadas a partir de la información publicada, ni por daños derivados del uso del sitio o de la imposibilidad de acceder a él.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">8. Modificaciones</h2>
            <p class="mt-3">Podemos actualizar estos términos en cualquier momento. La versión vigente estará publicada en esta página con su fecha de actualización.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">9. Legislación aplicable</h2>
            <p class="mt-3">Estos términos se rigen por las leyes de la República del Perú. Cualquier controversia se someterá a los tribunales competentes del Perú.</p>
        </div>

        <div class="rounded-2xl border border-brand-500/20 bg-brand-500/[0.06] p-5 text-sm leading-relaxed text-brand-700">
            Para consultas sobre estos términos, revisa nuestra <a href="{{ route('pages.privacidad') }}" class="font-bold underline underline-offset-4">política y privacidad</a> o contáctanos por nuestros canales oficiales.
        </div>
    </div>
</section>
@endsection
