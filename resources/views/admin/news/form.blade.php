@extends('layouts.app')

@section('title', ($news->exists ? 'Editar' : 'Crear').' noticia | Panel Punto Actual')

@section('content')
<div class="container-page max-w-4xl py-10">
    <a href="{{ route('admin.news.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-600">← Volver a noticias</a>
    <h1 class="mt-3 text-3xl font-black tracking-tight">{{ $news->exists ? 'Editar' : 'Crear' }} noticia</h1>

    <form method="post" enctype="multipart/form-data" action="{{ $news->exists ? route('admin.news.update', $news) : route('admin.news.store') }}" class="card mt-6 grid gap-5 p-6 sm:p-8">
        @csrf
        @if($news->exists) @method('PUT') @endif

        @if($errors->any())
            <div class="rounded-xl border border-brand-500/30 bg-brand-500/10 px-4 py-3 text-sm text-brand-700">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <label class="label">Título
            <input name="title" required value="{{ old('title', $news->title) }}" class="input">
        </label>

        <label class="label">Categoría
            <select name="category_id" class="input">
                <option value="">Sin categoría</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(old('category_id', $news->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Ciudad (noticias nacionales)
                <input name="city" value="{{ old('city', $news->city) }}" placeholder="Lima, Arequipa..." class="input">
            </label>
            <label class="label">País (noticias internacionales)
                <input name="country" value="{{ old('country', $news->country) }}" placeholder="Estados Unidos, Suiza..." class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">En la categoría <strong>Internacional</strong> se muestra el país; en las demás, la ciudad.</p>

        <label class="label">Resumen
            <textarea name="excerpt" rows="3" class="input">{{ old('excerpt', $news->excerpt) }}</textarea>
        </label>

        <label class="label">Contenido HTML
            <textarea name="body" rows="14" required class="input font-mono text-xs">{{ old('body', $news->body) }}</textarea>
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Subir imagen
                <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/avif" class="input file:mr-3 file:rounded-full file:border-0 file:bg-brand-600 file:px-4 file:py-1.5 file:text-xs file:font-bold file:text-white">
            </label>
            <label class="label">O URL de imagen
                <input name="image" value="{{ old('image', $news->image) }}" placeholder="https://..." class="input">
            </label>
        </div>
        <p class="text-xs text-zinc-500">La imagen se sube a Cloudinary dentro de <code class="text-brand-600">punto-actual/noticias</code>.</p>

        @if($news->image)
            <img src="{{ $news->image }}" alt="Imagen actual" class="max-h-56 w-full rounded-xl border border-black/10 object-cover">
        @endif

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">Fuente
                <input name="source_name" value="{{ old('source_name', $news->source_name) }}" class="input">
            </label>
            <label class="label">URL de la fuente
                <input name="source_url" value="{{ old('source_url', $news->source_url) }}" class="input">
            </label>
        </div>

        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
            <label class="label">Estado
                <select name="status" class="input">
                    <option value="draft" @selected(old('status', $news->status) === 'draft')>Borrador</option>
                    <option value="published" @selected(old('status', $news->status) === 'published')>Publicado</option>
                </select>
            </label>
            <label class="label">Fecha de publicación
                <input type="datetime-local" name="published_at" value="{{ old('published_at', $news->published_at?->format('Y-m-d\TH:i')) }}" class="input">
            </label>
            <label class="label">Destacada
                <span class="flex items-center gap-2 rounded-xl border border-black/10 bg-zinc-100 px-4 py-3 text-sm text-zinc-800">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $news->is_featured)) class="h-4 w-4 rounded border-black/20 bg-white text-brand-600">
                    Sección noticias
                </span>
            </label>
            <label class="label">Poner en portada
                <span class="flex items-center gap-2 rounded-xl border border-brand-500/20 bg-brand-50 px-4 py-3 text-sm text-brand-700">
                    <input type="checkbox" name="in_cover" value="1" @checked(old('in_cover', $news->in_cover)) class="h-4 w-4 rounded border-black/20 bg-white text-brand-600">
                    Máx. 4
                </span>
            </label>
        </div>
        <p class="text-xs text-zinc-500">El carrusel de portada usa las noticias marcadas aquí (máximo 4). La última marcada aparece primero.</p>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="label">SEO título
                <input name="seo_title" value="{{ old('seo_title', $news->seo_title) }}" class="input">
            </label>
            <label class="label">SEO descripción
                <input name="seo_description" value="{{ old('seo_description', $news->seo_description) }}" class="input">
            </label>
        </div>

        <button class="btn-primary">Guardar noticia</button>
    </form>
</div>
@endsection
