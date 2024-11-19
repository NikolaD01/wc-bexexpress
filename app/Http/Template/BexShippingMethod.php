<?php

namespace WC_BE\Http\Template;

use WC_BE\Http\Utility\Helpers\Fields;
use WC_Shipping_Method;

class BexShippingMethod extends WC_Shipping_Method
{
    /**
     * Constructor for your shipping class
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $woocommerce;
        unset($woocommerce->session->subtotal);

        $this->type               = isset($_POST['payment_method']) ? addslashes($_POST['payment_method']) : 'bex_dits_shipping';

        $this->id                 = 'bex_express_shipping_method'; // Id for your shipping method. Should be uunique.
        $this->method_title       = __('BexExpress');  // Title shown in admin
        $this->method_description = __('BexExpress description'); // Description shown in admin

        $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
        $this->title              = "BexExpress dostava"; // This can be added as an setting but for this example its forced.

        $this->init();
        self::register();
        parent::__construct();

    }

    public function initFormFields() : void
    {
        $this->form_fields =  Fields::bexFields();
    }

    public function init() : void
    {
        $this->initFormFields();
        $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

        add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
    }

    public function calculate_shipping($package = array()) : void
    {
        $options = get_option('woocommerce_bex_express_shipping_method_settings');
        $cost = (isset($options['shipping_amount']) && $options['shipping_amount'] != '') ? $options['shipping_amount'] : 400;
        $rate = array(
            'id'       => $this->id,
            'label'    => $this->title,
            'cost'     => $cost,
            'calc_tax' => 'per_item',
            'tax_status'  => 'none'
        );

        $this->add_rate($rate);
    }

    public static function register() : void
    {
        add_filter('woocommerce_shipping_methods', function ($methods) {
            $methods['bex_express_shipping_method'] = __CLASS__;
            return $methods;
        });

        add_action('woocommerce_shipping_init', function () {
            if (!class_exists(__CLASS__)) {
                require_once __FILE__;
            }
        });
    }
}