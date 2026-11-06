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
        'tagline' => env('SITE_TAGLINE', 'Noticias, empleos y cursos del Perú'),
        'default_image' => env('SITE_DEFAULT_IMAGE'),
        'email' => env('SITE_EMAIL', 'administracion@brevare.com'),
        'phone' => env('SITE_PHONE', '+51902517849'),
        'phone_display' => env('SITE_PHONE_DISPLAY', '+51 902 517 849'),
        'address' => env('SITE_ADDRESS', 'Avenida Camino Real 456, San Isidro, Lima, Perú'),
    ],

    'api_token' => env('API_TOKEN'),

];
