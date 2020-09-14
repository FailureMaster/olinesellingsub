<?php

/**
 * WCFMgs plugin core
 *
 * WCFM Multivendor Marketplace Support
 *
 * @author 		WC Lovers
 * @package 	wcfmd/core
 * @version   a.0.0
 */
 
class WCFMd_Marketplace {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	
    	// Delivery_boys args
    	add_filter( 'wcfmd_get_delivery_boys_args', array( &$this, 'wcfmmp_wcfm_filter_delivery_boys' ) );
    	
    	// Manage Staff
			add_action( 'wcfm_delivery_boys_manage', array( &$this, 'wcfmmp_wcfm_delivery_boys_manage' ) );
    	
    }
  }
    
	// WCMp Filter Delivery_boys
	function wcfmmp_wcfm_filter_delivery_boys( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function wcfmmp_wcfm_delivery_boys_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}