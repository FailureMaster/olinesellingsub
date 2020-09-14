<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Affiliate Registration Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/controllers
 * @version   1.1.0
 */

class WCFMaf_Affiliate_Registration_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMaf, $wpdb, $wcfm_affiliate_registration_form_data;
		
		$wcfm_affiliate_registration_form_data = array();
	  parse_str($_POST['wcfm_affiliate_registration_form'], $wcfm_affiliate_registration_form_data);
	  
	  $wcfm_affiliate_registration_messages = get_wcfmaf_affiliate_manage_messages();
	  $has_error = false;
	  $wcfm_affiliate = '';
	  
	  // Google reCaptcha support
	  if( apply_filters( 'wcfm_is_allow_affiliate_registration_recaptcha', true ) ) {
			if ( function_exists( 'gglcptch_init' ) ) {
				if(isset($wcfm_affiliate_registration_form_data['g-recaptcha-response']) && !empty($wcfm_affiliate_registration_form_data['g-recaptcha-response'])) {
					$_POST['g-recaptcha-response'] = $wcfm_affiliate_registration_form_data['g-recaptcha-response'];
				}
				$check_result = apply_filters( 'gglcptch_verify_recaptcha', true, 'string', 'wcfm_registration_form' );
				if ( true === $check_result ) {
						/* do necessary action */
				} else { 
					echo '{"status": false, "message": "' . $check_result . '"}';
					die;
				}
			} elseif ( function_exists( 'anr_captcha_form_field' ) ) {
				$check_result = anr_verify_captcha( $wcfm_affiliate_registration_form_data['g-recaptcha-response'] );
				if ( true === $check_result ) {
						/* do necessary action */
				} else { 
					echo '{"status": false, "message": "' . __( 'Captcha failed, please try again.', 'wc-frontend-manager' ) . '"}';
					die;
				}
			}
		}
		
		if ( empty( $wcfm_affiliate_registration_form_data['user_email'] ) || ! is_email( $wcfm_affiliate_registration_form_data['user_email'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
			die;
		}
		
		$wcfmaf_registration_static_fields = wcfm_get_option( 'wcfmaf_registration_static_fields', array() );
		$is_user_name = isset( $wcfmaf_registration_static_fields['user_name'] ) ? 'yes' : '';
		if( !$is_user_name ) {
			$user_email = sanitize_email( $wcfm_affiliate_registration_form_data['user_email'] );
			
			$username   = sanitize_user( current( explode( '@', $user_email ) ), true );
			
			$append     = 1;
			$o_username = $username;

			while ( username_exists( $username ) ) {
				$username = $o_username . $append;
				$append++;
			}
			$wcfm_affiliate_registration_form_data['user_name'] = $username;
		}
		
		if ( empty( $wcfm_affiliate_registration_form_data['user_name'] ) || ! validate_username( $wcfm_affiliate_registration_form_data['user_name'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
			die;
		}
		
		// WCFM form custom validation filter
		$custom_validation_results = apply_filters( 'wcfm_form_custom_validation', $wcfm_affiliate_registration_form_data, 'affiliate_registration' );
		if(isset($custom_validation_results['has_error']) && !empty($custom_validation_results['has_error'])) {
			$custom_validation_error = __( 'There has some error in submitted data.', 'wc-frontend-manager' );
			if( isset( $custom_validation_results['message'] ) && !empty( $custom_validation_results['message'] ) ) { $custom_validation_error = $custom_validation_results['message']; }
			echo '{"status": false, "message": "' . $custom_validation_error . '"}';
			die;
		}
		
		// Handle File Uploads
		$files_data = array();
		if ( ! empty( $_FILES ) ) {
			foreach ( $_FILES as $file_key => $file ) {
				$files_to_upload = wcfm_prepare_uploaded_files( $file );
				if( !empty( $files_to_upload ) ) {
					foreach ( $files_to_upload as $file_to_upload ) {
						$uploaded_file = wcfm_upload_file(
							$file_to_upload,
							array(
								'file_key' => $file_key,
							)
						);
			
						if ( is_wp_error( $uploaded_file ) ) {
							echo '{"status": false, "message": "' . $uploaded_file->get_error_message() . '"}';
							die;
						} else {
							$files_data[$file_key] = $uploaded_file->url;
						}
					}
				}
			}
		}
	  
	  if(isset($wcfm_affiliate_registration_form_data['user_name']) && !empty($wcfm_affiliate_registration_form_data['user_name'])) {
	  	if(isset($wcfm_affiliate_registration_form_data['user_email']) && !empty($wcfm_affiliate_registration_form_data['user_email'])) {
				$member_id = 0;
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				$is_update = false;
				if( isset($wcfm_affiliate_registration_form_data['member_id']) && $wcfm_affiliate_registration_form_data['member_id'] != 0 ) {
					$member_id = absint( $wcfm_affiliate_registration_form_data['member_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_affiliate_registration_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_affiliate_registration_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['email_exists'] . '"}';
						}
					}
					$password = wc_clean( $wcfm_affiliate_registration_form_data['passoword'] );
				}
				
				$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
				$affiliate_type_settings = array();
				if( isset( $wcfm_affiliate_options['affiliate_type_settings'] ) ) $affiliate_type_settings = $wcfm_affiliate_options['affiliate_type_settings'];
				$email_verification = isset( $affiliate_type_settings['email_verification'] ) ? 'yes' : '';
				$sms_verification   = isset( $affiliate_type_settings['sms_verification'] ) ? 'yes' : '';
				
				// EMAIL Verification
				if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
					$email_verified = false;
					if( !$has_error ) {
						if( $email_verification ) {
							if( $is_update ) {
								$email_verified = $wcfm_affiliate_registration_form_data['email_verified'];
							}
							
							if( !$is_update || !$email_verified ) {
								$verification_code = '';
								if( isset( $_SESSION['wcfm_affiliate'] ) && isset( $_SESSION['wcfm_affiliate']['email_verification_code'] ) ) {
									$verification_code = $_SESSION['wcfm_affiliate']['email_verification_code'];
								}
								$wcfm_email_verified_input = $wcfm_affiliate_registration_form_data['wcfm_email_verified_input'];
								
								if( $verification_code != $wcfm_email_verified_input ) {
									$has_error = true;
									echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['email_invalid_code'] . '"}';
								}
								
								if( !$has_error ) {
									$verification_email = '';
									if( isset( $_SESSION['wcfm_affiliate'] ) && isset( $_SESSION['wcfm_affiliate']['email_verification_for'] ) ) {
										$verification_email = $_SESSION['wcfm_affiliate']['email_verification_for'];
									}
									$wcfm_email_verified_for = $wcfm_affiliate_registration_form_data['user_email'];
									
									if( $verification_email != $wcfm_email_verified_for ) {
										$has_error = true;
										echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['email_invalid_code'] . '"}';
									}
								}
							}
							if( !$has_error ) $email_verified = true;
						}
					}
				}
				
				// SMS Verification
				if( apply_filters( 'wcfm_is_allow_sms_verification', true ) && function_exists( 'wcfm_is_store_page' ) ) {
					$sms_verified = false;
					if( !$has_error ) {
						if( $sms_verification ) {
							if( $is_update ) {
								$sms_verified = $wcfm_affiliate_registration_form_data['sms_verified'];
							}
							
							if( !$is_update || !$sms_verified ) {
								$verification_code = '';
								if( isset( $_SESSION['wcfm_affiliate'] ) && isset( $_SESSION['wcfm_affiliate']['sms_verification_code'] ) ) {
									$verification_code = $_SESSION['wcfm_affiliate']['sms_verification_code'];
								}
								$wcfm_sms_verified_input = $wcfm_affiliate_registration_form_data['wcfm_sms_verified_input'];
								
								if( $verification_code != $wcfm_sms_verified_input ) {
									$has_error = true;
									echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['sms_invalid_code'] . '"}';
								}
								
								if( !$has_error ) {
									$verification_sms = '';
									if( isset( $_SESSION['wcfm_affiliate'] ) && isset( $_SESSION['wcfm_affiliate']['sms_verification_for'] ) ) {
										$verification_sms = $_SESSION['wcfm_affiliate']['sms_verification_for'];
									}
									$wcfm_sms_verified_for = $wcfm_affiliate_registration_form_data['wcfmaf_static_infos']['phone'];
									
									if( $verification_sms != $wcfm_sms_verified_for ) {
										$has_error = true;
										echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['sms_invalid_code'] . '"}';
									}
								}
							}
							if( !$has_error ) $sms_verified = true;
						}
					}
				}
				
				if( !$has_error ) {
					$user_data = array( 'user_login'     => $wcfm_affiliate_registration_form_data['user_name'],
															'user_email'     => sanitize_email( $wcfm_affiliate_registration_form_data['user_email'] ),
															'display_name'   => sanitize_user( $wcfm_affiliate_registration_form_data['user_name'] ),
															'user_nicename'  => $wcfm_affiliate_registration_form_data['user_name'],
															'nickname'       => $wcfm_affiliate_registration_form_data['user_name'],
															'first_name'     => isset($wcfm_affiliate_registration_form_data['first_name']) ? $wcfm_affiliate_registration_form_data['first_name'] : '',
															'last_name'      => isset($wcfm_affiliate_registration_form_data['last_name']) ? $wcfm_affiliate_registration_form_data['last_name'] : '',
															'user_pass'      => $password,
															'role'           => apply_filters( 'wcfmaf_registration_default_role', 'subscriber' ),
															'ID'             => $member_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						
						if( !wcfm_is_vendor() ) {
							$member_id = wp_update_user( $user_data ) ;
						}
					} else {
						$member_id = wp_insert_user( $user_data ) ;
					}
						
					if( !$member_id ) {
						$has_error = true;
					} else {
						// Affiliate Code 
						$affiliate_code = substr( md5( $wcfm_affiliate_registration_form_data['user_email'] . '+' . $member_id ), 0, 10 );
						update_user_meta( $member_id, 'affiliate_code', $affiliate_code );
						
						// Affiliate Commission
						$global_commission = get_option( 'wcfm_affiliate_commission', array() );
						$global_commission['rule'] = 'global';
						update_user_meta( $member_id, 'wcfm_affiliate_commission', $global_commission );
							
						// Update First Name as Billing & Shipping First Name
						if( isset( $wcfm_affiliate_registration_form_data['first_name'] ) ) {
							update_user_meta( $member_id, 'billing_first_name', $wcfm_affiliate_registration_form_data['first_name'] );
							update_user_meta( $member_id, 'shipping_first_name', $wcfm_affiliate_registration_form_data['first_name'] );
						}
						
						// Update Last Name as Billing & Shipping Last Name
						if( isset( $wcfm_affiliate_registration_form_data['last_name'] ) ) {
							update_user_meta( $member_id, 'billing_last_name', $wcfm_affiliate_registration_form_data['last_name'] );
							update_user_meta( $member_id, 'shipping_last_name', $wcfm_affiliate_registration_form_data['last_name'] );
						}
						
						// Update Store Address as Billing & Shipping Address
						$wcfmaf_registration_static_fields = wcfm_get_option( 'wcfmaf_registration_static_fields', array() );
						if( !empty( $wcfmaf_registration_static_fields ) && isset( $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'] ) && !empty( $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'] ) ) {
							foreach( $wcfmaf_registration_static_fields as $wcfmaf_registration_static_field => $wcfmaf_registration_static_field_val ) {
								if( !empty( $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'] ) ) {
									$field_value = isset( $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'][$wcfmaf_registration_static_field] ) ? $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'][$wcfmaf_registration_static_field] : array();
								}
								
								switch( $wcfmaf_registration_static_field ) {
									case 'address':
										if( isset($field_value['addr_1']) ) {
											$billing_address_fields = array( 	
																						'billing_address_1'  => 'addr_1',
																						'billing_address_2'  => 'addr_2',
																						'billing_country'    => 'country',
																						'billing_city'       => 'city',
																						'billing_state'      => 'state',
																						'billing_postcode'   => 'zip',
																					);
			
											foreach( $billing_address_fields as $billing_address_field_key => $billing_address_field ) {
												if( isset( $field_value[$billing_address_field] ) ) {
													update_user_meta( $member_id, $billing_address_field_key, $field_value[$billing_address_field] );
												}
											}
											
											$shipping_address_fields = array( 	
																						'shipping_address_1'  => 'addr_1',
																						'shipping_address_2'  => 'addr_2',
																						'shipping_country'    => 'country',
																						'shipping_city'       => 'city',
																						'shipping_state'      => 'state',
																						'shipping_postcode'   => 'zip',
																					);
			
											foreach( $shipping_address_fields as $shipping_address_field_key => $shipping_address_field ) {
												if( isset( $field_value[$shipping_address_field] ) ) {
													update_user_meta( $member_id, $shipping_address_field_key, $field_value[$shipping_address_field] );
												}
											}
										}
									break;
									
									case 'phone':
										update_user_meta( $member_id, 'billing_phone', $field_value );
									break;
								}
							}
						}
						
						// Update Static Infos
						if( isset( $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'] ) ) {
							update_user_meta( $member_id, 'wcfmaf_static_infos', $wcfm_affiliate_registration_form_data['wcfmaf_static_infos'] );
						}
						
						// Direct File Upload
						if( !empty( $files_data ) ) {
							if( !isset( $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'] ) ) $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'] = $files_data;
							else {
								$wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'] = array_merge( $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'], $files_data);
							}
						}
						
						// Update Additional Infos
						if( isset( $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'] ) ) {
							update_user_meta( $member_id, 'wcfmaf_custom_infos', $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'] );
							
							// Toolset User Fields Compatibility added
							$wcfmaf_registration_custom_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
							$wcfmaf_custom_infos = (array) $wcfm_affiliate_registration_form_data['wcfmaf_custom_infos'];
							
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
									update_user_meta( $member_id, $wcfmaf_registration_custom_field['name'], $field_value );
								}
							}
						}
						
						// Email Verification Update - 1.3.2
						if( apply_filters( 'wcfm_is_allow_email_verification', true ) && $email_verification ) {
							if( $email_verified ) {
								update_user_meta( $member_id, '_wcfm_email_verified', true );
								update_user_meta( $member_id, '_wcfm_email_verified_for', $wcfm_affiliate_registration_form_data['user_email'] );
								unset( $_SESSION['wcfm_affiliate']['email_verification_code'] );
							}
						}
						update_user_meta( $member_id, 'wcemailverified', 'true' );
						
						// SMS Verification Update - 2.3.0
						if( apply_filters( 'wcfm_is_allow_sms_verification', true ) && function_exists( 'wcfm_is_store_page' ) && $sms_verification ) {
							if( $sms_verified ) {
								update_user_meta( $member_id, '_wcfm_sms_verified', true );
								update_user_meta( $member_id, '_wcfm_sms_verified_for', $wcfm_affiliate_registration_form_data['wcfmaf_static_infos']['phone'] );
								unset( $_SESSION['wcfm_affiliate']['sms_verification_code'] );
							}
						}
						
						// Free Affiliate Registration
						$affiliate_reject_rules = array();
						if( isset( $wcfm_affiliate_options['affiliate_reject_rules'] ) ) $affiliate_reject_rules = $wcfm_affiliate_options['affiliate_reject_rules'];
						$required_approval = isset( $affiliate_reject_rules['required_approval'] ) ? $affiliate_reject_rules['required_approval'] : 'no';
						
						if( $required_approval == 'no') {
							// Set Affiliate User Role
							$affiliate_user_role = apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' );
							$member_user = new WP_User(absint($member_id));
							
							if( wcfm_is_vendor( $member_id ) ) {
								$member_user->add_role( $affiliate_user_role );
							} else {
								$member_user->set_role( $affiliate_user_role );
							}
							
							update_user_meta( $member_id, 'wcfm_affiliate_application_status', 'approved' );
							
							// Sending Mail to new user
							define( 'DOING_WCFM_EMAIL', true );
							
							$mail_to = $wcfm_affiliate_registration_form_data['user_email'];
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
							$message = str_replace( '{first_name}', $member_user->display_name, $message );
							$message = str_replace( '{username}', $wcfm_affiliate_registration_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', __( 'Affiliate', 'wc-frontend-manager-affiliate' ), $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
							
							// Admin Desktop Notification
							$wcfm_messages = sprintf( __( '<b>%s</b> successfully registered as <b>Affiliate</b> to our site.', 'wc-multivendor-membership' ), '<a class="wcfm_dashboard_item_title" href="' . get_wcfm_affiliate_manage_url($member_id) . '" target="_blank">' . $member_user->display_name . '</a>' );
							$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'new_affiliate', false );
							
						} else {
							$WCFMaf->send_affiliate_approval_reminder_admin( $member_id );
						}
						
						if( apply_filters( 'wcfm_is_allow_disable_admin_bar', true ) ) {
							update_user_meta( $member_id, 'show_admin_bar_front', false );
						}
						update_user_meta( $member_id, 'wcemailverified', 'true' );	
						
						if( $member_id && !$is_update ) {
							global $current_user;
							$current_user = get_user_by( 'id', $member_id );
							wp_set_auth_cookie( $member_id, true );
						}
						
						do_action( 'wcfm_affiliate_registration', $member_id, $wcfm_affiliate_registration_form_data );
					}
					
					if(!$has_error) {
						echo '{"status": true, "message": "' . $wcfm_affiliate_registration_messages['registration_success'] . '", "redirect": "' . apply_filters( 'wcfm_affiliate_registration_thankyou_url', add_query_arg( 'afstep', 'thankyou', get_wcfm_affiliate_registration_page() ) ) . '"}';
					} else { 
					  echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['registration_failed'] . '"}'; 
					}
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_affiliate_registration_messages['no_username'] . '"}';
		}
		
		die;
	}
}