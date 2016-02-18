<?php

namespace Raphaelb\LaravelCollective;


use Raphaelb\Foundation\ServiceProvider;

/**
 * This is the class HtmlServiceProvider.
 *
 * @package        Raphaelb\LaravelCollective
 * @author         Raphael
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
        $this->registerFormBuilder();
    }

    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function () {
            return new Html();
        });
    }

    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new Form($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }

    public function provides()
    {
        return ['html', 'form', 'Raphaelb\LaravelCollective\Html', 'Raphaelb\LaravelCollective\Form'];
    }


}