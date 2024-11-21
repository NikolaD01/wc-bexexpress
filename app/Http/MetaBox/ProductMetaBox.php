<?php

namespace WC_BE\Http\MetaBox;

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use WC_BE\Core\Contracts\MetaBoxInterface;
use WC_BE\Dependencies\Twig\Error\LoaderError;
use WC_BE\Dependencies\Twig\Error\RuntimeError;
use WC_BE\Dependencies\Twig\Error\SyntaxError;
use WC_BE\Http\Utility\Helpers\View;

class ProductMetaBox implements MetaBoxInterface
{

    public function addMetaBox(): void
    {
        $screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
            ? wc_get_page_screen_id( 'shop-order' )
            : 'shop_order';

        add_meta_box(
            'bex_order_meta_box',
            __('Custom Product Meta', 'text-'),
            [$this, 'renderMetaBox'],
            $screen,
            'normal',
            'default'
        );
    }

    /**
     * @throws LoaderError
     * @throws SyntaxError
     * @throws RuntimeError
     */
    public function renderMetaBox($object): void
    {
        View::render('test.twig');
    }

    public function saveMetaBox($post_id): void
    {
        if (!isset($_POST['product_meta_nonce']) || !wp_verify_nonce($_POST['product_meta_nonce'], 'product_meta_nonce')) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

      //  $sanitized = MetaBoxValidator::sanitize($_POST['custom_meta_field']);
        update_post_meta($post_id, '_custom_meta_key', $_POST['custom_meta_field']);
    }
}