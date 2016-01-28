<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation;


use Symfony\Component\Console\Application as SymfonyApplication;

class Artisan extends SymfonyApplication
{
    public function __construct()
    {
        parent::__construct();
    }
}