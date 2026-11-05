@extends('layouts.app')

@section('title', 'Ingresar | Punto Actual')

@section('content')
<div class="container-page flex min-h-[70vh] max-w-md items-center py-12">
    <div class="card w-full p-7 sm:p-9">
        <p class="section-label">Panel editorial</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight">Ingresar</h1>
        <p class="mt-2 text-sm text-zinc-500">Acceso exclusivo para el equipo de Punto Actual.</p>

        <form method="post" action="{{ route('login') }}" class="mt-7 grid gap-5">
            @csrf
            <label class="label">Correo electrónico
                <input type="email" name="email" required autofocus value="{{ old('email') }}" class="input">
            </label>
            <label class="label">Contraseña
                <input type="password" name="password" required class="input">
            </label>
            <label class="flex items-center gap-2 text-sm text-zinc-600">
                <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-black/20 bg-white text-brand-600"> Recordarme
            </label>
            @error('email')<p class="text-sm font-semibold text-brand-600">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">Entrar</button>
        </form>
    </div>
</div>
@endsection
