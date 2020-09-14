<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Affiliate Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/controllers
 * @version   1.0.0
 */

class WCFMaf_Affiliate_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMaf;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$affiliate_role = apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' );
		
		$args = array(
									'role__in'     => array( $affiliate_role ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $length,
									'count_total'  => false
								 ); 
		
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) $args['search'] = $_POST['search']['value'];
		
		$args = apply_filters( 'wcfmaf_get_affiliate_args', $args );
		
		$wcfm_affiliate_array = get_users( $args );
		            
		// Get Product Count
		$affiliate_count = 0;
		$filtered_affiliate_count = 0;
		$affiliate_count = count($wcfm_affiliate_array);
		// Get Filtered Post Count
		$args['number'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_affiliate_array = get_users( $args );
		$filtered_affiliate_count = count($wcfm_filterd_affiliate_array);
		
		
		// Generate Products JSON
		$wcfm_affiliate_json = '';
		$wcfm_affiliate_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $affiliate_count . ',
															"recordsFiltered": ' . $filtered_affiliate_count . ',
															"data": ';
		$index = 0;
		$wcfm_affiliate_json_arr = array();
		if(!empty($wcfm_affiliate_array)) {
			foreach( $wcfm_affiliate_array as $wcfm_affiliate_single ) {
				
				// Name
				$wcfm_affiliate_json_arr[$index][] = '<a href="' . get_wcfm_affiliate_stats_url($wcfm_affiliate_single->ID) . '" class="wcfm_dashboard_item_title">' . apply_filters( 'wcfm_affiliate_display', $wcfm_affiliate_single->first_name . ' ' . $wcfm_affiliate_single->last_name, $wcfm_affiliate_single->ID ) . '</a><br />' . $wcfm_affiliate_single->user_email;
				
				// Commission
				$wcfm_affiliate_json_arr[$index][] = '<span class="affiliate_commission">' . wc_price( wcfm_get_affiliate_commission_stat( $wcfm_affiliate_single->ID ) ) . '</span>';
				
				// Paid
				$wcfm_affiliate_json_arr[$index][] = '<span class="affiliate_paid_commission">' . wc_price( wcfm_get_affiliate_commission_stat( $wcfm_affiliate_single->ID, 'paid' ) )  . '</span>';
				
				// Vendors
				$wcfm_affiliate_json_arr[$index][] = '<span class="affiliate_vendor_count">' . wcfm_get_affiliate_count_stat( $wcfm_affiliate_single->ID, 'vendor' ) . '</span>';
				
				// Orders
				$wcfm_affiliate_json_arr[$index][] = '<span class="affiliate_order_count">' . ( wcfm_get_affiliate_count_stat( $wcfm_affiliate_single->ID, 'order' ) + wcfm_get_affiliate_count_stat( $wcfm_affiliate_single->ID, 'vendor_order' ) ) . '</span>';
				
				// Action
				if( wcfm_affiliate_is_active( $wcfm_affiliate_single->ID ) ) {
					$actions  = '<a class="wcfm-action-icon" href="' . get_wcfm_affiliate_stats_url( $wcfm_affiliate_single->ID ) . '"><span class="wcfmfa fa-chart-line text_tip" data-tip="' . esc_attr__( 'Affiliate Stat', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
					$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_affiliate_manage_url( $wcfm_affiliate_single->ID ) . '"><span class="wcfmfa fa-edit text_tip" data-tip="' . esc_attr__( 'Manage Affiliate', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
					$actions .= '<br/><a href="#" data-memberid="'.$wcfm_affiliate_single->ID.'" class="wcfm_affiliate_disable_button wcfm-action-icon"><span class="wcfmfa fa-times-circle text_tip" data-tip="' . __( 'Disable Affiliate Account', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
				} else {
					$actions = '<a href="#" data-memberid="'.$wcfm_affiliate_single->ID.'" class="wcfm_affiliate_enable_button wcfm-action-icon"><span class="wcfmfa fa-check-circle text_tip" data-tip="' . __( 'Enable Affiliate Account', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
				}
				$actions .= '<a class="wcfm_affiliate_delete wcfm-action-icon" href="#" data-affiliateid="' . $wcfm_affiliate_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				$wcfm_affiliate_json_arr[$index][] = apply_filters ( 'wcfm_affiliate_actions', $actions, $wcfm_affiliate_single );
				
				
				$index++;
			}												
		}
		if( !empty($wcfm_affiliate_json_arr) ) $wcfm_affiliate_json .= json_encode($wcfm_affiliate_json_arr);
		else $wcfm_affiliate_json .= '[]';
		$wcfm_affiliate_json .= '
													}';
													
		echo $wcfm_affiliate_json;
	}
}