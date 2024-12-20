<?php
/**
 * Plugin Name: WooCommerce Bexexpress
 * Description: WooCommerce Integration of Bexexpress API for creating shipment and generating label
 * Version: 1.0.0
 * Author: Nikola Devic
 * Author URI: devicnikola01@gmail.com
 * Text Domain: wc-bexexpress
 * Domain Path: /languages
 */

use WC_BE\Http\Template\BexShippingMethod;
use WC_BE\Init;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define( 'WC_BE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WC_BE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

if ( ! file_exists( WC_BE_PLUGIN_DIR . 'vendor/autoload.php' ) ) {
    add_action( 'admin_notices', function () {
        echo '<div class="error"><p>WC Bexexpress requires Composer autoloader. Please run "composer install" in the plugin directory.</p></div>';
    } );
    return;
}

require_once WC_BE_PLUGIN_DIR . 'vendor/autoload.php';

add_action( 'plugins_loaded', function () {
    if ( ! class_exists( 'WooCommerce' ) ) {
        add_action( 'admin_notices', function () {
            echo '<div class="error"><p>WC Bexexpress requires WooCommerce to be installed and activated.</p></div>';
        } );
        return;
    }

    (new Init())->register();

    if (wp_script_is('wc-bexpress', 'enqueued')) {
        // Script is enqueued
        error_log('wc-bexpress script is enqueued!');
    } else {
        // Script is not enqueued
        error_log('wc-bexpress script is NOT enqueued.');
    }
});
