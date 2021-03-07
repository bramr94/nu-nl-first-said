<?php

return [
    'start_id' => env('START_ID', 6021052),
    'end_id' => env('END_ID', 6113408),
    'url' => env('ARTICLES_URL', 'https://www.nu.nl/maakt-niet-uit/'),

    'strip_from_articles' => [
        '?', ',', '.', '!', '“', '"', ':', ';', '(', ')', '/', '\\', '\'', '@', '#', '$', '%', '^', '&', '*', '='
    ]
];
