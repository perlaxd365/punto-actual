@extends('layouts.app')

@section('title', 'Recuperar contraseña | Punto Actual')

@section('content')
<div class="container-page flex min-h-[70vh] max-w-md items-center py-12">
    <div class="card w-full p-7 sm:p-9">
        <p class="section-label">Panel editorial</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight">Recuperar contraseña</h1>
        <p class="mt-2 text-sm text-zinc-500">Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.</p>

        @if(session('status'))
            <p class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('password.email') }}" class="mt-7 grid gap-5">
            @csrf
            <label class="label">Correo electrónico
                <input type="email" name="email" required autofocus value="{{ old('email') }}" class="input">
            </label>
            @error('email')<p class="text-sm font-semibold text-brand-600">{{ $message }}</p>@enderror
            <button class="btn-primary w-full">Enviar enlace</button>
        </form>

        <p class="mt-6 text-center text-sm text-zinc-500">
            <a href="{{ route('login') }}" class="font-semibold text-brand-600 underline underline-offset-4">Volver a ingresar</a>
        </p>
    </div>
</div>
@endsection
