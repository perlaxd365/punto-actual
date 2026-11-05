@extends('layouts.app')

@section('title', 'Convocatorias | Panel Punto Actual')

@section('content')
<div class="container-page py-10">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="section-label">Administración</p>
            <h1 class="mt-1 text-3xl font-black tracking-tight">Convocatorias</h1>
        </div>
        <a class="btn-primary !px-4 !py-2" href="{{ route('admin.vacancies.create') }}">Nueva convocatoria</a>
    </div>

    @if(session('ok'))
        <p class="mt-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('ok') }}</p>
    @endif

    <div class="card mt-6 overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Puesto</th>
                    <th>Entidad</th>
                    <th>Régimen</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="font-semibold text-ink-950">{{ $item->title }}</td>
                        <td>{{ $item->entity?->name }}</td>
                        <td>{{ $item->employment_regime }}</td>
                        <td><span class="badge-soft">{{ $item->status }}</span></td>
                        <td class="text-right"><a class="link-red" href="{{ route('admin.vacancies.edit', $item) }}">Editar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-zinc-500">Aún no hay convocatorias.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection
