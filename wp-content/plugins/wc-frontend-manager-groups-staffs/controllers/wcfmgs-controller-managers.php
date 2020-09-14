<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Managers Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Managers_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMgs;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$args = array(
									'role__in'     => array( 'shop_manager' ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $length,
									'count_total'  => false
								 ); 
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) $args['search'] = $_POST['search']['value'];
		
		$args = apply_filters( 'wcfmgs_get_shop_managers_args', $args );
		
		$wcfm_shop_managers_array = get_users( $args );
		
		// Get Product Count
		$shop_managers_count = 0;
		$filtered_shop_managers_count = 0;
		$shop_managers_count = count($wcfm_shop_managers_array);
		// Get Filtered Post Count
		$args['number'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_shop_managers_array = get_users( $args );
		$filtered_shop_managers_count = count($wcfm_filterd_shop_managers_array);
		
		
		// Generate Products JSON
		$wcfm_shop_managers_json = '';
		$wcfm_shop_managers_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $shop_managers_count . ',
															"recordsFiltered": ' . $filtered_shop_managers_count . ',
															"data": ';
		$index = 0;
		$wcfm_shop_managers_json_arr = array();
		if(!empty($wcfm_shop_managers_array)) {
			foreach( $wcfm_shop_managers_array as $wcfm_shop_managers_single ) {
				
				// Manager
				$shop_label =  '<a href="' . get_wcfm_shop_managers_manage_url($wcfm_shop_managers_single->ID) . '" class="wcfm_dashboard_item_title">' . $wcfm_shop_managers_single->user_login . '</a>';
				$wcfm_shop_managers_json_arr[$index][] = $shop_label;
				
				// Name
				$wcfm_shop_managers_json_arr[$index][] = $wcfm_shop_managers_single->first_name . ' ' . $wcfm_shop_managers_single->last_name;
				
				// Email
				$wcfm_shop_managers_json_arr[$index][] = $wcfm_shop_managers_single->user_email;
				
				// Groups
				$wcfm_user_groups = (array) get_user_meta( $wcfm_shop_managers_single->ID, '_wcfm_vendor_group', true );
				if( !empty( $wcfm_user_groups ) ) {
					$wcfm_user_group_str = '';
					foreach( $wcfm_user_groups as $wcfm_user_group ) {
						if( $wcfm_user_group ) {
							if( $wcfm_user_group_str ) $wcfm_user_group_str .= ', ';
							$wcfm_user_group_str .= get_the_title( $wcfm_user_group );
						}
					}
					if( !$wcfm_user_group_str ) $wcfm_user_group_str = '&ndash;';
					$wcfm_shop_managers_json_arr[$index][] = $wcfm_user_group_str;
				} else {
					$wcfm_shop_managers_json_arr[$index][] = '&ndash;';
				}
				
				// Action
				$actions = '<a class="wcfm-action-icon" href="' . get_wcfm_shop_managers_manage_url( $wcfm_shop_managers_single->ID ) . '"><span class="wcfmfa fa-edit text_tip" data-tip="' . esc_attr__( 'Manage Manager', 'wc-frontend-manager-ultimate' ) . '"></span></a>';
				$actions .= '<a class="wcfm_manager_delete wcfm-action-icon" href="#" data-managerid="' . $wcfm_shop_managers_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				$wcfm_shop_managers_json_arr[$index][] = apply_filters ( 'wcfm_shop_managers_actions', $actions, $wcfm_shop_managers_single );  
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_shop_managers_json_arr) ) $wcfm_shop_managers_json .= json_encode($wcfm_shop_managers_json_arr);
		else $wcfm_shop_managers_json .= '[]';
		$wcfm_shop_managers_json .= '
													}';
													
		echo $wcfm_shop_managers_json;
	}
}