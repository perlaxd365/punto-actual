@extends('layouts.app')

@section('title', 'Nueva contraseña | Punto Actual')

@section('content')
<div class="container-page flex min-h-[70vh] max-w-md items-center py-12">
    <div class="card w-full p-7 sm:p-9">
        <p class="section-label">Panel editorial</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight">Nueva contraseña</h1>
        <p class="mt-2 text-sm text-zinc-500">Define una contraseña de al menos 8 caracteres para tu cuenta.</p>

        <form method="post" action="{{ route('password.update') }}" class="mt-7 grid gap-5">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <label class="label">Correo electrónico
                <input type="email" name="email" required value="{{ old('email', $email) }}" class="input">
            </label>
            <label class="label">Nueva contraseña
                <input type="password" name="password" required autofocus class="input">
            </label>
            <label class="label">Confirmar contraseña
                <input type="password" name="password_confirmation" required class="input">
            </label>
            @error('email')<p class="text-sm font-semibold text-brand-600">{{ $message }}</p>@enderror
            @error('password')<p class="text-sm font-semibold text-brand-600">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">Actualizar contraseña</button>
        </form>

        <p class="mt-6 text-center text-sm text-zinc-500">
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 underline underline-offset-4">Volver a ingresar</a>
        </p>
    </div>
</div>
@endsection
