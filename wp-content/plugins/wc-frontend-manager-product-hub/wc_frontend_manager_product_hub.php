<?php
/**
 * Plugin Name: WooCommerce Frontend Manager - Product Hub
 * Plugin URI: https://wclovers.com
 * Description: Upgrade your Store Products to the next level and off-course from live site Front-end. Smartly and Elegantly.
 * Author: WC Lovers
 * Version: 1.0.8
 * Author URI: https://wclovers.com
 *
 * Text Domain: wc-frontend-manager-product-hub
 * Domain Path: /lang/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.6.0
 *
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! class_exists( 'WCFMph_Dependencies' ) )
	require_once 'helpers/class-wcfmph-dependencies.php';

require_once 'helpers/wcfmph-core-functions.php';
require_once 'wc_frontend_manager_product_hub_config.php';

if(!defined('WCFMph_TOKEN')) exit;
if(!defined('WCFMph_TEXT_DOMAIN')) exit;


if(!WCFMph_Dependencies::woocommerce_plugin_active_check()) {
	add_action( 'admin_notices', 'wcfmph_woocommerce_inactive_notice' );
} else {

	if(!WCFMph_Dependencies::wcfm_plugin_active_check()) {
		add_action( 'admin_notices', 'wcfmph_wcfm_inactive_notice' );
	} else {
		if(!class_exists('WCFMph')) {
			require_once( 'core/class-wcfmph.php' );
			global $WCFMph;
			$WCFMph = new WCFMph( __FILE__ );
			$GLOBALS['WCFMph'] = $WCFMph;
			
			// Activation Hooks
			register_activation_hook( __FILE__, array('wcfmph', 'activate_wcfmph') );
			register_activation_hook( __FILE__, 'flush_rewrite_rules' );
			
			// Deactivation Hooks
			register_deactivation_hook( __FILE__, array('wcfmph', 'deactivate_wcfmph') );
		}
	}
}
?>