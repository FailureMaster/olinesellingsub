<?php

/**
 * WCFMgs plugin core
 *
 * Marketplace WC Vendors Support
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.0
 */
 
class WCFMd_WCVendors {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	
    	// Delivery_boys args
    	add_filter( 'wcfmd_get_delivery_boys_args', array( &$this, 'wcvendors_wcfm_filter_delivery_boys' ) );
    	
    	// Manage Staff
			add_action( 'wcfm_delivery_boys_manage', array( &$this, 'wcvendors_wcfm_delivery_boys_manage' ) );
    	
    }
  }
    
	// WCMp Filter Delivery_boys
	function wcvendors_wcfm_filter_delivery_boys( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function wcvendors_wcfm_delivery_boys_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}