<?php

namespace WC_BE;

use Exception;


use WC_BE\Core\EventListener;
use WC_BE\Dependencies\DI\ContainerBuilder;
use WC_BE\Dependencies\Psr\Container\ContainerExceptionInterface;
use WC_BE\Dependencies\Psr\Container\ContainerInterface;
use WC_BE\Dependencies\Psr\Container\NotFoundExceptionInterface;
use WC_BE\Http\Providers\AppServiceProvider;
use WC_BE\Http\Utility\Registrar\ControllerRegistrar;

class Init
{
    protected ContainerInterface $container;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->initializeContainer();
    }

    /**
     * @throws Exception
     */
    protected function initializeContainer(): void
    {
        $builder = new ContainerBuilder();
        AppServiceProvider::register($builder);
        $this->container = $builder->build();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function register(): void
    {
        new EventListener();
        $controllerRegistrar = new ControllerRegistrar($this->container);
        $controllerRegistrar->register();
    }

    public function getContainer() {
        return $this->container;
    }
}
