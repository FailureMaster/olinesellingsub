<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Delivery Boys Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmd/controllers
 * @version   1.0.0
 */

class WCFMd_Delivery_Boys_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMd;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$delivery_boy_role = apply_filters( 'wcfm_delivery_boy_user_role', 'wcfm_delivery_boy' );
		
		$args = array(
									'role__in'     => array( $delivery_boy_role ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $length,
									'count_total'  => false
								 ); 
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) $args['search'] = $_POST['search']['value'];
		
		if( !empty( $_POST['delivery_boy_vendor'] ) ) {
			$args['meta_key']   = '_wcfm_vendor';        
			$args['meta_value'] = $_POST['delivery_boy_vendor'];
		}
		
		$args = apply_filters( 'wcfmd_get_delivery_boys_args', $args );
		
		$wcfm_delivery_boys_array = get_users( $args );
		            
		// Get Product Count
		$delivery_boys_count = 0;
		$filtered_delivery_boys_count = 0;
		$delivery_boys_count = count($wcfm_delivery_boys_array);
		// Get Filtered Post Count
		$args['number'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_delivery_boys_array = get_users( $args );
		$filtered_delivery_boys_count = count($wcfm_filterd_delivery_boys_array);
		
		
		// Generate Products JSON
		$wcfm_delivery_boys_json = '';
		$wcfm_delivery_boys_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $delivery_boys_count . ',
															"recordsFiltered": ' . $filtered_delivery_boys_count . ',
															"data": ';
		$index = 0;
		$wcfm_delivery_boys_json_arr = array();
		if(!empty($wcfm_delivery_boys_array)) {
			foreach( $wcfm_delivery_boys_array as $wcfm_delivery_boys_single ) {
				
				// Delivery Boy
				$shop_label =  '<a href="' . get_wcfm_delivery_boys_stats_url($wcfm_delivery_boys_single->ID) . '" class="wcfm_dashboard_item_title">' . $wcfm_delivery_boys_single->user_login . '</a>';
				$wcfm_delivery_boys_json_arr[$index][] = $shop_label;
				
				// Store
				$wcfm_vendors_id = get_user_meta( $wcfm_delivery_boys_single->ID, '_wcfm_vendor', true );
				if( $wcfm_vendors_id ) {
					$wcfm_delivery_boys_json_arr[$index][] =  '<span class="wcfm_vendor_store">' . apply_filters( 'wcfm_vendors_store_name_data', $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $wcfm_vendors_id ), $wcfm_vendors_id ) . '</span>';
				} else {
					$wcfm_delivery_boys_json_arr[$index][] = '&ndash;';
				}
				
				// Name
				$wcfm_delivery_boys_json_arr[$index][] = apply_filters( 'wcfm_delivery_boy_display', $wcfm_delivery_boys_single->first_name . ' ' . $wcfm_delivery_boys_single->last_name, $wcfm_delivery_boys_single->ID ) . '<br />' . $wcfm_delivery_boys_single->user_email;
				
				// Stats
				$delivery_boy_stats  = '<span class="tips wcicon-status-completed text_tip" data-tip="'. __( 'Delivered', 'wc-frontend-manager-delivery' ) .'"></span>&nbsp;' . __( 'Delivered', 'wc-frontend-manager-delivery' ) . ': <span style="font-size: 16px; font-weight: 600; color: #008C00;">' . wcfm_get_delivery_boy_delivery_stat( $wcfm_delivery_boys_single->ID, 'delivered' ) . '</span>';
				$delivery_boy_stats .= '<br/><span class="tips wcicon-status-pending text_tip" data-tip="'. __( 'Pending', 'wc-frontend-manager-delivery' ) .'"></span>&nbsp;' . __( 'Pending', 'wc-frontend-manager-delivery' ) . ': <span style="font-size: 16px; font-weight: 600; color: #FF1A00;">' . wcfm_get_delivery_boy_delivery_stat( $wcfm_delivery_boys_single->ID, 'pending' ) . '</span>';
				$wcfm_delivery_boys_json_arr[$index][] = $delivery_boy_stats;
				
				// Action
				$actions  = '<a class="wcfm-action-icon" href="' . get_wcfm_delivery_boys_stats_url( $wcfm_delivery_boys_single->ID ) . '"><span class="wcfmfa fa-shipping-fast text_tip" data-tip="' . esc_attr__( 'Details Stat', 'wc-frontend-manager-delivery' ) . '"></span></a>';
				$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_delivery_boys_manage_url( $wcfm_delivery_boys_single->ID ) . '"><span class="wcfmfa fa-edit text_tip" data-tip="' . esc_attr__( 'Manage Delivery Boy', 'wc-frontend-manager-delivery' ) . '"></span></a>';
				if( apply_filters( 'wcfm_is_allow_delete_delivery_boy', true ) ) {
					$actions .= '<a class="wcfm_staff_delete wcfm-action-icon" href="#" data-deliveryboyid="' . $wcfm_delivery_boys_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				}
				$wcfm_delivery_boys_json_arr[$index][] = apply_filters ( 'wcfm_delivery_boys_actions', $actions, $wcfm_delivery_boys_single );
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_delivery_boys_json_arr) ) $wcfm_delivery_boys_json .= json_encode($wcfm_delivery_boys_json_arr);
		else $wcfm_delivery_boys_json .= '[]';
		$wcfm_delivery_boys_json .= '
													}';
													
		echo $wcfm_delivery_boys_json;
	}
}