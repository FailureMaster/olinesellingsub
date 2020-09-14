<?php
/**
 * WCFMph plugin controllers
 *
 * Plugin WC Force Sales Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/controllers
 * @version   1.1.0
 */

class WCFMph_WC_Force_Sales_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// Appointments Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfs_wcfm_products_manage_meta_save' ), 90, 2 );
	}
	
	/**
	 * WC Bundle Product Meta data save
	 */
	function wcfs_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMph;
		
		if ( isset( $wcfm_products_manage_form_data[ '_force_sell_ids' ] ) && !empty( $wcfm_products_manage_form_data[ '_force_sell_ids' ] ) ) {
			update_post_meta( $new_product_id, '_force_sell_ids', $wcfm_products_manage_form_data[ '_force_sell_ids' ] );
		}
		
		if ( isset( $wcfm_products_manage_form_data[ '_force_sell_synced_ids' ] ) && !empty( $wcfm_products_manage_form_data[ '_force_sell_synced_ids' ] ) ) {
			update_post_meta( $new_product_id, '_force_sell_synced_ids', $wcfm_products_manage_form_data[ '_force_sell_synced_ids' ] );
		}
		
	}
}