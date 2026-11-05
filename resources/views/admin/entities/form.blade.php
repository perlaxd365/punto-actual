@extends('layouts.app')

@section('title', ($entity->exists ? 'Editar' : 'Crear').' entidad | Panel Punto Actual')

@section('content')
<div class="container-page max-w-3xl py-10">
    <a href="{{ route('admin.entities.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-600">← Volver a entidades</a>
    <h1 class="mt-3 text-3xl font-black tracking-tight">{{ $entity->exists ? 'Editar' : 'Crear' }} entidad</h1>

    <form method="post" enctype="multipart/form-data" action="{{ $entity->exists ? route('admin.entities.update', $entity) : route('admin.entities.store') }}" class="card mt-6 grid gap-5 p-6 sm:p-8">
        @csrf
        @if($entity->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-4 py-3 text-sm text-brand-700">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        @foreach(['name' => 'Nombre de entidad', 'entity_type' => 'Tipo (Pública / Privada)', 'region' => 'Región', 'website' => 'Sitio web', 'official_jobs_url' => 'Portal oficial de empleos'] as $field => $label)
            <label class="label">{{ $label }}
                <input name="{{ $field }}" value="{{ old($field, $entity->$field) }}" class="input">
            </label>
        @endforeach

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Subir logotipo
                <input type="file" name="logo_file" accept="image/jpeg,image/png,image/webp,image/avif" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-600 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white">
            </label>
            <label class="label">O URL del logotipo
                <input name="logo" value="{{ old('logo', $entity->logo) }}" class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">El logotipo se sube a Cloudinary dentro de <code class="text-brand-600">punto-actual/entidades</code>.</p>

        @if($entity->logo)
            <img src="{{ $entity->logo }}" alt="Logotipo actual" class="h-24 w-24 rounded-xl border border-black/10 object-cover">
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Subir portada
                <input type="file" name="cover_file" accept="image/jpeg,image/png,image/webp,image/avif" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-600 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white">
            </label>
            <label class="label">O URL de portada
                <input name="cover_image" value="{{ old('cover_image', $entity->cover_image) }}" placeholder="https://..." class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">La portada se sube a Cloudinary dentro de <code class="text-brand-600">punto-actual/entidades</code>.</p>

        @if($entity->cover_image)
            <img src="{{ $entity->cover_image }}" alt="Portada actual" class="max-h-48 w-full rounded-xl border border-black/10 object-cover">
        @endif

        <label class="label">Descripción
            <textarea name="description" rows="5" class="input">{{ old('description', $entity->description) }}</textarea>
        </label>

        <button class="btn-primary">Guardar entidad</button>
    </form>
</div>
@endsection
