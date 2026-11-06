<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:news="http://www.google.com/schemas/sitemap-news/0.9">
    <url><loc>{{ route('home') }}</loc></url>
    <url><loc>{{ route('news.index') }}</loc></url>
    <url><loc>{{ route('vacancies.index') }}</loc></url>
    <url><loc>{{ route('news.international') }}</loc></url>
    <url><loc>{{ route('pages.nosotros') }}</loc></url>
    <url><loc>{{ route('pages.editorial') }}</loc></url>
    <url><loc>{{ route('pages.contacto') }}</loc></url>
    @foreach($categories as $category)
        <url>
            <loc>{{ route('categories.show', $category->slug) }}</loc>
            <lastmod>{{ $category->updated_at?->toAtomString() ?? now()->toAtomString() }}</lastmod>
        </url>
    @endforeach
    @foreach($cities as $city)
        <url>
            <loc>{{ route('news.city', $city['slug']) }}</loc>
        </url>
    @endforeach
    @foreach($news as $item)
        <url>
            <loc>{{ route('news.show', $item->slug) }}</loc>
            <lastmod>{{ $item->updated_at->toAtomString() }}</lastmod>
            @if($item->published_at && $item->published_at->gt(now()->subDays(2)))
                <news:news>
                    <news:publication>
                        <news:name>{{ config('puntoactual.site.name') }}</news:name>
                        <news:language>es</news:language>
                    </news:publication>
                    <news:publication_date>{{ $item->published_at->toAtomString() }}</news:publication_date>
                    <news:title>{{ $item->title }}</news:title>
                </news:news>
            @endif
        </url>
    @endforeach
    @foreach($jobs as $job)
        <url>
            <loc>{{ route('vacancies.show', $job->slug) }}</loc>
            <lastmod>{{ $job->updated_at->toAtomString() }}</lastmod>
        </url>
    @endforeach
</urlset>
