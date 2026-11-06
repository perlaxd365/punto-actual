@extends('layouts.app')

@section('title', 'Suscriptores | Panel Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver al panel</a>

    <div class="mt-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="section-label">Audiencia</p>
            <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">Suscriptores</h1>
            <p class="mt-2 text-zinc-600">{{ $total }} suscriptores activos para el boletín.</p>
        </div>
        <a href="{{ route('admin.subscribers.export') }}" class="btn-ghost !px-4 !py-2">Exportar CSV</a>
    </div>

    @if(session('status'))
        <div class="mt-5 rounded-2xl border border-emerald-500/20 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    <div class="mt-6 card overflow-hidden">
        <table class="admin-table">
            <thead>
                <tr class="border-b border-black/10 text-xs uppercase tracking-wider text-zinc-500">
                    <th class="p-4">Correo</th>
                    <th class="p-4">Nombre</th>
                    <th class="p-4">Origen</th>
                    <th class="p-4">Estado</th>
                    <th class="p-4">Registrado</th>
                    <th class="p-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $subscriber)
                    <tr class="border-b border-black/5 last:border-0">
                        <td class="p-4 font-semibold text-ink-950">{{ $subscriber->email }}</td>
                        <td class="p-4 text-zinc-600">{{ $subscriber->name ?: '—' }}</td>
                        <td class="p-4 text-zinc-600">{{ $subscriber->source }}</td>
                        <td class="p-4"><span class="{{ $subscriber->status === 'active' ? 'badge-red' : 'badge-soft' }}">{{ $subscriber->status === 'active' ? 'Activo' : $subscriber->status }}</span></td>
                        <td class="p-4 text-zinc-600">{{ $subscriber->created_at?->format('d/m/Y H:i') }}</td>
                        <td class="p-4 text-right">
                            <form method="post" action="{{ route('admin.subscribers.destroy', $subscriber) }}" onsubmit="return confirm('¿Eliminar este suscriptor?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-xs font-bold text-brand-600 transition hover:text-brand-700">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-10 text-center text-zinc-500">Aún no hay suscriptores.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-8">{{ $subscribers->links() }}</div>
</div>
@endsection
