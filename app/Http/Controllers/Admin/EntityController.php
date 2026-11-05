<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EntityController extends Controller
{
    public function index()
    {
        $items = Entity::withCount('vacancies')->orderBy('name')->paginate(25);

        return view('admin.entities.index', compact('items'));
    }

    public function create()
    {
        $entity = new Entity();

        return view('admin.entities.form', compact('entity'));
    }

    public function store(Request $request, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(4));

        if ($request->hasFile('logo_file')) {
            $data['logo'] = $cloudinary->upload($request->file('logo_file'), CloudinaryImageService::FOLDER_ENTITIES)['secure_url'];
        }

        if ($request->hasFile('cover_file')) {
            $data['cover_image'] = $cloudinary->upload($request->file('cover_file'), CloudinaryImageService::FOLDER_ENTITIES)['secure_url'];
        }

        Entity::create($data);

        return redirect()->route('admin.entities.index')->with('ok', 'Entidad creada.');
    }

    public function edit(Entity $entidad)
    {
        $entity = $entidad;

        return view('admin.entities.form', compact('entity'));
    }

    public function update(Request $request, Entity $entidad, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);

        if ($request->hasFile('logo_file')) {
            $old = $entidad->logo;
            $data['logo'] = $cloudinary->upload($request->file('logo_file'), CloudinaryImageService::FOLDER_ENTITIES)['secure_url'];

            if ($cloudinary->isCloudinaryUrl($old)) {
                $cloudinary->delete($cloudinary->publicIdFromUrl($old));
            }
        }

        if ($request->hasFile('cover_file')) {
            $oldCover = $entidad->cover_image;
            $data['cover_image'] = $cloudinary->upload($request->file('cover_file'), CloudinaryImageService::FOLDER_ENTITIES)['secure_url'];

            if ($cloudinary->isCloudinaryUrl($oldCover)) {
                $cloudinary->delete($cloudinary->publicIdFromUrl($oldCover));
            }
        }

        $entidad->update($data);

        return redirect()->route('admin.entities.index')->with('ok', 'Entidad actualizada.');
    }

    public function destroy(Entity $entidad)
    {
        $entidad->delete();

        return back()->with('ok', 'Entidad eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:180',
            'entity_type' => 'required|string|max:80',
            'logo' => 'nullable|string|max:2048',
            'logo_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'cover_image' => 'nullable|string|max:2048',
            'cover_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'description' => 'nullable|string',
            'website' => 'nullable|url',
            'official_jobs_url' => 'nullable|url',
            'region' => 'nullable|string|max:100',
        ]);
    }
}
