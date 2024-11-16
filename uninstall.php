<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;
$transient_like = $wpdb->esc_like( 'fgd_geoip_' ) . '%';
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%{$transient_like}'" );
$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_%{$transient_like}'" );

wp_cache_flush();
