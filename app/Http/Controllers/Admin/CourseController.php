<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Entity;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index()
    {
        $items = Course::with('entity')->latest()->paginate(25);

        return view('admin.courses.index', compact('items'));
    }

    public function create()
    {
        $course = new Course;
        $entities = Entity::orderBy('name')->get();

        return view('admin.courses.form', compact('course', 'entities'));
    }

    public function store(Request $request, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? now() : null);

        if ($request->hasFile('image_file')) {
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_COURSES)['secure_url'];
        }

        Course::create($data);

        return redirect()->route('admin.courses.index')->with('ok', 'Curso creado.');
    }

    public function edit(Course $curso)
    {
        $course = $curso;
        $entities = Entity::orderBy('name')->get();

        return view('admin.courses.form', compact('course', 'entities'));
    }

    public function update(Request $request, Course $curso, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? ($curso->published_at ?? now()) : $curso->published_at);

        if ($request->hasFile('image_file')) {
            $old = $curso->image;
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_COURSES)['secure_url'];

            if ($cloudinary->isCloudinaryUrl($old)) {
                $cloudinary->delete($cloudinary->publicIdFromUrl($old));
            }
        }

        $curso->update($data);

        return redirect()->route('admin.courses.index')->with('ok', 'Curso actualizado.');
    }

    public function destroy(Course $curso)
    {
        $curso->delete();

        return back()->with('ok', 'Curso eliminado.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'entity_id' => 'nullable|exists:entities,id',
            'title' => 'required|string|max:220',
            'area' => 'required|string|max:120',
            'modality' => 'nullable|string|max:60',
            'level' => 'nullable|string|max:60',
            'duration' => 'nullable|string|max:120',
            'excerpt' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:2048',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'official_url' => 'required|url|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:220',
            'seo_description' => 'nullable|string|max:320',
        ]);
    }
}
