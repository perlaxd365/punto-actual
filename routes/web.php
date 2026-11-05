<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\VacancyController as AdminVacancyController;
use App\Http\Controllers\Admin\EntityController as AdminEntityController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\SeoController;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'featured' => News::published()->with('category')->featured()->latest('published_at')->take(4)->get(),
        'latest' => News::published()->with('category')->latest('published_at')->take(8)->get(),
        'vacancies' => \App\Models\Vacancy::published()->latest()->take(6)->get(),
        'categories' => Category::orderBy('name')->get(),
        'coverNews' => News::published()->with('category')->cover()->take(4)->get(),
        'international' => News::published()
            ->with('category')
            ->whereHas('category', fn ($query) => $query->where('slug', 'internacional'))
            ->latest('published_at')
            ->take(3)
            ->get(),
    ]);
})->name('home');

Route::get('/noticias', [NewsController::class, 'index'])->name('news.index');
Route::get('/internacional', [NewsController::class, 'international'])->name('news.international');
Route::get('/noticias/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/empleos', [VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/empleos/{vacancy:slug}', [VacancyController::class, 'show'])->name('vacancies.show');
Route::get('/entidades/{entity:slug}', [EntityController::class, 'show'])->name('entities.show');
Route::get('/categoria/{category:slug}', [NewsController::class, 'category'])->name('categories.show');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/feed.xml', [SeoController::class, 'feed'])->name('feed');

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended(route('admin.dashboard'));
    }
    return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
});
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('noticias', AdminNewsController::class)->except(['show'])->names('news');
    Route::resource('convocatorias', AdminVacancyController::class)->except(['show'])->names('vacancies');
    Route::resource('entidades', AdminEntityController::class)->except(['show'])->names('entities');
});
