<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Staffs Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Staffs_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMgs;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
		
		$args = array(
									'role__in'     => array( $staff_user_role ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $length,
									'count_total'  => false
								 ); 
		
		$args = apply_filters( 'wcfmgs_get_shop_staffs_args', $args );
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) {
			$serach_str = esc_attr( $_POST['search']['value'] );
			//$args['search'] = $serach_str;
			
			$args['meta_query'] = array( 
																	apply_filters( 'wcfm_get_customers_meta_search', array(
																																												 'relation' => 'OR',
																																													array(
																																															'key'     => 'first_name',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'last_name',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'nickname',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_first_name',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_email',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_phone',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_company',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_address_1',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_city',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_state',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																													array(
																																															'key'     => 'billing_postcode',
																																															'value'   => $serach_str,
																																															'compare' => 'LIKE'
																																													),
																																											) 
																		)
																);
		}
		
		$wcfm_shop_staffs_array = get_users( $args );
		            
		// Get Product Count
		$shop_staffs_count = 0;
		$filtered_shop_staffs_count = 0;
		$shop_staffs_count = count($wcfm_shop_staffs_array);
		// Get Filtered Post Count
		$args['number'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_shop_staffs_array = get_users( $args );
		$filtered_shop_staffs_count = count($wcfm_filterd_shop_staffs_array);
		
		
		// Generate Products JSON
		$wcfm_shop_staffs_json = '';
		$wcfm_shop_staffs_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $shop_staffs_count . ',
															"recordsFiltered": ' . $filtered_shop_staffs_count . ',
															"data": ';
		$index = 0;
		$wcfm_shop_staffs_json_arr = array();
		if(!empty($wcfm_shop_staffs_array)) {
			foreach( $wcfm_shop_staffs_array as $wcfm_shop_staffs_single ) {
				
				// Staff
				$shop_label =  '<a href="' . get_wcfm_shop_staffs_manage_url($wcfm_shop_staffs_single->ID) . '" class="wcfm_dashboard_item_title">' . $wcfm_shop_staffs_single->user_login . '</a>';
				$wcfm_shop_staffs_json_arr[$index][] = $shop_label;
				
				// Store
				$wcfm_vendors_id = get_user_meta( $wcfm_shop_staffs_single->ID, '_wcfm_vendor', true );
				if( $wcfm_vendors_id ) {
					$wcfm_shop_staffs_json_arr[$index][] =  '<span class="wcfm_vendor_store">' . apply_filters( 'wcfm_vendors_store_name_data', $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( $wcfm_vendors_id ), $wcfm_vendors_id ) . '</span>';
				} else {
					$wcfm_shop_staffs_json_arr[$index][] = '&ndash;';
				}
				
				// Name
				$wcfm_shop_staffs_json_arr[$index][] = $wcfm_shop_staffs_single->first_name . ' ' . $wcfm_shop_staffs_single->last_name;
				
				// Email
				$wcfm_shop_staffs_json_arr[$index][] = $wcfm_shop_staffs_single->user_email;
				
				// Action
				$actions = '<a class="wcfm-action-icon" href="' . get_wcfm_shop_staffs_manage_url( $wcfm_shop_staffs_single->ID ) . '"><span class="wcfmfa fa-edit text_tip" data-tip="' . esc_attr__( 'Manage Staff', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				$actions .= '<a class="wcfm_staff_delete wcfm-action-icon" href="#" data-staffid="' . $wcfm_shop_staffs_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				$wcfm_shop_staffs_json_arr[$index][] = apply_filters ( 'wcfm_shop_staffs_actions', $actions, $wcfm_shop_staffs_single );
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_shop_staffs_json_arr) ) $wcfm_shop_staffs_json .= json_encode($wcfm_shop_staffs_json_arr);
		else $wcfm_shop_staffs_json .= '[]';
		$wcfm_shop_staffs_json .= '
													}';
													
		echo $wcfm_shop_staffs_json;
	}
}