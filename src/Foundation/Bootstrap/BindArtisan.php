<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Foundation\Bootstrap;


use Raphaelb\Foundation\Application;
use Raphaelb\Foundation\Artisan;

class BindArtisan implements BootstrapInterface
{
    /**
     * bootstrap method
     *
     * @param \Raphaelb\Foundation\Application $app
     *
     * @return mixed
     */
    public function bootstrap(Application $app)
    {
        $app->singleton('artisan', function($app){
            return new Artisan();
        });
    }
}