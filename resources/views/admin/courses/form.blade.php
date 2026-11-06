@extends('layouts.app')

@section('title', ($course->exists ? 'Editar' : 'Crear').' curso | Panel Punto Actual')

@section('content')
<div class="container-page max-w-3xl py-10">
    <a href="{{ route('admin.courses.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-600">← Volver a cursos</a>
    <h1 class="mt-3 text-3xl font-black tracking-tight">{{ $course->exists ? 'Editar' : 'Crear' }} curso</h1>

    <form method="post" enctype="multipart/form-data" action="{{ $course->exists ? route('admin.courses.update', $course) : route('admin.courses.store') }}" class="card mt-6 grid gap-5 p-6 sm:p-8">
        @csrf
        @if($course->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-4 py-3 text-sm text-brand-700">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <label class="label">Título del curso
            <input name="title" value="{{ old('title', $course->title) }}" class="input" required>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Institución
                <select name="entity_id" class="input">
                    <option value="">Sin institución</option>
                    @foreach($entities as $entity)
                        <option value="{{ $entity->id }}" @selected((int) old('entity_id', $course->entity_id) === $entity->id)>{{ $entity->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="label">Área
                <input name="area" value="{{ old('area', $course->area) }}" placeholder="Tecnología, Idiomas, Negocios..." class="input" required>
            </label>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            <label class="label">Modalidad
                <input name="modality" value="{{ old('modality', $course->modality) }}" placeholder="Online, presencial, híbrido" class="input">
            </label>
            <label class="label">Nivel
                <input name="level" value="{{ old('level', $course->level) }}" placeholder="Básico, intermedio, avanzado" class="input">
            </label>
            <label class="label">Duración
                <input name="duration" value="{{ old('duration', $course->duration) }}" placeholder="3 meses, 40 horas..." class="input">
            </label>
        </div>

        <label class="label">Resumen
            <textarea name="excerpt" rows="2" maxlength="500" class="input">{{ old('excerpt', $course->excerpt) }}</textarea>
        </label>

        <label class="label">Descripción
            <textarea name="description" rows="6" class="input">{{ old('description', $course->description) }}</textarea>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Subir imagen del curso (obligatoria)
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/avif" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-600 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white">
            </label>
            <label class="label">O URL de imagen
                <input name="image" value="{{ old('image', $course->image) }}" placeholder="https://..." class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">La imagen se sube a Cloudinary dentro de <code class="text-brand-600">punto-actual/cursos</code>. Si no subes una, se usará la portada o el logo de la institución seleccionada.</p>

        @if($course->display_image)
            <img src="{{ $course->display_image }}" alt="Imagen actual" class="max-h-48 w-full rounded-xl border border-black/10 object-cover">
            @if(! $course->image)
                <p class="text-xs font-semibold text-brand-600">Este curso está usando la imagen de la institución.</p>
            @endif
        @endif

        <label class="label">Enlace oficial del curso
            <input name="official_url" value="{{ old('official_url', $course->official_url) }}" placeholder="https://..." class="input" required>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Estado
                <select name="status" class="input">
                    <option value="draft" @selected(old('status', $course->status) === 'draft')>Borrador</option>
                    <option value="published" @selected(old('status', $course->status) === 'published')>Publicado</option>
                </select>
            </label>
            <label class="label">Fecha de publicación
                <input type="datetime-local" name="published_at" value="{{ old('published_at', $course->published_at?->format('Y-m-d\TH:i')) }}" class="input">
            </label>
        </div>

        <label class="flex items-center gap-2 text-sm font-semibold text-zinc-700">
            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $course->is_featured)) class="rounded border-black/20 text-brand-600 focus:ring-brand-500">
            Destacar en el directorio y en la portada
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Título SEO
                <input name="seo_title" value="{{ old('seo_title', $course->seo_title) }}" class="input">
            </label>
            <label class="label">Descripción SEO
                <input name="seo_description" value="{{ old('seo_description', $course->seo_description) }}" maxlength="320" class="input">
            </label>
        </div>

        <button class="btn-primary">Guardar curso</button>
    </form>
</div>
@endsection
