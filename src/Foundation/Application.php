<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation;


use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;



class Application extends Container
{
    public function __construct()
    {
        $this->bind('artisan', function() {
            return new Artisan;
        });

        $this->singleton('config', function() {
            return new Repository;
        });

        $this->bind('filesystem', function() {
            return new Filesystem;
        });

    }

}