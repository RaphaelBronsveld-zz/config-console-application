<?php

namespace Raphaelb\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;

class Application extends Container
{
    protected $config;

    protected $basePath;

    /**
     * Application constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
        $this->basePath = $basePath;
        $this->instance('fs', new Filesystem());
        $this->config = $this->initConfig();

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
    protected function initConfig(){
        /** @var \Illuminate\Filesystem\Filesystem $fs */
        $fs = $this->make('fs');
        $config = new Repository();
        $this->instance('config', $config);
        foreach($this->make('fs')->files($this->configPath()) as $file){
            $key = $this->getFileNameWithoutExtension($file);
            $config->set($key, $fs->getRequire($file));
        }
        return $config;
    }

    /**
     * register method
     *
     * @param $provider
     */
    public function register($provider)
    {
        $this->config['app.providers'] = $provider;
    }

    /**
     * registerProviders method
     *
     * @internal param $providers
     */
    protected function registerConfigApp()
    {
        $this->config = $this->config['app'];
    }

    /**
     * start method
     */
    public function start()
    {
        $bindings = $this->make('config');

        $this->addSingletons($bindings);
        $this->addBindings($bindings);

        $this->registerConfigApp();
    }

    /**
     * addSingletons method
     *
     * @param $bindings
     */
    protected function addSingletons($bindings)
    {
        foreach ( $bindings['app.singletons'] as $singleton => $class )
        {
            $this->singleton($singleton, $class);
        }
    }

    /**
     * addBindings method
     *
     * @param $bindings
     */
    protected function addBindings($bindings)
    {
        foreach($bindings['app.bindings'] as $provider => $class)
        {
            $this->bind($provider, $class);
        }
    }

    /**
     * getFileName method
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getFileName($file){
        return str_replace($this->basePath(), '', $file);
    }

    /**
     * getFileNameWithoutExtension method
     *
     * @param $file
     *
     * @return mixed
     */
    protected function getFileNameWithoutExtension($file){
        $file = str_replace($this->basePath(), '', $file);
        return str_replace(['.php', DIRECTORY_SEPARATOR, 'config'], '', $file);
    }
}