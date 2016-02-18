<?php

namespace Raphaelb\Foundation;

use Raphaelb\Contracts\Application as ApplicationContract;

class Kernel {

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var array
     */
    protected $bootstrappers = [
        'Raphaelb\Foundation\Bootstrap\LoadConfiguration',
        'Raphaelb\Foundation\Bootstrap\RegisterProviders'
    ];

    /**
     * Kernel constructor.
     *
     * @param \Raphaelb\Contracts\Application $app
     */
    public function __construct(ApplicationContract $app)
    {
        $this->app = $app;
    }

    /**
     * handle method
     */
    public function handle(){

        $this->bootstrap();
    }

    /**
     * bootstrap method
     */
    public function bootstrap(){
        $this->app->bootstrapWith($this->bootstrappers());
    }

    /**
     * bootstrappers method
     *
     * @return array
     */
    public function bootstrappers(){
        return $this->bootstrappers;
    }

}