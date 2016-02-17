<?php


class Singleton {

    private static $instance = null;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }


    protected function __construct()
    {

    }

    private function __clone()
    {

    }

    private function __wakeup()
    {
    }
}

class SingletonChild extends Singleton {

}

$obj1 = Singleton::getInstance();

var_dump($obj1 === Singleton::getInstance());

$obj2 = SingletonChild::getInstance();

var_dump($obj2  === SingletonChild::getInstance());
var_dump($obj2 === Singleton::getInstance());

