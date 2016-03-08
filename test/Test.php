<?php

class Test
{

    protected $config;

    public function __construct($testdir)
    {
        echo 'Test directory ' . $testdir . "\n";
        $this->setConfig();
    }

    public function getconfig(){
        return $this->config;
    }

    public function setConfig()
    {
        $this->config = require dirname(__DIR__) . "/config/app.php";
        $CONFIGDIR = dirname(__DIR__) . "/config/app.php";

        echo $CONFIGDIR;
    }

}