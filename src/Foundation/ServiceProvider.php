<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation;

/**
 * This is the class ServiceProvider.
 *
 * @package        Raphaelb\Foundation
 * @author         Sebwite
 * @copyright      Copyright (c) 2015, Sebwite. All rights reserved
 *
 * Abstractmaken.
 *                 Register functie toevoegen, abstract
 *                 Boot method toevoegen, niet abstract
 */
abstract class ServiceProvider
{
    protected $app;

    protected $defer = true;

    /**
     * ServiceProvider constructor.
     *
     * @param $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * register method
     *
     * @return mixed
     */
    abstract public function register();

    /**
     * boot method
     */
    public function boot(){

    }

    /**
     * Determine if the provider is deferred.
     *
     * @return bool
     */
    public function isDeferred()
    {
        return $this->defer;
    }

}