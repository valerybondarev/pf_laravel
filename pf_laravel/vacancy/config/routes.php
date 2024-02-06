<?php

return [
    'namespace' => 'App\Http',

    'modules' => [
        'api' => [
            'namespace' => 'Api\Controllers',
            'directory' => 'api',
            'prefix' => 'api',
            'as' => 'api.',
            'middleware' => ['web', 'api'],
        ],

        'web' => [
            'namespace' => 'Web\Controllers',
            'directory' => 'web',
            'as' => 'web.',
            'middleware' => 'web',
        ],

        'admin' => [
            'namespace' => 'Admin\Controllers',
            'directory' => 'admin',
            'prefix' => 'admin',
            'as' => 'admin.',
            'middleware' => ['web', 'admin.auth', 'permission:admin'],
        ],

        'admin-auth' => [
            'namespace' => 'Admin\Controllers\Auth',
            'directory' => 'admin-auth',
            'prefix' => 'admin\auth',
            'as' => 'admin.auth.',
            'middleware' => ['web'],
        ],
    ],

];
