<?php

/**
 * WCFM Analytics plugin
 *
 * WCFM Analytics Core
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.0
 */

class WCFMa {

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
	public $WCFMa_fields;
	public $is_marketplace;
	public $WCFMa_marketplace;
	public $WCFMa_capability;
	public $wcfma_non_ajax;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_base_name = plugin_basename( $file );
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCFMa_TOKEN;
		$this->text_domain = WCFMa_TEXT_DOMAIN;
		$this->version = WCFMa_VERSION;
		
		add_action('init', array(&$this, 'init'));
		
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCFM, $WCFMa;
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		if( ( version_compare( WC_VERSION, '3.0', '<' ) ) ) {
			//add_action( 'admin_notices', 'wcfm_woocommerce_version_notice' );
			return;
		}
		
		// Capability Controller
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class( 'capability' );
			$this->wcfma_capability = new WCFMa_Capability();
		}
		
		// Check Marketplace
		$this->is_marketplace = wcfm_is_marketplace();

		// Init library
		$this->load_class('library');
		$this->library = new WCFMa_Library();

		if ( $is_wcfm_analytics_enable = is_wcfm_analytics() ) {
			// Init ajax
			if (defined('DOING_AJAX')) {
				$this->load_class('ajax');
				$this->ajax = new WCFMa_Ajax();
			}
	
			if (!is_admin() || defined('DOING_AJAX')) {
				$this->load_class('frontend');
				$this->frontend = new WCFMa_Frontend();
			}
			
			if (!is_admin() || defined('DOING_AJAX')) {
				if( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() && WCFMa_Dependencies::wpjms_plugin_active_check() ) {
					$this->load_class('listings-stats');
					$this->listings_stats = new WCFMa_Listings_Stats();
				}
			}
		}
		
		// WCfM License Activation
		if (is_admin()) {
			$this->load_class('license');
			$this->license = WCFMa_LICENSE();
		}
		
		if( !defined('DOING_AJAX') ) {
			$this->load_class( 'non-ajax' );
			$this->wcfma_non_ajax = new WCFMa_Non_Ajax();
		}
		
		// Template loader
		$this->load_class( 'template' );
		$this->template = new WCFMa_Template();
		
		$this->wcfma_fields = $WCFM->wcfm_fields;
		
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
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-frontend-manager-analytics' );

		//load_textdomain( 'wc-frontend-manager-analytics', WP_LANG_DIR . "/wc-frontend-manager-analytics/wc-frontend-manager-analytics-$locale.mo");
		load_textdomain( 'wc-frontend-manager-analytics', $this->plugin_path . "lang/wc-frontend-manager-analytics-$locale.mo");
		load_textdomain( 'wc-frontend-manager-analytics', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-analytics-$locale.mo");
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
	static function activate_wcfma() {
		global $WCFM, $WCFMa, $wp_roles;

		// License Activation
		$WCFMa->load_class('license');
		WCFMa_LICENSE()->activation();
		
		update_option('wcfma_installed', 1);
	}

	/**
	 * UnInstall upon deactivation.
	 *
	 * @access public
	 * @return void
	 */
	static function deactivate_wcfma() {
		global $WCFM, $WCFMa;
		
		// License Deactivation
		$WCFMa->load_class('license');
		WCFMa_LICENSE()->uninstall();
        
		delete_option('wcfma_installed');
	}
	
}