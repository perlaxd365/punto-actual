@if(config('ads.enabled') && config('ads.client') && request()->cookie('pa_consent') === 'all')
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('ads.client') }}" crossorigin="anonymous"></script>
@endif
