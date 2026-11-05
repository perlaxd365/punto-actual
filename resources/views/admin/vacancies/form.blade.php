@extends('layouts.app')

@section('title', ($vacancy->exists ? 'Editar' : 'Crear').' convocatoria | Panel Punto Actual')

@section('content')
<div class="container-page max-w-5xl py-10">
    <a href="{{ route('admin.vacancies.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-600">← Volver a convocatorias</a>
    <h1 class="mt-3 text-3xl font-black tracking-tight">{{ $vacancy->exists ? 'Editar' : 'Crear' }} convocatoria</h1>

    <form method="post" enctype="multipart/form-data" action="{{ $vacancy->exists ? route('admin.vacancies.update', $vacancy) : route('admin.vacancies.store') }}" class="card mt-6 grid gap-5 p-6 sm:p-8">
        @csrf
        @if($vacancy->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-4 py-3 text-sm text-brand-700">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <label class="label">Entidad
            <select name="entity_id" required class="input">
                @foreach($entities as $entity)
                    <option value="{{ $entity->id }}" @selected(old('entity_id', $vacancy->entity_id) == $entity->id)>{{ $entity->name }}</option>
                @endforeach
            </select>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Subir portada
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/avif" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-600 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white">
            </label>
            <label class="label">O URL de portada
                <input name="image" value="{{ old('image', $vacancy->image) }}" placeholder="https://..." class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">La portada se sube a Cloudinary dentro de <code class="text-brand-600">punto-actual/convocatorias</code>.</p>

        @if($vacancy->image)
            <img src="{{ $vacancy->image }}" alt="Portada actual" class="max-h-56 w-full rounded-xl border border-black/10 object-cover">
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Puesto
                <input name="title" required value="{{ old('title', $vacancy->title) }}" class="input">
            </label>
            <label class="label">Código
                <input name="reference_code" value="{{ old('reference_code', $vacancy->reference_code) }}" class="input">
            </label>
            <label class="label">Régimen
                <input name="employment_regime" required placeholder="CAS, 728, 276..." value="{{ old('employment_regime', $vacancy->employment_regime) }}" class="input">
            </label>
            <label class="label">Tipo
                <input name="employment_type" value="{{ old('employment_type', $vacancy->employment_type) }}" class="input">
            </label>
        </div>

        @foreach(['description' => 'Descripción', 'functions' => 'Funciones', 'requirements' => 'Requisitos', 'education_level' => 'Formación académica', 'professional_career' => 'Carrera profesional', 'general_experience' => 'Experiencia general', 'specific_experience' => 'Experiencia específica', 'courses_training' => 'Cursos y especialización', 'knowledge' => 'Conocimientos', 'schedule' => 'Cronograma'] as $field => $label)
            <label class="label">{{ $label }}
                <textarea name="{{ $field }}" rows="3" class="input">{{ old($field, $vacancy->$field) }}</textarea>
            </label>
        @endforeach

        <div class="grid gap-5 md:grid-cols-3">
            @foreach(['salary_min' => 'Remuneración mínima', 'salary_max' => 'Remuneración máxima', 'vacancies_count' => 'Número de plazas', 'department' => 'Departamento', 'province' => 'Provincia', 'district' => 'Distrito', 'workplace' => 'Lugar de prestación', 'work_mode' => 'Modalidad', 'published_at' => 'Fecha de publicación', 'application_start_at' => 'Inicio de postulación', 'application_end_at' => 'Fin de postulación'] as $field => $label)
                <label class="label">{{ $label }}
                    <input name="{{ $field }}" value="{{ old($field, $vacancy->$field) }}" class="input"
                        @if(str_contains($field, 'at')) type="datetime-local" @elseif(str_contains($field, 'salary') || $field === 'vacancies_count') type="number" step="0.01" @endif>
                </label>
            @endforeach
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Moneda
                <input name="salary_currency" value="{{ old('salary_currency', $vacancy->salary_currency ?: 'PEN') }}" class="input">
            </label>
            <label class="label">Estado
                <select name="status" class="input">
                    @foreach(['draft' => 'Borrador', 'published' => 'Publicado', 'expiring' => 'Por vencer', 'finished' => 'Finalizado', 'cancelled' => 'Cancelado'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $vacancy->status) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <label class="label">URL oficial de convocatoria
            <input name="official_url" required value="{{ old('official_url', $vacancy->official_url) }}" class="input">
        </label>
        <label class="label">URL de bases PDF
            <input name="bases_url" value="{{ old('bases_url', $vacancy->bases_url) }}" class="input">
        </label>

        <button class="btn-primary">Guardar convocatoria</button>
    </form>
</div>
@endsection
