<?php

namespace WC_BE;

use Exception;
use WC_BE\Http\Template\BexCheckout;
use WP_CLI;

use WC_BE\Core\Commands\DropCommand;
use WC_BE\Core\Commands\SeedCommand;
use WC_BE\Core\Managers\MetaBoxManager;
use WC_BE\Core\Managers\TableManager;
use WC_BE\Dependencies\DI\ContainerBuilder;
use WC_BE\Dependencies\Psr\Container\ContainerExceptionInterface;
use WC_BE\Dependencies\Psr\Container\ContainerInterface;
use WC_BE\Dependencies\Psr\Container\NotFoundExceptionInterface;
use WC_BE\Http\Providers\AppServiceProvider;
use WC_BE\Http\Template\BexShippingMethod;
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

        new TableManager();
        new BexShippingMethod();
        new BexCheckout();
        new MetaBoxManager();
        $this->setCommands();

        $controllerRegistrar = new ControllerRegistrar($this->container);
        $controllerRegistrar->register();
    }

    public function setCommands() : void
    {

        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('wc-bex seed', [SeedCommand::class, 'run']);
            WP_CLI::add_command('wc-bex drop', [DropCommand::class, 'run']);
        }
    }
    public function getContainer()
    {
        return $this->container;
    }
}
