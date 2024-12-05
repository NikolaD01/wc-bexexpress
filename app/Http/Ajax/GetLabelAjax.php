<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Core\Factories\AjaxFactory;
use WC_BE\Dependencies\GuzzleHttp\Exception\GuzzleException;
use WC_BE\Http\Services\API\BexExpressClientService;

class GetLabelAjax implements ajaxInterface
{
    /**
     * @throws GuzzleException
     */
    public function handle() : void
    {

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

        if(!$post_id)
            wp_send_json_error('Order ID not provided');


        $shipmentId = get_post_meta($post_id, '_bex_shipment_id', true);

        if(!$shipmentId)
            wp_send_json_error('Shipment ID not provided');

        $baseUri = 'http://api.bex.rs:62502';
        $settings = get_option('woocommerce_bex_express_shipping_method_settings');
        $apiToken = $settings['bex_api_token'] ?? '';


        $bexExpressClientService = new BexExpressClientService($baseUri, $apiToken);
        try {
            $response = json_decode($bexExpressClientService->getLabel($shipmentId), true);
            if (isset($response['parcelLabel'])) {
                $pdfData = base64_decode($response['parcelLabel']);
                if ($pdfData === false) {
                    wp_send_json_error('Failed to decode PDF data.');
                }

                $uploadDir = WC_BE_PLUGIN_DIR . 'public/assets/pdf/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileName = 'label.pdf';
                $filePath = $uploadDir . $fileName;

                file_put_contents($filePath, $pdfData);

                $fileUrl = WC_BE_PLUGIN_URL . 'public/assets/pdf/' . $fileName;

                wp_send_json_success(['fileUrl' => $fileUrl]);
            } else {
                wp_send_json_error('Invalid response');
            }
        } catch (GuzzleException $e) {
            wp_send_json_error($e->getMessage());
        }


    }
}