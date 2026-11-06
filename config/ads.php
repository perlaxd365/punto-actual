<?php

return [

    'enabled' => (bool) env('ADS_ENABLED', false),

    'client' => env('GOOGLE_ADSENSE_ID'),

    'pub_id' => env('GOOGLE_ADSENSE_PUB_ID'),

    'slots' => [
        'home' => env('ADS_SLOT_HOME'),
        'article' => env('ADS_SLOT_ARTICLE'),
        'sidebar' => env('ADS_SLOT_SIDEBAR'),
    ],

];
