<?php
/**
 * License and copyright information bundled with this package in the LICENSE file
 */

namespace Raphaelb\Foundation\Providers;

use Raphaelb\Foundation\Artisan;
use Raphaelb\Foundation\ServiceProvider;

/**
 * This is the class ServiceProvider.
 *
 * @package        Raphaelb\Foundation
 * @author         Raphael
 * @copyright      Copyright (c) 2015, Sebwite. All rights reserved
 *
 */
class ArtisanServiceProvider extends ServiceProvider
{
    protected $defer = true;

    protected $commands = [];

    /**
     * register method
     */
    public function register(){
        $this->app->singleton('artisan', function(){
            return new Artisan();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return  ['artisan'];
    }
}