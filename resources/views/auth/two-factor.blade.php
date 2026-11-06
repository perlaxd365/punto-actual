@extends('layouts.app')

@section('title', 'Verificación en dos pasos | Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page flex min-h-[70vh] max-w-md items-center py-12">
    <div class="card w-full p-7 sm:p-9">
        <p class="section-label">Seguridad</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight">Verificación en dos pasos</h1>
        <p class="mt-2 text-sm text-zinc-500">Ingresa el código de 6 dígitos de tu app autenticadora o uno de tus códigos de recuperación.</p>

        <form method="post" action="{{ route('two-factor.challenge') }}" class="mt-7 grid gap-5">
            @csrf
            <label class="label">Código
                <input name="code" required autofocus autocomplete="one-time-code" inputmode="numeric" class="input text-center text-lg tracking-[0.3em]">
            </label>
            @error('code')<p class="text-sm font-semibold text-brand-600">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">Verificar</button>
        </form>

        <a href="{{ route('login') }}" class="mt-5 inline-block text-sm font-semibold text-zinc-500 transition hover:text-brand-700">Volver al inicio de sesión</a>
    </div>
</div>
@endsection
