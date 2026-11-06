<?php

namespace App\Providers;

use App\Models\Course;
use App\Models\News;
use App\Models\Vacancy;
use App\Observers\CourseObserver;
use App\Observers\NewsObserver;
use App\Observers\VacancyObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        News::observe(NewsObserver::class);
        Vacancy::observe(VacancyObserver::class);
        Course::observe(CourseObserver::class);
    }
}
