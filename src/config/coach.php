<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Demo Config
    |--------------------------------------------------------------------------
    |
    | The following config lines are used for development of package
    | Shep/Coach
    |
    */

    'key' => 'value',

    'assets_path' => '/vendor/shep/coach/assets',

    'lang_path' => '/vendor/shep/coach/src/resources/lang',

    'controllers' => [
        'namespace' => 'Shep\\Coach\\Http\\Controllers',
    ],

    'storage' => [
        'disk' => 'coach',
    ],

    'upload' => [
        'base_path' => 'coach/upload/',
        'path' => ['slug', 'id'],
        'photo' => [
            'extension' => 'webp',
            'max_size' => 2000,
            'options' => [
                'webp:method' => '6',
            ]
        ]
    ],

    'disks' => [
        'Coach' => [
            'driver' => 'local',
            'root' => public_path().'/vendor/shep/coach/assets',
            'url' => env('APP_URL').'/vendor/shep/coach/assets',
            'visibility' => 'public',
        ]
    ],

];
