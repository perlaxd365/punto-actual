@extends('layouts.app')

@section('title', 'Cursos | Panel Punto Actual')

@section('content')
<div class="container-page py-10">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="section-label">Administración</p>
            <h1 class="mt-1 text-3xl font-black tracking-tight">Cursos</h1>
        </div>
        <a class="btn-primary !px-4 !py-2" href="{{ route('admin.courses.create') }}">Nuevo curso</a>
    </div>

    @if(session('ok'))
        <p class="mt-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-300">{{ session('ok') }}</p>
    @endif

    <div class="card mt-6 overflow-x-auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Curso</th>
                    <th>Área</th>
                    <th>Institución</th>
                    <th>Estado</th>
                    <th>Destacado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $item)
                    <tr>
                        <td class="font-semibold text-ink-950">{{ $item->title }}</td>
                        <td>{{ $item->area }}</td>
                        <td>{{ $item->entity?->name ?? '—' }}</td>
                        <td>
                            <span class="{{ $item->status === 'published' ? 'badge-red' : 'badge-soft' }}">{{ $item->status === 'published' ? 'Publicado' : 'Borrador' }}</span>
                        </td>
                        <td>{{ $item->is_featured ? 'Sí' : '—' }}</td>
                        <td class="whitespace-nowrap text-right">
                            <a class="link-red" href="{{ route('admin.courses.edit', $item) }}">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-8 text-center text-zinc-500">Aún no hay cursos. Crea el primero.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $items->links() }}</div>
</div>
@endsection
