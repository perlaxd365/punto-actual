<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Totp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SecurityController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $setupSecret = $user->hasTwoFactorEnabled() ? null : $request->session()->get('2fa_setup_secret');

        return view('admin.security.index', [
            'user' => $user,
            'setupSecret' => $setupSecret,
            'otpauth' => $setupSecret ? Totp::otpauthUrl($setupSecret, $user->email, config('puntoactual.site.name')) : null,
            'recoveryCodes' => $request->session()->get('recovery_codes', []),
        ]);
    }

    public function start(Request $request)
    {
        if ($request->user()->hasTwoFactorEnabled()) {
            return redirect()->route('admin.security.index');
        }

        $request->session()->put('2fa_setup_secret', Totp::randomSecret());

        return redirect()->route('admin.security.index')->with('status', 'Clave generada. Agrégala en tu app autenticadora.');
    }

    public function confirm(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $user = $request->user();
        $secret = $request->session()->get('2fa_setup_secret');

        if (! $secret) {
            return back()->withErrors(['code' => 'La sesión de configuración expiró. Vuelve a generar la clave.']);
        }

        if (! Totp::verify($secret, (string) $request->input('code'))) {
            return back()->withErrors(['code' => 'Código inválido. Verifica la hora de tu dispositivo e inténtalo de nuevo.']);
        }

        $codes = collect(range(1, 8))->map(fn () => strtoupper(Str::random(10)))->all();

        $user->forceFill([
            'two_factor_secret' => $secret,
            'two_factor_recovery_codes' => json_encode(array_map(fn ($code) => Hash::make($code), $codes)),
            'two_factor_confirmed_at' => now(),
        ])->save();

        $request->session()->forget('2fa_setup_secret');
        $request->session()->flash('recovery_codes', $codes);

        return redirect()->route('admin.security.index')->with('status', 'Verificación en dos pasos activada.');
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('admin.security.index')->with('status', 'Verificación en dos pasos desactivada.');
    }

    public function regenerateRecoveryCodes(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        $user = $request->user();

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Contraseña incorrecta.']);
        }

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('admin.security.index');
        }

        $codes = collect(range(1, 8))->map(fn () => strtoupper(Str::random(10)))->all();

        $user->forceFill([
            'two_factor_recovery_codes' => json_encode(array_map(fn ($code) => Hash::make($code), $codes)),
        ])->save();

        $request->session()->flash('recovery_codes', $codes);

        return redirect()->route('admin.security.index')->with('status', 'Nuevos códigos de recuperación generados.');
    }
}
