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


    /**
     * Artisan constructor.
     */
    public function __construct()
    {
        parent::__construct();

    }

}