<?php

namespace WC_BE\Core\Factories;

use WC_BE\Http\Contracts\MetaBox\MetaBoxInterface;
use WC_BE\Http\MetaBox\ProductMetaBox;
use function cli\err;

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