<?php

return [

    'singletons' => [
        'artisan' => 'Raphaelb\Foundation\Artisan',
        'config' => 'Illuminate\Config\Repository'
    ],

    'bindings'  => [
        'filesystem' => 'Illuminate\Filesystem\Filesystem',
        'Html' => 'Raphaelb\LaravelCollective\Html',
        'Form' => 'Raphaelb\LaravelCollective\Form'
    ],
    'providers' => [
        Raphaelb\LaravelCollective\HtmlServiceProvider::class,

    ]

];