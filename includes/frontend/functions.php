<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Woo Floating Minicart functions
 *
 * Allows user to get WooCommerce Floating Minicart.
 *
 * Woo_floating_minicart_functions
 */

add_action('fmc_woocommerce_fragments_compatibilty', 'fmc_woocommerce_fragments_compatibilty_function', 10, 1 );
function fmc_woocommerce_fragments_compatibilty_function($fmc){
	
	if( $fmc->fmc_version_check() ){
						
			add_filter('woocommerce_add_to_cart_fragments', array( $fmc, 'woo_floating_minicart_add_to_cart_fragment'));
		} else {
			
			add_filter('add_to_cart_fragments', array( $fmc, 'woo_floating_minicart_add_to_cart_fragment'));
			
		}

}