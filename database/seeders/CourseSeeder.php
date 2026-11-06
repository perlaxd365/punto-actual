<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Entity;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            'SENATI' => ['slug' => 'senati', 'website' => 'https://www.senati.edu.pe', 'region' => 'Nacional', 'description' => 'Servicio Nacional de Adiestramiento en Trabajo Industrial. Formación técnica y tecnológica para el sector productivo del Perú.'],
            'SENCICO' => ['slug' => 'sencico', 'website' => 'https://www.sencico.gob.pe', 'region' => 'Nacional', 'description' => 'Servicio Nacional de Capacitación para la Industria de la Construcción. Formación técnica vinculada a la construcción.'],
            'CIBERTEC' => ['slug' => 'cibertec', 'website' => 'https://www.cibertec.edu.pe', 'region' => 'Lima', 'description' => 'Institución de educación superior con programas de tecnología, negocios y ofimática.'],
            'IDAT' => ['slug' => 'idat', 'website' => 'https://www.idat.edu.pe', 'region' => 'Lima', 'description' => 'Instituto de diseño y tecnología con carreras y cursos orientados a la industria digital.'],
            'TECSUP' => ['slug' => 'tecsup', 'website' => 'https://www.tecsup.edu.pe', 'region' => 'Nacional', 'description' => 'Institución de formación tecnológica con programas de ingeniería aplicada y tecnología digital.'],
            'CERTUS' => ['slug' => 'certus', 'website' => 'https://www.certus.edu.pe', 'region' => 'Lima', 'description' => 'Institución de educación superior enfocada en negocios, contabilidad y administración.'],
            'ZEGEL IPAE' => ['slug' => 'zegel-ipae', 'website' => 'https://www.zegelipae.edu.pe', 'region' => 'Lima', 'description' => 'Institución de educación superior con programas de negocios, administración y emprendimiento.'],
            'ISIL' => ['slug' => 'isil', 'website' => 'https://www.isil.pe', 'region' => 'Lima', 'description' => 'Institución de educación superior con carreras y cursos de negocios, marketing y comunicación.'],
            'Toulouse Lautrec' => ['slug' => 'toulouse-lautrec', 'website' => 'https://www.toulouselautrec.edu.pe', 'region' => 'Lima', 'description' => 'Escuela de diseño, comunicación y creatividad con formación orientada a la industria creativa.'],
            'PUCP' => ['slug' => 'pucp', 'website' => 'https://www.pucp.edu.pe', 'region' => 'Lima', 'description' => 'Pontificia Universidad Católica del Perú. Formación académica y programas de especialización.'],
            'ESAN' => ['slug' => 'esan', 'website' => 'https://www.esan.edu.pe', 'region' => 'Lima', 'description' => 'Escuela de administración de negocios con programas de gestión y especialización profesional.'],
            'ICPNA' => ['slug' => 'icpna', 'website' => 'https://www.icpna.edu.pe', 'region' => 'Nacional', 'description' => 'Instituto Cultural Peruano Norteamericano. Enseñanza del idioma inglés en el Perú.'],
            'Británico' => ['slug' => 'britanico', 'website' => 'https://www.britanico.edu.pe', 'region' => 'Nacional', 'description' => 'Asociación Cultural Peruano Británica. Enseñanza del idioma inglés y programas académicos.'],
            "D'Gallia" => ['slug' => 'dgallia', 'website' => 'https://www.dgallia.edu.pe', 'region' => 'Lima', 'description' => 'Instituto de gastronomía con formación en cocina, repostería y gestión culinaria.'],
            'IPP' => ['slug' => 'ipp', 'website' => 'https://www.ipp.edu.pe', 'region' => 'Lima', 'description' => 'Instituto Peruano de Publicidad. Formación en comunicación, marketing y creatividad.'],
        ];

        $entities = [];

        foreach ($providers as $name => $data) {
            $logo = asset('images/entities/'.$data['slug'].'.png');

            $entities[$name] = Entity::firstOrCreate(
                ['name' => $name],
                [
                    'slug' => $data['slug'],
                    'entity_type' => 'Institución educativa',
                    'website' => $data['website'],
                    'region' => $data['region'],
                    'description' => $data['description'],
                    'logo' => $logo,
                    'cover_image' => $logo,
                ]
            );

            $entities[$name]->updateQuietly([
                'logo' => $logo,
                'cover_image' => $logo,
            ]);
        }

        $courses = [
            ['slug' => 'excel-basico-a-avanzado', 'title' => 'Excel de básico a avanzado', 'provider' => 'CIBERTEC', 'area' => 'Ofimática', 'modality' => 'Online y presencial', 'level' => 'Básico a avanzado', 'duration' => '3 a 6 meses (referencial)', 'excerpt' => 'Domina hojas de cálculo, fórmulas, tablas dinámicas y análisis de datos con Excel.', 'description' => "Excel es una de las herramientas más solicitadas en el mercado laboral peruano. Este curso te ayuda a pasar de lo básico a funciones avanzadas, tablas dinámicas, gráficos y análisis de datos para el trabajo diario.\n\nIdeal para estudiantes, asistentes administrativos, contadores y profesionales que necesitan organizar y analizar información con eficiencia."],
            ['slug' => 'programacion-web-full-stack', 'title' => 'Programación web full stack', 'provider' => 'IDAT', 'area' => 'Tecnología', 'modality' => 'Online', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Aprende frontend y backend para crear aplicaciones web completas y funcionales.', 'description' => "La programación web sigue siendo una de las habilidades más buscadas por las empresas. Este programa combina HTML, CSS, JavaScript y tecnologías de backend para formar desarrolladores capaces de construir aplicaciones completas.\n\nEs una buena ruta de entrada al sector tecnológico para quienes buscan reconvertirse profesionalmente."],
            ['slug' => 'python-analisis-de-datos', 'title' => 'Python para análisis de datos', 'provider' => 'TECSUP', 'area' => 'Tecnología', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => '40 a 80 horas (referencial)', 'excerpt' => 'Usa Python para limpiar, procesar y analizar datos con librerías especializadas.', 'description' => "Python es el lenguaje más usado para ciencia de datos y automatización. Este curso introduce el análisis de datos con librerías como Pandas y visualización de resultados para la toma de decisiones.\n\nRecomendado para profesionales de tecnología, negocios y áreas analíticas que quieran fortalecer su perfil."],
            ['slug' => 'power-bi-visualizacion-de-datos', 'title' => 'Power BI y visualización de datos', 'provider' => 'CIBERTEC', 'area' => 'Datos', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => '40 a 60 horas (referencial)', 'excerpt' => 'Crea dashboards e informes interactivos para comunicar datos de negocio.', 'description' => "Power BI permite transformar datos en informes visuales útiles para la gestión. Este curso trabaja modelado de datos, medidas, dashboards y buenas prácticas de visualización.\n\nEs una habilidad valorada en áreas de administración, finanzas, marketing y operaciones."],
            ['slug' => 'ciberseguridad-fundamentos', 'title' => 'Ciberseguridad: fundamentos y buenas prácticas', 'provider' => 'TECSUP', 'area' => 'Tecnología', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Comprende amenazas, protección de sistemas y buenas prácticas de seguridad digital.', 'description' => "La ciberseguridad se volvió prioritaria para empresas e instituciones. Este programa presenta los fundamentos de seguridad, gestión de riesgos, protección de redes y respuesta ante incidentes.\n\nOrientado a profesionales de TI y personas interesadas en especializarse en un área con alta demanda."],
            ['slug' => 'inteligencia-artificial-aplicada', 'title' => 'Inteligencia artificial aplicada', 'provider' => 'PUCP', 'area' => 'Tecnología', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Explora aplicaciones de inteligencia artificial y su impacto en los negocios.', 'description' => "La inteligencia artificial está transformando industrias y puestos de trabajo. Este programa introduce conceptos clave, casos de uso y herramientas para aplicar IA en entornos profesionales.\n\nDirigido a profesionales que quieren entender el potencial de la IA y aprovecharla en sus organizaciones."],
            ['slug' => 'cloud-computing-aws', 'title' => 'Cloud computing con AWS', 'provider' => 'TECSUP', 'area' => 'Tecnología', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => '40 a 80 horas (referencial)', 'excerpt' => 'Aprende los fundamentos de la nube y los servicios principales de AWS.', 'description' => "Los servicios en la nube son la base de gran parte de la infraestructura digital actual. Este curso introduce los conceptos de cloud computing y los servicios fundamentales de Amazon Web Services.\n\nRecomendado para profesionales de TI que buscan actualizar sus competencias en infraestructura y despliegue."],
            ['slug' => 'java-desarrollo-de-aplicaciones', 'title' => 'Java para desarrollo de aplicaciones', 'provider' => 'CIBERTEC', 'area' => 'Tecnología', 'modality' => 'Online y presencial', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Aprende programación orientada a objetos y desarrollo de aplicaciones con Java.', 'description' => "Java sigue siendo un lenguaje central en sistemas empresariales y aplicaciones de gran escala. Este curso desarrolla lógica de programación, orientación a objetos y construcción de aplicaciones.\n\nEs una alternativa sólida para quienes buscan empleo en desarrollo de software."],
            ['slug' => 'diseno-ux-ui', 'title' => 'Diseño UX/UI', 'provider' => 'Toulouse Lautrec', 'area' => 'Diseño', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Diseña experiencias digitales centradas en el usuario y prototipos interactivos.', 'description' => "El diseño UX/UI combina investigación de usuarios, arquitectura de información y diseño de interfaces. Este programa enseña a crear experiencias digitales claras y funcionales.\n\nIdeal para diseñadores, comunicadores y profesionales de producto que quieran especializarse."],
            ['slug' => 'diseno-grafico-digital', 'title' => 'Diseño gráfico digital', 'provider' => 'IDAT', 'area' => 'Diseño', 'modality' => 'Online', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Desarrolla piezas gráficas para medios digitales e impresos con criterio visual.', 'description' => "El diseño gráfico es una habilidad versátil para redes sociales, publicidad y comunicación corporativa. Este programa trabaja composición, tipografía, color y herramientas digitales.\n\nRecomendado para quienes quieren crear contenido visual profesional o iniciar un emprendimiento creativo."],
            ['slug' => 'fotografia-profesional', 'title' => 'Fotografía profesional', 'provider' => 'Toulouse Lautrec', 'area' => 'Diseño', 'modality' => 'Presencial', 'level' => 'Básico a avanzado', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Aprende manejo de cámara, iluminación y edición para proyectos fotográficos.', 'description' => "La fotografía profesional tiene salida en publicidad, productos, eventos y medios. Este curso cubre técnica, iluminación, composición y edición de imágenes.\n\nApropiado para quienes buscan dominar la imagen como herramienta creativa y comercial."],
            ['slug' => 'photoshop-e-illustrator', 'title' => 'Adobe Photoshop e Illustrator', 'provider' => 'IDAT', 'area' => 'Diseño', 'modality' => 'Online', 'level' => 'Básico a avanzado', 'duration' => '40 a 80 horas (referencial)', 'excerpt' => 'Domina las herramientas clave de edición de imágenes y diseño vectorial.', 'description' => "Photoshop e Illustrator son estándares en diseño digital. Este curso enseña edición de imágenes, retoque, composición y creación de piezas vectoriales para distintos soportes.\n\nÚtil para diseñadores, community managers y emprendedores que producen contenido propio."],
            ['slug' => 'ingles-general', 'title' => 'Inglés general', 'provider' => 'ICPNA', 'area' => 'Idiomas', 'modality' => 'Presencial y online', 'level' => 'Básico a avanzado', 'duration' => 'Según niveles (referencial)', 'excerpt' => 'Desarrolla comprensión y expresión en inglés para el trabajo y la vida diaria.', 'description' => "El inglés es una de las competencias más valoradas en el mercado laboral peruano. Este programa desarrolla comprensión auditiva, lectura, escritura y conversación por niveles.\n\nRecomendado para estudiantes y profesionales que buscan ampliar sus oportunidades académicas y laborales."],
            ['slug' => 'ingles-para-negocios', 'title' => 'Inglés para negocios', 'provider' => 'Británico', 'area' => 'Idiomas', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Comunícate con seguridad en reuniones, correos y entornos corporativos.', 'description' => "El inglés de negocios permite comunicarse con clientes, equipos internacionales y socios comerciales. Este curso aborda vocabulario corporativo, correos, presentaciones y conversaciones profesionales.\n\nDirigido a profesionales con base de inglés que buscan desenvolverse en entornos globales."],
            ['slug' => 'marketing-digital', 'title' => 'Marketing digital', 'provider' => 'ISIL', 'area' => 'Marketing', 'modality' => 'Online', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Planifica estrategias digitales, contenidos y campañas para atraer clientes.', 'description' => "El marketing digital es clave para empresas de todos los tamaños. Este programa cubre estrategia, redes sociales, publicidad digital, contenido y medición de resultados.\n\nRecomendado para emprendedores, comunicadores y profesionales que gestionan marcas o negocios."],
            ['slug' => 'community-manager-redes-sociales', 'title' => 'Community manager y redes sociales', 'provider' => 'ISIL', 'area' => 'Marketing', 'modality' => 'Online', 'level' => 'Básico', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Gestiona comunidades digitales y crea contenido para redes sociales.', 'description' => "Las redes sociales son el principal canal de comunicación de muchas marcas. Este curso enseña a planificar contenido, responder a comunidades y analizar el desempeño de las publicaciones.\n\nUna buena opción para quienes quieren trabajar en comunicación digital o gestionar sus propios proyectos."],
            ['slug' => 'contabilidad-general', 'title' => 'Contabilidad general', 'provider' => 'CERTUS', 'area' => 'Negocios', 'modality' => 'Presencial y online', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Aprende registro contable, estados financieros y obligaciones tributarias básicas.', 'description' => "La contabilidad es una competencia transversal en empresas y organizaciones. Este curso desarrolla el registro de operaciones, elaboración de estados financieros y conceptos tributarios básicos.\n\nDirigido a quienes buscan empleo administrativo o necesitan ordenar las finanzas de un negocio."],
            ['slug' => 'administracion-de-empresas', 'title' => 'Administración de empresas', 'provider' => 'ZEGEL IPAE', 'area' => 'Negocios', 'modality' => 'Presencial y online', 'level' => 'Básico a avanzado', 'duration' => 'Programa de especialización (referencial)', 'excerpt' => 'Fortalece la gestión de equipos, procesos y recursos en una organización.', 'description' => "La administración de empresas permite coordinar personas, procesos y recursos para alcanzar objetivos. Este programa aborda planificación, organización, dirección y control.\n\nRecomendado para mandos medios, emprendedores y profesionales que asumen responsabilidades de gestión."],
            ['slug' => 'recursos-humanos', 'title' => 'Gestión de recursos humanos', 'provider' => 'ESAN', 'area' => 'Negocios', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Aprende selección, gestión del talento y clima laboral en las organizaciones.', 'description' => "La gestión del talento es central para el desempeño de las organizaciones. Este curso trabaja selección, evaluación, desarrollo y clima laboral con enfoque estratégico.\n\nOrientado a profesionales de administración, psicología y áreas de personal."],
            ['slug' => 'logistica-y-almacen', 'title' => 'Logística y almacén', 'provider' => 'ZEGEL IPAE', 'area' => 'Negocios', 'modality' => 'Presencial', 'level' => 'Básico', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Gestiona inventarios, almacenes y procesos logísticos con eficiencia.', 'description' => "La logística conecta el abastecimiento con la entrega al cliente. Este curso cubre gestión de almacenes, inventarios, control de stock y procesos de distribución.\n\nRecomendado para personas que trabajan o buscan empleo en comercio, almacenes y operaciones."],
            ['slug' => 'ventas-y-atencion-al-cliente', 'title' => 'Ventas y atención al cliente', 'provider' => 'CERTUS', 'area' => 'Negocios', 'modality' => 'Presencial', 'level' => 'Básico', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Desarrolla técnicas de venta, comunicación y servicio al cliente.', 'description' => "La atención al cliente y las ventas son habilidades valoradas en casi todos los sectores. Este curso trabaja comunicación, manejo de objeciones, negociación y servicio.\n\nÚtil para quienes buscan mejorar su desempeño comercial o ingresar a áreas de atención al público."],
            ['slug' => 'seguridad-y-salud-en-el-trabajo', 'title' => 'Seguridad y salud en el trabajo', 'provider' => 'ISIL', 'area' => 'Negocios', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Aprende prevención de riesgos laborales y cumplimiento de la normativa peruana.', 'description' => "La seguridad y salud en el trabajo es una exigencia legal y una prioridad en las empresas. Este curso introduce la identificación de riesgos, prevención y cultura de seguridad.\n\nDirigido a supervisores, trabajadores y profesionales responsables de seguridad laboral."],
            ['slug' => 'finanzas-para-no-financieros', 'title' => 'Finanzas para no financieros', 'provider' => 'ESAN', 'area' => 'Negocios', 'modality' => 'Online', 'level' => 'Intermedio', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Interpreta estados financieros y toma mejores decisiones de negocio.', 'description' => "Comprender las finanzas permite tomar decisiones más acertadas. Este curso explica estados financieros, costos, presupuestos e indicadores clave para profesionales de distintas áreas.\n\nRecomendado para gerentes, emprendedores y profesionales que necesitan conversar con áreas financieras."],
            ['slug' => 'oratoria-y-locucion', 'title' => 'Oratoria y locución', 'provider' => 'IPP', 'area' => 'Comunicación', 'modality' => 'Presencial y online', 'level' => 'Básico', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Mejora tu expresión oral, presencia y seguridad al hablar en público.', 'description' => "La oratoria y la locución ayudan a comunicar con claridad y persuasión. Este curso trabaja voz, dicción, lenguaje corporal y estructura de discursos y presentaciones.\n\nIdeal para profesionales, voceros, docentes y personas que necesitan hablar en público."],
            ['slug' => 'electricidad-industrial', 'title' => 'Electricidad industrial', 'provider' => 'SENATI', 'area' => 'Oficios', 'modality' => 'Presencial', 'level' => 'Básico a avanzado', 'duration' => '1 a 2 años (referencial)', 'excerpt' => 'Formación técnica en instalaciones eléctricas y mantenimiento industrial.', 'description' => "La electricidad industrial tiene alta demanda en minería, manufactura y mantenimiento. Este programa forma en instalaciones eléctricas, motores, control y seguridad.\n\nOrientado a quienes buscan una carrera técnica con salida laboral directa."],
            ['slug' => 'mecanica-automotriz', 'title' => 'Mecánica automotriz', 'provider' => 'SENATI', 'area' => 'Oficios', 'modality' => 'Presencial', 'level' => 'Básico a avanzado', 'duration' => '1 a 2 años (referencial)', 'excerpt' => 'Aprende diagnóstico, mantenimiento y reparación de vehículos.', 'description' => "La mecánica automotriz combina conocimiento técnico y trabajo práctico. Este programa cubre motores, sistemas de frenos, suspensión, electricidad y diagnóstico.\n\nRecomendado para quienes desean trabajar en talleres, concesionarios o emprender un servicio propio."],
            ['slug' => 'soldadura-industrial', 'title' => 'Soldadura industrial', 'provider' => 'SENATI', 'area' => 'Oficios', 'modality' => 'Presencial', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Capacítate en procesos de soldadura y fabricación metálica.', 'description' => "La soldadura es una habilidad técnica requerida en construcción, metalmecánica e industria. Este curso trabaja procesos, materiales, seguridad y control de calidad.\n\nUna opción de formación técnica con rápida inserción en el sector productivo."],
            ['slug' => 'autocad-y-dibujo-tecnico', 'title' => 'AutoCAD y dibujo técnico', 'provider' => 'SENCICO', 'area' => 'Oficios', 'modality' => 'Presencial y online', 'level' => 'Básico a avanzado', 'duration' => '6 a 12 meses (referencial)', 'excerpt' => 'Elabora planos y diseños técnicos con AutoCAD para construcción e ingeniería.', 'description' => "AutoCAD es una herramienta estándar en arquitectura, ingeniería y construcción. Este curso enseña dibujo técnico, planos, vistas y representación de proyectos.\n\nDirigido a estudiantes y profesionales vinculados a construcción, diseño e ingeniería."],
            ['slug' => 'gastronomia-y-cocina', 'title' => 'Gastronomía y cocina', 'provider' => "D'Gallia", 'area' => 'Gastronomía', 'modality' => 'Presencial', 'level' => 'Básico a avanzado', 'duration' => '1 a 2 años (referencial)', 'excerpt' => 'Formación en técnicas culinarias, higiene y gestión de cocina.', 'description' => "La gastronomía peruana es reconocida dentro y fuera del país. Este programa forma en técnicas de cocina, preparación de recetas, higiene y organización de cocina.\n\nRecomendado para quienes buscan trabajar en restaurantes, hoteles o emprender en el rubro gastronómico."],
            ['slug' => 'reposteria', 'title' => 'Repostería', 'provider' => "D'Gallia", 'area' => 'Gastronomía', 'modality' => 'Presencial', 'level' => 'Básico', 'duration' => 'Programa corto (referencial)', 'excerpt' => 'Aprende técnicas de repostería, decoración y producción de postres.', 'description' => "La repostería es una actividad con demanda en panaderías, cafeterías y emprendimientos. Este curso cubre masas, cremas, decoración y buenas prácticas de producción.\n\nIdeal para quienes desean iniciar un negocio de postres o trabajar en el sector gastronómico."],
        ];

        $featured = [
            'excel-basico-a-avanzado',
            'ingles-general',
            'programacion-web-full-stack',
            'marketing-digital',
            'contabilidad-general',
            'inteligencia-artificial-aplicada',
        ];

        foreach ($courses as $index => $data) {
            $provider = $entities[$data['provider']] ?? null;
            unset($data['provider']);

            Course::firstOrCreate(
                ['slug' => $data['slug']],
                $data + [
                    'entity_id' => $provider?->id,
                    'image' => asset('images/courses/'.$data['slug'].'.jpg'),
                    'official_url' => $provider?->website ?? 'https://www.gob.pe/',
                    'status' => 'published',
                    'is_featured' => in_array($data['slug'], $featured, true),
                    'published_at' => now()->subDays($index),
                ]
            );
        }

        $sampleByArea = [
            'Ofimática' => 'news-1.jpg',
            'Tecnología' => 'cover-1.jpg',
            'Datos' => 'cover-1.jpg',
            'Diseño' => 'cover-2.jpg',
            'Idiomas' => 'cover-3.jpg',
            'Marketing' => 'cover-4.jpg',
            'Negocios' => 'cover-4.jpg',
            'Comunicación' => 'cover-2.jpg',
            'Oficios' => 'vacancy-1.jpg',
            'Gastronomía' => 'entity-cover-1.jpg',
        ];

        foreach (Course::all() as $course) {
            $path = 'images/courses/'.$course->slug.'.jpg';
            $image = is_file(public_path($path))
                ? asset($path)
                : asset('images/samples/'.($sampleByArea[$course->area] ?? 'cover-1.jpg'));

            $course->updateQuietly(['image' => $image]);
        }
    }
}
