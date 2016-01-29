<?php

return [

    'singletons' => [
        'artisan' => 'Raphaelb\Foundation\Artisan',
        'config' => 'Illuminate\Config\Repository'
    ],

    'providers'  => [
        'filesystem' => 'Illuminate\Filesystem\Filesystem',
        'Html' => 'Raphaelb\LaravelCollective\Html',
        'Form' => 'Raphaelb\LaravelCollective\Form'
    ]

];