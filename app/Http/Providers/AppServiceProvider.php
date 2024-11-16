<?php

namespace WC_BE\Http\Providers;

use WC_BE\Dependencies\DI\ContainerBuilder;

use WC_BE\Http\Controllers\DashboardController\DashboardController;
use WC_BE\Http\Services\MailService;
use WC_BE\Http\Services\QRService;
use function WC_BE\Dependencies\DI\autowire;
use function WC_BE\Dependencies\DI\create;
use function WC_BE\Dependencies\DI\get;

class AppServiceProvider
{
    /**
     * Provides Controllers with services, services must be in same order as in Controller __construct class
     *
     */
    public static function register(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
//            MockServiceTest::class => create(MockServiceTest::class),
//            MockService::class => autowire(MockService::class)
//                ->constructor(
//                    get(MockServiceTest::class),
//                ),
        ]);
    }
}