<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation;


use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Application as SymfonyApplication;

/**
 * Inherits SymfonyApp
 */
class Artisan extends SymfonyApplication
{

    protected $app;

    /**
     * Artisan constructor.
     */
    public function __construct()
    {


        parent::__construct();

        $this->app->bind('artisan', function() {
            return new Artisan;
        });

        $this->app->singleton('configuration', function() {
            return new Repository;
        });

        $this->app->bind('filesystem', function() {
            return new Filesystem;
        });
    }

}