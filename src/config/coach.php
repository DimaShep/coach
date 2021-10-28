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

    'controllers' => [
        'namespace' => 'Shep\\Coach\\Http\\Controllers',
    ],

    'storage' => [
        'disk' => 'coach',
    ],

    'disks' => [
        'coach' => [
            'driver' => 'local',
            'root' => public_path().'/vendor/shep/coach/assets',
            'url' => env('APP_URL').'/vendor/shep/coach/assets',
            'visibility' => 'public',
        ]
    ],

];
