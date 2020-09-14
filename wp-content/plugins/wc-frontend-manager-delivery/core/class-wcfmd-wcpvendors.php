<?php

/**
 * WCFMgs plugin core
 *
 * Marketplace WC Product Vendors Support
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.0
 */
 
class WCFMd_WCPVendors {
	
	public $vendor_id;
	
	public function __construct() {
    global $WCFM;
    
    if( wcfm_is_vendor() ) {
    	
    	$this->vendor_id   = apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() );
    	
    	// Delivery_boys args
    	add_filter( 'wcfm_staff_user_role', array( &$this, 'wcpvendors_wcfm_staff_user_role' ) );
    	add_filter( 'wcfmd_get_delivery_boys_args', array( &$this, 'wcpvendors_wcfm_filter_delivery_boys' ) );
    	
    	// filter the user list for vendors
		  //add_action( 'pre_get_users', array( &$this, 'wcpvendors_wcfm_filter_users' ), 50 );
    	
    	// Manage Staff
			add_action( 'wcfm_delivery_boys_manage', array( &$this, 'wcpvendors_wcfm_delivery_boys_manage' ) );
    	
    }
  }
  
  // WCP Vendor Staff User Role
  function wcpvendors_wcfm_staff_user_role( $staff_user_role ) {
  	$staff_user_role = 'wc_product_vendors_manager_vendor';
  	return $staff_user_role;
  }
    
	// WCP Vendor Filter Delivery_boys
	function wcpvendors_wcfm_filter_delivery_boys( $args ) {
		$args['meta_key'] = '_wcfm_vendor';        
		$args['meta_value'] = $this->vendor_id;
		return $args;
	}
	
	/**
	 * Filters the user list query to only the vendor can manage
	 *
	 * @return bool
	 */
	public function wcpvendors_wcfm_filter_users( $query ) {
		$meta = array(
			array(
			'key'     => '_wcfm_vendor',
			'value'   => $this->vendor_id,
			'compare' => 'LIKE',
			),
		);

		$query->set( 'meta_query', $meta );
		$query->set( 'role__in', array( 'wc_product_vendors_manager_vendor' ) );

		return true;
	}
	
	// WCP Vendor Staff Manage
	function wcpvendors_wcfm_delivery_boys_manage( $staff_id ) {
		$vendor_data = WC_Product_Vendors_Utils::get_vendor_data_from_user();
		$vendor_data['admins'][] = $staff_id;
		update_term_meta( WC_Product_Vendors_Utils::get_logged_in_vendor(), 'vendor_data', $vendor_data );
		update_user_meta( $staff_id, '_wcfm_vendor', $this->vendor_id );
	}
}