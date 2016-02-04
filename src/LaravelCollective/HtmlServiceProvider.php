<?php

namespace Raphaelb\LaravelCollective;


use Raphaelb\Foundation\ServiceProvider;

/**
 * This is the class HtmlServiceProvider.
 *
 * @package        Raphaelb\LaravelCollective
 * @author         Sebwite
 * @copyright      Copyright (c) 2015, Sebwite. All rights reserved
 *
 *                 extend de basis service provider.
 *                 Bind Form en Html in de Application/Containeer
 */
class HtmlServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->registerHtmlBuilder();
    }

    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new Html($app['url'], $app['view']);
        });
    }




}