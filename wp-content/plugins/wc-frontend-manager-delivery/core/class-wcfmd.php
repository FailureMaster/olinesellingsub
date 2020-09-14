<?php

/**
 * WCFM Delivery plugin
 *
 * WCFM Delivery Core
 *
 * @author 		WC Lovers
 * @package 	wcfmd/core
 * @version   1.0.0
 */

class WCFMd {

	public $plugin_base_name;
	public $plugin_url;
	public $plugin_path;
	public $version;
	public $token;
	public $text_domain;
	public $library;
	public $template;
	public $shortcode;
	public $admin;
	public $frontend;
	public $listings_stats;
	public $ajax;
	private $file;
	public $settings;
	public $license;
	public $wcfmd_delivery_time;
	public $WCFMd_fields;
	public $is_marketplace;
	public $WCFMd_marketplace;
	public $WCFMd_capability;
	public $wcfmd_non_ajax;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_base_name = plugin_basename( $file );
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCFMd_TOKEN;
		$this->text_domain = WCFMd_TEXT_DOMAIN;
		$this->version = WCFMd_VERSION;
		
		// Installer Hook
		add_action( 'init', array( &$this, 'run_wcfmd_installer' ) );
		
		add_action( 'init', array(&$this, 'init') );
		
		add_action( 'wcfm_init', array( &$this, 'init_wcfmd' ), 14 );
		
		add_filter( 'wcfm_modules',  array( &$this, 'get_wcfmd_modules' ), 22 );
		
		// Update Delivery Order Status on WC Order Status changed
		add_action( 'woocommerce_order_status_changed', array(&$this, 'wcfmd_delivery_order_status_changed'), 30, 3 );
		
		// ON Delete Order Item Delete Delivery Order
		add_action( 'woocommerce_before_delete_order_item', array(&$this, 'wcfmd_delivery_order_item_delete' ), 30 );
		add_action( 'woocommerce_delete_order_item', array(&$this, 'wcfmd_delivery_order_item_delete' ), 30 );
		
		// ON Trashed Order Trash Delivery Order
		add_action( 'woocommerce_trash_order', array(&$this, 'wcfmd_delivery_order_trash' ), 30 );
		add_action( 'wp_trash_post', array(&$this, 'wcfmd_delivery_order_trash' ), 30 );
		
		// ON Delete Order delete Delivery Order
		add_action( 'woocommerce_delete_order', array(&$this, 'wcfmd_delivery_order_delete' ), 30 );
		add_action( 'before_delete_post', array(&$this, 'wcfmd_delivery_order_delete' ), 30 );
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCFM, $WCFMd;
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		// WCfM License Activation
		if (is_admin()) {
			$this->load_class('license');
			$this->license = WCFMd_LICENSE();
		}
		
		if( !defined('DOING_AJAX') ) {
			$this->load_class( 'non-ajax' );
			$this->wcfma_non_ajax = new WCFMd_Non_Ajax();
		}
	}
		
	function init_wcfmd() {
		global $WCFM, $WCFMd;
		
		// Capability Controller
		if ( !is_admin() || ( defined('DOING_AJAX') || defined('WCFM_REST_API_CALL') ) ) {
			$this->load_class( 'capability' );
			$this->wcfmd_capability = new WCFMd_Capability();
		}
		
		// Check Marketplace
		$this->is_marketplace = wcfm_is_marketplace();
		
		if ( !is_admin() || ( defined('DOING_AJAX') || defined('WCFM_REST_API_CALL') ) ) {
			if( $this->is_marketplace ) {
				if( wcfm_is_vendor()) {
					$this->load_class( $this->is_marketplace );
					if( $this->is_marketplace == 'wcvendors' ) $this->wcfmd_marketplace = new WCFMd_WCVendors();
					if( $this->is_marketplace == 'wcpvendors' ) $this->wcfmd_marketplace = new WCFMd_WCPVendors();
					if( $this->is_marketplace == 'wcmarketplace' ) $this->wcfmd_marketplace = new WCFMd_WCMarketplace();
					if( $this->is_marketplace == 'dokan' ) $this->wcfmd_marketplace = new WCFMd_Dokan();
					if( $this->is_marketplace == 'wcfmmarketplace' ) $this->wcfmd_marketplace = new WCFMd_Marketplace();
				}
			}
		}

		// Init library
		$this->load_class('library');
		$this->library = new WCFMd_Library();
		
		// Delivery TIme
		if( apply_filters( 'wcfm_is_pref_delivery_time', true ) ) {
			$this->load_class('delivery-time');
			$this->wcfmd_delivery_time = new WCFMd_Delivery_Time();
		}

		// Init ajax
		if ( defined('DOING_AJAX') || defined('WCFM_REST_API_CALL') ) {
			$this->load_class('ajax');
			$this->ajax = new WCFMd_Ajax();
		}

		if (!is_admin() || ( defined('DOING_AJAX') || defined('WCFM_REST_API_CALL') ) ) {
			$this->load_class('frontend');
			$this->frontend = new WCFMd_Frontend();
		}
		
		// Template loader
		$this->load_class( 'template' );
		$this->template = new WCFMd_Template();
		
		$this->wcfmd_fields = $WCFM->wcfm_fields;
		
	}
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-frontend-manager-delivery' );

		//load_textdomain( 'wc-frontend-manager-delivery', WP_LANG_DIR . "/wc-frontend-manager-delivery/wc-frontend-manager-delivery-$locale.mo");
		load_textdomain( 'wc-frontend-manager-delivery', $this->plugin_path . "lang/wc-frontend-manager-delivery-$locale.mo");
		load_textdomain( 'wc-frontend-manager-delivery', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-delivery-$locale.mo");
	}
	
	/**
	 * List of WCFM Delivery modules
	 */
	function get_wcfmd_modules( $wcfm_modules ) {
		$wcfmd_modules = array(
			                    'delivery'             	=> array( 'label' => __( 'Delivery Person', 'wc-frontend-manager-delivery' ) ),
			                    'delivery_time'         => array( 'label' => __( 'Delivery Time', 'wc-frontend-manager-delivery' ) ),
													);
		$wcfm_modules = array_merge( $wcfm_modules, $wcfmd_modules );
		return $wcfm_modules;
	}
	
	/**
	 * Delivery Order Status update on WC Order status change
	 */
	function wcfmd_delivery_order_status_changed( $order_id, $status_from, $status_to ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		//$withdrawal_auto_cancel_order_status   = apply_filters( 'wcfmmp_withdrawal_auto_cancel_order_status', array( 'cancelled', 'failed', 'refunded' ) );
		$delivery_trashed_order_status       = apply_filters( 'wcfmmp_commission_trashed_order_status', array( 'cancelled', 'failed' ) );
		
		if( apply_filters( 'wcfm_is_allow_trashed_cancelled_orders', true ) ) {
			if( in_array( $status_to, $delivery_trashed_order_status ) ) {
				$this->wcfmd_delivery_order_trash( $order_id );
			} else {
			  $this->wcfmd_delivery_order_untrash( $order_id );
			}
		}
		
		$order = wc_get_order( $order_id );
		
		do_action( 'wcfmmp_order_status_updated', $order_id, $status_from, $status_to, $order );
	}
	
	/**
	 * Delivery Order Delete on Order Item Delete - WC Order Action
	 */
	function wcfmd_delivery_order_item_delete( $item_id ) {
		global $wpdb;
		
		$delivery_orders = $wpdb->get_results(  $wpdb->prepare( "SELECT ID from {$wpdb->prefix}wcfm_delivery_orders WHERE `item_id` = %d", $item_id ) );
		foreach( $delivery_orders as $delivery_order ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_delivery_orders_meta WHERE order_delivery_id = %d", $delivery_order->ID ) );
		}
		
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_delivery_orders WHERE `item_id` = %d", $item_id ) );
	}
	
	/**
	 * Delivery Order Un Trash on Order Retrive
	 */
	function wcfmd_delivery_order_untrash( $order_id ) {
		global $wpdb;
		$wpdb->update("{$wpdb->prefix}wcfm_delivery_orders", array('is_trashed' => 0), array('order_id' => $order_id), array('%d'), array('%d'));
	}
	
	/**
	 * Delivery Order Trash on Order Trashed
	 */
	function wcfmd_delivery_order_trash( $order_id ) {
		global $wpdb;
		
		if ( in_array( get_post_type( $order_id ), wc_get_order_types(), true ) ) {
			$order = wc_get_order( $order_id );
			if ( is_a( $order , 'WC_Order' ) ) {
				$wpdb->update("{$wpdb->prefix}wcfm_delivery_orders", array('is_trashed' => 1), array('order_id' => $order_id), array('%d'), array('%d'));
				//$this->wcfmd_delivery_order_reset( $order_id );
			}
		}
	}
	
	/**
	 * Delivery Order Delete on Order Delete
	 */
	function wcfmd_delivery_order_delete( $order_id ) {
		global $wpdb;
		
		if ( in_array( get_post_type( $order_id ), wc_get_order_types(), true ) ) {
			$order = wc_get_order( $order_id );
			if ( is_a( $order , 'WC_Order' ) ) {
				$this->wcfmd_delivery_order_reset( $order_id );
			}
		}
	}
	
	/**
	 * Delivery Order Reset
	 */
	public function wcfmd_delivery_order_reset( $order_id ) {
		global $wpdb;
		
		$delivery_orders = $wpdb->get_results(  $wpdb->prepare( "SELECT ID from {$wpdb->prefix}wcfm_delivery_orders WHERE order_id = %d", $order_id ) );
		foreach( $delivery_orders as $delivery_order ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_delivery_orders_meta WHERE order_delivery_id = %d", $delivery_order->ID ) );
		}
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_delivery_orders WHERE order_id = %d", $order_id ) );
	}

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}

	// End load_class()

	/**
	 * Install upon activation.
	 *
	 * @access public
	 * @return void
	 */
	static function activate_wcfmd() {
		global $WCFM, $WCFMd, $wpdb;

		require_once ( $WCFMd->plugin_path . 'helpers/class-wcfmd-install.php' );
		$WCFMd_Install = new WCFMd_Install();
		
		// License Activation
		$WCFMd->load_class('license');
		WCFMd_LICENSE()->activation();
	}
	
	/**
	 * Check Installer upon load.
	 *
	 * @access public
	 * @return void
	 */
	function run_wcfmd_installer() {
		global $WCFM, $WCFMd, $wpdb;
		
		$wcfm_delivery_tables = $wpdb->query( "SHOW tables like '{$wpdb->prefix}wcfm_delivery_orders'");
		if( !$wcfm_delivery_tables ) {
			delete_option( 'wcfmd_table_install' );
		}
		
		if ( !get_option("wcfmd_table_install") ) {
			require_once ( $WCFMd->plugin_path . 'helpers/class-wcfmd-install.php' );
			$WCFMd_Install = new WCFMd_Install();
			
			update_option('wcfmd_installed', 1);
		}
	}

	/**
	 * UnInstall upon deactivation.
	 *
	 * @access public
	 * @return void
	 */
	static function deactivate_wcfmd() {
		global $WCFM, $WCFMd;
		
		// License Deactivation
		$WCFMd->load_class('license');
		WCFMd_LICENSE()->uninstall();
        
		delete_option('wcfmd_installed');
	}
	
}