<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Constant Variables
    |--------------------------------------------------------------------------
    |
    | This value is the global variables to your app, 
    | so you can retrieve common value without over and over.
    |
    */

    'default' => [
        'sort_order'       => 'desc',
        'limit_data'       => 10,
        'img_quality'      => 100,
        'img_thumb_width'  => 400,
        'img_thumb_height' => 400,
    ],
    'location' => [
        'upload'       => 'uploads',
        'backend_path' => 'backend',
        'backend_url'  => 'xms',
    ],
    'backend' => [
        'guard' => 'backend',
        'allowed_url' => [
            'login',
            'logout',
            'home',
            'dashboard',
            'profile',
            'me',
        ]
    ]

];
