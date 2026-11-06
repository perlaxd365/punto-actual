<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Entity;
use App\Models\News;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@puntoactual.pe'],
            ['name' => 'Administrador Punto Actual', 'password' => Hash::make('Cambiar123!')]
        );

        foreach (['Política', 'Economía', 'Sociedad', 'Educación', 'Salud', 'Tecnología', 'Regiones', 'Deportes', 'Internacional'] as $name) {
            Category::firstOrCreate(['slug' => Str::slug($name)], ['name' => $name, 'type' => 'news']);
        }

        $entity = Entity::firstOrCreate(
            ['slug' => 'entidad-demostracion'],
            [
                'name' => 'Entidad de demostración',
                'entity_type' => 'Pública',
                'description' => 'Registro ficticio para mostrar la ficha institucional. Reemplazar por información oficial.',
                'region' => 'Lima',
                'cover_image' => asset('images/samples/entity-cover-1.jpg'),
            ]
        );

        $cat = Category::where('slug', 'sociedad')->first();

        News::firstOrCreate(
            ['slug' => 'bienvenido-a-punto-actual'],
            [
                'category_id' => $cat->id,
                'user_id' => $user->id,
                'title' => 'Bienvenido a Punto Actual',
                'excerpt' => 'Este es un contenido de demostración.',
                'body' => '<p>Publica aquí noticias verificadas, con contexto, autoría y fuentes.</p>',
                'image' => asset('images/samples/news-1.jpg'),
                'city' => 'Lima',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
            ]
        );

        $demoNews = [
            [
                'slug' => 'gobierno-anuncia-medidas-economicas',
                'title' => 'Gobierno anuncia medidas económicas para las regiones',
                'excerpt' => 'El paquete busca impulsar la inversión y el empleo en las provincias.',
                'category' => 'economia',
                'city' => 'Lima',
                'image' => asset('images/samples/cover-1.jpg'),
                'days' => 1,
            ],
            [
                'slug' => 'nuevas-becas-educacion-superior',
                'title' => 'Nuevas becas para educación superior',
                'excerpt' => 'Miles de jóvenes podrán acceder a programas técnicos y universitarios.',
                'category' => 'educacion',
                'city' => 'Lima',
                'image' => asset('images/samples/cover-2.jpg'),
                'days' => 2,
            ],
            [
                'slug' => 'avances-salud-publica-peru',
                'title' => 'Avances en salud pública llegan a más regiones',
                'excerpt' => 'Nuevos establecimientos amplían la cobertura en zonas rurales.',
                'category' => 'salud',
                'city' => 'Cusco',
                'image' => asset('images/samples/cover-3.jpg'),
                'days' => 3,
            ],
            [
                'slug' => 'programas-sociales-amplian-cobertura',
                'title' => 'Programas sociales amplían su cobertura',
                'excerpt' => 'Más familias acceden a los servicios de apoyo en todo el país.',
                'category' => 'sociedad',
                'city' => 'Piura',
                'image' => asset('images/samples/cover-4.jpg'),
                'days' => 4,
            ],
        ];

        foreach ($demoNews as $item) {
            $category = Category::where('slug', $item['category'])->first();

            News::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $category->id,
                    'user_id' => $user->id,
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'body' => '<p>'.$item['excerpt'].'</p><p>Contenido de demostración para el portal.</p>',
                    'image' => $item['image'],
                    'city' => $item['city'],
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now()->subDays($item['days']),
                ]
            );
        }

        $intl = Category::where('slug', 'internacional')->first();

        $internationalNews = [
            [
                'slug' => 'cumbre-climatica-lideres-mundiales',
                'title' => 'Cumbre climática reúne a líderes de 40 países',
                'excerpt' => 'Los principales bloques económicos buscan acuerdos concretos para reducir emisiones.',
                'country' => 'Suiza',
                'image' => asset('images/samples/cover-2.jpg'),
                'days' => 1,
            ],
            [
                'slug' => 'economia-global-senales-recuperacion',
                'title' => 'Economía global muestra señales de recuperación',
                'excerpt' => 'Los mercados reaccionan a los nuevos datos de empleo e inflación internacional.',
                'country' => 'Estados Unidos',
                'image' => asset('images/samples/cover-3.jpg'),
                'days' => 2,
            ],
            [
                'slug' => 'avances-tecnologicos-marcaran-el-ano',
                'title' => 'Los avances tecnológicos que marcarán el año',
                'excerpt' => 'Inteligencia artificial, energía limpia y nuevos dispositivos dominan la agenda global.',
                'country' => 'Japón',
                'image' => asset('images/samples/cover-4.jpg'),
                'days' => 3,
            ],
        ];

        foreach ($internationalNews as $item) {
            News::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'category_id' => $intl->id,
                    'user_id' => $user->id,
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'body' => '<p>'.$item['excerpt'].'</p><p>Contenido de demostración para la sección internacional.</p>',
                    'image' => $item['image'],
                    'country' => $item['country'],
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now()->subDays($item['days']),
                ]
            );
        }

        Vacancy::firstOrCreate(
            ['slug' => 'convocatoria-de-demostracion'],
            [
                'entity_id' => $entity->id,
                'title' => 'Convocatoria de demostración – no postular',
                'image' => asset('images/samples/vacancy-1.jpg'),
                'employment_regime' => 'CAS',
                'description' => 'Ejemplo ficticio para probar el portal. No corresponde a una oferta real.',
                'general_experience' => 'Ejemplo: 2 años de experiencia general.',
                'specific_experience' => 'Ejemplo: 1 año en funciones relacionadas.',
                'salary_min' => 2500,
                'salary_currency' => 'PEN',
                'department' => 'Lima',
                'official_url' => 'https://www.gob.pe/',
                'status' => 'published',
                'is_featured' => true,
                'published_at' => now(),
                'application_end_at' => now()->addMonth(),
            ]
        );

        $demoVacancies = [
            [
                'slug' => 'analista-de-sistemas-lima',
                'title' => 'Analista de sistemas',
                'regime' => 'CAS',
                'department' => 'Lima',
                'salary' => 3500,
                'image' => asset('images/samples/cover-1.jpg'),
                'days' => 1,
            ],
            [
                'slug' => 'especialista-en-salud-arequipa',
                'title' => 'Especialista en salud',
                'regime' => '728',
                'department' => 'Arequipa',
                'salary' => 4200,
                'image' => asset('images/samples/news-1.jpg'),
                'days' => 2,
            ],
            [
                'slug' => 'asistente-administrativo-la-libertad',
                'title' => 'Asistente administrativo',
                'regime' => '276',
                'department' => 'La Libertad',
                'salary' => 2200,
                'image' => asset('images/samples/cover-2.jpg'),
                'days' => 3,
            ],
            [
                'slug' => 'analista-de-presupuesto-lima',
                'title' => 'Analista de presupuesto',
                'regime' => 'CAS',
                'department' => 'Lima',
                'salary' => 3800,
                'image' => asset('images/samples/cover-3.jpg'),
                'days' => 4,
            ],
        ];

        foreach ($demoVacancies as $item) {
            Vacancy::firstOrCreate(
                ['slug' => $item['slug']],
                [
                    'entity_id' => $entity->id,
                    'title' => $item['title'],
                    'image' => $item['image'],
                    'employment_regime' => $item['regime'],
                    'description' => 'Convocatoria de demostración para probar el portal.',
                    'salary_min' => $item['salary'],
                    'salary_currency' => 'PEN',
                    'vacancies_count' => 1,
                    'department' => $item['department'],
                    'official_url' => 'https://www.gob.pe/',
                    'status' => 'published',
                    'is_featured' => false,
                    'published_at' => now()->subDays($item['days']),
                    'application_end_at' => now()->addMonth(),
                ]
            );
        }

        News::where('in_cover', true)->update(['in_cover' => false, 'cover_at' => null]);

        $coverNews = News::published()->orderByDesc('published_at')->take(4)->get();

        foreach ($coverNews as $index => $item) {
            $item->update([
                'in_cover' => true,
                'cover_at' => now()->subMinutes($index),
            ]);
        }

        $this->call(CourseSeeder::class);
    }
}
