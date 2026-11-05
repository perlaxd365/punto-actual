<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Vacancy;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VacancyController extends Controller
{
    public function index()
    {
        $items = Vacancy::with('entity')->latest()->paginate(20);

        return view('admin.vacancies.index', compact('items'));
    }

    public function create()
    {
        $vacancy = new Vacancy();
        $entities = Entity::orderBy('name')->get();

        return view('admin.vacancies.form', compact('vacancy', 'entities'));
    }

    public function store(Request $request, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $data['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image_file')) {
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_VACANCIES)['secure_url'];
        }

        Vacancy::create($data);

        return redirect()->route('admin.vacancies.index')->with('ok', 'Convocatoria creada.');
    }

    public function edit(Vacancy $convocatoria)
    {
        $vacancy = $convocatoria;
        $entities = Entity::orderBy('name')->get();

        return view('admin.vacancies.form', compact('vacancy', 'entities'));
    }

    public function update(Request $request, Vacancy $convocatoria, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image_file')) {
            $old = $convocatoria->image;
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_VACANCIES)['secure_url'];

            if ($cloudinary->isCloudinaryUrl($old)) {
                $cloudinary->delete($cloudinary->publicIdFromUrl($old));
            }
        }

        $convocatoria->update($data);

        return redirect()->route('admin.vacancies.index')->with('ok', 'Convocatoria actualizada.');
    }

    public function destroy(Vacancy $convocatoria)
    {
        $convocatoria->delete();

        return back()->with('ok', 'Convocatoria eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'title' => 'required|string|max:220',
            'image' => 'nullable|string|max:2048',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
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
            'salary_currency' => 'required|string|max:5',
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
            'status' => 'required|in:draft,published,expiring,finished,cancelled',
            'is_featured' => 'nullable|boolean',
        ]);
    }
}
