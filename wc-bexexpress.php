<?php
/**
 * Plugin Name: WC Bexexpress
 * Description: WooCommerce Integration of Bexexpress
 * Version: 1.0.0
 * Author: ND.
 * Author URI:
 * Text Domain: wc-bexexpress
 * Domain Path: /languages
 */



if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p> WC Bexexpress requires Composer autoloader. Please run "composer install" in the plugin directory.</p></div>';
	} );

	return;
}


if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}
add_action('plugins_loaded', function() {
    $init = new \WC_BE\Init();
    $init->register();
});

define( 'PS_QR_ACCESS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PS_QR_ACCESS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );


