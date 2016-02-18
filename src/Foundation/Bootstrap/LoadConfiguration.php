<?php

namespace Raphaelb\Foundation\Bootstrap;

use Illuminate\Config\Repository;
use Raphaelb\Foundation\Application;
use Sebwite\Support\Filesystem;
use Sebwite\Support\Path;

/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */

class LoadConfiguration implements BootstrapInterface {

    /**
     * bootstrap method
     *
     * @param \Raphaelb\Foundation\Application $app
     *
     * @return mixed
     */
    public function bootstrap(Application $app)
    {
        $items = [];

        /** @var \Illuminate\Filesystem\Filesystem $fs */
        $fs     = new Filesystem();
        $app->instance('config', $config = new Repository($items));

        foreach ( $fs->files($app->getConfigPath()) as $file )
        {
            $config->set(
                Path::getFilenameWithoutExtension($file),
                $fs->getRequire($file)
            );
        }
        return $config;
    }
}