<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Entity;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => 'nullable|string|max:120',
            'area' => 'nullable|string|max:120',
            'modality' => 'nullable|string|max:60',
        ]);

        $query = Course::published()->with('entity');

        if (! empty($filters['q'])) {
            $term = trim($filters['q']);
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', "%{$term}%")
                    ->orWhere('excerpt', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('area', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['area'])) {
            $query->where('area', $filters['area']);
        }

        if (! empty($filters['modality'])) {
            $query->where('modality', $filters['modality']);
        }

        $courses = $query->orderByDesc('is_featured')
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        $areas = Course::published()->select('area')->distinct()->orderBy('area')->pluck('area');
        $modalities = Course::published()
            ->whereNotNull('modality')
            ->where('modality', '!=', '')
            ->select('modality')
            ->distinct()
            ->orderBy('modality')
            ->pluck('modality');

        $featured = Course::published()
            ->with('entity')
            ->featured()
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('courses.index', compact('courses', 'areas', 'modalities', 'featured'));
    }

    public function show(Course $course)
    {
        abort_unless(
            $course->status === 'published' && (! $course->published_at || $course->published_at->isPast()),
            404
        );

        $course->load('entity');

        $similar = Course::published()
            ->with('entity')
            ->where('area', $course->area)
            ->whereKeyNot($course->getKey())
            ->latest('published_at')
            ->take(6)
            ->get();

        $recommended = Course::published()
            ->with('entity')
            ->whereKeyNot($course->getKey())
            ->where('area', '!=', $course->area)
            ->orderByDesc('is_featured')
            ->latest('published_at')
            ->take(6)
            ->get();

        return view('courses.show', compact('course', 'similar', 'recommended'));
    }

    public function provider(Entity $entity)
    {
        $courses = Course::published()
            ->with('entity')
            ->where('entity_id', $entity->id)
            ->latest('published_at')
            ->paginate(12);

        return view('courses.provider', compact('entity', 'courses'));
    }
}
