<?php

/**
 * WCFM Product Hub plugin
 *
 * WCFM Product Hub Core
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */

class WCFMph {

	public $plugin_base_name;
	public $plugin_url;
	public $plugin_path;
	public $version;
	public $token;
	public $text_domain;
	public $library;
	public $shortcode;
	public $admin;
	public $frontend;
	public $listings_stats;
	public $ajax;
	private $file;
	public $settings;
	public $license;
	public $WCFMph_fields;
	public $is_marketplece;
	public $WCFMph_marketplace;
	public $WCFMph_capability;
	public $wcfmph_non_ajax;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_base_name = plugin_basename( $file );
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCFMph_TOKEN;
		$this->text_domain = WCFMph_TEXT_DOMAIN;
		$this->version = WCFMph_VERSION;
		
		add_action('init', array(&$this, 'init'));
		
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCFM, $WCFMph;
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		// Capability Controller
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class( 'capability' );
			$this->wcfmph_capability = new WCFMph_Capability();
		}
		
		// Init library
		$this->load_class('library');
		$this->library = new WCFMph_Library();

		// Init ajax
		if (defined('DOING_AJAX')) {
			$this->load_class('ajax');
			$this->ajax = new WCFMph_Ajax();
		}

		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('frontend');
			$this->frontend = new WCFMph_Frontend();
		}
			
		// DC License Activation
		if (is_admin()) {
			$this->load_class('license');
			$this->license = WCFMph_LICENSE();
		}
		
		if( !defined('DOING_AJAX') ) {
			$this->load_class( 'non-ajax' );
			$this->wcfmph_non_ajax = new WCFMph_Non_Ajax();
		}
		
		$this->wcfmph_fields = $WCFM->wcfm_fields;
		
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
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-frontend-manager-product-hub' );

		//load_textdomain( 'wc-frontend-manager-product-hub', WP_LANG_DIR . "/wc-frontend-manager-product-hub/wc-frontend-manager-product-hub-$locale.mo");
		load_textdomain( 'wc-frontend-manager-product-hub', $this->plugin_path . "lang/wc-frontend-manager-product-hub-$locale.mo");
		load_textdomain( 'wc-frontend-manager-product-hub', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-product-hub-$locale.mo");
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
	static function activate_wcfmph() {
		global $WCFM, $WCFMph, $wp_roles;

		// License Activation
		$WCFMph->load_class('license');
		WCFMph_LICENSE()->activation();
		
		update_option('wcfmph_installed', 1);
	}

	/**
	 * UnInstall upon deactivation.
	 *
	 * @access public
	 * @return void
	 */
	static function deactivate_wcfmph() {
		global $WCFM, $WCFMph;
		
		// License Deactivation
		$WCFMph->load_class('license');
		WCFMph_LICENSE()->uninstall();
        
		delete_option('wcfmph_installed');
	}
	
}