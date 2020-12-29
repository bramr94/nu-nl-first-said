<?php

return [
    'start_id' => env('START_ID'),
    'url' => env('ARTICLES_URL', 'https://www.nu.nl/maakt-niet-uit/'),

    'strip_from_articles' => [
        '?', ',', '.', '!', '“', '"', '"”'
    ]
];
