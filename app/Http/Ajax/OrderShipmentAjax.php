<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Dependencies\GuzzleHttp\Exception\GuzzleException;
use WC_BE\Http\Services\API\BexExpressClientService;
use WC_BE\Http\Utility\Helpers\OrderHelper;
use WC_BE\Http\Utility\Helpers\ShipmentDataHelper;

class OrderShipmentAjax implements AjaxInterface
{
    public function handle() : void
    {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $meta_data = json_decode($_POST['meta_data']);

        $baseUri = 'http://api.bex.rs:62502';
        $settings = get_option('woocommerce_bex_express_shipping_method_settings');
        $apiToken = $settings['bex_api_token'] ?? '';

        if(!$apiToken)
            wp_send_json_error('Api Token is required');

        $order = wc_get_order($post_id);

        if(!$order)
            wp_send_json_error("Order not found.");

        $data = [
            'post_id' => $post_id,
            'meta_data' => $meta_data,
            'order' => $order,
            'settings' => $settings,
        ];

        $shipmentData = ShipmentDataHelper::createShipmentData($data);

        $bexExpressClientService = new BexExpressClientService($baseUri, $apiToken);

        try {
            $response = json_decode($bexExpressClientService->createShipment($shipmentData), true);

            if (isset($response['shipmentsResultList'])) {
                $shipmentResult = $response['shipmentsResultList'][0];
                if (isset($shipmentResult['shipmentId'])) {
                    $shipmentId = $shipmentResult['shipmentId'];
                    $updated = update_post_meta($post_id, '_bex_shipment_id', $shipmentId);
                    if ($updated) {
                        $savedShipmentId = get_post_meta($post_id, '_bex_shipment_id', true);
                        if ($savedShipmentId) {
                            wp_send_json_success($response);
                        } else {
                            wp_send_json_error("Failed to save shipment ID.");
                        }
                    } else {
                        wp_send_json_error("Failed to update shipment ID.");
                    }
                }
                wp_send_json_success($response);
            } else {
                wp_send_json_error("Shipment creation failed: " . json_encode($response));
            }
        } catch (GuzzleException $e) {
            wp_send_json_error($e->getMessage());
        }
    }
}