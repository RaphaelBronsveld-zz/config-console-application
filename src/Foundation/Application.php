<?php

namespace Raphaelb\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Sebwite\Support\Path;

class Application extends Container
{
    protected $config;

    protected $app;

    protected $basePath;

    protected $providers=[];

    /**
     * Application constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
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
    protected function initConfig(){
        /** @var \Illuminate\Filesystem\Filesystem $fs */
        $fs = $this->make('fs');
        $config = new Repository();
        $this->instance('config', $config);

        foreach($fs->files($this->configPath()) as $file){
            $config->set(
                Path::getFilenameWithoutExtension($file),
                $fs->getRequire($file)
            );
        }

        return $config;
    }

    /**
     * boot method
     *
     * @internal param $provider
     */
    protected function boot()
    {

    }

    /**
     * register method
     *
     * @param $provider
     */
    public function register($provider){
        $class = new $provider($this->app);
        $provider->register();
        // kijken of provider deffered is.
        // Zoja: niet uitvoeren registier functie
        // Zo nee. Uitvoeren register functie
        $this->providers = $provider;
    }

    /**
     * start method
     */
    public function start()
    {
        $this->instance('fs', new Filesystem());
        $this->config = $this->initConfig();

        $this->addSingletons($this->config);
        $this->addBindings($this->config);
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
        $filename = $this->getFileName($file);
        return str_replace(['.php', DIRECTORY_SEPARATOR, 'config'], '', $filename);
    }
}