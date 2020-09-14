<?php
/**
 * WCFM Affiliate plugin core
 *
 * Plugin shortcode
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.1.0
 */
 
class WCFMaf_Shortcode {

	public $list_product;

	public function __construct() {
		// WC Frontend Manager Affiliate Registration Shortcode
		add_shortcode('wcfm_affiliate_registration', array(&$this, 'wcfm_affiliate_registraion'));
	}

	public function wcfm_affiliate_registraion($attr) {
		global $WCFM;
		$this->load_class('affiliate-registration');
		return $this->shortcode_wrapper(array('WCFM_Affiliate_Registration_Shortcode', 'output'));
	}
	
	/**
	 * Shortcode Wrapper
	 *
	 * @access public
	 * @param mixed $function
	 * @param array $atts (default: array())
	 * @return string
	 */
	public function shortcode_wrapper($function, $atts = array()) {
		ob_start();
		call_user_func($function, $atts);
		return ob_get_clean();
	}

	/**
	 * Shortcode CLass Loader
	 *
	 * @access public
	 * @param mixed $class_name
	 * @return void
	 */
	public function load_class($class_name = '') {
		global $WCFMaf;
		if ('' != $class_name && '' != $WCFMaf->token) {
			include_once ( $WCFMaf->plugin_path . 'includes/shortcodes/class-' . esc_attr($WCFMaf->token) . '-shortcode-' . esc_attr($class_name) . '.php' );
		}
	}

}
?>