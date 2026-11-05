<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $vacancies = Vacancy::with('entity')
            ->when($request->q, fn ($q, $value) => $q->where('title', 'like', "%{$value}%"))
            ->when($request->region, fn ($q, $value) => $q->where('department', $value))
            ->when($request->regime, fn ($q, $value) => $q->where('employment_regime', $value))
            ->when($request->status, fn ($q, $value) => $q->where('status', $value))
            ->latest('published_at')
            ->paginate(min((int) $request->input('per_page', 20), 100));

        return response()->json($vacancies);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'entity_id' => 'nullable|exists:entities,id',
            'entity_name' => 'nullable|string|max:180',
            'title' => 'required|string|max:220',
            'image' => 'nullable|url|max:2048',
            'reference_code' => 'nullable|string|max:100',
            'employment_regime' => 'required|string|max:80',
            'employment_type' => 'nullable|string|max:100',
            'description' => 'required|string',
            'functions' => 'nullable|string',
            'requirements' => 'nullable|string',
            'education_level' => 'nullable|string',
            'professional_career' => 'nullable|string',
            'general_experience' => 'nullable|string',
            'specific_experience' => 'nullable|string',
            'courses_training' => 'nullable|string',
            'knowledge' => 'nullable|string',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency' => 'nullable|string|max:5',
            'vacancies_count' => 'nullable|integer|min:1',
            'department' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'workplace' => 'nullable|string|max:255',
            'work_mode' => 'nullable|string|max:50',
            'published_at' => 'nullable|date',
            'application_start_at' => 'nullable|date',
            'application_end_at' => 'nullable|date|after_or_equal:application_start_at',
            'schedule' => 'nullable|string',
            'official_url' => 'required|url|max:2048',
            'bases_url' => 'nullable|url|max:2048',
            'status' => 'nullable|in:draft,published,expiring,finished,cancelled',
            'is_featured' => 'nullable|boolean',
        ]);

        $entityName = $data['entity_name'] ?? null;
        unset($data['entity_name']);

        if (empty($data['entity_id'])) {
            if (! $entityName) {
                return response()->json([
                    'message' => 'Debes enviar entity_id o entity_name.',
                ], 422);
            }

            $entity = Entity::firstOrCreate(
                ['slug' => Str::slug($entityName)],
                [
                    'name' => $entityName,
                    'entity_type' => 'Pública',
                    'region' => $data['department'] ?? null,
                ]
            );

            $data['entity_id'] = $entity->id;
        }

        $data['status'] = $data['status'] ?? 'published';
        $data['salary_currency'] = $data['salary_currency'] ?? 'PEN';
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? now() : null);
        $data['slug'] = $this->uniqueSlug($data['title']);

        $vacancy = Vacancy::create($data);

        return response()->json([
            'message' => 'Convocatoria creada.',
            'data' => $vacancy->fresh('entity'),
            'url' => route('vacancies.show', $vacancy->slug),
        ], 201);
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base.'-'.Str::lower(Str::random(5));

        while (Vacancy::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(5));
        }

        return $slug;
    }
}
