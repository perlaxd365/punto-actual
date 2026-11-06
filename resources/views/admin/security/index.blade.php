@extends('layouts.app')

@section('title', 'Seguridad | Panel Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver al panel</a>

    <div class="mt-5 max-w-3xl">
        <p class="section-label">Seguridad de la cuenta</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">Verificación en dos pasos</h1>
        <p class="mt-2 text-zinc-600">Protege el panel editorial con un código adicional generado por tu app autenticadora (Google Authenticator, Authy, 1Password, etc.).</p>

        @if(session('status'))
            <div class="mt-5 rounded-2xl border border-emerald-500/20 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-2xl border border-brand-500/20 bg-brand-50 p-4 text-sm font-semibold text-brand-700">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @if(!empty($recoveryCodes))
            <div class="mt-5 rounded-2xl border border-amber-500/30 bg-amber-50 p-5">
                <p class="text-sm font-bold text-amber-800">Guarda estos códigos de recuperación. No se volverán a mostrar.</p>
                <div class="mt-3 grid grid-cols-2 gap-2 font-mono text-sm text-amber-900 sm:grid-cols-4">
                    @foreach($recoveryCodes as $code)
                        <span class="rounded-lg border border-amber-500/30 bg-white/70 px-2 py-1 text-center">{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="mt-6 card p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Estado</p>
                    <p class="mt-1 text-lg font-black text-ink-950">{{ $user->hasTwoFactorEnabled() ? 'Activado' : 'Desactivado' }}</p>
                </div>
                <span class="{{ $user->hasTwoFactorEnabled() ? 'badge-red' : 'badge-soft' }}">{{ $user->hasTwoFactorEnabled() ? 'Protegido' : 'Sin 2FA' }}</span>
            </div>

            @if(! $user->hasTwoFactorEnabled())
                @if(! $setupSecret)
                    <form method="post" action="{{ route('admin.security.start') }}" class="mt-5">
                        @csrf
                        <button class="btn-primary">Activar verificación en dos pasos</button>
                    </form>
                @else
                    <div class="mt-5 grid gap-4">
                        <div class="rounded-2xl border border-black/10 bg-zinc-50 p-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-zinc-500">Clave secreta</p>
                            <code class="mt-1 block break-all text-base font-bold tracking-wider text-ink-950">{{ $setupSecret }}</code>
                            <p class="mt-2 text-xs text-zinc-500">Ingrésala manualmente en tu app autenticadora. También puedes usar esta URI: </p>
                            <code class="mt-1 block break-all text-xs text-zinc-600">{{ $otpauth }}</code>
                        </div>

                        <form method="post" action="{{ route('admin.security.confirm') }}" class="grid gap-3">
                            @csrf
                            <label class="label">Código de 6 dígitos
                                <input name="code" required inputmode="numeric" autocomplete="one-time-code" class="input text-center text-lg tracking-[0.3em]">
                            </label>
                            <button class="btn-primary">Confirmar y activar</button>
                        </form>
                    </div>
                @endif
            @else
                <div class="mt-5 grid gap-5">
                    <form method="post" action="{{ route('admin.security.recovery') }}" class="grid gap-3 rounded-2xl border border-black/10 p-4">
                        @csrf
                        <p class="text-sm font-bold text-ink-950">Regenerar códigos de recuperación</p>
                        <label class="label">Contraseña
                            <input type="password" name="password" required class="input">
                        </label>
                        <button class="btn-ghost justify-self-start">Generar nuevos códigos</button>
                    </form>

                    <form method="post" action="{{ route('admin.security.disable') }}" class="grid gap-3 rounded-2xl border border-brand-500/20 bg-brand-500/[0.04] p-4">
                        @csrf
                        <p class="text-sm font-bold text-ink-950">Desactivar verificación en dos pasos</p>
                        <label class="label">Contraseña
                            <input type="password" name="password" required class="input">
                        </label>
                        <button class="btn-ghost justify-self-start border-brand-500/30 text-brand-700">Desactivar 2FA</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
