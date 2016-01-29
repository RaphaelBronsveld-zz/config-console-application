<?php

namespace Raphaelb\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;

class Application extends Container
{
    protected $providers;

    protected $app;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $items = require __DIR__ . '/../../config/app.php';

        $this->singleton('config', function ($items) use ($items)
        {
            return new Repository($items);
        });

        $this->start();
    }

    /**
     * register method
     *
     * @param $provider
     */
    public function register($provider)
    {
        $this->providers[] = $provider;
    }

    /**
     * registerProviders method
     */
    protected function registerProviders()
    {
    }

    /**
     * start method
     */
    public function start()
    {
        $bindings = $this->make('config');
        $this->addSingletons($bindings);
        $this->addBindings($bindings);
    }

    protected function addSingletons($bindings)
    {
        foreach ( $bindings[ 'singletons' ] as $singleton => $class )
        {
            $this->singleton($singleton, $class);
        }
    }

    protected function addBindings($bindings)
    {
        foreach($bindings['bindings'] as $provider => $class)
        {
            $this->bind($provider, $class);
        }
    }

}