<?php
/**
 * Part of the Sebwite PHP packages.
 *
 * License and copyright information bundled with this package in the LICENSE file
 */


namespace Raphaelb\Test;


use Raphaelb\Test\TestClassInterface;

class TestClass implements TestClassInterface
{
    protected $path;

    /**
     * TestClass constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }


}