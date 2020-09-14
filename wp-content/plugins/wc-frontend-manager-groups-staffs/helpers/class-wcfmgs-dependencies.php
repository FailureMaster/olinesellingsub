<?php
/**
 * WCFM Groups & Staffs Dependency Checker
 *
 */
class WCFMgs_Dependencies {
	
	private static $active_plugins;
	
	static function init() {
		self::$active_plugins = (array) get_option( 'active_plugins', array() );
		if ( is_multisite() )
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
	}
	
	// WooCommerce
	static function woocommerce_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
		return false;
	}
	
	// WC Frontend Manager
	static function wcfm_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'wc-frontend-manager/wc_frontend_manager.php', self::$active_plugins ) || array_key_exists( 'wc-frontend-manager/wc_frontend_manager.php', self::$active_plugins );
		return false;
	}
	
	// WC Appointments Support
	static function wcfm_wc_appointments_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-appointments/woocommerce-appointments.php', self::$active_plugins ) || array_key_exists( 'woocommerce-appointments/woocommerce-appointments.php', self::$active_plugins );
		return false;
	}
}