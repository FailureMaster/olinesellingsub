<?php
/**
 * WCFM Product Hub Dependency Checker
 *
 */
class WCFMph_Dependencies {
	
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
	
	// WooCommerce Product Bundles
	static function wc_product_bundles_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-product-bundles/woocommerce-product-bundles.php', self::$active_plugins ) || array_key_exists( 'woocommerce-product-bundles/woocommerce-product-bundles.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Force Sales
	static function wc_force_sales_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-force-sells/woocommerce-force-sells.php', self::$active_plugins ) || array_key_exists( 'woocommerce-force-sells/woocommerce-force-sells.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Chained Product
	static function wc_chained_product_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-chained-products/woocommerce-chained-products.php', self::$active_plugins ) || array_key_exists( 'woocommerce-chained-products/woocommerce-chained-products.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Composite Product
	static function wc_composite_product_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'woocommerce-composite-products/woocommerce-composite-products.php', self::$active_plugins ) || array_key_exists( 'woocommerce-composite-products/woocommerce-composite-products.php', self::$active_plugins );
		return false;
	}
	
	// WooCommerce Group By Product
	static function wc_groupbuy_product_plugin_active_check() {
		if ( ! self::$active_plugins ) self::init();
		return in_array( 'groupby-for-woocommerce/wc-groupbuy.php', self::$active_plugins ) || array_key_exists( 'groupby-for-woocommerce/wc-groupbuy.php', self::$active_plugins );
		return false;
	}
}