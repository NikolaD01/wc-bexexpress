<?php

namespace WC_BE\Http\Ajax;

use WC_BE\Core\Contracts\AjaxInterface;
use WC_BE\Dependencies\GuzzleHttp\Exception\GuzzleException;
use WC_BE\Http\Services\API\BexExpressClientService;
use WC_BE\Http\Utility\Helpers\OrderHelper;

class OrderShipmentAjax implements AjaxInterface
{
    public function handle() : void
    {
        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $meta_data = json_decode($_POST['meta_data']);

        $baseUri = 'https://api.bex.rs:62502';
        $settings = get_option('woocommerce_bex_express_shipping_method_settings');
        $apiToken = $settings['bex_api_token'] ?? '';

        if(!$apiToken)
            wp_send_json_error('Api Token is required');

        $order = wc_get_order($post_id);

        if(!$order)
            wp_send_json_error("Order not found.");

        $packageDetails = OrderHelper::getTotalPackage($order);
        $shipmentCategory = OrderHelper::getShipmentCategory($packageDetails['total_weight']);
        $billingAddress = OrderHelper::getBillingAddress($order);

        var_dump($billingAddress);
        $orderTotal = (float)$order->get_total();
        $payToSender = min($orderTotal, 1000000);


        $bexExpressClientService = new BexExpressClientService($baseUri, $apiToken);
        $shipmentData = [
            "post" => $post_id,
            "meta" => $meta_data,
            "settings" => $settings,
            "shipmentslist" => [
                [
                    "shipmentId" => (int)$settings['bex_shipment_id'] ?? 0,
                    "serviceSpeed" => (int)$settings['bex_service_speed'] ?? 1,
                    "shipmentType" => (int)$settings['bex_shipment_type'] ?? 1,
                    "shipmentCategory" => $shipmentCategory,
                    "shipmentWeight" => (int)$settings['bex_shipment_weight'] ?? 0,
                    "totalPackages" => (int)$packageDetails['total_items'] ?? null,
                    "invoiceAmount" => (int)$settings['bex_invoice_amount'] ?? 0,
                    "shipmentContents" => (int)$settings['bex_shipment_contents'] ?? 1,
                    "commentPublic" => "TEST WITH ID",
                    "commentPrivate" => "2142400137051",
                    "personalDelivery" => false,
                    "returnSignedInvoices" => false,
                    "returnSignedConfirmation" => !(($settings['bex_return_signed_confirmation'] === 0)),
                    "returnPackage" => false,
                    "payType" => (int)$settings['bex_pay_type'] ?? 2,
                    "insuranceAmount" => (int)$settings['bex_insurance_amount'] ?? 0,
                    "payToSender" => (int)$payToSender,
                    "payToSenderViaAccount" => false,
                    "sendersAccountNumber" => $settings['bex_senders_account_number'] ?? '',
                    "bankTransferComment" => "",
                    "tasks" => [
                        [
                            "type" => 1,
                            "nameType" => 3,
                            "name1" => "21811",
                            "name2" => "",
                            "taxId" => "",
                            "adressType" => 3,
                            "municipalites" => 104,
                            "place" => "0",
                            "street" => "0",
                            "houseNumber" => 150,
                            "apartment" => "",
                            "contactPerson" => "",
                            "phone" => "0648516928",
                            "date" => "",
                            "timeFrom" => "07:55",
                            "timeTo" => "14:55",
                            "preNotification" => 0,
                            "comment" => "",
                            "parcelShop" => 0,
                        ],
                        [
                            "type" => 2,
                            "nameType" => 1,
                            "name1" => $billingAddress['first_name'],
                            "name2" => $billingAddress['last_name'],
                            "taxId" => "",
                            "adressType" => 3,
                            "municipalites" => $billingAddress['state'],
                            "place" => $billingAddress['city'],
                            "street" => $billingAddress['address_1'],
                            "houseNumber" => $billingAddress['address_2'],
                            "apartment" => "",
                            "contactPerson" => $billingAddress['first_name'] . " " . $billingAddress['last_name'],
                            "phone" => $billingAddress['phone'],
                            "date" => "",
                            "timeFrom" => "",
                            "timeTo" => "",
                            "preNotification" => 60,
                            "comment" => "API Test",
                            "parcelShop" => 0,
                        ],
                    ],
                    "reports" => [
                        [
                            "mode" => 1,
                            "type" => 1,
                            "adress" => "mirkotoskic@bex.rs,testmail@bex.rs",
                        ],
                    ],
                ],
            ],
        ];

        try {
            wp_send_json_success($shipmentData);
         //   $response = $bexExpressClientService->createShipment($shipmentData);
           // echo "Response: " . $response->getBody();
        } catch (GuzzleException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}