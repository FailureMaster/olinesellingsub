<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Affiliate Stats Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/controllers
 * @version   1.0.0
 */

class WCFMaf_Affiliate_Stats_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMaf;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$affiliate_id = $_POST['wcfm_affiliate']; 
		$status          = $_POST['status_type'];
		
		$sql  = "SELECT COUNT(ID) FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
		$sql .= " WHERE 1=1";
		$sql .= " AND affiliate_id = {$affiliate_id}";
		if( $status ) $sql .= " AND commission_status = '{$status}'";
		$sql .= ' AND `is_trashed` = 0';
		$affiliate_count = $wpdb->get_var( $sql );
		            
		// Get Product Count
		$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
		$sql .= " WHERE 1=1";
		$sql .= " AND affiliate_id = {$affiliate_id}";
		if( $status ) $sql .= " AND commission_status = '{$status}'";
		$sql .= ' AND `is_trashed` = 0';
		$sql .= " ORDER BY `ID` DESC";
		$sql .= " LIMIT {$length}";
		$sql .= " OFFSET {$offset}";
		
		
		$wcfm_affiliate_orders_array = $wpdb->get_results( $sql );
		$wcfm_affiliate_order_count  = count( $wcfm_affiliate_orders_array );
		
		
		// Generate Products JSON
		$wcfm_affiliate_json = '';
		$wcfm_affiliate_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $wcfm_affiliate_order_count . ',
															"recordsFiltered": ' . $affiliate_count . ',
															"data": ';
		$index = 0;
		$wcfm_affiliate_orders_json_arr = array();
		if(!empty($wcfm_affiliate_orders_array)) {
			foreach( $wcfm_affiliate_orders_array as $wcfm_affiliate_order_single ) {
				
				// Status
				if( $wcfm_affiliate_order_single->commission_status == 'pending' ) {
					$wcfm_affiliate_orders_json_arr[$index][] = '<span class="order-status tips wcicon-status-pending text_tip" data-tip="' . __( 'Pending', 'wc-frontend-manager-affiliate' ) . '"></span>';
				} else {
					$wcfm_affiliate_orders_json_arr[$index][] = '<span class="order-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Paid', 'wc-frontend-manager-affiliate' ) . '"></span>';
				}
				
				// Commission
				$wcfm_affiliate_orders_json_arr[$index][] = '<span class="wcfm_order_title">#' . sprintf( '%06u', $wcfm_affiliate_order_single->ID ) . '</a>';
				
				// Amount
				$wcfm_affiliate_orders_json_arr[$index][] = wc_price( $wcfm_affiliate_order_single->commission_amount );
				
				// Type
				if( $wcfm_affiliate_order_single->commission_type == 'vendor' ) {
					$wcfm_affiliate_orders_json_arr[$index][] = '<span class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type . '">' . __( 'Vendor Register', 'wc-frontend-manager-affiliate' ) . '</span>';
				} else if( $wcfm_affiliate_order_single->commission_type == 'vendor_order' ) {
					$wcfm_affiliate_orders_json_arr[$index][] = '<span class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type . '">' . __( 'Vendor Order', 'wc-frontend-manager-affiliate' ) . '</span>';
				} else {
					$wcfm_affiliate_orders_json_arr[$index][] = '<span class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type. '">' . __( 'Order', 'wc-frontend-manager-affiliate' ) . '</span>';
				}
				
				// Date
				$wcfm_affiliate_orders_json_arr[$index][] =  date_i18n( wc_date_format() . ' ' . wc_time_format(), strtotime($wcfm_affiliate_order_single->created) );
				
				// Action
				$actions = '<a class="wcfmaff_commission_show_details wcfm-action-icon" href="#" data-affiliate_id="' . $wcfm_affiliate_order_single->ID . '"><span class="wcfmfa fa-eye text_tip" data-tip="' . esc_attr__( 'Show Details', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
				if( $wcfm_affiliate_order_single->commission_status == 'pending' ) {
					if( !wcfm_is_affiliate() ) {
						$actions .= '<a class="wcfmaff_commission_mark_paid wcfm-action-icon" href="#" data-affiliate_id="' . $wcfm_affiliate_order_single->ID . '"><span class="wcfmfa fa-check-circle text_tip" data-tip="' . esc_attr__( 'Mark Paid', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
						$actions .= '<br /><a class="wcfmaff_commission_mark_reject wcfm-action-icon" href="#" data-affiliate_id="' . $wcfm_affiliate_order_single->ID . '"><span class="wcfmfa fa-times-circle text_tip" data-tip="' . esc_attr__( 'Mark Rejected', 'wc-frontend-manager-affiliate' ) . '"></span></a>';
					}
				} elseif( $wcfm_affiliate_order_single->commission_paid_date ) {
					$actions .= '<br /><span class="wcfmfa fa-clock text_tip" style="color: #00798b;" data-tip="' . esc_attr__( 'Paid ON', 'wc-frontend-manager-affiliate' ) . '"></span>&nbsp;' . date_i18n( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_affiliate_order_single->commission_paid_date ) );
				}
				$wcfm_affiliate_orders_json_arr[$index][] = apply_filters ( 'wcfm_affiliate_stats_actions', $actions, $wcfm_affiliate_order_single );
				
				$index++;
			}												
		}
		if( !empty($wcfm_affiliate_orders_json_arr) ) $wcfm_affiliate_json .= json_encode($wcfm_affiliate_orders_json_arr);
		else $wcfm_affiliate_json .= '[]';
		$wcfm_affiliate_json .= '
													}';
													
		echo $wcfm_affiliate_json;
	}
}