<?php

return [

    'enabled' => (bool) env('ANALYTICS_ENABLED', true),

    'ga4_id' => env('GOOGLE_ANALYTICS_ID'),

    'gtm_id' => env('GOOGLE_TAG_MANAGER_ID'),

    'clarity_id' => env('MICROSOFT_CLARITY_ID'),

    'meta_pixel_id' => env('META_PIXEL_ID'),

    'tiktok_pixel_id' => env('TIKTOK_PIXEL_ID'),

    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    'bing_site_verification' => env('BING_SITE_VERIFICATION'),

];
