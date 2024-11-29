<?php

namespace WC_BE\Http\Utility\Helpers;

use WC_Order;

class OrderHelper
{

    public static function getTotalPackage(WC_Order $order): array
    {
        $total_items = 0;
        $total_weight = 0;

        foreach ($order->get_items() as $item_id => $item) {
            $product = $item->get_product();
            if ($product) {
                $quantity = $item->get_quantity();
                $weight = $product->get_weight();

                $total_items += $quantity;
                $total_weight += $weight * $quantity;
            }
        }

        return [
            'total_items' => $total_items,
            'total_weight' => $total_weight,
        ];
    }

    public static function getShipmentCategory(float $weight): int
    {
        return match (true) {
            $weight <= 0.5 => 1,
            $weight <= 1 => 2,
            $weight <= 2 => 3,
            $weight <= 5 => 4,
            $weight <= 10 => 5,
            $weight <= 15 => 6,
            $weight <= 20 => 7,
            $weight <= 30 => 8,
            $weight <= 50 => 9,
            default => 31,
        };
    }

    public static function getBillingAddress(WC_Order $order): array
    {
        return [
            'first_name' => $order->get_billing_first_name(),
            'last_name' => $order->get_billing_last_name(),
            'address_1' => $order->get_billing_address_1(),
            'address_2' => $order->get_billing_address_2(),
            'city' => $order->get_billing_city(),
            'postcode' => $order->get_billing_postcode(),
            'country' => $order->get_billing_country(),
            'state' => $order->get_billing_state(),
            'phone' => $order->get_billing_phone(),
            'email' => $order->get_billing_email(),
        ];
    }
}