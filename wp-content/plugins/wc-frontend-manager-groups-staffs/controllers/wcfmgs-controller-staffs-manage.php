<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Staffs Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Staffs_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_staff_manager_form_data;
		
		$wcfm_staff_manager_form_data = array();
	  parse_str($_POST['wcfm_staffs_manage_form'], $wcfm_staff_manager_form_data);
	  
	  $wcfm_staff_messages = get_wcfmgs_staffs_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_staff_manager_form_data['user_name']) && !empty($wcfm_staff_manager_form_data['user_name'])) {
	  	if(isset($wcfm_staff_manager_form_data['user_email']) && !empty($wcfm_staff_manager_form_data['user_email'])) {
	  		
	  		if ( ! is_email( $wcfm_staff_manager_form_data['user_email'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
					die;
				}
				
				if ( ! validate_username( $wcfm_staff_manager_form_data['user_name'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
					die;
				}
				
				$staff_id = 0;
				$is_update = false;
				if( isset($wcfm_staff_manager_form_data['staff_id']) && $wcfm_staff_manager_form_data['staff_id'] != 0 ) {
					$staff_id = absint( $wcfm_staff_manager_form_data['staff_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_staff_manager_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_staff_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_staff_manager_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_staff_messages['email_exists'] . '"}';
						}
					}
				}
				
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				if( !$has_error ) {
					$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
					
					$user_data = array( 'user_login'     => $wcfm_staff_manager_form_data['user_name'],
															'user_email'     => $wcfm_staff_manager_form_data['user_email'],
															'display_name'   => $wcfm_staff_manager_form_data['user_name'],
															'nickname'       => $wcfm_staff_manager_form_data['user_name'],
															'first_name'     => $wcfm_staff_manager_form_data['first_name'],
															'last_name'      => $wcfm_staff_manager_form_data['last_name'],
															'user_pass'      => $password,
															'role'           => $staff_user_role,
															'ID'             => $staff_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						$staff_id = wp_update_user( $user_data ) ;
					} else {
						$staff_id = wp_insert_user( $user_data ) ;
						
						// Staff Real Author
						update_user_meta( $staff_id, '_wcfm_staff_author', get_current_user_id() );
					}
						
					if( !$staff_id ) {
						$has_error = true;
					} else {
						if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
							if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
								
								// Remove Rules
								if(isset($_POST['removed_rules']) && !empty($_POST['removed_rules'])) {
									foreach($_POST['removed_rules'] as $removed_rule) {
										$availability_object = new WC_Appointments_Availability( $removed_rule );
										$availability_object->delete();
									}
								}
								
								// Availability Rules
								$availability_rule_index = 0;
								$availability_rules = array();
								$availability_default_rules = array(  "type"        => 'custom',
																											"from"        => '',
																											"to"          => '',
																											"appointable" => '',
																											"qty"         => ''
																										);
								if( isset($wcfm_staff_manager_form_data['_wc_appointment_availability']) && !empty($wcfm_staff_manager_form_data['_wc_appointment_availability']) ) {
									foreach( $wcfm_staff_manager_form_data['_wc_appointment_availability'] as $availability_rule ) {
										$current_id = !empty( $availability_rule['avail_id'] ) ? intval( $availability_rule['avail_id'] ) : 0;
				
										$availability = new WC_Appointments_Availability( $current_id );
										$availability->set_ordering( $availability_rule_index );
										$availability->set_range_type( $availability_rule['type'] );
										$availability->set_kind( 'availability#staff' );
										$availability->set_kind_id( $staff_id );
										
										$availability->set_appointable( wc_clean( wp_unslash( $availability_rule['appointable'] ) ) );
										
										$availability->set_title( sanitize_text_field( wp_unslash( $availability_rule['title'] ) ) );
										
										$availability->set_priority( intval( $availability_rule['priority'] ) );
										
										
										if( $availability_rule['type'] == 'custom' ) {
											$availability->set_from_range( wc_clean( wp_unslash( $availability_rule['from_custom'] ) ) );
											$availability->set_to_range( wc_clean( wp_unslash( $availability_rule['to_custom'] ) ) );
										} elseif( $availability_rule['type'] == 'months' ) {
											$availability->set_from_range( wc_clean( wp_unslash( $availability_rule['from_months'] ) ) );
											$availability->set_to_range( wc_clean( wp_unslash( $availability_rule['to_months'] ) ) );
										} elseif($availability_rule['type'] == 'weeks' ) {
											$availability->set_from_range( wc_clean( wp_unslash( $availability_rule['from_weeks'] ) ) );
											$availability->set_to_range( wc_clean( wp_unslash( $availability_rule['to_weeks'] ) ) );
										} elseif($availability_rule['type'] == 'days' ) {
											$availability->set_from_range( wc_clean( wp_unslash( $availability_rule['from_days'] ) ) );
											$availability->set_to_range( wc_clean( wp_unslash( $availability_rule['to_days'] ) ) );
										} elseif($availability_rule['type'] == 'custom:daterange' ) {
											$availability->set_from_date( wc_clean( wp_unslash( $availability_rule['from_custom'] ) ) );
											$availability->set_to_date( wc_clean( wp_unslash( $availability_rule['to_custom'] ) ) );
											$availability->set_from_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['from_time'] ) ) );
											$availability->set_to_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['to_time'] ) ) );
										} elseif($availability_rule['type'] == 'time:range' ) {
											$availability->set_from_date( wc_clean( wp_unslash( $availability_rule['from_custom'] ) ) );
											$availability->set_to_date( wc_clean( wp_unslash( $availability_rule['to_custom'] ) ) );
											$availability->set_from_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['from_time'] ) ) );
											$availability->set_to_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['to_time'] ) ) );
										} else {
											$availability->set_from_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['from_time'] ) ) );
											$availability->set_to_range( wc_appointment_sanitize_time( wp_unslash( $availability_rule['to_time'] ) ) );
										}
										
										$availability->save();
										$availability_rule_index++;
									}
								}
								update_user_meta( $staff_id, '_wc_appointment_availability', $availability_rules );
								update_user_meta( $staff_id, '_wc_appointment_staff_qty', $wcfm_staff_manager_form_data['_wc_appointment_staff_qty'] );
							}
						}
						
						if( !$is_update ) {
							// Sending Mail to new user
							define( 'DOING_WCFM_EMAIL', true );
							
							$mail_to = $wcfm_staff_manager_form_data['user_email'];
							$new_account_mail_subject = "{site_name}: New Account Created";
							$new_account_mail_body = __( 'Dear', 'wc-frontend-manager-groups-staffs' ) . ' {first_name}' .
																			 ',<br/><br/>' . 
																			 __( 'Your account has been created as {user_role}. Follow the below details to log into the system', 'wc-frontend-manager-groups-staffs' ) .
																			 '<br/><br/>' . 
																			 __( 'Site', 'wc-frontend-manager-groups-staffs' ) . ': {site_url}' . 
																			 '<br/>' .
																			 __( 'Login', 'wc-frontend-manager-groups-staffs' ) . ': {username}' .
																			 '<br/>' . 
																			 __( 'Password', 'wc-frontend-manager-groups-staffs' ) . ': {password}' .
																			 '<br /><br/>Thank You';
																			 
							$wcfmgs_new_account_mail_subject = get_option( 'wcfmgs_new_account_mail_subject' );
							if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
							$wcfmgs_new_account_mail_body = get_option( 'wcfmgs_new_account_mail_body' );
							if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;
							
							$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
							$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
							$message = str_replace( '{site_url}', get_bloginfo( 'url' ), $new_account_mail_body );
							$message = str_replace( '{first_name}', $wcfm_staff_manager_form_data['first_name'], $message );
							$message = str_replace( '{username}', $wcfm_staff_manager_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', __( 'Shop Staff', 'wc-frontend-manager-groups-staffs' ), $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
							
							// Desktop notification message for new_customer
							$author_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
							$author_is_admin = 0;
							$author_is_vendor = 1;
							$message_to = 0;
							$wcfm_messages = sprintf( __( 'A new staff <b>%s</b> added to the store by <b>%s</b>', 'wc-frontend-manager-groups-staffs' ), $wcfm_staff_manager_form_data['first_name'] . ' ' . $wcfm_staff_manager_form_data['last_name'], get_user_by( 'id', $author_id )->display_name );
							$WCFM->wcfm_notification->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'new_staff' );
						}
						
						// Staff Vendor
						if( wcfm_is_marketplace() && isset( $wcfm_staff_manager_form_data['wcfm_vendor'] ) && !empty( $wcfm_staff_manager_form_data['wcfm_vendor'] ) ) {
							update_user_meta( $staff_id, '_wcfm_vendor', $wcfm_staff_manager_form_data['wcfm_vendor'] );
						} else {
							delete_user_meta( $staff_id, '_wcfm_vendor' );
						}
						
						// Update User capability
						if( isset( $wcfm_staff_manager_form_data['has_custom_capability'] ) ) {
							update_user_meta( $staff_id, '_wcfm_user_has_custom_capability', 'yes' );
							
							if( isset( $wcfm_staff_manager_form_data['wcfmgs_capability_manager_options'] ) ) {
								update_user_meta( $staff_id, '_wcfm_user_capability_options', $wcfm_staff_manager_form_data['wcfmgs_capability_manager_options'] );
							} else {
								delete_user_meta( $staff_id, '_wcfm_user_capability_options' );
							}
						} else {
							update_user_meta( $staff_id, '_wcfm_user_has_custom_capability', 'no' );
							delete_user_meta( $staff_id, '_wcfm_user_capability_options' );
						}
						
						// Update general restriction
						update_user_meta( $staff_id, 'show_admin_bar_front', false );
						update_user_meta( $staff_id, 'wcemailverified', 'true' );	
							
						do_action( 'wcfm_staffs_manage', $staff_id );
					}
							
					if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_staff_messages['staff_saved'] . '", "redirect": "' . apply_filters( 'wcfm_staff_manage_redirect', get_wcfm_shop_staffs_manage_url($staff_id), $staff_id ) . '"}'; }
					else { echo '{"status": false, "message": "' . $wcfm_staff_messages['staff_failed'] . '"}'; }
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_staff_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_staff_messages['no_username'] . '"}';
		}
		
		die;
	}
}