<?php

return [

    'frontend' => [
        'controller' => App\Http\Controllers\HomeController::class,
        'file_path' => 'frontend',
    ],

    'theme1' => [
        'controller' => App\Http\Controllers\Theme\Theme1Controller::class,
        'file_path' => 'theme1',
    ],

    'theme2' => [
        'controller' => App\Http\Controllers\Theme\Theme2Controller::class,
        'file_path' => 'theme2'
    ],

    'getTheme' => [
        'controller' => App\Http\Controllers\HomeController::class,
        'file_path' => 'frontend'
    ],
];
