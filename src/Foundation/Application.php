<?php

namespace Raphaelb\Foundation;

use Illuminate\Config\Repository;
use Illuminate\Container\Container;

class Application extends Container
{
    protected $configProviders;

    protected $config;

    protected $app;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->config = $this->initConfig();

        $this->start();
    }

    /**
     * getConfigFiles method
     *
     * @return array
     */
    protected function getConfigFiles(){
        $dir = $this->getDir();
        $files = [];
        $scanned_directory = array_diff(scandir($dir), array('..', '.'));
        foreach ($scanned_directory as $filename) {
            $filename = preg_replace("/(.+)\.php$/", "$1", $filename);
            $files[] = $filename;
        }
        return $files;
    }

    /**
     * initConfig method
     *
     * @return \Illuminate\Config\Repository
     */
    protected function initConfig(){
        $dir = $this->getDir();
        $config = new Repository();
        $this->instance('config', $config);
        $files = $this->getConfigFiles();
        foreach($files as $file){
            $config->set($file, require $dir . $file . ".php");
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
    protected function registerProviders()
    {
        $this->configProviders = $this->config['app.providers'];
    }

    /**
     * start method
     */
    public function start()
    {
        $bindings = $this->make('config');
        $this->addSingletons($bindings);
        $this->addBindings($bindings);
        $this->registerProviders();
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
     * getDir method
     *
     * @return string
     */
    protected function getDir(){
        return __DIR__ . '/../../config/';
    }
}