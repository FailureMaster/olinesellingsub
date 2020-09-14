<?php
/**
 * WCFMph plugin controllers
 *
 * Plugin WC Chained Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/controllers
 * @version   1.1.0
 */

class WCFMph_WC_Chained_Product_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// Chaind Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wccp_wcfm_products_manage_meta_save' ), 90, 2 );
    
    // Chaind Product Variations Meta Data Save
    add_filter( 'wcfm_product_variation_data_factory', array( &$this, 'wccp_wcfm_products_variation_save' ), 150, 5 );
	}
	
	/**
	 * WC Chained Product Meta data save
	 */
	function wccp_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMph;
		
		if ( isset( $wcfm_products_manage_form_data[ '_chained_product_detail' ] ) && !empty( $wcfm_products_manage_form_data[ '_chained_product_detail' ] ) ) {
			$chained_product_detail = array();
			foreach( $wcfm_products_manage_form_data[ '_chained_product_detail' ] as $chained_product ) {
				if( isset( $chained_product['product_id'] ) && !empty( $chained_product['product_id'] ) ) {
					$chained_product_detail[$chained_product['product_id']] = $chained_product;
					$chained_product_detail[$chained_product['product_id']]['product_name'] = 'sadasds';
				}
			}
			update_post_meta( $new_product_id, '_chained_product_detail', $chained_product_detail );
		}
		
		if ( isset( $wcfm_products_manage_form_data[ '_chained_product_manage_stock' ] ) && !empty( $wcfm_products_manage_form_data[ '_chained_product_manage_stock' ] ) ) {
			update_post_meta( $new_product_id, '_chained_product_manage_stock', 'yes' );
		} else {
			update_post_meta( $new_product_id, '_chained_product_manage_stock', 'no' );
		}
	}
	
	/**
	 * WC Chained Product Variation Data Save
	 */
	function wccp_wcfm_products_variation_save( $wcfm_variation_data, $new_product_id, $variation_id, $variations, $wcfm_products_manage_form_data ) {
	 	global $wpdb, $WCFM, $WCFMu;
	 	  
		if ( isset( $variations[ '_chained_product_detail' ] ) && !empty( $variations[ '_chained_product_detail' ] ) ) {
			$chained_product_detail = array();
			foreach( $variations[ '_chained_product_detail' ] as $chained_product ) {
				if( isset( $chained_product['product_id'] ) && !empty( $chained_product['product_id'] ) ) {
					$chained_product_detail[$chained_product['product_id']] = $chained_product;
					$chained_product_detail[$chained_product['product_id']]['product_name'] = 'sadasds';
				}
			}
			update_post_meta( $variation_id, '_chained_product_detail', $chained_product_detail );
		}
		
		if ( isset( $variations[ '_chained_product_manage_stock' ] ) && !empty( $variations[ '_chained_product_manage_stock' ] ) ) {
			update_post_meta( $variation_id, '_chained_product_manage_stock', 'yes' );
		} else {
			update_post_meta( $variation_id, '_chained_product_manage_stock', 'no' );
		}
	}
}