<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Groups Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Groups_Manage_Controller {
	
	public function __construct() {
		global $WCFM;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $wpdb, $wcfm_group_manager_form_data;
		
		$wcfm_group_manager_form_data = array();
	  parse_str($_POST['wcfm_groups_manage_form'], $wcfm_group_manager_form_data);
	  
	  $wcfm_group_messages = get_wcfmgs_groups_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_group_manager_form_data['title']) && !empty($wcfm_group_manager_form_data['title'])) {
	  	$is_update = false;
	  	$is_publish = false;
	  	$is_marketplace = wcfm_is_marketplace();
	  	$current_user_id = get_current_user_id();
	  	
	  	$group_status = 'publish';
	  	
	  	// Creating new group
			$new_group = array(
				'post_title'   => wc_clean( $wcfm_group_manager_form_data['title'] ),
				'post_status'  => $group_status,
				'post_type'    => 'wcfm_vendor_groups',
				'post_excerpt' => $wcfm_group_manager_form_data['excerpt'],
				'post_author'  => $current_user_id
				//'post_name' => sanitize_title($wcfm_group_manager_form_data['title'])
			);
			
			if(isset($wcfm_group_manager_form_data['group_id']) && $wcfm_group_manager_form_data['group_id'] == 0) {
				$new_group_id = wp_insert_post( $new_group, true );
				
				// Group Real Author
				update_post_meta( $new_group_id, '_wcfm_group_author', get_current_user_id() );
			} else { // For Update
				$is_update = true;
				$new_group['ID'] = $wcfm_group_manager_form_data['group_id'];
				$new_group_id = wp_update_post( $new_group, true );
			}
			
			if(!is_wp_error($new_group_id)) {
				// For Update
				if($is_update) {
					$new_group_id = $wcfm_group_manager_form_data['group_id'];
				
					// Remove Group from old Vendors
					if( $is_marketplace ) {
						$old_group_vendors = (array) get_post_meta( $new_group_id, '_group_vendors', true );
						if( !empty( $old_group_vendors ) ) {
							foreach( $old_group_vendors as $old_group_vendor ) {
								$wcfm_vendor_groups = (array) get_user_meta( $old_group_vendor, '_wcfm_vendor_group', true );
								if( !empty( $wcfm_vendor_groups ) ) {
									if( ( $key = array_search( $new_group_id, $wcfm_vendor_groups ) ) !== false ) {
										unset( $wcfm_vendor_groups[$key] );
									}
									update_user_meta( $old_group_vendor, '_wcfm_vendor_group', $wcfm_vendor_groups );
									update_user_meta( $old_group_vendor, '_wcfm_vendor_group_list', implode( ",", array_unique( $wcfm_vendor_groups ) ) );
								}
							}
						}
					}
					
					// Remove Group from old Managers
					$old_group_managers = (array) get_post_meta( $new_group_id, '_group_managers', true );
					if( !empty( $old_group_managers ) ) {
						foreach( $old_group_managers as $old_group_manager ) {
							$wcfm_vendor_groups = (array) get_user_meta( $old_group_manager, '_wcfm_vendor_group', true );
							if( !empty( $wcfm_vendor_groups ) ) {
								if( ( $key = array_search( $new_group_id, $wcfm_vendor_groups ) ) !== false ) {
									unset( $wcfm_vendor_groups[$key] );
								}
								update_user_meta( $old_group_manager, '_wcfm_vendor_group', $wcfm_vendor_groups );
								update_user_meta( $old_group_manager, '_wcfm_vendor_group_list', implode( ",", array_unique( $wcfm_vendor_groups ) ) );
							}
						}
					}
				}
				
				// Update Group Vendors
				if( $is_marketplace ) {
					if( isset( $wcfm_group_manager_form_data['group_vendors'] ) && !empty( $wcfm_group_manager_form_data['group_vendors'] ) ) {
						update_post_meta( $new_group_id, '_group_vendors', $wcfm_group_manager_form_data['group_vendors'] );
						
						// Associate Vendors with Group
						foreach( $wcfm_group_manager_form_data['group_vendors'] as $group_vendor ) {
							$wcfm_vendor_groups = (array) get_user_meta( $group_vendor, '_wcfm_vendor_group', true );
							$wcfm_vendor_groups[] = $new_group_id;
							update_user_meta( $group_vendor, '_wcfm_vendor_group', array_unique( $wcfm_vendor_groups ) );
							update_user_meta( $group_vendor, '_wcfm_vendor_group_list', implode( ",", array_unique( $wcfm_vendor_groups ) ) );
						}
					} else {
						update_post_meta( $new_group_id, '_group_vendors', array() );
					}
				}
				
				// Update Group Managers
				if( isset( $wcfm_group_manager_form_data['group_managers'] ) && !empty( $wcfm_group_manager_form_data['group_managers'] ) ) {
					update_post_meta( $new_group_id, '_group_managers', $wcfm_group_manager_form_data['group_managers'] );
					
					// Associate Managers with Group
					foreach( $wcfm_group_manager_form_data['group_managers'] as $group_manager ) {
						$wcfm_vendor_groups = (array) get_user_meta( $group_manager, '_wcfm_vendor_group', true );
						$wcfm_vendor_groups[] = $new_group_id;
						update_user_meta( $group_manager, '_wcfm_vendor_group', array_unique( $wcfm_vendor_groups ) );
						update_user_meta( $group_manager, '_wcfm_vendor_group_list', implode( ",", array_unique( $wcfm_vendor_groups ) ) );
					}
				} else {
					update_post_meta( $new_group_id, '_group_managers', array() );
				}
				
				// Update Group capability
				if( isset( $wcfm_group_manager_form_data['wcfmgs_capability_manager_options'] ) ) {
					update_post_meta( $new_group_id, '_group_capability_options', $wcfm_group_manager_form_data['wcfmgs_capability_manager_options'] );
				} else {
					update_post_meta( $new_group_id, '_group_capability_options', array() );
				}
				
				// Thumbnail
				if( isset( $wcfm_group_manager_form_data['thumbnail'] ) && !empty( $wcfm_group_manager_form_data['thumbnail'] ) ) {
					update_post_meta( $new_group_id, 'thumbnail', $wcfm_group_manager_form_data['thumbnail'] );
				} else {
					update_post_meta( $new_group_id, 'thumbnail', '' );
				}
				
				
				// Hook for additional processing
				do_action( 'wcfm_groups_manage_from_process', $new_group_id, $wcfm_group_manager_form_data );
				
				echo '{"status": true, "message": "' . $wcfm_group_messages['group_saved'] . '", "redirect": "' . get_wcfm_groups_manage_url($new_group_id) . '"}';
				die;
			} else {
				echo '{"status": false, "message": "' . $wcfm_group_messages['group_failed'] . '"}';
			}
		} else {
			echo '{"status": false, "message": "' . $wcfm_group_messages['no_title'] . '"}';
		}
		
		die;
	}
}