@extends('layouts.app')

@section('title', 'Boletín | Panel Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver al panel</a>

    <div class="mt-5 max-w-3xl">
        <p class="section-label">Audiencia</p>
        <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">Enviar boletín</h1>
        <p class="mt-2 text-zinc-600">Se enviará a <strong class="text-ink-950">{{ $total }}</strong> suscriptores activos. Cada correo incluye el enlace para cancelar la suscripción.</p>

        @if(session('status'))
            <div class="mt-5 rounded-2xl border border-emerald-500/20 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-2xl border border-brand-500/20 bg-brand-50 p-4 text-sm font-semibold text-brand-700">
                @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <form method="post" action="{{ route('admin.newsletter.send') }}" class="card mt-6 grid gap-5 p-6">
            @csrf
            <label class="label">Asunto
                <input name="subject" required maxlength="150" value="{{ old('subject') }}" placeholder="Lo más importante de la semana" class="input">
            </label>
            <label class="label">Contenido
                <textarea name="body" required rows="12" maxlength="20000" placeholder="Escribe el texto del boletín. Puedes usar saltos de línea." class="input">{{ old('body') }}</textarea>
            </label>
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-xs text-zinc-500">El envío es inmediato. Verifica el remitente y el SMTP en <code class="rounded bg-zinc-100 px-1.5 py-0.5 text-brand-600">.env</code>.</p>
                <button class="btn-primary" onclick="return confirm('¿Enviar este boletín a {{ $total }} suscriptores?')">Enviar boletín</button>
            </div>
        </form>
    </div>
</div>
@endsection
