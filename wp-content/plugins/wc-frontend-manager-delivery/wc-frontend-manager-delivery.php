<?php
/**
 * Plugin Name: WCFM - WooCommerce Frontend Manager - Delivery
 * Plugin URI: https://wclovers.com/product/woocommerce-frontend-manager-delivery/
 * Description: Manage your own shipment with your own delivery persons. Easily and Smoothly.
 * Author: WC Lovers
 * Version: 1.2.4
 * Author URI: https://wclovers.com
 *
 * Text Domain: wc-frontend-manager-delivery
 * Domain Path: /lang/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 4.2.0
 *
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! class_exists( 'WCFMd_Dependencies' ) )
	require_once 'helpers/class-wcfmd-dependencies.php';

require_once 'helpers/wcfmd-core-functions.php';
require_once 'wc-frontend-manager-delivery-config.php';

if(!defined('WCFMd_TOKEN')) exit;
if(!defined('WCFMd_TEXT_DOMAIN')) exit;


if(!WCFMd_Dependencies::woocommerce_plugin_active_check()) {
	add_action( 'admin_notices', 'wcfmd_woocommerce_inactive_notice' );
} else {

	if(!WCFMd_Dependencies::wcfm_plugin_active_check()) {
		add_action( 'admin_notices', 'wcfmd_wcfm_inactive_notice' );
	} else {
		if(!class_exists('WCFMd')) {
			include_once( 'core/class-wcfmd.php' );
			global $WCFMd;
			$WCFMd = new WCFMd( __FILE__ );
			$GLOBALS['WCFMd'] = $WCFMd;
			
			// Activation Hooks
			register_activation_hook( __FILE__, array('wcfmd', 'activate_wcfmd') );
			register_activation_hook( __FILE__, 'flush_rewrite_rules' );
			
			// Deactivation Hooks
			register_deactivation_hook( __FILE__, array('wcfmd', 'deactivate_wcfmd') );
		}
	}
}
?>