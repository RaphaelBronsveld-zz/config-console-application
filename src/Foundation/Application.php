<?php

namespace Raphaelb\Foundation;

use Collective\Html\HtmlServiceProvider;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Sebwite\Support\Path;

class Application extends Container
{
    protected $config;

    protected $basePath;

    protected $providers=[];

    protected $defferedProviders;

    /**
     * Application constructor.
     *
     * @param $basePath
     */
    public function __construct($basePath)
    {
        $this->setApp();
        $this->basePath = $basePath;
        $this->start();
    }

    protected function setApp(){
        $this->singleton('app', $this);
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
     * @param ServiceProvider $provider
     */
    public function register($provider){
        $provider = new $provider($this);

        if($provider->isDeferred())
        {
            $provides = $provider->provides();

            $this->defferedProviders = array_fill_keys($provides, $provider);

            $this->resolving('html', function(){
                $app = 'im actually resolving now';
                dd($app);
            });

        } else {
            $provider->register();
        }

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