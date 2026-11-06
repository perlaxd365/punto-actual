@php($analytics = config('analytics'))
@if($analytics['enabled'] && $analytics['gtm_id'] && request()->cookie('pa_consent') === 'all')
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id={{ $analytics['gtm_id'] }}"
                height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
@endif
