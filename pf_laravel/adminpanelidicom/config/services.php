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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'filters' => [
        'focs' => env('FILTERS_FOCS', 'http://filter_focs/images'),
        'norm' => env('FILTERS_NORM', 'http://filter_norm/images'),
        'kont' => env('FILTERS_KONT', 'http://filter_kont/images'),
        'kont3' => env('FILTERS_KONT3', 'http://filter_kont3/images'),
        'centr' => env('FILTERS_CENTR', 'http://filter_centr/images'),
        '3dm' => env('FILTERS_3DM', 'http://filter_3dm/images'),
    ]

];
