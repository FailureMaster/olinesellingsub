<?php
/**
 * WCFM plugin core
 *
 * Plugin Capability Controller
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.1
 */
 
class WCFMa_Capability {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM, $WCFMa;
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', (array) get_option( 'wcfm_capability_options' ) );
		
		add_filter( 'wcfm_is_allow_analytics', array( &$this, 'wcfmcap_is_allow_analytics' ), 500 );		
	}
	
  // WCFM wcfmcap Analytics
  function wcfmcap_is_allow_analytics( $allow ) {
  	$analytics = ( isset( $this->wcfm_capability_options['analytics'] ) ) ? $this->wcfm_capability_options['analytics'] : 'no';
  	if( $analytics == 'yes' ) return false;
  	return $allow;
  }
}