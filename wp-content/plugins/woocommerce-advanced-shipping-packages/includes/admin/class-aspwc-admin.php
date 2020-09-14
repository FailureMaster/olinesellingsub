<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class ASPWC_Admin.
 *
 * Admin class handles everything on the admin side of things.
 *
 * @class		ASPWC_Admin
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class ASPWC_Admin {

	/**
	 * @var ASPWC_Admin_Settings Admin settings class.
	 */
	public $settings;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		require_once plugin_dir_path( __FILE__ ) . 'class-aspwc-admin-settings.php';
		$this->settings = new ASPWC_Admin_Settings();

		// Enqueue scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Add to WC Screen IDs to load scripts.
		add_filter( 'woocommerce_screen_ids', array( $this, 'add_screen_ids' ) );

		// Initialize class
		add_action( 'admin_init', array( $this, 'init' ) );

	}


	/**
	 * Initialize class.
	 *
	 * Initialize the class components/hooks on admin_init so its called once.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		global $pagenow;

		if ( 'plugins.php' == $pagenow ) :
			add_filter( 'plugin_action_links_' . plugin_basename( Advanced_Shipping_Packages_for_WooCommerce()->file ), array( $this, 'add_plugin_action_links' ), 10, 2 );
		endif;
	}


	/**
	 * Enqueue scripts.
	 *
	 * Enqueue admin style sheets and javascripts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook Name of the page hook
	 */
	public function enqueue_scripts( $hook ) {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Style script
		wp_register_style( 'advanced-shipping-packages-for-woocommerce-css', plugins_url( 'assets/css/advanced-shipping-packages-for-woocommerce.min.css', Advanced_Shipping_Packages_for_WooCommerce()->file ), array(), Advanced_Shipping_Packages_for_WooCommerce()->version );

		// Javascript
		wp_register_script( 'advanced-shipping-packages-for-woocommerce-js', plugins_url( 'assets/js/advanced-shipping-packages-for-woocommerce' . $suffix . '.js', Advanced_Shipping_Packages_for_WooCommerce()->file ), array( 'jquery', 'jquery-ui-sortable', 'jquery-blockui', 'jquery-tiptip' ), Advanced_Shipping_Packages_for_WooCommerce()->version, true );

		wp_localize_script( 'advanced-shipping-packages-for-woocommerce-js', 'wpc', array(
			'nonce'         => wp_create_nonce( 'wpc-ajax-nonce' ),
			'action_prefix' => 'aspwc_',
			'asset_url'     => plugins_url( 'assets/', advanced_shipping_packages_for_woocommerce()->file ),
		) );

		// Only load scripts on relevant pages
		if (
			( isset( $_REQUEST['post'] ) && 'shipping_package' == get_post_type( $_REQUEST['post'] ) ) ||
			( isset( $_REQUEST['post_type'] ) && 'shipping_package' == $_REQUEST['post_type'] ) ||
			( isset( $_REQUEST['section'] ) && 'advanced_shipping_packages' == $_REQUEST['section'] )
		) :

			wp_localize_script( 'wp-conditions', 'wpc2', array(
				'action_prefix' => 'aspwc_',
			) );

			wp_enqueue_style( 'advanced-shipping-packages-for-woocommerce-css' );
			wp_enqueue_script( 'advanced-shipping-packages-for-woocommerce-js' );
			wp_enqueue_script( 'wp-conditions' );

			wp_dequeue_script( 'autosave' );

		endif;

	}


	/**
	 * Screen IDs.
	 *
	 * Add 'was' to the screen IDs so the WooCommerce scripts are loaded.
	 *
	 * @since 1.0.5
	 *
	 * @param  array $screen_ids List of existing screen IDs.
	 * @return array             List of modified screen IDs.
	 */
	public function add_screen_ids( $screen_ids ) {

		$screen_ids[] = 'shipping_package';

		return $screen_ids;

	}


	/**
	 * Plugin action links.
	 *
	 * Add links to the plugins.php page below the plugin name
	 * and besides the 'activate', 'edit', 'delete' action links.
	 *
	 * @since 1.1.8
	 *
	 * @param  array  $links List of existing links.
	 * @param  string $file  Name of the current plugin being looped.
	 * @return array         List of modified links.
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( $file == plugin_basename( Advanced_Shipping_Packages_for_WooCommerce()->file ) ) :
			$links = array_merge( array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=wc-settings&tab=shipping&section=advanced_shipping_packages' ) ) . '">' . __( 'Settings', 'advanced-shipping-packages-for-woocommerce' ) . '</a>'
			), $links );
		endif;

		return $links;

	}


}
