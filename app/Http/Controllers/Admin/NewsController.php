<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Services\CloudinaryImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $items = News::latest()->paginate(20);

        return view('admin.news.index', compact('items'));
    }

    public function create()
    {
        $news = new News();
        $categories = Category::where('type', 'news')->get();

        return view('admin.news.form', compact('news', 'categories'));
    }

    public function store(Request $request, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = Str::slug($data['title']).'-'.Str::lower(Str::random(5));
        $data['is_featured'] = $request->boolean('is_featured');
        $data['in_cover'] = $request->boolean('in_cover');
        $data['cover_at'] = $data['in_cover'] ? now() : null;
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? now() : null);

        if ($request->hasFile('image_file')) {
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_NEWS)['secure_url'];
        }

        News::create($data);
        $this->enforceCoverLimit();

        return redirect()->route('admin.news.index')->with('ok', 'Noticia creada.');
    }

    public function edit(News $noticia)
    {
        $news = $noticia;
        $categories = Category::where('type', 'news')->get();

        return view('admin.news.form', compact('news', 'categories'));
    }

    public function update(Request $request, News $noticia, CloudinaryImageService $cloudinary)
    {
        $data = $this->validated($request);
        $data['is_featured'] = $request->boolean('is_featured');
        $data['in_cover'] = $request->boolean('in_cover');

        if ($data['in_cover']) {
            $data['cover_at'] = $noticia->in_cover ? ($noticia->cover_at ?? now()) : now();
        } else {
            $data['cover_at'] = null;
        }

        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? ($noticia->published_at ?? now()) : $noticia->published_at);

        if ($request->hasFile('image_file')) {
            $old = $noticia->image;
            $data['image'] = $cloudinary->upload($request->file('image_file'), CloudinaryImageService::FOLDER_NEWS)['secure_url'];

            if ($cloudinary->isCloudinaryUrl($old)) {
                $cloudinary->delete($cloudinary->publicIdFromUrl($old));
            }
        }

        $noticia->update($data);
        $this->enforceCoverLimit();

        return redirect()->route('admin.news.index')->with('ok', 'Noticia actualizada.');
    }

    public function destroy(News $noticia)
    {
        $noticia->delete();

        return back()->with('ok', 'Noticia eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'title' => 'required|string|max:220',
            'excerpt' => 'nullable|string|max:500',
            'body' => 'required|string',
            'image' => 'nullable|string|max:2048',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'city' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'source_name' => 'nullable|string|max:180',
            'source_url' => 'nullable|url|max:2048',
            'status' => 'required|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'in_cover' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:220',
            'seo_description' => 'nullable|string|max:320',
        ]);
    }

    private function enforceCoverLimit(): void
    {
        $keep = News::where('in_cover', true)
            ->orderByDesc('cover_at')
            ->orderByDesc('published_at')
            ->take(4)
            ->pluck('id');

        News::where('in_cover', true)
            ->whereNotIn('id', $keep)
            ->update(['in_cover' => false, 'cover_at' => null]);
    }
}
