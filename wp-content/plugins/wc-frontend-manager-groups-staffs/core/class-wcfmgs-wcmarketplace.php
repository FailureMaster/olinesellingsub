<?php

/**
 * WCFMgs plugin core
 *
 * Marketplace WC Marketplace Support
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.0
 */
 
class WCFMgs_WCMarketplace {
	
	public $vendor_id;
	private $vendor_term;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
    	$this->vendor_term = get_user_meta( $this->vendor_id, '_vendor_term_id', true );
    	
    	// Staffs args
    	add_filter( 'wcfmgs_get_shop_staffs_args', array( &$this, 'wcmarketplace_wcfm_filter_staffs' ) );
    	
    	// Manage Staff
			add_action( 'wcfm_staffs_manage', array( &$this, 'wcmarketplace_wcfm_staffs_manage' ) );
    	
    }
  }
  
  // WCMp Filter Staffs
	function wcmarketplace_wcfm_filter_staffs( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	// WCMp Staff Manage
	function wcmarketplace_wcfm_staffs_manage( $staff_id ) {
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}