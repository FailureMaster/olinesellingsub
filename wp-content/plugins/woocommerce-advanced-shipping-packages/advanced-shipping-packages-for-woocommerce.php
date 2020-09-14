<?php
/**
 * Plugin Name:  WooCommerce Advanced Shipping Packages
 * Plugin URI:   https://woocommerce.com/products/woocommerce-advanced-shipping-packages/
 * Description:  Split your order into multiple shipping packages when you need it to, with the products you want to.
 * Version:      1.1.7
 * Author:       Jeroen Sormani
 * Author URI:   https://jeroensormani.com/
 * Text Domain:  advanced-shipping-packages-for-woocommerce
 *
 * WC requires at least: 3.0
 * WC tested up to: 4.2
 * Woo: 1922995:7e5f76d76a24335e1b21d633ffd2b28d
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '7e5f76d76a24335e1b21d633ffd2b28d', '1922995' );

/**
 * Class Advanced_Shipping_Packages_for_WooCommerce.
 *
 * Main plugin class handles everything like initializing the other parts.
 *
 * @class       Advanced_Shipping_Packages_for_WooCommerce
 * @version     1.0.0
 * @author      Jeroen Sormani
 */
class Advanced_Shipping_Packages_for_WooCommerce {


	/**
	 * Version.
	 *
	 * @since 1.0.0
	 * @var string $version Plugin version number.
	 */
	public $version = '1.1.7';


	/**
	 * File.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin __FILE__ path.
	 */
	public $file = __FILE__;


	/**
	 * Instance of Advanced_Shipping_Packages_for_WooCommerce.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of ASPWC.
	 */
	private static $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( ! is_woocommerce_active() ) {
			return;
		}

	}


	/**
	 * Instance.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 *
	 * @return object Instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;

	}


	/**
	 * Init.
	 *
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Load textdomain
		$this->load_textdomain();

		require_once plugin_dir_path( __FILE__ ) . '/libraries/wp-conditions/functions.php';

		/**
		 * Admin class
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/admin/class-aspwc-admin.php';
		$this->admin = new ASPWC_Admin();

		/**
		 * Post type
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-aspwc-post-type.php';
		$this->post_type = new ASPWC_Post_Type();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) :
			/**
			 * AJAX class
			 */
			require_once plugin_dir_path( __FILE__ ) . 'includes/class-aspwc-ajax.php';
			$this->ajax = new ASPWC_AJAX();
		endif;

		/**
		 * Matching functions
		 */
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-aspwc-match-conditions.php';
		$this->matcher = new ASPWC_Match_Conditions();

		require_once plugin_dir_path( __FILE__ ) . 'includes/aspwc-core-functions.php';

	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'advanced-shipping-packages-for-woocommerce' );

		// Load textdomain
		load_textdomain( 'advanced-shipping-packages-for-woocommerce', WP_LANG_DIR . '/advanced-shipping-packages-for-woocommerce/advanced-shipping-packages-for-woocommerce-' . $locale . '.mo' );
		load_plugin_textdomain( 'advanced-shipping-packages-for-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages' );

	}


}


if ( ! function_exists( 'advanced_shipping_packages_for_woocommerce' ) ) :

	/**
	 * The main function responsible for returning the Advanced_Shipping_Packages_for_WooCommerce object.
	 *
	 * Use this function like you would a global variable, except without needing to declare the global.
	 *
	 * Example: <?php advanced_shipping_packages_for_woocommerce()->method_name(); ?>
	 *
	 * @since 1.0.0
	 *
	 * @return object advanced_shipping_packages_for_woocommerce class object.
	 */
	function advanced_shipping_packages_for_woocommerce() {

		return Advanced_Shipping_Packages_for_WooCommerce::instance();

	}


endif;

advanced_shipping_packages_for_woocommerce()->init();
