<?php

namespace WC_BE\Core\Factories;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Http\Ajax\CheckoutCitiesAjax;
use WC_BE\Http\Ajax\CheckoutStreetsAjax;
use WC_BE\Http\Ajax\getLabelAjax;
use WC_BE\Http\Ajax\OrderShipmentAjax;

class AjaxFactory
{
    public static function create(string $action_name): ?AjaxInterface
    {
        return match ($action_name) {
            'checkout_cities' => new CheckoutCitiesAjax(),
            'checkout_streets' => new CheckoutStreetsAjax(),
            'create_order' => new OrderShipmentAjax(),
            'get_label' => new GetLabelAjax(),
            default => null,
        };
    }
}