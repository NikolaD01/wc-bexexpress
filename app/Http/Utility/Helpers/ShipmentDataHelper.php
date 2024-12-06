<?php

namespace WC_BE\Http\Utility\Helpers;

class ShipmentDataHelper
{
    public static function createShipmentData(array $data): array
    {
        $order = $data['order'];
        $settings = $data['settings'];
        $post_id = $data['post_id'];
        $meta_data = $data['meta_data'];

        $packageDetails = OrderHelper::getTotalPackage($order);
        $shipmentCategory = OrderHelper::getShipmentCategory($packageDetails['total_weight']);
        $billingAddress = OrderHelper::getBillingAddress($order);

        $city = self::getCity($billingAddress['city']);
        $address = self::getAddress($billingAddress['address_1']);
        $type = self::getAddressType($city, $address);


        $orderTotal = (float)$order->get_total();
        $payToSender = min($orderTotal, 1000000);

        return [
            "shipmentslist" => [
                [
                    "shipmentId" => (int)$settings['bex_shipment_id'] ?? 0,
                    "serviceSpeed" => (int)$settings['bex_service_speed'] ?? 1,
                    "shipmentType" => (int)$settings['bex_shipment_type'] ?? 1,
                    "shipmentCategory" => $shipmentCategory,
                    "shipmentWeight" => (int)$settings['bex_shipment_weight'] ?? 0,
                    "totalPackages" => (int)$packageDetails['total_items'] ?? 1,
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
                            "nameType" => 1,
                            "name1" => $settings['bex_first_name'],
                            "name2" => $settings['bex_last_name'],
                            "adressType" => 3,
                            "municipalites" => (int)$settings['bex_municipalites'],
                            "place" => (int)$settings['bex_place'],
                            "street" => (int)$settings['bex_street'],
                            "houseNumber" => (int)$settings['bex_house_number'],
                            "contactPerson" => $settings['bex_first_name'] . " " . $settings['bex_last_name'],
                            "phone" => $settings['bex_phonenumber'],
                        ],
                        [
                            "type" => 2,
                            "nameType" => 1,
                            "name1" => $billingAddress['first_name'],
                            "name2" => $billingAddress['last_name'],
                            "adressType" => $type,
                            "municipalites" => (int)$billingAddress['state'],
                            "place" => $city,
                            "street" => $address,
                            "houseNumber" => (int)$billingAddress['address_2'],
                            "contactPerson" => $billingAddress['first_name'] . " " . $billingAddress['last_name'],
                            "phone" => $billingAddress['phone'],
                            "preNotification" => 60,
                            "comment" => "API Test",
                        ],
                    ],
                    "reports" => [
                        [
                            "mode" => 1,
                            "type" => 1,
                            "address" => "",
                        ],
                    ],
                ],
            ],
        ];
    }

    private static function getAddressType(int|string $city, int|string $address): int
    {
        $isCityInt = is_numeric($city) && ctype_digit((string)$city);
        $isAddressInt = is_numeric($address) && ctype_digit((string)$address);

        if ($isCityInt && $isAddressInt) {
            return 3;
        } elseif ($isCityInt || $isAddressInt) {
            return 2;
        } else {
            return 1;
        }
    }

    private static function getCity(int|string $city): int|string
    {
        return is_numeric($city) && ctype_digit((string)$city) ? (int)$city : $city;
    }

    private static function getAddress(int|string $address): int|string
    {
        return is_numeric($address) && ctype_digit((string)$address) ? (int)$address : $address;
    }
}
