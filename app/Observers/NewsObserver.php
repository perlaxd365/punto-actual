<?php

namespace App\Observers;

use App\Jobs\SubmitUrlToGoogle;
use App\Models\News;

class NewsObserver
{
    public function saved(News $news): void
    {
        if ($news->status === 'published' && $news->published_at && $news->published_at->isPast()) {
            SubmitUrlToGoogle::dispatchAfterResponse(route('news.show', $news->slug), 'URL_UPDATED');
        }
    }

    public function deleted(News $news): void
    {
        SubmitUrlToGoogle::dispatchAfterResponse(route('news.show', $news->slug), 'URL_DELETED');
    }
}
