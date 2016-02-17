<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Test;


use Raphaelb\Foundation\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{

    /**
     * register method
     *
     * @return mixed
     */
    public function register()
    {
        $this->app->singleton('test', TestClass::class);
    }

    /**
     * provides method
     *
     * @return mixed
     */
    public function provides()
    {
        return ['test', TestClass::class, TestClassInterface::class ];
    }
}