<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Delivery Boys Stats Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmd/controllers
 * @version   1.0.0
 */

class WCFMd_Delivery_Boys_Stats_Controller {
	
	public function __construct() {
		global $WCFM;
		
		if( !defined('WCFM_REST_API_CALL') ) {
			$this->processing();
		}
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMd;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$delivery_boy_id = $_POST['wcfm_delivery_boy']; 
		$status          = $_POST['status_type'];
		
		$sql  = "SELECT COUNT(ID) FROM `{$wpdb->prefix}wcfm_delivery_orders`";
		$sql .= " WHERE 1=1";
		$sql .= " AND delivery_boy = {$delivery_boy_id}";
		$sql .= " AND is_trashed = 0";
		if( $status ) $sql .= " AND delivery_status = '{$status}'";
		$delivery_count = $wpdb->get_var( $sql );
		            
		// Get Product Count
		$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_delivery_orders`";
		$sql .= " WHERE 1=1";
		$sql .= " AND delivery_boy = {$delivery_boy_id}";
		if( $status ) $sql .= " AND delivery_status = '{$status}'";
		$sql .= " AND is_trashed = 0";
		$sql .= " ORDER BY `order_id` ASC";
		$sql .= " LIMIT {$length}";
		$sql .= " OFFSET {$offset}";
		
		
		$wcfm_delivery_orders_array = $wpdb->get_results( $sql );
		if( defined('WCFM_REST_API_CALL') ) {
      return $wcfm_delivery_orders_array;
    }
		$wcfm_delivery_order_count  = count( $wcfm_delivery_orders_array );
		
		
		// Generate Products JSON
		$wcfm_delivery_boys_json = '';
		$wcfm_delivery_boys_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $wcfm_delivery_order_count . ',
															"recordsFiltered": ' . $delivery_count . ',
															"data": ';
		$index = 0;
		$wcfm_delivery_orders_json_arr = array();
		if(!empty($wcfm_delivery_orders_array)) {
			foreach( $wcfm_delivery_orders_array as $wcfm_delivery_order_single ) {
				
				$the_order = wc_get_order( $wcfm_delivery_order_single->order_id );
				if( !is_a( $the_order, 'WC_Order' ) ) continue;
				
				// Status
				if( $wcfm_delivery_order_single->delivery_status == 'pending' ) {
					$wcfm_delivery_orders_json_arr[$index][] = '<span class="order-status tips wcicon-status-pending text_tip" data-tip="' . __( 'Pending', 'wc-frontend-manager-delivery' ) . '"></span>';
				} else {
					$wcfm_delivery_orders_json_arr[$index][] = '<span class="order-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Delivered', 'wc-frontend-manager-delivery' ) . '"></span>';
				}
				
				// Order
				/*if( wcfm_is_delivery_boy() || !apply_filters( 'wcfm_is_allow_order_details', true ) ) {
				  $order_label = '<span class="wcfm_order_title">#' . esc_attr( $the_order->get_order_number() ) . '</span>';
				} else {
					$order_label = '<a target="_blank" href="' . get_wcfm_view_order_url( $wcfm_delivery_order_single->order_id ) . '" class="wcfm_order_title">#' . esc_attr( $the_order->get_order_number() ) . '</a>';
				}
				if ( $the_order->get_payment_method_title() ) {
					$order_label .= '<br /><small class="meta">' . __( 'Via', 'wc-frontend-manager' ) . ' ' . esc_html( $the_order->get_payment_method_title() ) . '</small>';
				}
				
				if( in_array( $wcfm_delivery_order_single->payment_method, array( 'cod' ) ) ) {
				  $gross_sales_total = $WCFMd->frontend->wcfmd_get_delivery_meta( $wcfm_delivery_order_single->ID, 'gross_sales_total', true );
				  if( $gross_sales_total ) {
				  	$order_label .= '<br /><small class="meta">' . __( 'Payment', 'wc-frontend-manager' ) . ' ' . wc_price( $gross_sales_total ) . '</small>';
				  }
				}*/
				
				$wcfm_delivery_orders_json_arr[$index][] = '';
				
				// Item
				$order_item_details  = '<div class="order_items order_items_visible" cellspacing="0">';
				if( wcfm_is_delivery_boy() || !apply_filters( 'wcfm_is_allow_order_details', true ) ) {
				  $order_item_details .= '<span class="wcfm_order_title">#' . esc_attr( $the_order->get_order_number() ) . '</span>';
				} else {
					$order_item_details .= '<a target="_blank" href="' . get_wcfm_view_order_url( $wcfm_delivery_order_single->order_id ) . '" class="wcfm_order_title">#' . esc_attr( $the_order->get_order_number() ) . '</a>';
				}
				
				try {
					$line_item = new WC_Order_Item_Product( $wcfm_delivery_order_single->item_id );
					$product   = $line_item->get_product();
					$item_meta_html = strip_tags( wc_display_item_meta( $line_item, array(
																																				'before'    => "\n- ",
																																				'separator' => "\n- ",
																																				'after'     => "",
																																				'echo'      => false,
																																				'autop'     => false,
																																			) ) );
			
					$order_item_details .= '<div class=""><span class="qty">' . $line_item->get_quantity() . 'x</span><span class="name">' . $line_item->get_name();
					if ( $product && $product->get_sku() ) {
						$order_item_details .= ' (' . __( 'SKU:', 'wc-frontend-manager' ) . ' ' . esc_html( $product->get_sku() ) . ')';
					}
					//if ( ! empty( $item_meta_html ) ) $order_item_details .= $item_meta_html; //'<span class="img_tip" data-tip="' . $item_meta_html . '"></span>';
					$order_item_details .= '</span></div>';
				} catch (Exception $e) {
					wcfm_log( "order List Error ::" . $order->order_id . " => " . $e->getMessage() );
					unset( $wcfm_delivery_orders_json_arr[$index] );
					continue;
				}
				
				if ( $the_order->get_payment_method_title() ) {
					$order_item_details .= '<small class="meta">' . __( 'Via', 'wc-frontend-manager' ) . ' ' . esc_html( $the_order->get_payment_method_title() ) . '</small>';
				}
				
				if( in_array( $wcfm_delivery_order_single->payment_method, array( 'cod' ) ) ) {
				  $gross_sales_total = $WCFMd->frontend->wcfmd_get_delivery_meta( $wcfm_delivery_order_single->ID, 'gross_sales_total', true );
				  if( $gross_sales_total ) {
				  	$order_item_details .= '<br /><span class="meta" style="color: red;">' . __( 'Remaining Payment', 'wc-frontend-manager' ) . ' ' . wc_price( $gross_sales_total ) . '</span>';
				  }
				}
				
				$order_item_details .= '</div>';
				
				$wcfm_delivery_orders_json_arr[$index][] = $order_item_details;
				
				// Store
				if( $wcfm_delivery_order_single->vendor_id ) {
					$store = '<span class="wcfm_vendor_store">' . apply_filters( 'wcfm_vendors_store_name_data', wcfm_get_vendor_store( $wcfm_delivery_order_single->vendor_id ), $wcfm_delivery_order_single->vendor_id ) . '</span>';
					if( apply_filters( 'wcfm_is_allow_store_location_to_delivery_boys', true ) ) {
						$store_user = wcfmmp_get_store( $wcfm_delivery_order_single->vendor_id );
						$store_info        = $store_user->get_shop_info();
						$store_address     = $store_user->get_address_string();
						$map_location      = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';
						$store_lat         = isset( $store_info['store_lat'] ) ? esc_attr( $store_info['store_lat'] ) : 0;
						$store_lng         = isset( $store_info['store_lng'] ) ? esc_attr( $store_info['store_lng'] ) : 0;
						if( $store_address && $store_lat && $store_lng ) {
							$store .= '<div class="wcfm_store_location"><i class="wcfmfa fa-map-marker" aria-hidden="true"></i>&nbsp;<a href="https://google.com/maps/place/' . rawurlencode( $store_address ) . '/@' . $store_lat . ',' . $store_lng . '" target="_blank">' . $store_address . '</a></div>';
						} else if( $store_address ) {
							$store .= '<div class="wcfm_store_location"><i class="wcfmfa fa-map-marker" aria-hidden="true"></i>&nbsp;<a href="https://maps.google.com/?q=' . rawurlencode( $store_address ) . '&z=16" target="_blank">' . $store_address . '</a></div>';
						}
					}
					$store_user = $wcfm_delivery_orders_json_arr[$index][] = $store;
				} else {
					$wcfm_delivery_orders_json_arr[$index][] = '&ndash;';
				}
				
				// Customer
				if( apply_filters( 'wcfm_allow_view_customer_name', true ) ) {
					$user_info = array();
					if ( $the_order->get_user_id() ) {
						$user_info = get_userdata( $the_order->get_user_id() );
					}
		
					if ( ! empty( $user_info ) ) {
		
						$username = '';
		
						if ( $user_info->first_name || $user_info->last_name ) {
							$username .= esc_html( sprintf( _x( '%1$s %2$s', 'full name', 'wc-frontend-manager' ), ucfirst( $user_info->first_name ), ucfirst( $user_info->last_name ) ) );
						} else {
							$username .= esc_html( ucfirst( $user_info->display_name ) );
						}
		
					} else {
						if ( $the_order->get_billing_first_name() || $the_order->get_billing_last_name() ) {
							$username = trim( sprintf( _x( '%1$s %2$s', 'full name', 'wc-frontend-manager' ), $the_order->get_billing_first_name(), $the_order->get_billing_last_name() ) );
						} else if ( $the_order->get_billing_company() ) {
							$username = trim( $the_order->get_billing_company() );
						} else {
							$username = __( 'Guest', 'wc-frontend-manager' );
						}
					}
					
					$username = apply_filters( 'wcfm_order_by_user', $username, $the_order->get_id() );
				} else {
					$username = __( 'Guest', 'wc-frontend-manager' );
				}
				if( apply_filters( 'wcfm_allow_view_customer_email', true ) && $the_order->get_billing_email() ) {
					$username .= '<br /><span class="wcfmfa fa-envelope" style="color: #00798b;"></span>&nbsp;' . $the_order->get_billing_email();
				}
				if( apply_filters( 'wcfm_allow_view_customer_email', true ) && $the_order->get_billing_phone() ) {
				  $username .= '<br /><span class="wcfmfa fa-phone" style="color: #4096EE;"></span>&nbsp;' . $the_order->get_billing_phone();
				}
				$wcfm_delivery_orders_json_arr[$index][] = $username;
				
				// Shipping Address
				$shipping_address = '&ndash;';
				if( apply_filters( 'wcfm_allow_customer_shipping_details', true ) ) {
					$delivery_location = get_post_meta( $the_order->get_id(), '_wcfmmp_user_location', true );
					if( $delivery_location ) {
						$lat               = get_post_meta( $the_order->get_id(), '_wcfmmp_user_location_lat', true );
						$lng               = get_post_meta( $the_order->get_id(), '_wcfmmp_user_location_lng', true );
						$shipping_address = '<a href="https://google.com/maps/place/' . rawurlencode( $delivery_location ) . '/@' . $lat . ',' . $lng . '" target="_blank"><span>' . $delivery_location . '</span></a>';
					} else if ( $the_order->get_formatted_shipping_address() ) {
						$shipping_address = wp_kses( $the_order->get_formatted_shipping_address(), array( 'br' => array() ) );
					}
				}
				if( apply_filters( 'wcfm_is_pref_delivery_time', true ) && apply_filters( 'wcfm_is_allow_delivery_time', true ) ) {
					$wcfmd_delvery_times = get_post_meta( $the_order->get_id(), '_wcfmd_delvery_times', true );
					if( !empty(  $wcfmd_delvery_times ) ) {
						foreach( $wcfmd_delvery_times as $vendor_id => $wcfmd_delvery_time ) {
							if( $vendor_id != $wcfm_delivery_order_single->vendor_id ) continue;
							$shipping_address .=  '<br/><p class="wcfm_order_list_delivery_time"><i class="wcfmfa fa-clock" style="color:#ff1400"></i>&nbsp;&nbsp;<strong>';
							$shipping_address .= __( 'Delivery Time', 'wc-multivendor-marketplace' ).':</strong> ' . date_i18n( wc_date_format() . ' ' . wc_time_format(), $wcfmd_delvery_time ) . '</p>';
						}
					}
				}
				$wcfm_delivery_orders_json_arr[$index][] = '<div class="wcfm_customer_location" style=""><a href="' . $the_order->get_shipping_address_map_url() . '" target="_blank">' . $shipping_address . '</a></div>';
				
				// Action
				$actions = '&ndash;';
				if( $wcfm_delivery_order_single->delivery_status == 'pending' ) {
					$actions = '<a class="wcfm_order_mark_delivered wcfm-action-icon" href="#" data-delivery_id="' . $wcfm_delivery_order_single->ID . '"><span class="wcfmfa fa-check-circle text_tip" data-tip="' . esc_attr__( 'Mark Delivered', 'wc-frontend-manager-delivery' ) . '"></span></a>';
				} elseif( $wcfm_delivery_order_single->delivery_date ) {
					$actions = '<span class="wcfmfa fa-clock text_tip" style="color: #00798b;" data-tip="' . esc_attr__( 'Delivered ON', 'wc-frontend-manager-delivery' ) . '"></span>&nbsp;' . date_i18n( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_delivery_order_single->delivery_date ) );
				}
				$wcfm_delivery_orders_json_arr[$index][] = apply_filters ( 'wcfm_delivery_boy_stats_actions', $actions, $wcfm_delivery_order_single );
				
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_delivery_orders_json_arr) ) $wcfm_delivery_boys_json .= json_encode($wcfm_delivery_orders_json_arr);
		else $wcfm_delivery_boys_json .= '[]';
		$wcfm_delivery_boys_json .= '
													}';
													
		echo $wcfm_delivery_boys_json;
	}
}