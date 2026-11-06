@extends('layouts.app')

@section('title', 'Equipo | Panel Punto Actual')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="container-page py-10 sm:py-14">
    <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-700">← Volver al panel</a>

    <div class="mt-5 flex flex-wrap items-end justify-between gap-3">
        <div>
            <p class="section-label">Equipo</p>
            <h1 class="mt-1 text-3xl font-black tracking-tight sm:text-4xl">Usuarios y roles</h1>
            <p class="mt-2 text-zinc-600">Administra quién puede entrar al panel y con qué rol.</p>
        </div>
        <a href="{{ route('admin.security.index') }}" class="btn-ghost !px-4 !py-2">Seguridad de mi cuenta</a>
    </div>

    @if(session('status'))
        <div class="mt-5 rounded-2xl border border-emerald-500/20 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">{{ session('status') }}</div>
    @endif

    @if($errors->any())
        <div class="mt-5 rounded-2xl border border-brand-500/20 bg-brand-50 p-4 text-sm font-semibold text-brand-700">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <div class="mt-6 card overflow-hidden">
        <table class="admin-table">
            <thead>
                <tr class="border-b border-black/10 text-xs uppercase tracking-wider text-zinc-500">
                    <th class="p-4">Usuario</th>
                    <th class="p-4">Correo</th>
                    <th class="p-4">2FA</th>
                    <th class="p-4">Rol</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr class="border-b border-black/5 last:border-0">
                        <td class="p-4 font-semibold text-ink-950">{{ $user->name }}</td>
                        <td class="p-4 text-zinc-600">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="{{ $user->hasTwoFactorEnabled() ? 'badge-red' : 'badge-soft' }}">{{ $user->hasTwoFactorEnabled() ? 'Activo' : 'Inactivo' }}</span>
                        </td>
                        <td class="p-4">
                            <form method="post" action="{{ route('admin.team.update', $user) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="input !w-auto !py-2 text-sm">
                                    <option value="admin" @selected($user->role === 'admin')>Administrador</option>
                                    <option value="editor" @selected($user->role === 'editor')>Editor</option>
                                </select>
                                <button class="btn-ghost !px-3 !py-2 text-xs">Guardar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
