<?php

namespace Raphaelb\Foundation;

use Collective\Html\HtmlServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Raphaelb\LaravelCollective\Html;
use Sebwite\Support\Path;

class Application extends Container
{
    protected $config;

    protected $basePath;

    /**
     * @var \Raphaelb\Foundation\ServiceProvider[]
     */
    protected $providers = [ ];

    /**
     * @var \Raphaelb\Foundation\ServiceProvider[]
     */
    protected $deferredProviders = [];

    /**
     * @var \Raphaelb\Foundation\ServiceProvider[]
     */
    protected $deferredServices = [];

    /**
     * Application constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
        $this->singleton('app', $this);

        $this->basePath = $basePath;

        $this->start();
    }

    /**
     * configPath method
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath() . DIRECTORY_SEPARATOR . 'config';
    }

    /**
     * basePath method
     *
     * @return mixed
     */
    public function basePath()
    {
        return $this->basePath;
    }

    /**
     * initConfig method
     *
     * @return \Illuminate\Config\Repository
     */
    protected function initConfig()
    {
        /** @var \Illuminate\Filesystem\Filesystem $fs */
        $fs     = $this->make('fs');
        $config = new Repository();
        $this->instance('config', $config);

        foreach ( $fs->files($this->configPath()) as $file )
        {
            $config->set(
                Path::getFilenameWithoutExtension($file),
                $fs->getRequire($file)
            );
        }

        return $config;
    }

    /**
     * register method
     *
     * @param ServiceProvider $provider
     */
    public function register($provider)
    {
        /** @var \Raphaelb\Foundation\ServiceProvider $provider */
        $provider = new $provider($this);

        if ( $provider->isDeferred() )
        {
            $provides               = $provider->provides();
            $this->deferredServices = array_merge($this->deferredServices, array_fill_keys($provides, $provider));
        }
        else
        {
            $provider->register();
            $this->providers[] = $provider;
        }
    }

    public function isDeferredService($service)
    {
        return array_key_exists($service, $this->deferredServices) ;
    }

    public function loadDeferredService($service)
    {
        $provider = $this->deferredServices[$service];

        if(in_array($provider, $this->providers, true))
            {

            } else {
                $this->deferredProviders[] = $provider;
                $this->providers[] = $provider;
                $provider->register();
            }
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [ ])
    {

        if ( $this->isDeferredService($abstract))
        {
            $this->loadDeferredService($abstract);
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * start method
     */
    public function start()
    {
        $this->instance('fs', new Filesystem());
        $this->config = $this->initConfig();

        $this->addSingletons($this->config);
    }

    /**
     * addSingletons method
     *
     * @param $bindings
     */
    protected function addSingletons($bindings)
    {
        foreach ( $bindings[ 'app.singletons' ] as $singleton => $class )
        {
            $this->singleton($singleton, $class);
        }
    }

    /**
     * getFileName method
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getFileName($file)
    {
        return str_replace($this->basePath(), '', $file);
    }

    /**
     * getFileNameWithoutExtension method
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getFileNameWithoutExtension($file)
    {
        $filename = $this->getFileName($file);

        return str_replace([ '.php', DIRECTORY_SEPARATOR, 'config' ], '', $filename);
    }
}