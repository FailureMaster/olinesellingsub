<?php
/**
 * WCFM Affiliate plugin core
 *
 * Plugin Capability Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.1
 */
 
class WCFMaf_Capability {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM, $WCFMaf;
		
		//$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', (array) get_option( 'wcfm_capability_options' ) );
		
		//add_filter( 'wcfm_is_allow_affiliate', array( &$this, 'wcfmcap_is_allow_affiliate' ), 500 );		
	}
	
  // WCFM wcfmcap Analytics
  function wcfmcap_is_allow_affiliate( $allow ) {
  	$affiliate = ( isset( $this->wcfm_capability_options['affiliate'] ) ) ? $this->wcfm_capability_options['affiliate'] : 'no';
  	if( $affiliate == 'yes' ) return false;
  	return $allow;
  }
}