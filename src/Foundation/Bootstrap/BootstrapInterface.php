<?php

namespace Raphaelb\Foundation\Bootstrap;

use Raphaelb\Foundation\Application;

interface BootstrapInterface {

    /**
     * bootstrap method
     *
     * @param \Raphaelb\Foundation\Application $app
     *
     * @return mixed
     */
    public function bootstrap(Application $app);
}