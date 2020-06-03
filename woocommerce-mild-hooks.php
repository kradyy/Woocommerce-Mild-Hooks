<?php
/**
 * Plugin Name: Woocommerce Hooks
 * Plugin URI: https://mildmedia.se
 * Description: Changes the behavior of WooCommerce 
 * Version: 1.0
 * Author: Mild Media, Chris
 * Author URI: https://mildmedia.se
 * License: GPL2
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class WCMild {

	public function __construct() {
		// Remove/update only billing fields
		add_filter( 'woocommerce_billing_fields', array($this, 'override_billing_fields') );

		// Set a custom value to checkout fields
		add_filter( 'woocommerce_checkout_get_value',  array($this, 'modify_checkout_fields', 10, 2) );

		// Add custom styles to Woocommerce
		add_action( 'wp_enqueue_scripts', array($this, 'custom_woocommerce_css'), 100 );
	}

	function override_billing_fields( $fields ){
		$fields_to_remove = array(
			'billing_first_name',
			'billing_last_name',
			'billing_company',
			'billing_address_1'
		);

		foreach( $fields_to_remove as $field ){
			unset( $fields[$field] );
		}

		// Send value via POST
		$_POST['billing_first_name'] = '';

		return $fields;
	}

	function modify_checkout_fields ( $value, $input ) {
			$checkout_fields = array(
				'billing_first_name'    => '',
				'billing_last_name'     => '',
				'order_comments'        => '',
			);
			
			foreach( $checkout_fields as $key_field => $field_value ){
				if( $input == $key_field && ! empty( $field_value ) ){
					$value = $field_value;
				}
			}
		return $value;
	}

	function custom_woocommerce_css() {
		wp_enqueue_style('mm-custom-wc', plugin_dir_url(__FILE__) . 'assets/css/custom-woocommerce.css', false, 1);
	}
}

$GLOBALS['WCMild'] = new WCMild();