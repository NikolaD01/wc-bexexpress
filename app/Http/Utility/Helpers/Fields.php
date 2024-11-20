<?php

namespace WC_BE\Http\Utility\Helpers;

class Fields
{
    public static function bexFields() : array
    {

        $arr = Cities::get();
        asort($arr);
        return array(
            'bex_api_token' => array(
                'title' => __('API TOKEN *', 'bex-express-shipping'),
                'type' => 'text',
                'description' => __('Bex API Token', 'bex-express-shipping'),
                'required' => true
            ),
            'shipping_amount' => array(
                'title' => __('Fixed shipping amount (RSD)', 'bex-express-shipping'),
                'type' => 'text',
                'default' => '400',
                'required' => true
            ),
            'bex_shipment_id' => array(
                'title' => __('ShipmentId *', 'bex-express-shipping'),
                'type' => 'text',
                'default' => '1',
                'description' => __('The way package will be added to system', 'bex-express-shipping'),
                'required' => true
            ),
            'bex_service_speed' => array(
                'title' => __('Service Speed *', 'bex-express-shipping'),
                'type' => 'text',
                'default' => '1',
                'description' => __('Service speed always 1', 'bex-express-shipping'),
                'required' => true
            ),
            'bex_shipment_type' => array(
                'title' => __('Shipment Type *', 'bex-express-shipping'),
                'type' => 'select',
                'options' => [
                    '1' => 'standard',
                    '2' => 'specijal',
                    '3' => 'procenat fakture'
                ],
                'default' => '1',
                'description' => __('Shipment type, the way shipment will be delivered', 'bex-express-shipping'),
                'required' => true
            ),
            'bex_shipment_weight' => array(
                'title' => __('shipment Weight *', 'bex-express-shipping'),
                'type' => 'text',
                'default' => '0',
                'description' => __('Total shipment weight always 0', 'bex-express-shipping'),
            ),
            'bex_invoice_amount' => array(
                'title' => __('invoice Amount *', 'bex-express-shipping'),
                'type' => 'text',
                'description' => __('invoice Amount only for shipment Type 6, otherwise 0 ', 'bex-express-shipping'),
                'default' => '0',
                'required' => true
            ),
            'bex_return_signed_confirmation' => array(
                'title' => __('Return Signed Confirmation *', 'bex-express-shipping'),
                'type' => 'checkbox',
                'description' => __('The courier service confirmation MUST be signed upon delivery and returned to the sender.', 'bex-express-shipping'),
                'required' => true,
                'default' => 'no'
            ),
            'bex_pay_type' => array(
                'title' => __('pay Type	*', 'bex-express-shipping'),
                'type' => 'select',
                'options' => [
                    '1' => 'pošiljalac gotovina',
                    '2' => 'primalac gotovina',
                    '6' => 'kupac preko banke'
                ],
                'default' => '2',
                'required' => true
            ),
            'bex_insurance_amount' => array(
                'title' => __('insurance Amount *', 'bex-express-shipping'),
                'type' => 'text',
                'description' => __('The amount of money refunded to the sender in case the shipment is lost/damaged, etc.
                 (The sender pays a certain percentage based on this amount).
                  insuranceAmount=0 indicates that the shipment is not insured.', 'bex-express-shipping'),
                'default' => '0',
            ),
            'bex_senders_acccount_number' => array(
                'title' => __('Senders account number ', 'bex-express-shipping'),
                'type' => 'text',
                'description' => __('The bank account number in case the money 
                needs to be transferred via bank (leave empty for cash transfer).', 'bex-express-shipping'),
            ),
        );
    }
}