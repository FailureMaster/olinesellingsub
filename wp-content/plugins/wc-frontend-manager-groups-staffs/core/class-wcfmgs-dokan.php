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
 
class WCFMgs_Dokan {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	
    	// Staffs args
    	add_filter( 'wcfmgs_get_shop_staffs_args', array( &$this, 'dokan_wcfm_filter_staffs' ) );
    	
    	// Manage Staff
			add_action( 'wcfm_staffs_manage', array( &$this, 'dokan_wcfm_staffs_manage' ) );
    	
    }
  }
    
	// WCMp Filter Staffs
	function dokan_wcfm_filter_staffs( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function dokan_wcfm_staffs_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}