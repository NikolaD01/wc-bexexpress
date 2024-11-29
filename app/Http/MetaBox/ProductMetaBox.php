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
            __('BexExpress', 'text-'),
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


        $order_id  = $object->id ?? get_the_ID();

        View::render('dashboard.twig', [
            'shipmentCreated' =>  (bool)get_post_meta($order_id, '_bex_shipment_id', true),
            'headers' => ['Name', 'Value'],
            'data' => [
                ['commentPublic', get_post_meta($order_id, 'bex_commentpublic', true)],
                ['commentPrivate', get_post_meta($order_id,'bex_commentprivate', true)],
                ['personalDelivery', get_post_meta($order_id,'bex_personaldelivery', true)],
                ['returnSignedInvoices', get_post_meta($order_id, 'bex_returnsignedinvoices', true)],
                ['returnPackage', get_post_meta($order_id, 'bex_returnpackage', true)],
                ['payToSenderViaAccount', get_post_meta($order_id, 'bex_paytosenderviaaccount', true)],
                ['bankTransferComment', get_post_meta($order_id, 'bex_banktransfercomment', true)],
            ],
        ]);
    }

    public function saveMetaBox(): void
    {}
}