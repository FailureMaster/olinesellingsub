<?php

/**
 * WCFMgs plugin core
 *
 * Marketplace Dokan Support
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.1.8
 */
 
class WCFMd_Dokan {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	
    	// Staffs args
    	add_filter( 'wcfmd_get_delivery_boys_args', array( &$this, 'dokan_wcfm_filter_delivery_boys' ) );
    	
    	// Manage Staff
			add_action( 'wcfm_delivery_boys_manage', array( &$this, 'dokan_wcfm_delivery_boys_manage' ) );
    	
    }
  }
    
	// WCMp Filter Staffs
	function dokan_wcfm_filter_delivery_boys( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function dokan_wcfm_delivery_boys_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}