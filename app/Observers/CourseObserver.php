<?php

namespace App\Observers;

use App\Jobs\SubmitUrlToGoogle;
use App\Jobs\SubmitUrlToIndexNow;
use App\Models\Course;

class CourseObserver
{
    public function saved(Course $course): void
    {
        if ($course->status === 'published' && (! $course->published_at || $course->published_at->isPast())) {
            $url = route('courses.show', $course->slug);

            SubmitUrlToGoogle::dispatchAfterResponse($url, 'URL_UPDATED');
            SubmitUrlToIndexNow::dispatchAfterResponse($url);
        }
    }

    public function deleted(Course $course): void
    {
        if ($course->slug) {
            $url = route('courses.show', $course->slug);

            SubmitUrlToGoogle::dispatchAfterResponse($url, 'URL_DELETED');
            SubmitUrlToIndexNow::dispatchAfterResponse($url);
        }
    }
}
