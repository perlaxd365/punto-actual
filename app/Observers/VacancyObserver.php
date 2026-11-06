<?php

namespace App\Observers;

use App\Jobs\SubmitUrlToGoogle;
use App\Models\Vacancy;

class VacancyObserver
{
    public function saved(Vacancy $vacancy): void
    {
        if (in_array($vacancy->status, ['published', 'expiring'], true)) {
            SubmitUrlToGoogle::dispatchAfterResponse(route('vacancies.show', $vacancy->slug), 'URL_UPDATED');
        }
    }

    public function deleted(Vacancy $vacancy): void
    {
        SubmitUrlToGoogle::dispatchAfterResponse(route('vacancies.show', $vacancy->slug), 'URL_DELETED');
    }
}
