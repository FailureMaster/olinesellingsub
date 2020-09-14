<?php
/**
 * WCFM Product Hub plugin core
 *
 * Plugin Capability Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */
 
class WCFMph_Capability {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM, $WCFMph;
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', (array) get_option( 'wcfm_capability_options' ) );
		
		add_filter( 'wcfm_is_allow_wc_product_bundles', array( &$this, 'wcfmcap_is_allow_wc_product_bundles' ), 500 );		
	}
	
  // WCFM wcfmcap WC Product bundles
  function wcfmcap_is_allow_wc_product_bundles( $allow ) {
  	$product_bundle = ( isset( $this->wcfm_capability_options['product_bundle'] ) ) ? $this->wcfm_capability_options['product_bundle'] : 'no';
  	if( $product_bundle == 'yes' ) return false;
  	return $allow;
  }
}