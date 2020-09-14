<?php
/**
 * WCFMph plugin controllers
 *
 * Plugin WC Group Buy Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/controllers
 * @version   1.0.2
 */

class WCFMph_WC_Groupbuy_Product_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// Appointments Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcgroupbuy_wcfm_products_manage_meta_save' ), 90, 2 );
	}
	
	/**
	 * WC Group Buy Product Meta data save
	 */
	function wcgroupbuy_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMph;
		
		$product_type = empty( $wcfm_products_manage_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $wcfm_products_manage_form_data['product_type'] ) );

		if ( $product_type != 'groupbuy' ) return;
		
		if (isset($wcfm_products_manage_form_data['_groupbuy_max_deals'] ) && !empty($wcfm_products_manage_form_data['_groupbuy_max_deals']) && !empty($wcfm_products_manage_form_data['_groupbuy_price'])) {

			update_post_meta( $new_product_id, '_manage_stock', 'yes'  );

			if(get_post_meta( $new_product_id, '_groupbuy_participants_count',TRUE )) {
				$number_of_activ_deals = intval(wc_clean( $wcfm_products_manage_form_data['_groupbuy_max_deals'])) - intval(get_post_meta( $new_product_id, '_groupbuy_participants_count',TRUE ));
				update_post_meta( $new_product_id, '_stock', $number_of_activ_deals  );
				if( $number_of_activ_deals > 0 ){
					update_post_meta( $new_product_id, '_stock_status', 'instock'  );
				}

			} else {
				update_post_meta( $new_product_id, '_stock', wc_clean( $wcfm_products_manage_form_data['_groupbuy_max_deals'])  );
				update_post_meta( $new_product_id, '_stock_status', 'instock'  );
			}

			update_post_meta( $new_product_id, '_backorders', 'no'  );
				
		}	else {
			update_post_meta( $new_product_id, '_manage_stock', 'no'  );
			update_post_meta( $new_product_id, '_backorders', 'no'  );
			update_post_meta( $new_product_id, '_stock_status', 'instock'  );

		}


	  if ( isset($wcfm_products_manage_form_data['_groupbuy_price'] ) && !empty($wcfm_products_manage_form_data['_groupbuy_price']) ) {

	  	$deal_price = wc_format_decimal( wc_clean( $wcfm_products_manage_form_data['_groupbuy_price'] ) );
			update_post_meta( $new_product_id, '_groupbuy_price', $deal_price  );
			update_post_meta( $new_product_id, '_sale_price', $deal_price );
			update_post_meta( $new_product_id, '_price', $deal_price );
	  }

	  if (isset($wcfm_products_manage_form_data['_groupbuy_regular_price'] ) && !empty($wcfm_products_manage_form_data['_groupbuy_regular_price'])){

		$deal_regular_price = wc_format_decimal( wc_clean( $wcfm_products_manage_form_data['_groupbuy_regular_price'] ) );
			update_post_meta( $new_product_id, '_groupbuy_regular_price', $deal_regular_price  );
			update_post_meta( $new_product_id, '_regular_price', $deal_regular_price );

	  }

		if (isset($wcfm_products_manage_form_data['_groupbuy_max_deals_per_user'] ) && !empty($wcfm_products_manage_form_data['_groupbuy_max_deals_per_user']) ){

			update_post_meta( $new_product_id, '_groupbuy_max_deals_per_user', wc_clean( $wcfm_products_manage_form_data['_groupbuy_max_deals_per_user'] ) );

			if ($wcfm_products_manage_form_data['_groupbuy_max_deals_per_user'] <= 1){
				update_post_meta( $new_product_id, '_sold_individually', 'yes'  );
			} else {
				update_post_meta( $new_product_id, '_sold_individually', 'no'  );
			}

		} else {
			delete_post_meta( $new_product_id, '_groupbuy_max_deals_per_user');
			update_post_meta( $new_product_id, '_sold_individually', 'no'  );
		}

		if (isset($wcfm_products_manage_form_data['_groupbuy_min_deals'] ))
			update_post_meta( $new_product_id, '_groupbuy_min_deals', wc_clean( $wcfm_products_manage_form_data['_groupbuy_min_deals'] ) );
		if (isset($wcfm_products_manage_form_data['_groupbuy_max_deals'] ))
			update_post_meta( $new_product_id, '_groupbuy_max_deals', wc_clean( $wcfm_products_manage_form_data['_groupbuy_max_deals'] ) );
		if (isset($wcfm_products_manage_form_data['_groupbuy_dates_from'] ))
			update_post_meta( $new_product_id, '_groupbuy_dates_from', wc_clean( $wcfm_products_manage_form_data['_groupbuy_dates_from'] ) );
		if (isset($wcfm_products_manage_form_data['_groupbuy_dates_to'] ))
			update_post_meta( $new_product_id, '_groupbuy_dates_to', wc_clean( $wcfm_products_manage_form_data['_groupbuy_dates_to'] ) );

		if (isset($wcfm_products_manage_form_data['_relist_groupbuy_dates_from']) && isset($wcfm_products_manage_form_data['_relist_groupbuy_dates_to']) && !empty($wcfm_products_manage_form_data['_relist_groupbuy_dates_from']) && !empty($wcfm_products_manage_form_data['_relist_groupbuy_dates_to'])) {
			$this->wcfm_groupbuy_do_relist( $new_product_id, $wcfm_products_manage_form_data['_relist_groupbuy_dates_from'], $wcfm_products_manage_form_data['_relist_groupbuy_dates_to'] );
		}
	}
	
	function wcfm_groupbuy_do_relist( $new_product_id, $relist_from, $relist_to ) {
		global $wpdb;

		update_post_meta( $new_product_id, '_groupbuy_dates_from', stripslashes($relist_from) );
		update_post_meta( $new_product_id, '_groupbuy_dates_to', stripslashes($relist_to) );
		update_post_meta( $new_product_id, '_groupbuy_relisted', current_time('mysql') );
		delete_post_meta( $new_product_id, '_groupbuy_closed' );
		delete_post_meta( $new_product_id, '_groupbuy_started' );
		delete_post_meta( $new_product_id, '_groupbuy_has_started' );
		delete_post_meta( $new_product_id, '_groupbuy_fail_reason' );
		delete_post_meta( $new_product_id, '_groupbuy_participant_id' );
		delete_post_meta( $new_product_id, '_groupbuy_participants_count' );
		delete_post_meta( $new_product_id, '_groupbuy_order_hold_on' );
		
		
		$groupbuy_max_deals = get_post_meta( $new_product_id, '_groupbuy_max_deals', true );
		update_post_meta( $new_product_id, '_stock', $groupbuy_max_deals  );
		update_post_meta( $new_product_id, '_stock_status', 'instock' );

		$order_id = get_post_meta( $new_product_id, 'order_id', true );
		// check if the custom field has a value
		if (!empty($order_id)) {
			delete_post_meta( $new_product_id, '_order_id' );
		}

		$wpdb->delete(
			$wpdb->usermeta, array(
				'meta_key'   => 'my_groupbuys',
				'meta_value' => $new_product_id,
			), array( '%s', '%s' )
		);

		do_action( 'woocommerce_groupbuy_do_relist',  $new_product_id, $relist_from, $relist_to );
	}
}