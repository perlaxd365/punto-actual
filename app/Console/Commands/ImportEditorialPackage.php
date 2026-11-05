<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Entity;
use App\Models\News;
use App\Models\User;
use App\Models\Vacancy;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class ImportEditorialPackage extends Command
{
    protected $signature = 'editorial:import
                            {path : Ruta de la carpeta del paquete editorial}
                            {--dry-run : Valida e importa dentro de una transacción que se revierte}';

    protected $description = 'Importa categorías, entidades, noticias y convocatorias desde un paquete editorial';

    /**
     * @var array<string, array{created: int, updated: int, unchanged: int}>
     */
    private array $stats = [];

    public function __construct()
    {
        parent::__construct();

        foreach (['categorías', 'entidades', 'noticias', 'convocatorias'] as $type) {
            $this->stats[$type] = ['created' => 0, 'updated' => 0, 'unchanged' => 0];
        }
    }

    public function handle(): int
    {
        $packagePath = realpath($this->argument('path'));

        if ($packagePath === false || ! is_dir($packagePath)) {
            $this->error('No existe la carpeta del paquete.');

            return self::FAILURE;
        }

        try {
            $manifest = $this->validatePackage($packagePath);
            DB::beginTransaction();

            News::withoutEvents(function () use ($packagePath): void {
                $this->importCategories($packagePath);
                $this->importEntities($packagePath);
                $this->importNews($packagePath);
                $this->applyCoverSelection($packagePath);
                $this->importVacancies($packagePath);
                $this->enforceCoverLimit();
            });

            if ($this->option('dry-run')) {
                DB::rollBack();
                $this->warn('Modo de prueba: todos los cambios fueron revertidos.');
            } else {
                DB::commit();
                $this->info('Paquete importado y confirmado.');
            }

            $this->table(
                ['Registro', 'Creados', 'Actualizados', 'Sin cambios'],
                collect($this->stats)
                    ->map(fn (array $stats, string $type): array => [
                        $type,
                        $stats['created'],
                        $stats['updated'],
                        $stats['unchanged'],
                    ])
                    ->values()
                    ->all()
            );

            $this->line('Noticias en paquete: '.$manifest['counts']['news']);
            $this->line('Convocatorias en paquete: '.$manifest['counts']['vacancies']);

            return self::SUCCESS;
        } catch (\Throwable $exception) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            report($exception);
            $this->error($exception->getMessage());

            if ($this->output->isVerbose()) {
                $this->line($exception->getTraceAsString());
            }

            return self::FAILURE;
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function validatePackage(string $packagePath): array
    {
        $requiredFiles = [
            'manifest.json',
            'categorias/categorias.csv',
            'entidades/entidades.csv',
            'noticias/noticias.csv',
            'noticias/portada.csv',
            'convocatorias/convocatorias.csv',
            'convocatorias/adjuntos.csv',
        ];

        foreach ($requiredFiles as $file) {
            if (! is_file($packagePath.DIRECTORY_SEPARATOR.$file)) {
                throw new RuntimeException("Falta el archivo requerido: {$file}");
            }
        }

        $manifest = json_decode(
            file_get_contents($packagePath.DIRECTORY_SEPARATOR.'manifest.json') ?: '',
            true,
            flags: JSON_THROW_ON_ERROR
        );

        $expectedCounts = [
            'categories' => count($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'categorias/categorias.csv')),
            'entities' => count($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'entidades/entidades.csv')),
            'news' => count($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'noticias/noticias.csv')),
            'vacancies' => count($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'convocatorias/convocatorias.csv')),
        ];

        foreach ($expectedCounts as $type => $actual) {
            $declared = (int) ($manifest['counts'][$type] ?? -1);

            if ($declared !== $actual) {
                throw new RuntimeException(
                    "El manifiesto declara {$declared} {$type}, pero el CSV contiene {$actual}."
                );
            }
        }

        $this->assertUniqueSlugs(
            $this->readCsv($packagePath.DIRECTORY_SEPARATOR.'noticias/noticias.csv'),
            'noticias'
        );
        $this->assertUniqueSlugs(
            $this->readCsv($packagePath.DIRECTORY_SEPARATOR.'convocatorias/convocatorias.csv'),
            'convocatorias'
        );

        if (! User::query()->exists()) {
            throw new RuntimeException('No existe un usuario para asignar como autor de las noticias.');
        }

        return $manifest;
    }

    private function importCategories(string $packagePath): void
    {
        foreach ($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'categorias/categorias.csv') as $row) {
            $this->upsertRecord(Category::class, 'categorías', $row['slug'], [
                'name' => $row['name'],
                'slug' => $row['slug'],
                'description' => $this->nullable($row['description']),
                'type' => $row['type'] ?: 'news',
            ]);
        }
    }

    private function importEntities(string $packagePath): void
    {
        foreach ($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'entidades/entidades.csv') as $row) {
            $this->upsertRecord(Entity::class, 'entidades', $row['slug'], [
                'name' => $row['name'],
                'slug' => $row['slug'],
                'entity_type' => $row['entity_type'],
                'logo' => $this->nullable($row['logo']),
                'cover_image' => $this->nullable($row['cover_image']),
                'description' => $this->nullable($row['description']),
                'website' => $this->nullable($row['website']),
                'official_jobs_url' => $this->nullable($row['official_jobs_url']),
                'region' => $this->nullable($row['region']),
            ]);
        }
    }

    private function importNews(string $packagePath): void
    {
        $authorId = User::query()->orderBy('id')->value('id');
        $categories = Category::query()->get(['id', 'name', 'slug']);

        foreach ($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'noticias/noticias.csv') as $row) {
            $category = $categories->first(
                fn (Category $item): bool => $item->name === $row['category']
                    || $item->slug === Str::slug($row['category'])
            );

            if (! $category) {
                $slug = Str::slug($row['category']);

                if (Category::query()->where('slug', $slug)->exists()) {
                    $slug .= '-'.Str::lower(Str::random(4));
                }

                $category = Category::query()->create([
                    'name' => $row['category'],
                    'slug' => $slug,
                    'type' => 'news',
                ]);
                $categories->push($category);
                $this->stats['categorías']['created']++;
            }

            $this->assertUrl($row['source_url'], 'fuente de noticia');
            $this->upsertRecord(News::class, 'noticias', $row['slug'], [
                'category_id' => $category->id,
                'user_id' => $authorId,
                'title' => $row['title'],
                'slug' => $row['slug'],
                'excerpt' => $this->nullable($row['excerpt']),
                'body' => $row['body'],
                'image' => $this->nullable($row['image']),
                'city' => $this->nullable($row['city']),
                'country' => $this->nullable($row['country']),
                'source_name' => $this->nullable($row['source_name']),
                'source_url' => $this->nullable($row['source_url']),
                'status' => $this->oneOf($row['status'], ['draft', 'published'], 'estado de noticia'),
                'is_featured' => $this->boolean($row['is_featured']),
                'in_cover' => $this->boolean($row['in_cover']),
                'cover_at' => $this->date($row['cover_at']),
                'published_at' => $this->date($row['published_at']),
                'seo_title' => $this->nullable($row['seo_title']),
                'seo_description' => $this->nullable($row['seo_description']),
            ]);
        }
    }

    private function importVacancies(string $packagePath): void
    {
        $entities = Entity::query()->get(['id', 'name', 'slug'])->keyBy('name');
        $attachmentMap = collect(
            $this->readCsv($packagePath.DIRECTORY_SEPARATOR.'convocatorias/adjuntos.csv')
        )
            ->groupBy('vacancy_slug')
            ->map(fn ($rows): array => $rows
                ->map(function (array $attachment): array {
                    $this->assertUrl($attachment['url'], 'adjunto de convocatoria');

                    return [
                        'name' => $attachment['nombre'],
                        'url' => $attachment['url'],
                    ];
                })
                ->values()
                ->all())
            ->all();

        foreach ($this->readCsv($packagePath.DIRECTORY_SEPARATOR.'convocatorias/convocatorias.csv') as $row) {
            $entity = $entities->get($row['entity']);

            if (! $entity) {
                throw new RuntimeException("No se encontró la entidad: {$row['entity']}");
            }

            $this->assertUrl($row['official_url'], 'URL oficial de convocatoria');
            $this->assertUrl($row['bases_url'], 'URL de bases de convocatoria');

            $this->upsertRecord(Vacancy::class, 'convocatorias', $row['slug'], [
                'entity_id' => $entity->id,
                'title' => $row['title'],
                'slug' => $row['slug'],
                'reference_code' => $this->nullable($row['reference_code']),
                'image' => $this->nullable($row['image']),
                'employment_regime' => $row['employment_regime'],
                'employment_type' => $this->nullable($row['employment_type']),
                'description' => $row['description'],
                'functions' => $this->nullable($row['functions']),
                'requirements' => $this->nullable($row['requirements']),
                'education_level' => $this->nullable($row['education_level']),
                'professional_career' => $this->nullable($row['professional_career']),
                'general_experience' => $this->nullable($row['general_experience']),
                'specific_experience' => $this->nullable($row['specific_experience']),
                'courses_training' => $this->nullable($row['courses_training']),
                'knowledge' => $this->nullable($row['knowledge']),
                'salary_min' => $this->decimal($row['salary_min']),
                'salary_max' => $this->decimal($row['salary_max']),
                'salary_currency' => $row['salary_currency'] ?: 'PEN',
                'vacancies_count' => $this->integer($row['vacancies_count']),
                'department' => $this->nullable($row['department']),
                'province' => $this->nullable($row['province']),
                'district' => $this->nullable($row['district']),
                'workplace' => $this->nullable($row['workplace']),
                'work_mode' => $this->nullable($row['work_mode']),
                'published_at' => $this->date($row['published_at']),
                'application_start_at' => $this->date($row['application_start_at']),
                'application_end_at' => $this->date($row['application_end_at']),
                'schedule' => $this->nullable($row['schedule']),
                'official_url' => $row['official_url'],
                'bases_url' => $this->nullable($row['bases_url']),
                'attachments' => $attachmentMap[$row['slug']] ?? null,
                'status' => $this->oneOf(
                    $row['status'],
                    ['draft', 'published', 'expiring', 'finished', 'cancelled'],
                    'estado de convocatoria'
                ),
                'is_featured' => $this->boolean($row['is_featured']),
                'editorial_verified_at' => $this->date($row['editorial_verified_at']),
            ]);
        }
    }

    private function applyCoverSelection(string $packagePath): void
    {
        $selected = collect(
            $this->readCsv($packagePath.DIRECTORY_SEPARATOR.'noticias/portada.csv')
        )->keyBy('news_slug');

        News::query()
            ->where('in_cover', true)
            ->whereNotIn('slug', $selected->keys()->all())
            ->update(['in_cover' => false, 'cover_at' => null]);

        foreach ($selected as $slug => $cover) {
            $news = News::query()->where('slug', $slug)->first();

            if (! $news) {
                throw new RuntimeException("La portada referencia una noticia inexistente: {$slug}");
            }

            $news->update([
                'in_cover' => true,
                'cover_at' => $this->date($cover['cover_at']),
            ]);
        }
    }

    private function enforceCoverLimit(): void
    {
        $keep = News::query()
            ->where('in_cover', true)
            ->orderByDesc('cover_at')
            ->orderByDesc('published_at')
            ->take(4)
            ->pluck('id');

        News::query()
            ->where('in_cover', true)
            ->whereNotIn('id', $keep)
            ->update(['in_cover' => false, 'cover_at' => null]);
    }

    /**
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  class-string<TModel>  $modelClass
     * @param  array<string, mixed>  $values
     */
    private function upsertRecord(string $modelClass, string $type, string $slug, array $values): void
    {
        $model = $modelClass::query()->where('slug', $slug)->first();
        $isNew = $model === null;

        if ($isNew) {
            $model = new $modelClass;
        }

        $model->fill($values);

        if (! $isNew && ! $model->isDirty()) {
            $this->stats[$type]['unchanged']++;

            return;
        }

        if (! $isNew && $this->output->isVeryVerbose()) {
            $this->line(
                "Actualizando {$type}/{$slug}: ".implode(', ', array_keys($model->getDirty()))
            );
        }

        $model->save();
        $this->stats[$type][$isNew ? 'created' : 'updated']++;
    }

    /**
     * @return list<array<string, string>>
     */
    private function readCsv(string $path): array
    {
        $handle = fopen($path, 'rb');

        if ($handle === false) {
            throw new RuntimeException("No se pudo abrir el archivo: {$path}");
        }

        try {
            $header = fgetcsv($handle);

            if ($header === false) {
                throw new RuntimeException("El archivo está vacío: {$path}");
            }

            $header[0] = trim(str_replace("\xEF\xBB\xBF", '', $header[0]), '"');
            $rows = [];

            while (($values = fgetcsv($handle)) !== false) {
                if ($values === [null] || count($values) !== count($header)) {
                    throw new RuntimeException("Fila CSV inválida en: {$path}");
                }

                $rows[] = array_combine($header, $values);
            }

            return $rows;
        } finally {
            fclose($handle);
        }
    }

    /**
     * @param  list<array<string, string>>  $rows
     */
    private function assertUniqueSlugs(array $rows, string $type): void
    {
        foreach ($rows as $row) {
            if (($row['slug'] ?? '') === '') {
                throw new RuntimeException("Hay una fila sin slug en {$type}.");
            }
        }

        $duplicates = collect($rows)->pluck('slug')->duplicates();

        if ($duplicates->isNotEmpty()) {
            throw new RuntimeException(
                "Slugs duplicados en {$type}: ".$duplicates->implode(', ')
            );
        }
    }

    private function nullable(?string $value): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : $value;
    }

    private function boolean(?string $value): bool
    {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    private function integer(?string $value): ?int
    {
        return $this->nullable($value) === null ? null : (int) $value;
    }

    private function decimal(?string $value): ?float
    {
        return $this->nullable($value) === null ? null : (float) $value;
    }

    private function date(?string $value): ?CarbonImmutable
    {
        $value = $this->nullable($value);

        return $value === null ? null : CarbonImmutable::parse($value);
    }

    /**
     * @param  list<string>  $allowed
     */
    private function oneOf(?string $value, array $allowed, string $label): string
    {
        if (! in_array($value, $allowed, true)) {
            throw new RuntimeException("Valor inválido para {$label}: {$value}");
        }

        return $value;
    }

    private function assertUrl(?string $url, string $label): void
    {
        if ($this->nullable($url) !== null && filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new RuntimeException("URL inválida para {$label}: {$url}");
        }
    }
}
