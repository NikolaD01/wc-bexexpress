<?php

namespace WC_BE\Core\Factories;

use WC_BE\Core\Contracts\MetaBoxInterface;
use WC_BE\Http\MetaBox\ProductMetaBox;

class MetaBoxFactory
{
    public static function create($type): ?MetaBoxInterface
    {
        return match ($type) {
            'shop_order' => new ProductMetaBox(),
            default => null
        };
    }
}