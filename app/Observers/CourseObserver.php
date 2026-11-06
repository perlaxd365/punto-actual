<?php

namespace App\Observers;

use App\Jobs\SubmitUrlToGoogle;
use App\Models\Course;

class CourseObserver
{
    public function saved(Course $course): void
    {
        if ($course->status === 'published' && (! $course->published_at || $course->published_at->isPast())) {
            SubmitUrlToGoogle::dispatchAfterResponse(route('courses.show', $course->slug), 'URL_UPDATED');
        }
    }

    public function deleted(Course $course): void
    {
        if ($course->slug) {
            SubmitUrlToGoogle::dispatchAfterResponse(route('courses.show', $course->slug), 'URL_DELETED');
        }
    }
}
