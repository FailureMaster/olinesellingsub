<?php
/**
 * Plugin Name: WCFM - WooCommerce Frontend Manager - Analytics
 * Plugin URI: https://wclovers.com
 * Description: Analyze your Store and Stroe Products Analytics from live site Front-end. Smoothly and Elegantly.
 * Author: WC Lovers
 * Version: 2.2.3
 * Author URI: https://wclovers.com
 *
 * Text Domain: wc-frontend-manager-analytics
 * Domain Path: /lang/
 *
 * WC requires at least: 3.0.0
 * WC tested up to: 3.8.0
 *
 */

if(!defined('ABSPATH')) exit; // Exit if accessed directly

if ( ! class_exists( 'WCFMa_Dependencies' ) )
	require_once 'helpers/class-wcfma-dependencies.php';

require_once 'helpers/wcfma-core-functions.php';
require_once 'wc_frontend_manager_analytics_config.php';

if(!defined('WCFMa_TOKEN')) exit;
if(!defined('WCFMa_TEXT_DOMAIN')) exit;


if(!WCFMa_Dependencies::woocommerce_plugin_active_check()) {
	add_action( 'admin_notices', 'wcfma_woocommerce_inactive_notice' );
} else {

	if(!WCFMa_Dependencies::wcfm_plugin_active_check()) {
		add_action( 'admin_notices', 'wcfma_wcfm_inactive_notice' );
	} else {
		if(!class_exists('WCFMa')) {
			include_once( 'core/class-wcfma.php' );
			global $WCFMa;
			$WCFMa = new WCFMa( __FILE__ );
			$GLOBALS['WCFMa'] = $WCFMa;
			
			// Activation Hooks
			register_activation_hook( __FILE__, array('wcfma', 'activate_wcfma') );
			register_activation_hook( __FILE__, 'flush_rewrite_rules' );
			
			// Deactivation Hooks
			register_deactivation_hook( __FILE__, array('wcfma', 'deactivate_wcfma') );
		}
	}
}
?>