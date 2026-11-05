<?php

use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\VacancyController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.token')->group(function () {
    Route::get('/news', [NewsController::class, 'index']);
    Route::post('/news', [NewsController::class, 'store']);

    Route::get('/vacancies', [VacancyController::class, 'index']);
    Route::post('/vacancies', [VacancyController::class, 'store']);
});
