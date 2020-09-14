<?php
/**
 * WCFMph plugin controllers
 *
 * Plugin WC Composite Products Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmph/controllers
 * @version   1.1.0
 */

class WCFMph_WC_Composite_Product_Products_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// Appointments Product Meta Data Save
    add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wccp_wcfm_products_manage_meta_save' ), 90, 2 );
	}
	
	/**
	 * WC Composite Product Meta data save
	 */
	function wccp_wcfm_products_manage_meta_save( $new_product_id, $wcfm_products_manage_form_data ) {
		global $wpdb, $WCFM, $WCFMph;
		
		$product_type = empty( $wcfm_products_manage_form_data['product_type'] ) ? WC_Product_Factory::get_product_type( $new_product_id ) : sanitize_title( stripslashes( $wcfm_products_manage_form_data['product_type'] ) );
		
		// Only set props if the product is a bookable product.
		if ( $product_type != 'composite' ) {
			return;
		}
		
		$composite_id = $new_product_id;
		
		$product = wc_get_product( $new_product_id );
    //session_start();
    if ( $product->is_type('composite') ) {
    	
    	$composite = new WC_Product_Composite( $new_product_id );

    	if ( $composite ) {
    	
				$props = array(
					'layout'                    => 'single',
					'editable_in_cart'          => false,
					'shop_price_calc'           => 'defaults',
					
					'sold_individually'         => false,
					'sold_individually_context' => 'product',
					
					'composite_data'            => array(),
					'scenario_data'             => array()
				);
	
				/*
				 * Extended "Sold Individually" option.
				 */
	
				if ( ! empty( $wcfm_products_manage_form_data[ '_bto_sold_individually' ] ) ) {
	
					$sold_individually_context = wc_clean( $wcfm_products_manage_form_data[ '_bto_sold_individually' ] );
	
					if ( in_array( $sold_individually_context, array( 'product', 'configuration' ) ) ) {
						$props[ 'sold_individually' ]         = true;
						$props[ 'sold_individually_context' ] = $sold_individually_context;
					}
				}
				
				/*
				 * Composite style.
				 */
		
				if ( isset( $wcfm_products_manage_form_data[ '_bto_style' ] ) ) {
					$props[ 'layout' ] = stripslashes( $wcfm_products_manage_form_data[ '_bto_style' ] );
				}
				
				/*
				 * "Catalog Price" option.
				 */
	
				if ( ! empty( $wcfm_products_manage_form_data[ '_bto_shop_price_calc' ] ) ) {
					$props[ 'shop_price_calc' ] = wc_clean( $wcfm_products_manage_form_data[ '_bto_shop_price_calc' ] );
				}
				
				/*
				 * "Edit in cart" option.
				 */
		
				if ( ! empty( $wcfm_products_manage_form_data[ '_bto_edit_in_cart' ] ) ) {
					$props[ 'editable_in_cart' ] = true;
				}
	
				/*
				 * Edit in cart option.
				 */
	
				if ( ! empty( $wcfm_products_manage_form_data[ '_bto_add_to_cart_form_location' ] ) ) {
					$form_location = wc_clean( $wcfm_products_manage_form_data[ '_bto_add_to_cart_form_location' ] );

					if ( in_array( $form_location, array_keys( WC_Product_Composite::get_add_to_cart_form_location_options() ) ) ) {
						$props[ 'add_to_cart_form_location' ] = $form_location;
					}
				}
	
				/*
				 * Components and Scenarios.
				 */
		
				$zero_product_item_exists          = false;
				$individually_priced_options_count = 0;
				$composite_data                    = array();
		
				if ( isset( $wcfm_products_manage_form_data[ 'bto_data' ] ) ) {
		
					/*--------------------------*/
					/*  Components.             */
					/*--------------------------*/
		
					$counter  = 0;
					$ordering = array();
		
					foreach ( $wcfm_products_manage_form_data[ 'bto_data' ] as $row_id => $post_data ) {
		
						$bto_ids     = isset( $post_data[ 'assigned_ids' ] ) ? $post_data[ 'assigned_ids' ] : '';
						$bto_cat_ids = isset( $post_data[ 'assigned_category_ids' ] ) ? $post_data[ 'assigned_category_ids' ] : '';
		
						$group_id    = isset ( $post_data[ 'group_id' ] ) ? stripslashes( $post_data[ 'group_id' ] ) : ( current_time( 'timestamp' ) + $counter );
						$counter++;
		
						$composite_data[ $group_id ] = array();
		
						/*
						 * Save query type.
						 */
		
						if ( isset( $post_data[ 'query_type' ] ) && ! empty( $post_data[ 'query_type' ] ) ) {
							$composite_data[ $group_id ][ 'query_type' ] = stripslashes( $post_data[ 'query_type' ] );
						} else {
							$composite_data[ $group_id ][ 'query_type' ] = 'product_ids';
						}
		
						if ( ! empty( $bto_ids ) ) {
		
							// Convert select2 v3/4 data.
							if ( is_array( $bto_ids ) ) {
								$bto_ids = array_map( 'intval', $post_data[ 'assigned_ids' ] );
							} else {
								$bto_ids = array_filter( array_map( 'intval', explode( ',', $post_data[ 'assigned_ids' ] ) ) );
							}
		
							foreach ( $bto_ids as $key => $id ) {
		
								$composited_product = wc_get_product( $id );
		
								if ( $composited_product && in_array( $composited_product->get_type(), apply_filters( 'woocommerce_composite_products_supported_types', array( 'simple', 'variable', 'bundle' ) ) ) ) {
		
									$error = apply_filters( 'woocommerce_composite_products_custom_type_save_error', false, $id );
		
									if ( $error ) {
										//self::add_notice( $error, 'error' );
										continue;
									}
		
									// Save assigned IDs.
									$composite_data[ $group_id ][ 'assigned_ids' ][] = $id;
								}
							}
		
							if ( ! empty( $composite_data[ $group_id ][ 'assigned_ids' ] ) ) {
								$composite_data[ $group_id ][ 'assigned_ids' ] = array_unique( $composite_data[ $group_id ][ 'assigned_ids' ] );
							}
						}
		
						if ( ! empty( $bto_cat_ids ) ) {
							$bto_cat_ids = array_map( 'absint', $post_data[ 'assigned_category_ids' ] );
							$composite_data[ $group_id ][ 'assigned_category_ids' ] = array_values( $bto_cat_ids );
						}
		
						// True if no products were added.
						if ( ( $composite_data[ $group_id ][ 'query_type' ] === 'product_ids' && empty( $composite_data[ $group_id ][ 'assigned_ids' ] ) ) || ( $composite_data[ $group_id ][ 'query_type' ] === 'category_ids' && empty( $composite_data[ $group_id ][ 'assigned_category_ids' ] ) ) ) {
		
							unset( $composite_data[ $group_id ] );
							$zero_product_item_exists = true;
							continue;
						}
		
						// Run query to get component option IDs.
						$component_options = WC_CP_Component::query_component_options( $composite_data[ $group_id ] );
		
						/*
						 * Save selection style.
						 */
		
						$component_options_style = 'dropdowns';
		
						if ( isset( $post_data[ 'selection_mode' ] ) ) {
							$component_options_style = stripslashes( $post_data[ 'selection_mode' ] );
						}
		
						$composite_data[ $group_id ][ 'selection_mode' ] = $component_options_style;
		
						/*
						 * Save default preferences.
						 */
		
						if ( ! empty( $post_data[ 'default_id' ] ) && count( $component_options ) > 0 ) {
		
							if ( in_array( $post_data[ 'default_id' ], $component_options ) )
								$composite_data[ $group_id ][ 'default_id' ] = stripslashes( $post_data[ 'default_id' ] );
							else {
								$composite_data[ $group_id ][ 'default_id' ] = '';
							}
		
						} else {
		
							// If the component option is only one, set it as default.
							if ( count( $component_options ) === 1 && ! isset( $post_data[ 'optional' ] ) ) {
								$composite_data[ $group_id ][ 'default_id' ] = $component_options[0];
							} else {
								$composite_data[ $group_id ][ 'default_id' ] = '';
							}
						}
		
						/*
						 * Save title preferences.
						 */
		
						if ( ! empty( $post_data[ 'title' ] ) ) {
							$composite_data[ $group_id ][ 'title' ] = strip_tags( stripslashes( $post_data[ 'title' ] ) );
						} else {
		
							$composite_data[ $group_id ][ 'title' ] = __( 'Untitled Component', 'woocommerce-composite-products' );
							//self::add_notice( __( 'Please give a valid <strong>Name</strong> to each Component before saving.', 'woocommerce-composite-products' ), 'error' );
		
							if ( isset( $wcfm_products_manage_form_data[ 'post_status' ] ) && $wcfm_products_manage_form_data[ 'post_status' ] === 'publish' ) {
								$props[ 'status' ] = 'draft';
							}
						}
		
						/*
						 * Unpaginated selections style notice.
						 */
		
						if ( ! WC_CP_Component::options_style_supports( $component_options_style, 'pagination' ) ) {
							$unpaginated_options_count = count( $component_options );
		
							if ( $unpaginated_options_count > 30 ) {
								$dropdowns_prompt = sprintf( __( 'You have added %1$s product options to "%2$s". To reduce the load on your server, it is recommended to use the <strong>Product Thumbnails</strong> Options Style, which enables a paginated display of Component Options.', 'woocommerce-composite-products' ), $unpaginated_options_count, strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
								//self::add_notice( $dropdowns_prompt, 'warning' );
							}
						}
		
						/*
						 * Save description.
						 */
		
						if ( ! empty( $post_data[ 'description' ] ) ) {
							$composite_data[ $group_id ][ 'description' ] = wp_kses_post( stripslashes( $post_data[ 'description' ] ) );
						} else {
							$composite_data[ $group_id ][ 'description' ] = '';
						}
		
						/*
						 * Save image.
						 */
		
						if ( ! empty( $post_data[ 'thumbnail' ] ) ) {
							$composite_data[ $group_id ][ 'thumbnail' ] = wc_clean( $post_data[ 'thumbnail' ] );
							$composite_data[ $group_id ][ 'thumbnail_id' ] = $WCFM->wcfm_get_attachment_id( wc_clean( $post_data[ 'thumbnail' ] ) );
						} else {
							$composite_data[ $group_id ][ 'thumbnail' ] = '';
							$composite_data[ $group_id ][ 'thumbnail_id' ] = '';
						}
		
						/*
						 * Save min quantity data.
						 */
		
						if ( isset( $post_data[ 'quantity_min' ] ) && is_numeric( $post_data[ 'quantity_min' ] ) ) {
		
							$quantity_min = absint( $post_data[ 'quantity_min' ] );
		
							if ( $quantity_min >= 0 ) {
								$composite_data[ $group_id ][ 'quantity_min' ] = $quantity_min;
							} else {
								$composite_data[ $group_id ][ 'quantity_min' ] = 1;
		
								$error = sprintf( __( 'The <strong>Min Quantity</strong> entered for "%s" was not valid and has been reset. Please enter a non-negative integer value.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
								//self::add_notice( $error, 'error' );
							}
		
						} else {
							// If its not there, it means the product was just added.
							$composite_data[ $group_id ][ 'quantity_min' ] = 1;
		
							$error = sprintf( __( 'The <strong>Min Quantity</strong> entered for "%s" was not valid and has been reset. Please enter a non-negative integer value.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
							//self::add_notice( $error, 'error' );
						}
		
						$quantity_min = $composite_data[ $group_id ][ 'quantity_min' ];
		
						/*
						 * Save max quantity data.
						 */
		
						if ( isset( $post_data[ 'quantity_max' ] ) && ( is_numeric( $post_data[ 'quantity_max' ] ) || $post_data[ 'quantity_max' ] === '' ) ) {
		
							$quantity_max = $post_data[ 'quantity_max' ] !== '' ? absint( $post_data[ 'quantity_max' ] ) : '';
		
							if ( $quantity_max === '' || ( $quantity_max > 0 && $quantity_max >= $quantity_min ) ) {
								$composite_data[ $group_id ][ 'quantity_max' ] = $quantity_max;
							} else {
								$composite_data[ $group_id ][ 'quantity_max' ] = 1;
		
								$error = sprintf( __( 'The <strong>Max Quantity</strong> you entered for "%s" was not valid and has been reset. Please enter a positive integer value greater than (or equal to) <strong>Min Quantity</strong>, or leave the field empty.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
								//self::add_notice( $error, 'error' );
							}
		
						} else {
							// If its not there, it means the product was just added.
							$composite_data[ $group_id ][ 'quantity_max' ] = 1;
		
							$error = sprintf( __( 'The <strong>Max Quantity</strong> you entered for "%s" was not valid and has been reset. Please enter a positive integer value greater than (or equal to) <strong>Min Quantity</strong>, or leave the field empty.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
							//self::add_notice( $error, 'error' );
						}
		
						/*
						 * Save discount data.
						 */
		
						if ( isset( $post_data[ 'discount' ] ) ) {
		
							if ( is_numeric( $post_data[ 'discount' ] ) ) {
		
								$discount = wc_format_decimal( $post_data[ 'discount' ] );
		
								if ( $discount < 0 || $discount > 100 ) {
		
									$error = sprintf( __( 'The <strong>Discount</strong> value you entered for "%s" was not valid and has been reset. Please enter a positive number between 0-100.', 'woocommerce-composite-products' ), strip_tags( stripslashes( $post_data[ 'title' ] ) ) );
									//self::add_notice( $error, 'error' );
		
									$composite_data[ $group_id ][ 'discount' ] = '';
		
								} else {
									$composite_data[ $group_id ][ 'discount' ] = $discount;
								}
							} else {
								$composite_data[ $group_id ][ 'discount' ] = '';
							}
						} else {
							$composite_data[ $group_id ][ 'discount' ] = '';
						}
		
						/*
						 * Save priced-individually data.
						 */
		
						if ( isset( $post_data[ 'priced_individually' ] ) ) {
							$composite_data[ $group_id ][ 'priced_individually' ] = 'yes';
		
							// Add up options.
							$individually_priced_options_count += count( $component_options );
		
						} else {
							$composite_data[ $group_id ][ 'priced_individually' ] = 'no';
						}
		
						/*
						 * Save priced-individually data.
						 */
		
						if ( isset( $post_data[ 'shipped_individually' ] ) ) {
							$composite_data[ $group_id ][ 'shipped_individually' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'shipped_individually' ] = 'no';
						}
		
						/*
						 * Save optional data.
						 */
		
						if ( isset( $post_data[ 'optional' ] ) ) {
							$composite_data[ $group_id ][ 'optional' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'optional' ] = 'no';
						}
		
						/*
						 * Save product title visiblity data.
						 */
		
						if ( isset( $post_data[ 'hide_product_title' ] ) ) {
							$composite_data[ $group_id ][ 'hide_product_title' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'hide_product_title' ] = 'no';
						}
		
						/*
						 * Save product description visiblity data.
						 */
		
						if ( isset( $post_data[ 'hide_product_description' ] ) ) {
							$composite_data[ $group_id ][ 'hide_product_description' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'hide_product_description' ] = 'no';
						}
		
						/*
						 * Save product thumbnail visiblity data.
						 */
		
						if ( isset( $post_data[ 'hide_product_thumbnail' ] ) ) {
							$composite_data[ $group_id ][ 'hide_product_thumbnail' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'hide_product_thumbnail' ] = 'no';
						}
		
						/*
						 * Save product price visibility data.
						 */
		
						if ( isset( $post_data[ 'hide_product_price' ] ) ) {
							$composite_data[ $group_id ][ 'hide_product_price' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'hide_product_price' ] = 'no';
						}
		
						/*
						 * Save component subtotal visibility data.
						 */
		
						if ( isset( $post_data[ 'hide_subtotal_product' ] ) ) {
							$composite_data[ $group_id ][ 'hide_subtotal_product' ] = 'no';
						} else {
							$composite_data[ $group_id ][ 'hide_subtotal_product' ] = 'yes';
						}
		
						/*
						 * Save component subtotal visibility data.
						 */
		
						if ( isset( $post_data[ 'hide_subtotal_cart' ] ) ) {
							$composite_data[ $group_id ][ 'hide_subtotal_cart' ] = 'no';
						} else {
							$composite_data[ $group_id ][ 'hide_subtotal_cart' ] = 'yes';
						}
		
						/*
						 * Save component subtotal visibility data.
						 */
		
						if ( isset( $post_data[ 'hide_subtotal_orders' ] ) ) {
							$composite_data[ $group_id ][ 'hide_subtotal_orders' ] = 'no';
						} else {
							$composite_data[ $group_id ][ 'hide_subtotal_orders' ] = 'yes';
						}
		
						/*
						 * Save show orderby data.
						 */
		
						if ( isset( $post_data[ 'show_orderby' ] ) ) {
							$composite_data[ $group_id ][ 'show_orderby' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'show_orderby' ] = 'no';
						}
		
						/*
						 * Save show filters data.
						 */
		
						if ( isset( $post_data[ 'show_filters' ] ) ) {
							$composite_data[ $group_id ][ 'show_filters' ] = 'yes';
						} else {
							$composite_data[ $group_id ][ 'show_filters' ] = 'no';
						}
		
						/*
						 * Save filters.
						 */
		
						if ( ! empty( $post_data[ 'attribute_filters' ] ) ) {
							$attribute_filter_ids = array_map( 'absint', $post_data[ 'attribute_filters' ] );
							$composite_data[ $group_id ][ 'attribute_filters' ] = array_values( $attribute_filter_ids );
						}
		
						/*
						 * Prepare position data.
						 */
		
						if ( isset( $post_data[ 'position' ] ) ) {
							$ordering[ (int) $post_data[ 'position' ] ] = $group_id;
						} else {
							$ordering[ count( $ordering ) ] = $group_id;
						}
		
						/**
						 * Filter the component data before saving. Add custom errors via 'add_notice()'.
						 *
						 * @param  array   $component_data
						 * @param  array   $post_data
						 * @param  string  $component_id
						 * @param  string  $composite_id
						 */
						$composite_data[ $group_id ] = apply_filters( 'woocommerce_composite_process_component_data', $composite_data[ $group_id ], $post_data, $group_id, $composite_id );
					}
		
					ksort( $ordering );
					$ordered_composite_data = array();
					$ordering_loop          = 0;
		
					foreach ( $ordering as $group_id ) {
						$ordered_composite_data[ $group_id ]               = $composite_data[ $group_id ];
						$ordered_composite_data[ $group_id ][ 'position' ] = $ordering_loop;
						$ordering_loop++;
					}
					$props[ 'composite_data' ] = $ordered_composite_data;
				
					$composite->set( $props );
					$composite->save();
				}
			}
    }
	}
}