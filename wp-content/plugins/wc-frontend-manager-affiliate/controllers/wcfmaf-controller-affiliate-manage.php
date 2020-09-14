<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Affiliate Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/controllers
 * @version   1.0.0
 */

class WCFMaf_Affiliate_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMaf;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_affiliate_manager_form_data;
		
		$wcfm_affiliate_manager_form_data = array();
	  parse_str($_POST['wcfm_affiliate_manage_form'], $wcfm_affiliate_manager_form_data);
	  
	  $wcfm_affiliate_messages = get_wcfmaf_affiliate_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_affiliate_manager_form_data['user_name']) && !empty($wcfm_affiliate_manager_form_data['user_name'])) {
	  	if(isset($wcfm_affiliate_manager_form_data['user_email']) && !empty($wcfm_affiliate_manager_form_data['user_email'])) {
	  		
	  		if ( ! is_email( $wcfm_affiliate_manager_form_data['user_email'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
					die;
				}
				
				if ( ! validate_username( $wcfm_affiliate_manager_form_data['user_name'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
					die;
				}
				
				$affiliate_id = 0;
				$is_update = false;
				if( isset($wcfm_affiliate_manager_form_data['affiliate_id']) && $wcfm_affiliate_manager_form_data['affiliate_id'] != 0 ) {
					$affiliate_id = absint( $wcfm_affiliate_manager_form_data['affiliate_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_affiliate_manager_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_affiliate_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_affiliate_manager_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_affiliate_messages['email_exists'] . '"}';
						}
					}
				}
				
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				if( !$has_error ) {
					$affiliate_user_role = apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' );
					
					$user_data = array( 'user_login'     => $wcfm_affiliate_manager_form_data['user_name'],
															'user_email'     => $wcfm_affiliate_manager_form_data['user_email'],
															'display_name'   => $wcfm_affiliate_manager_form_data['user_name'],
															'nickname'       => $wcfm_affiliate_manager_form_data['user_name'],
															'first_name'     => $wcfm_affiliate_manager_form_data['first_name'],
															'last_name'      => $wcfm_affiliate_manager_form_data['last_name'],
															'user_pass'      => $password,
															'role'           => $affiliate_user_role,
															'ID'             => $affiliate_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						$affiliate_id = wp_update_user( $user_data ) ;
					} else {
						$affiliate_id = wp_insert_user( $user_data ) ;
					}
						
					if( !$affiliate_id ) {
						$has_error = true;
					} else {
						
						if( !$is_update ) {
							// Affiliate Code 
							$affiliate_code = substr( md5( $wcfm_affiliate_manager_form_data['user_email'] . '+' . $affiliate_id ), 0, 10 );
							update_user_meta( $affiliate_id, 'affiliate_code', $affiliate_code );
							
							// Sending Mail to new user
							define( 'DOING_WCFM_EMAIL', true );
							
							$mail_to = $wcfm_affiliate_manager_form_data['user_email'];
							$new_account_mail_subject = "{site_name}: New Account Created";
							$new_account_mail_body = __( 'Dear', 'wc-frontend-manager-affiliate' ) . ' {first_name}' .
																			 ',<br/><br/>' . 
																			 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-manager-affiliate' ) .
																			 '<br/><br/>' . 
																			 __( 'Site', 'wc-frontend-manager-affiliate' ) . ': {site_url}' . 
																			 '<br/>' .
																			 __( 'Login', 'wc-frontend-manager-affiliate' ) . ': {username}' .
																			 '<br/>' . 
																			 __( 'Password', 'wc-frontend-manager-affiliate' ) . ': {password}' .
																			 '<br /><br/>Thank You';
																			 
							$wcfmgs_new_account_mail_subject = wcfm_get_option( 'wcfmaf_new_account_mail_subject' );
							if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
							$wcfmgs_new_account_mail_body = wcfm_get_option( 'wcfmaf_new_account_mail_body' );
							if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;
							
							$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
							$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
							$message = str_replace( '{site_url}', get_bloginfo( 'url' ), $new_account_mail_body );
							$message = str_replace( '{first_name}', $wcfm_affiliate_manager_form_data['first_name'], $message );
							$message = str_replace( '{username}', $wcfm_affiliate_manager_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', __( 'Affiliate', 'wc-frontend-manager-affiliate' ), $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
							
						}
						
						// Store Phone
						if( isset( $wcfm_affiliate_manager_form_data['user_phone'] ) ) {
							update_user_meta( $affiliate_id, 'billing_phone', $wcfm_affiliate_manager_form_data['user_phone'] );
						} else {
							update_user_meta( $affiliate_id, 'billing_phone', '' );
						}
						
						// Affiliate Commission
						if( isset( $wcfm_affiliate_manager_form_data['commission'] ) && !empty( $wcfm_affiliate_manager_form_data['commission'] ) ) {
							update_user_meta( $affiliate_id, 'wcfm_affiliate_commission', $wcfm_affiliate_manager_form_data['commission'] );
						} else {
							$global_commission = get_option( 'wcfm_affiliate_commission', array() );
							$global_commission['rule'] = 'global';
							update_user_meta( $affiliate_id, 'wcfm_affiliate_commission', $global_commission );
						}
						
						// Update Additional Infos
						if( isset( $wcfm_affiliate_manager_form_data['wcfmaf_custom_infos'] ) ) {
							update_user_meta( $affiliate_id, 'wcfmaf_custom_infos', $wcfm_affiliate_manager_form_data['wcfmaf_custom_infos'] );
							
							// Toolset User Fields Compatibility added
							$wcfmaf_registration_custom_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
							$wcfmaf_custom_infos = (array) $wcfm_affiliate_manager_form_data['wcfmaf_custom_infos'];
							
							if( !empty( $wcfmaf_registration_custom_fields ) ) {
								foreach( $wcfmaf_registration_custom_fields as $wcfmaf_registration_custom_field ) {
									if( !isset( $wcfmaf_registration_custom_field['enable'] ) ) continue;
									if( !$wcfmaf_registration_custom_field['label'] ) continue;
									$field_value = '';
									$wcfmaf_registration_custom_field['name'] = sanitize_title( $wcfmaf_registration_custom_field['label'] );
								
									if( !empty( $wcfmaf_custom_infos ) ) {
										if( $wcfmaf_registration_custom_field['type'] == 'checkbox' ) {
											$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] : 'no';
										} elseif( $wcfmaf_registration_custom_field['type'] == 'upload' ) {
											$field_name  = 'wcfmaf_custom_infos[' . $wcfmaf_registration_custom_field['name'] . ']';
											$field_id    = md5( $field_name );
											$field_value = isset( $wcfmaf_custom_infos[$field_id] ) ? $wcfmaf_custom_infos[$field_id] : '';
										} else {
											$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] : '';
										}
									}
									if( !$field_value ) $field_value = '';
									update_user_meta( $affiliate_id, $wcfmaf_registration_custom_field['name'], $field_value );
								}
							}
						}
						
						// Update general restriction
						update_user_meta( $affiliate_id, 'wcfm_affiliate_application_status', 'approved' );
						update_user_meta( $affiliate_id, 'show_admin_bar_front', false );
						update_user_meta( $affiliate_id, 'wcemailverified', 'true' );	
							
						do_action( 'wcfm_affiliate_manage', $affiliate_id );
					}
							
					if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_affiliate_messages['affiliate_saved'] . '", "redirect": "' . apply_filters( 'wcfm_affiliate_manage_redirect', get_wcfm_affiliate_manage_url( $affiliate_id ), $affiliate_id ) . '"}'; }
					else { echo '{"status": false, "message": "' . $wcfm_affiliate_messages['affiliate_failed'] . '"}'; }
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_affiliate_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_affiliate_messages['no_username'] . '"}';
		}
		
		die;
	}
}