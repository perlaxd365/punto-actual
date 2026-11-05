<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::with('category')
            ->when($request->q, fn ($q, $value) => $q->where('title', 'like', "%{$value}%"))
            ->when($request->category, fn ($q, $value) => $q->whereHas('category', fn ($c) => $c->where('slug', $value)))
            ->when($request->status, fn ($q, $value) => $q->where('status', $value))
            ->latest('published_at')
            ->paginate(min((int) $request->input('per_page', 20), 100));

        return response()->json($news);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:220',
            'body' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:120',
            'image' => 'nullable|url|max:2048',
            'city' => 'nullable|string|max:120',
            'country' => 'nullable|string|max:120',
            'source_name' => 'nullable|string|max:180',
            'source_url' => 'nullable|url|max:2048',
            'status' => 'nullable|in:draft,published',
            'is_featured' => 'nullable|boolean',
            'in_cover' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'seo_title' => 'nullable|string|max:220',
            'seo_description' => 'nullable|string|max:320',
        ]);

        $categoryName = $data['category'] ?? null;
        unset($data['category']);

        $data['category_id'] = $this->categoryId($categoryName);
        $data['user_id'] = User::query()->orderBy('id')->value('id');
        $data['status'] = $data['status'] ?? 'published';
        $data['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $data['in_cover'] = (bool) ($data['in_cover'] ?? false);
        $data['cover_at'] = $data['in_cover'] ? now() : null;
        $data['published_at'] = $data['published_at'] ?? ($data['status'] === 'published' ? now() : null);
        $data['slug'] = $this->uniqueSlug($data['title']);

        $news = News::create($data);
        $this->enforceCoverLimit();

        return response()->json([
            'message' => 'Noticia creada.',
            'data' => $news->fresh('category'),
            'url' => route('news.show', $news->slug),
        ], 201);
    }

    private function categoryId(?string $name): ?int
    {
        if (! $name) {
            return null;
        }

        $category = Category::where('slug', Str::slug($name))
            ->orWhere('name', $name)
            ->first();

        if ($category) {
            return $category->id;
        }

        return Category::create([
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'type' => 'news',
        ])->id;
    }

    private function uniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base.'-'.Str::lower(Str::random(5));

        while (News::where('slug', $slug)->exists()) {
            $slug = $base.'-'.Str::lower(Str::random(5));
        }

        return $slug;
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
