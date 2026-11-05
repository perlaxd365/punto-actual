<?php

return [

    'social' => [
        'facebook' => env('SOCIAL_FACEBOOK_URL'),
        'instagram' => env('SOCIAL_INSTAGRAM_URL'),
        'tiktok' => env('SOCIAL_TIKTOK_URL'),
        'x' => env('SOCIAL_X_URL'),
        'youtube' => env('SOCIAL_YOUTUBE_URL'),
    ],

    'site' => [
        'name' => env('APP_NAME', 'Punto Actual'),
        'tagline' => env('SITE_TAGLINE', 'Noticias y empleos del Perú'),
        'default_image' => env('SITE_DEFAULT_IMAGE'),
    ],

    'api_token' => env('API_TOKEN'),

];
