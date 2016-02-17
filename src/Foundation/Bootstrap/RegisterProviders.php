<?php

namespace Raphaelb\Foundation\Bootstrap;

use Raphaelb\Foundation\Application;

/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */

class RegisterProviders implements BootstrapInterface {

    /**
     * bootstrap method
     *
     * @param Application $app
     *
     * @return mixed
     */
    public function bootstrap(Application $app)
    {
        $app->registerProviders();
    }
}