<?php

namespace Raphaelb\Foundation;

use Illuminate\Container\Container;
use Raphaelb\Contracts\Application as ApplicationContract;

class Application extends Container implements ApplicationContract
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
    protected $deferredProviders = [ ];

    /**
     * @var \Raphaelb\Foundation\ServiceProvider[]
     */
    protected $deferredServices = [ ];

    /**
     * Application constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath = null)
    {
        $this->instance('app', $this);

        $this->registerCoreContainerAliases();

        if ( $basePath )
        {
            $this->setBasePath($basePath);
        }
    }

    /**
     * Set the base path for the application.
     *
     * @param  string $basePath
     *
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        return $this;
    }

    public function bootstrapWith(array $bootstrappers)
    {
        foreach ( $bootstrappers as $bootstrapper )
        {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * configPath method
     *
     * @return string
     */
    public function getConfigPath()
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

    public function registerProviders()
    {
        foreach ( $this->make('config')->get('app.providers') as $provider )
        {
            $this->register($provider);
        }
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
            $provides                  = $provider->provides();
            $this->deferredServices    = array_merge($this->deferredServices, array_fill_keys($provides, $provider));
            $this->deferredProviders[] = $provider;
            $this->providers[]         = $provider;
            $provider->register();
        }
        else
        {
            $provider->register();
            $this->providers[] = $provider;
        }
    }

    /**
     * isDeferredService method
     *
     * @param $service
     *
     * @return bool
     */
    public function isDeferredService($service)
    {
        return array_key_exists($service, $this->deferredServices);
    }

    /**
     * loadDeferredService method
     *
     * @param $service
     */
    public function loadDeferredService($service)
    {
        $provider = $this->deferredServices[ $service ];

        if ( in_array($provider, $this->providers, true) )
        {
            // Provider already registered.
        }
    }

    /**
     * Resolve the given type from the container
     *
     * @param  string $abstract
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [ ])
    {

        if ( $this->isDeferredService($abstract) )
        {
            $this->loadDeferredService($abstract);
        }

        return parent::make($abstract, $parameters);
    }

    public function registerCoreContainerAliases()
    {
        $aliases = [
            'app' => [ 'Raphaelb\Foundation\Application', 'Raphaelb\Contracts\Application' ]
        ];

        foreach ( $aliases as $key => $aliases )
        {
            foreach ( $aliases as $alias )
            {
                $this->alias($key, $alias);
            }
        }
    }
}