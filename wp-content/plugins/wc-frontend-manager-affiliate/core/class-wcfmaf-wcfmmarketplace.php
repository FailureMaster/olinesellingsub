<?php

/**
 * WCFMgs plugin core
 *
 * WCFM Multivendor Marketplace Support
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   a.0.0
 */
 
class WCFMaf_Marketplace {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    //if( wcfm_is_vendor() ) {
    	
    	//$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	
    	// Affiliate_boys args
    	//add_filter( 'wcfmaf_get_affiliate_args', array( &$this, 'wcfmmp_wcfm_filter_affiliate' ) );
    	
    	// Manage Staff
			//add_action( 'wcfm_affiliate_manage', array( &$this, 'wcfmmp_wcfm_affiliate_manage' ) );
    	
    //}
  }
    
	// WCMp Filter Affiliate_boys
	function wcfmmp_wcfm_filter_affiliate( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function wcfmmp_wcfm_affiliate_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}