<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;


class Application extends Container
{

    public function __construct()
    {
        $items = require __DIR__.'/../../config/app.php';

        $this->singleton('config', function($items) use ($items) {
            return new Repository($items);
        });
    }

}