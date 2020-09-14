<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Shop Managers Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Managers_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMu;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_manager_manager_form_data;
		
		$wcfm_manager_manager_form_data = array();
	  parse_str($_POST['wcfm_managers_manage_form'], $wcfm_manager_manager_form_data);
	  
	  $wcfm_manager_messages = get_wcfmgs_managers_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_manager_manager_form_data['user_name']) && !empty($wcfm_manager_manager_form_data['user_name'])) {
	  	if(isset($wcfm_manager_manager_form_data['user_email']) && !empty($wcfm_manager_manager_form_data['user_email'])) {
	  		
	  		if ( ! is_email( $wcfm_manager_manager_form_data['user_email'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
					die;
				}
				
				if ( ! validate_username( $wcfm_manager_manager_form_data['user_name'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
					die;
				}
				
				$manager_id = 0;
				$is_update = false;
				if( isset($wcfm_manager_manager_form_data['manager_id']) && $wcfm_manager_manager_form_data['manager_id'] != 0 ) {
					$manager_id = absint( $wcfm_manager_manager_form_data['manager_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_manager_manager_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_manager_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_manager_manager_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_manager_messages['email_exists'] . '"}';
						}
					}
				}
				
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				if( !$has_error ) {
					$user_data = array( 'user_login'     => $wcfm_manager_manager_form_data['user_name'],
															'user_email'     => $wcfm_manager_manager_form_data['user_email'],
															'display_name'   => $wcfm_manager_manager_form_data['user_name'],
															'nickname'       => $wcfm_manager_manager_form_data['user_name'],
															'first_name'     => $wcfm_manager_manager_form_data['first_name'],
															'last_name'      => $wcfm_manager_manager_form_data['last_name'],
															'user_pass'      => $password,
															'role'           => 'shop_manager',
															'ID'             => $manager_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						$manager_id = wp_update_user( $user_data );
					} else {
						$manager_id = wp_insert_user( $user_data );
						
						// Manager Real Author
						update_user_meta( $manager_id, '_wcfm_manager_author', get_current_user_id() );
					}
						
					if( !$manager_id ) {
						$has_error = true;
					} else {
						if( !$is_update ) {
							define( 'DOING_WCFM_EMAIL', true );
							
							// Sending Mail to new user
							$mail_to = $wcfm_manager_manager_form_data['user_email'];
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
							$message = str_replace( '{first_name}', $wcfm_manager_manager_form_data['first_name'], $message );
							$message = str_replace( '{username}', $wcfm_manager_manager_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', __( 'Shop Manager', 'wc-frontend-manager-groups-staffs' ), $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
						}
						
						// Update User capability
						if( isset( $wcfm_manager_manager_form_data['has_custom_capability'] ) ) {
							update_user_meta( $manager_id, '_wcfm_user_has_custom_capability', 'yes' );
							
							if( isset( $wcfm_manager_manager_form_data['wcfmgs_capability_manager_options'] ) ) {
								update_user_meta( $manager_id, '_wcfm_user_capability_options', $wcfm_manager_manager_form_data['wcfmgs_capability_manager_options'] );
							} else {
								delete_user_meta( $manager_id, '_wcfm_user_capability_options' );
							}
						} else {
							update_user_meta( $manager_id, '_wcfm_user_has_custom_capability', 'no' );
							delete_user_meta( $manager_id, '_wcfm_user_capability_options' );
						}
						
						update_user_meta( $manager_id, 'show_admin_bar_front', false );
						update_user_meta( $manager_id, 'wcemailverified', 'true' );	
						
						do_action( 'wcfm_managers_manage', $manager_id );
					}
					
					if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_manager_messages['manager_saved'] . '", "redirect": "' . apply_filters( 'wcfm_manager_manage_redirect', get_wcfm_shop_managers_manage_url($manager_id), $manager_id ) . '"}'; }
					else { echo '{"status": false, "message": "' . $wcfm_manager_messages['manager_failed'] . '"}'; }
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_manager_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_manager_messages['no_username'] . '"}';
		}
		
		die;
	}
}