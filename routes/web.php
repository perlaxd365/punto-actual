<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\VacancyController as AdminVacancyController;
use App\Http\Controllers\Admin\EntityController as AdminEntityController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\NewsletterCampaignController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OgImageController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\EntityController;
use App\Http\Controllers\SeoController;
use App\Models\Category;
use App\Models\News;
use App\Models\User;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'featured' => News::published()->with('category')->featured()->latest('published_at')->take(4)->get(),
        'latest' => News::published()->with('category')->latest('published_at')->take(8)->get(),
        'vacancies' => \App\Models\Vacancy::published()->latest()->take(6)->get(),
        'categories' => Category::orderBy('name')->get(),
        'coverNews' => News::published()->with('category')->cover()->take(6)->get(),
        'international' => News::published()
            ->with('category')
            ->whereHas('category', fn ($query) => $query->where('slug', 'internacional'))
            ->latest('published_at')
            ->take(3)
            ->get(),
        'local' => News::published()
            ->with('category')
            ->whereHas('category', fn ($query) => $query->where('slug', '!=', 'internacional'))
            ->latest('published_at')
            ->take(9)
            ->get(),
    ]);
})->name('home');

Route::get('/noticias', [NewsController::class, 'index'])->name('news.index');
Route::get('/internacional', [NewsController::class, 'international'])->name('news.international');
Route::get('/noticias/{news:slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/og/noticia/{news:slug}', [OgImageController::class, 'news'])->name('og.news');
Route::get('/empleos', [VacancyController::class, 'index'])->name('vacancies.index');
Route::get('/empleos/{vacancy:slug}', [VacancyController::class, 'show'])->name('vacancies.show');
Route::get('/og/empleo/{vacancy:slug}', [OgImageController::class, 'vacancy'])->name('og.vacancy');
Route::get('/entidades/{entity:slug}', [EntityController::class, 'show'])->name('entities.show');
Route::get('/categoria/{category:slug}', [NewsController::class, 'category'])->name('categories.show');
Route::view('/nosotros', 'pages.nosotros')->name('pages.nosotros');
Route::view('/privacidad', 'pages.privacidad')->name('pages.privacidad');
Route::view('/terminos', 'pages.terminos')->name('pages.terminos');
Route::view('/politica-editorial', 'pages.politica-editorial')->name('pages.editorial');
Route::view('/contacto', 'pages.contacto')->name('pages.contacto');
Route::post('/suscripcion', [NewsletterController::class, 'store'])->middleware('throttle:10,1')->name('newsletter.store');
Route::get('/suscripcion/baja/{subscriber}', [NewsletterController::class, 'unsubscribe'])->middleware('signed')->name('newsletter.unsubscribe');
Route::get('/ads.txt', fn () => response(
    config('ads.pub_id') ? 'google.com, '.config('ads.pub_id').', DIRECT, f08c47fec0942fa0' : '',
    200,
    ['Content-Type' => 'text/plain']
))->name('ads.txt');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/feed.xml', [SeoController::class, 'feed'])->name('feed');

Route::get('/login', fn () => view('auth.login'))->name('login');
Route::get('/recuperar-contrasena', [PasswordResetController::class, 'request'])->middleware('guest')->name('password.request');
Route::post('/recuperar-contrasena', [PasswordResetController::class, 'email'])->middleware(['guest', 'throttle:6,1'])->name('password.email');
Route::get('/restablecer-contrasena/{token}', [PasswordResetController::class, 'reset'])->middleware('guest')->name('password.reset');
Route::post('/restablecer-contrasena', [PasswordResetController::class, 'update'])->middleware(['guest', 'throttle:6,1'])->name('password.update');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate(['email' => 'required|email', 'password' => 'required']);
    $user = User::where('email', $credentials['email'])->first();

    if (! $user || ! Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['email' => 'Credenciales incorrectas.'])->onlyInput('email');
    }

    if ($user->hasTwoFactorEnabled()) {
        $request->session()->put('2fa_user_id', $user->id);
        $request->session()->put('2fa_remember', $request->boolean('remember'));

        return redirect()->route('two-factor.challenge');
    }

    Auth::login($user, $request->boolean('remember'));
    $request->session()->regenerate();

    return redirect()->intended(route('admin.dashboard'));
})->middleware('throttle:10,1');

Route::get('/login/2fa', function (Request $request) {
    if (! $request->session()->has('2fa_user_id')) {
        return redirect()->route('login');
    }

    return view('auth.two-factor');
})->name('two-factor.challenge');

Route::post('/login/2fa', function (Request $request) {
    $request->validate(['code' => 'required|string']);

    $user = User::find($request->session()->get('2fa_user_id'));

    if (! $user || ! $user->hasTwoFactorEnabled()) {
        $request->session()->forget(['2fa_user_id', '2fa_remember']);

        return redirect()->route('login');
    }

    $code = preg_replace('/\s+/', '', (string) $request->input('code'));
    $valid = Totp::verify((string) $user->two_factor_secret, $code);

    if (! $valid) {
        $codes = json_decode((string) $user->two_factor_recovery_codes, true) ?: [];
        foreach ($codes as $index => $hash) {
            if (Hash::check($code, $hash)) {
                unset($codes[$index]);
                $user->forceFill(['two_factor_recovery_codes' => json_encode(array_values($codes))])->save();
                $valid = true;
                break;
            }
        }
    }

    if (! $valid) {
        return back()->withErrors(['code' => 'Código inválido. Inténtalo de nuevo.']);
    }

    $remember = (bool) $request->session()->pull('2fa_remember', false);
    $request->session()->forget('2fa_user_id');
    Auth::login($user, $remember);
    $request->session()->regenerate();

    return redirect()->intended(route('admin.dashboard'));
})->middleware('throttle:10,1');
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

    Route::get('suscriptores', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('suscriptores/exportar', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('suscriptores/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    Route::get('boletin', [NewsletterCampaignController::class, 'index'])->name('newsletter.index');
    Route::post('boletin/enviar', [NewsletterCampaignController::class, 'send'])->name('newsletter.send');

    Route::get('equipo', [TeamController::class, 'index'])->name('team.index');
    Route::put('equipo/{user}', [TeamController::class, 'update'])->name('team.update');

    Route::get('seguridad', [SecurityController::class, 'index'])->name('security.index');
    Route::post('seguridad/activar', [SecurityController::class, 'start'])->name('security.start');
    Route::post('seguridad/confirmar', [SecurityController::class, 'confirm'])->name('security.confirm');
    Route::post('seguridad/desactivar', [SecurityController::class, 'disable'])->name('security.disable');
    Route::post('seguridad/recuperacion', [SecurityController::class, 'regenerateRecoveryCodes'])->name('security.recovery');
});
