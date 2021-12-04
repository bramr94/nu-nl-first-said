<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'twitter' => [
        'auth_access_token' => env('TWITTER_AUTH_ACCESS_TOKEN', ''),
        'auth_account_token_secret' => env('TWITTER_AUTH_ACCESS_TOKEN_SECRET', ''),
        'consumer_access_token' => env('TWITTER_CONSUMER_ACCESS_TOKEN', ''),
        'consumer_access_token_secret' => env('TWITTER_CONSUMER_ACCESS_TOKEN_SECRET', ''),
        'bearer' => env('TWITTER_BEARER', '')
    ]

];
