@extends('layouts.app')

@section('title', 'Política y privacidad | Punto Actual')
@section('description', 'Política de privacidad de Punto Actual: qué datos recopilamos, cómo los usamos, cookies, terceros y tus derechos.')

@section('content')
<div class="relative overflow-hidden border-b border-black/10 bg-[#f4f1ee]">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(700px_320px_at_85%_0%,rgba(225,29,46,0.14),transparent_65%)]"></div>
    <div class="container-page relative py-14 sm:py-20">
        <p class="section-label">Institucional</p>
        <h1 class="mt-2 max-w-3xl text-4xl font-black tracking-tight sm:text-5xl">Política y privacidad</h1>
        <p class="mt-4 max-w-2xl text-lg leading-relaxed text-zinc-600">Tu privacidad es importante para nosotros. Aquí explicamos qué información tratamos y cómo la protegemos.</p>
        <p class="mt-3 text-xs font-semibold uppercase tracking-wider text-zinc-500">Última actualización: {{ date('d/m/Y') }}</p>
    </div>
</div>

<section class="container-page py-14 sm:py-16">
    <div class="mx-auto max-w-3xl space-y-10 text-base leading-relaxed text-zinc-600">
        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">1. Responsable del tratamiento</h2>
            <p class="mt-3">Punto Actual es un portal informativo independiente operado en el Perú. El tratamiento de datos se realiza conforme a la Ley N.° 29733, Ley de Protección de Datos Personales, y su reglamento.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">2. Datos que recopilamos</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5">
                <li><strong class="text-ink-950">Datos de navegación:</strong> páginas visitadas, tipo de dispositivo, navegador, idioma y dirección IP aproximada.</li>
                <li><strong class="text-ink-950">Datos que tú nos brindas:</strong> únicamente si decides escribirnos o registrarte en algún servicio habilitado.</li>
                <li><strong class="text-ink-950">Cookies y tecnologías similares:</strong> para recordar preferencias y medir el uso del sitio.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">3. Finalidad del tratamiento</h2>
            <ul class="mt-3 list-disc space-y-2 pl-5">
                <li>Mostrar y mejorar el contenido del portal.</li>
                <li>Medir audiencia y rendimiento para tomar decisiones editoriales.</li>
                <li>Atender consultas, sugerencias o solicitudes de los usuarios.</li>
                <li>Cumplir obligaciones legales cuando corresponda.</li>
            </ul>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">4. Cookies y analítica</h2>
            <p class="mt-3">Usamos herramientas de analítica (como Google Analytics, Google Tag Manager y Microsoft Clarity) para entender cómo se usa el sitio. Estas herramientas pueden establecer cookies propias. Puedes desactivar o eliminar las cookies desde la configuración de tu navegador; algunas funciones podrían dejar de funcionar correctamente.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">5. Terceros y encargados</h2>
            <p class="mt-3">Para operar el portal nos apoyamos en proveedores tecnológicos, entre ellos servicios de alojamiento, distribución de imágenes (Cloudinary) y analítica. Estos proveedores tratan datos por cuenta nuestra y bajo sus propias políticas de seguridad. No vendemos datos personales.</p>
            <p class="mt-3">El sitio contiene enlaces a portales oficiales y medios de terceros. No somos responsables de las políticas de privacidad de esos sitios; te recomendamos revisarlas antes de proporcionar tus datos.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">6. Conservación y seguridad</h2>
            <p class="mt-3">Conservamos la información solo durante el tiempo necesario para las finalidades descritas. Aplicamos medidas técnicas y organizativas razonables para protegerla frente a accesos no autorizados, pérdida o alteración.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">7. Tus derechos</h2>
            <p class="mt-3">Puedes solicitar el acceso, rectificación, cancelación u oposición al tratamiento de tus datos personales (derechos ARCO), así como revocar tu consentimiento, escribiendo a nuestros canales oficiales de contacto indicados en el sitio. Atenderemos tu solicitud en los plazos que establece la ley.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">8. Menores de edad</h2>
            <p class="mt-3">El portal está dirigido al público general. No recopilamos intencionalmente datos personales de menores de edad sin el consentimiento de sus padres o tutores.</p>
        </div>

        <div>
            <h2 class="text-2xl font-black tracking-tight text-ink-950">9. Cambios en esta política</h2>
            <p class="mt-3">Podemos actualizar esta política para reflejar cambios legales u operativos. Publicaremos la versión vigente en esta página con su fecha de actualización.</p>
        </div>

        <div class="rounded-2xl border border-brand-500/20 bg-brand-500/[0.06] p-5 text-sm leading-relaxed text-brand-700">
            Si tienes dudas sobre esta política o sobre el tratamiento de tus datos, escríbenos a través de nuestros canales oficiales. También puedes consultar nuestros <a href="{{ route('pages.terminos') }}" class="font-bold underline underline-offset-4">términos y condiciones</a>.
        </div>
    </div>
</section>
@endsection
