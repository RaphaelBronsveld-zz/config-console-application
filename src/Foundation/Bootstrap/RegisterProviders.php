<?php

namespace Raphaelb\Foundation\Bootstrap;

use Raphaelb\Foundation\Application;

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