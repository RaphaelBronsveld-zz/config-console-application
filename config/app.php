<?php

return [
    'singletons' => [
        'artisan' => 'Raphaelb\Foundation\Artisan'
    ],

    'bindings'  => [
        'fs' => 'Illuminate\Filesystem\Filesystem'
    ],
    'providers' => [
        Raphaelb\LaravelCollective\HtmlServiceProvider::class
    ]

];