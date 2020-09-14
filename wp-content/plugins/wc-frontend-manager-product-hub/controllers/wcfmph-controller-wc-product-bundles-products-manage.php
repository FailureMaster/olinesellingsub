<?php
/**
 * WCFMph plugin controllers
 *
 * Plugin WC Bundle Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/controllers
 * @version   1.1.0
 */

class WCFMph_WC_Product_Bundles_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// Appointments Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcpb_wcfm_products_manage_meta_save' ), 90, 2 );
	}
	
	/**
	 * WC Bundle Product Meta data save
	 */
	function wcpb_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMph;
		
		/*
		 * Process bundle-sell IDs.
		 */
	 
		if ( ! empty( $wcfm_products_manage_form_data[ 'bundle_sell_ids' ] ) ) {
			$bundle_sell_ids = ! empty( $wcfm_products_manage_form_data[ 'bundle_sell_ids' ] ) && is_array( $wcfm_products_manage_form_data[ 'bundle_sell_ids' ] ) ? array_map( 'intval', (array) $wcfm_products_manage_form_data[ 'bundle_sell_ids' ] ) : array();
			if ( ! empty( $bundle_sell_ids ) ) {
				update_post_meta( $new_product_id, '_wc_pb_bundle_sell_ids', $bundle_sell_ids );
			} else {
				delete_post_meta( $new_product_id, '_wc_pb_bundle_sell_ids' );
			}
		}
		
		/*
		 * Process bundle-sells title.
		 */
		if ( ! empty( $wcfm_products_manage_form_data[ 'wc_pb_bundle_sells_title' ] ) ) {
			$bundle_sells_title = ! empty( $wcfm_products_manage_form_data[ 'wc_pb_bundle_sells_title' ] ) ? $wcfm_products_manage_form_data[ 'wc_pb_bundle_sells_title' ] : false;

			if ( $bundle_sells_title ) {
				update_post_meta( $new_product_id, '_wc_pb_bundle_sells_title', wp_kses_post( stripslashes( $bundle_sells_title ) ) );
			} else {
				delete_post_meta( $new_product_id, '_wc_pb_bundle_sells_title' );
			}
		}
		
		$product_type = empty( $wcfm_products_manage_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $wcfm_products_manage_form_data['product_type'] ) );
		
		// Only set props if the product is a bookable product.
		if ( $product_type != 'bundle' ) {
			return;
		}
		
		$product = wc_get_product( $new_product_id );
		
    //session_start();
    if ( $product->is_type('bundle') ) {
    	
    	$props = array(
        'layout'                    => 'default',
        'editable_in_cart'          => false,
        'sold_individually'         => false,
        'sold_individually_context' => 'product'
      );

      /*
       * Layout.
       */

      if ( ! empty( $wcfm_products_manage_form_data[ '_wc_pb_layout_style' ] ) ) {
        $props[ 'layout' ] = wc_clean( $wcfm_products_manage_form_data[ '_wc_pb_layout_style' ] );
      }
      
      /*
			 * Item grouping option.
			 */

			$group_mode_pre = $product->get_group_mode( 'edit' );

			if ( ! empty( $wcfm_products_manage_form_data[ '_wc_pb_group_mode' ] ) ) {
				$props[ 'group_mode' ] = wc_clean( $wcfm_products_manage_form_data[ '_wc_pb_group_mode' ] );
			}

      /*
       * Cart editing option.
       */

      if ( ! empty( $wcfm_products_manage_form_data[ '_wc_pb_edit_in_cart' ] ) ) {
        $props[ 'editable_in_cart' ] = true;
      }

      /*
       * Extended "Sold Individually" option.
       */

      if ( ! empty( $wcfm_products_manage_form_data[ '_wc_pb_sold_individually' ] ) ) {

        $sold_individually_context = wc_clean( $wcfm_products_manage_form_data[ '_wc_pb_sold_individually' ] );

        if ( in_array( $sold_individually_context, array( 'product', 'configuration' ) ) ) {
          $props[ 'sold_individually' ]         = true;
          $props[ 'sold_individually_context' ] = $sold_individually_context;
        }
      }
      
      /*
			 * "Form location" option.
			 */

			if ( ! empty( $wcfm_products_manage_form_data[ '_wc_pb_add_to_cart_form_location' ] ) ) {

				$form_location = wc_clean( $wcfm_products_manage_form_data[ '_wc_pb_add_to_cart_form_location' ] );

				if ( in_array( $form_location, array_keys( WC_Product_Bundle::get_add_to_cart_form_location_options() ) ) ) {
					$props[ 'add_to_cart_form_location' ] = $form_location;
				}
			}
      
      $posted_bundle_data    = isset( $wcfm_products_manage_form_data[ 'bundle_data' ] ) ? $wcfm_products_manage_form_data[ 'bundle_data' ] : false;
      
      //$wcfmph_msg = __('Please define defaults for variation attributes, to hide from single-product template the item ','woocommerce-product-bundles');
      if( $posted_bundle_data && is_array( $posted_bundle_data ) && !empty( $posted_bundle_data ) ) {
				foreach($posted_bundle_data as $i => $pbd) {
					if( isset( $posted_bundle_data[$i] ) && isset( $posted_bundle_data[$i]['default_variation_attributes'] ) ) {
						$posted_bundle_data[$i]['default_variation_attributes'] = $posted_bundle_data[$i]['default_variation_attributes'];
						$posted_bundle_data[$i]['menu_order'] = $i;
		
						$bundle_item_obj = wc_get_product( $posted_bundle_data[$i]['product_id'] );
						$bundle_item_type  = $bundle_item_obj->get_type();
						if ( !isset($posted_bundle_data[$i]['single_product_visibility']) ) {
							if ( in_array( $bundle_item_type, array( 'variable', 'variable-subscription' ) ) ) {
								if ( isset($posted_bundle_data[$i][ 'override_default_variation_attributes' ]) && 'yes' === $posted_bundle_data[$i][ 'override_default_variation_attributes' ] ) {
									if ( ! empty( $posted_bundle_data[$i]['default_variation_attributes'] ) ) {
										foreach ( $posted_bundle_data[$i]['default_variation_attributes'] as $default_name => $default_value ) {
											if ( ! $default_value ) {
												//sprintf( __( 'To hide item <strong>#%1$s: %2$s</strong> from the single-product template, please define defaults for its variation attributes.', 'woocommerce-product-bundles' ), $i, $bundle_item_obj->get_name() ) ;
												//echo 'hello single-product template, please define defaults for its variation attributes.';
	
												//$_SESSION["wcfmph_msg"] = __('To hide item for variable/variable subscription product from the single-product template, please define defaults for its variation attributes.','woocommerce-product-bundles');
												
											}
										}
									}
								} else {
									//sprintf( __( 'To hide item <strong>#%1$s: %2$s</strong> from the single-product template, please define defaults for its variation attributes.', 'woocommerce-product-bundles' ), $i, $bundle_item_obj->get_name() ) ;
									//echo 'single-product template, please define defaults for its variation attributes.';
									//$_SESSION["wcfmph_msg"] = $wcfmph_msg.$bundle_item_obj->get_name();
									//$_SESSION["wcfmph_msg"] = __('To hide item for variable/variable subscription product from the single-product template, please define defaults for its variation attributes.','woocommerce-product-bundles');
								}
							}
						}
					}
				}
      }
      
      if( class_exists( 'WC_PB_Min_Max_Items' ) ) {
				if ( ! empty( $wcfm_products_manage_form_data[ '_wcpb_min_qty_limit' ] ) && is_numeric( $wcfm_products_manage_form_data[ '_wcpb_min_qty_limit' ] ) ) {
					$product->add_meta_data( '_wcpb_min_qty_limit', stripslashes( $wcfm_products_manage_form_data[ '_wcpb_min_qty_limit' ] ), true );
				} else {
					$product->delete_meta_data( '_wcpb_min_qty_limit' );
				}
		
				if ( ! empty( $wcfm_products_manage_form_data[ '_wcpb_max_qty_limit' ] ) && is_numeric( $wcfm_products_manage_form_data[ '_wcpb_max_qty_limit' ] ) ) {
					$product->add_meta_data( '_wcpb_max_qty_limit', stripslashes( $wcfm_products_manage_form_data[ '_wcpb_max_qty_limit' ] ), true );
				} else {
					$product->delete_meta_data( '_wcpb_max_qty_limit' );
				}
			}
      
      $processed_bundle_data = WC_PB_Meta_Box_Product_Data::process_posted_bundle_data( $posted_bundle_data, $new_product_id );
      //print_r($processed_bundle_data);die;
      if ( !empty( $processed_bundle_data ) ) {
        foreach ( $processed_bundle_data as $key => $data ) {
          $processed_bundle_data[ $key ] = array(
            'bundled_item_id' => $data[ 'item_id' ],
            'bundle_id'       => $new_product_id,
            'product_id'      => $data[ 'product_id' ],
            //'menu_order'      => $key,
            'menu_order'      => ( !empty($data[ 'menu_order' ]) ) ? $data[ 'menu_order' ] : $key,
            'meta_data'       => array_diff_key( $data, array( 'item_id' => 1, 'product_id' => 1, 'menu_order' => 1 ) )
          );
        }

        $props[ 'bundled_data_items' ] = $processed_bundle_data;
      }

      $product->set_props($props);
      $product->save();
    }
	}
}