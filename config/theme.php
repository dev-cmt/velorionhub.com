<?php

return [

    'frontend' => [
        'controller' => App\Http\Controllers\HomeController::class,
        'views_path' => 'frontend',
        'assets_path' => 'frontend',
    ],

    'theme1' => [
        'controller' => App\Http\Controllers\Theme\Theme1Controller::class,
        'views_path' => 'theme1',
        'assets_path' => 'theme1',
    ],

    'theme2' => [
        'controller' => App\Http\Controllers\Theme\Theme2Controller::class,
        'views_path' => 'theme2',
        'assets_path' => 'theme2',
    ],

    'getTheme' => [
        'controller' => App\Http\Controllers\HomeController::class,
        'views_path' => 'frontend',
        'assets_path' => 'frontend',
    ],
];
