<?php

return [

    'singletons' => [
        'artisan' => 'Raphaelb\Foundation\Artisan'
    ],

    'bindings'  => [
        'fs' => 'Illuminate\Filesystem\Filesystem',
        'Html' => 'Raphaelb\LaravelCollective\Html',
        'Form' => 'Raphaelb\LaravelCollective\Form'
    ],
    'providers' => [
        Raphaelb\LaravelCollective\HtmlServiceProvider::class,
        'test'
    ]

];