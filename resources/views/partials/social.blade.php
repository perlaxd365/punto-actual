@php
    $social = collect(config('puntoactual.social'))->filter();
@endphp
@if($social->isNotEmpty())
<div class="flex items-center gap-1">
    @if($social->get('facebook'))
        <a href="{{ $social->get('facebook') }}" target="_blank" rel="noopener" data-track="social_click" data-track-network="facebook" aria-label="Facebook" class="rounded-full p-2 text-zinc-600 transition hover:bg-black/5 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.44 2.89h-2.34v6.99A10 10 0 0 0 22 12Z"/></svg>
        </a>
    @endif
    @if($social->get('instagram'))
        <a href="{{ $social->get('instagram') }}" target="_blank" rel="noopener" data-track="social_click" data-track-network="instagram" aria-label="Instagram" class="rounded-full p-2 text-zinc-600 transition hover:bg-black/5 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41a3.8 3.8 0 0 1-1.38-.9 3.8 3.8 0 0 1-.9-1.38c-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16Zm0 1.8c-3.15 0-3.52.01-4.76.07-1.15.05-1.77.24-2.19.41-.55.21-.94.47-1.35.88-.41.41-.67.8-.88 1.35-.17.42-.36 1.04-.41 2.19-.06 1.24-.07 1.61-.07 4.76s.01 3.52.07 4.76c.05 1.15.24 1.77.41 2.19.21.55.47.94.88 1.35.41.41.8.67 1.35.88.42.17 1.04.36 2.19.41 1.24.06 1.61.07 4.76.07s3.52-.01 4.76-.07c1.15-.05 1.77-.24 2.19-.41.55-.21.94-.47 1.35-.88.41-.41.67-.8.88-1.35.17-.42.36-1.04.41-2.19.06-1.24.07-1.61.07-4.76s-.01-3.52-.07-4.76c-.05-1.15-.24-1.77-.41-2.19a3.6 3.6 0 0 0-.88-1.35 3.6 3.6 0 0 0-1.35-.88c-.42-.17-1.04-.36-2.19-.41-1.24-.06-1.61-.07-4.76-.07Zm0 3.06a4.98 4.98 0 1 1 0 9.96 4.98 4.98 0 0 1 0-9.96Zm0 1.8a3.18 3.18 0 1 0 0 6.36 3.18 3.18 0 0 0 0-6.36Zm5.18-3.24a1.16 1.16 0 1 1 0 2.32 1.16 1.16 0 0 1 0-2.32Z"/></svg>
        </a>
    @endif
    @if($social->get('tiktok'))
        <a href="{{ $social->get('tiktok') }}" target="_blank" rel="noopener" data-track="social_click" data-track-network="tiktok" aria-label="TikTok" class="rounded-full p-2 text-zinc-600 transition hover:bg-black/5 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16.6 5.82A5.6 5.6 0 0 0 15.5 2h-3.1v13.2a2.6 2.6 0 1 1-1.85-2.49V9.53a5.7 5.7 0 1 0 4.95 5.64V8.7a8.7 8.7 0 0 0 4.5 1.26V6.87a5.6 5.6 0 0 1-3.4-1.05Z"/></svg>
        </a>
    @endif
    @if($social->get('x'))
        <a href="{{ $social->get('x') }}" target="_blank" rel="noopener" data-track="social_click" data-track-network="x" aria-label="X" class="rounded-full p-2 text-zinc-600 transition hover:bg-black/5 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.21-6.82-5.96 6.82H1.69l7.73-8.84L1.26 2.25h6.82l4.71 6.23 5.45-6.23Zm-1.16 17.52h1.83L7.01 4.13H5.05l12.03 15.64Z"/></svg>
        </a>
    @endif
    @if($social->get('youtube'))
        <a href="{{ $social->get('youtube') }}" target="_blank" rel="noopener" data-track="social_click" data-track-network="youtube" aria-label="YouTube" class="rounded-full p-2 text-zinc-600 transition hover:bg-black/5 hover:text-brand-600">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z"/></svg>
        </a>
    @endif
</div>
@endif
