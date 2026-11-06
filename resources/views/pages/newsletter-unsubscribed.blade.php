@extends('layouts.app')

@section('title', 'Suscripción cancelada | Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page flex min-h-[60vh] max-w-lg items-center py-16">
    <div class="card w-full p-8 text-center sm:p-10">
        <p class="section-label">Boletín</p>
        <h1 class="mt-2 text-3xl font-black tracking-tight">Suscripción cancelada</h1>
        <p class="mt-3 text-sm leading-relaxed text-zinc-600">El correo <strong class="text-ink-950">{{ $subscriber->email }}</strong> ya no recibirá el boletín de Punto Actual. Puedes volver a suscribirte cuando quieras desde el portal.</p>
        <a href="{{ route('home') }}" class="btn-primary mt-6">Volver al inicio</a>
    </div>
</div>
@endsection
