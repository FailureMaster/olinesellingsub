<?php
/**
 * WCFM plugin controllers
 *
 * Plugin WCFMgs Groups Dashboard Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Groups_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $_POST, $WCFMu, $WCFMgs;
		
		$length = $_POST['length'];
		$offset = $_POST['start'];
		
		$args = array(
							'posts_per_page'   => $length,
							'offset'           => $offset,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'wcfm_vendor_groups',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => true 
						);
		if( isset( $_POST['search'] ) && !empty( $_POST['search']['value'] )) $args['s'] = $_POST['search']['value'];
		
		$args = apply_filters( 'wcfm_vendor_groups_args', $args );
		
		$wcfm_groups_array = get_posts( $args );
		
		// Get Group Count
		$group_count = 0;
		$filtered_group_count = 0;
		$wcfm_groups_count = wp_count_posts('wcfm_vendor_groups');
		$group_count = ( $wcfm_groups_count->publish + $wcfm_groups_count->pending + $wcfm_groups_count->draft );
		// Get Filtered Post Count
		$args['posts_per_page'] = -1;
		$args['offset'] = 0;
		$wcfm_filterd_groups_array = get_posts( $args );
		$filtered_group_count = count($wcfm_filterd_groups_array);
		$is_marketplace = wcfm_is_marketplace();
		
		// Generate Groups JSON
		$wcfm_groups_json = '';
		$wcfm_groups_json = '{
															"draw": ' . $_POST['draw'] . ',
															"recordsTotal": ' . $group_count . ',
															"recordsFiltered": ' . $filtered_group_count . ',
															"data": ';
		if(!empty($wcfm_groups_array)) {
			$index = 0;
			$wcfm_groups_json_arr = array();
			foreach($wcfm_groups_array as $wcfm_groups_single) {
				
				// Thumb
				$thumbnail = get_post_meta( $wcfm_groups_single->ID, 'thumbnail', true );
				if( $thumbnail ) {
					$wcfm_groups_json_arr[$index][] =  '<a href="' . get_wcfm_groups_manage_url($wcfm_groups_single->ID) . '"><img src="' . wcfm_get_attachment_url( $thumbnail ) . '" width="30" /></a>';
				} else {
					$wcfm_groups_json_arr[$index][] =  '<a href="' . get_wcfm_groups_manage_url($wcfm_groups_single->ID) . '"><img src="' . apply_filters( 'woocommerce_placeholder_img_src', WC()->plugin_url() . '/assets/images/placeholder.png' ) . '" width="30" /></a>';
				}
				
				// Group
				$wcfm_groups_json_arr[$index][] =  '<a href="' . get_wcfm_groups_manage_url($wcfm_groups_single->ID) . '" class="wcfm_dashboard_item_title">' . $wcfm_groups_single->post_title . '</a>';
				
				// Vendor Count
				if( $is_marketplace ) {
					$_group_vendors = get_post_meta( $wcfm_groups_single->ID, '_group_vendors', true );
					if( $_group_vendors && is_array( $_group_vendors ) ) { 
						$wcfm_groups_json_arr[$index][] =  '<span class="vendor_count">' . count( $_group_vendors ) . '</span>';
					} else {
						$wcfm_groups_json_arr[$index][] =  '<span class="vendor_count">&ndash;</span>';
					}
				} else {
					$wcfm_groups_json_arr[$index][] =  '<span class="vendor_count">&ndash;</span>';
				}
				
				// Manager Count
				$_group_managers = get_post_meta( $wcfm_groups_single->ID, '_group_managers', true );
				if( $_group_managers && is_array( $_group_managers ) ) {
					$wcfm_groups_json_arr[$index][] =  '<span class="manager_count">' . count( $_group_managers ) . '</span>';
				} else {
					$wcfm_groups_json_arr[$index][] =  '<span class="manager_count">&ndash;</span>';
				}
				
				// Action
				$actions = '';
				if( apply_filters( 'wcfm_is_allow_groups_archive', true ) ) {
					$actions = '<a class="wcfm-action-icon" target="_blank" href="' . get_permalink($wcfm_groups_single->ID) . '"><span class="wcfmfa fa-eye text_tip" data-tip="' . esc_attr__( 'View', 'wc-frontend-manager' ) . '"></span></a>'; 
				}
				$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_groups_manage_url($wcfm_groups_single->ID) . '"><span class="wcfmfa fa-edit text_tip" data-tip="' . esc_attr__( 'Edit', 'wc-frontend-manager' ) . '"></span></a>';
				$actions .= '<a class="wcfm_group_delete wcfm-action-icon" href="#" data-groupid="' . $wcfm_groups_single->ID . '"><span class="wcfmfa fa-trash-alt text_tip" data-tip="' . esc_attr__( 'Delete', 'wc-frontend-manager' ) . '"></span></a>';
				$wcfm_groups_json_arr[$index][] = apply_filters ( 'wcfm_vendor_groups_actions', $actions, $wcfm_groups_single );
				
				$index++;
			}												
		}
		if( !empty($wcfm_groups_json_arr) ) $wcfm_groups_json .= json_encode($wcfm_groups_json_arr);
		else $wcfm_groups_json .= '[]';
		$wcfm_groups_json .= '
													}';
													
		echo $wcfm_groups_json;
	}
}