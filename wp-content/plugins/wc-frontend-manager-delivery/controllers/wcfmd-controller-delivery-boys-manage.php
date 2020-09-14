<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Delivery Boys Manage Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmd/controllers
 * @version   1.0.0
 */

class WCFMd_Delivery_Boys_Manage_Controller {
	
	public function __construct() {
		global $WCFM, $WCFMd;
		
		$this->processing();
	}
	
	public function processing() {
		global $WCFM, $WCFMu, $wpdb, $wcfm_delivery_boy_manager_form_data;
		
		$wcfm_delivery_boy_manager_form_data = array();
	  parse_str($_POST['wcfm_delivery_boys_manage_form'], $wcfm_delivery_boy_manager_form_data);
	  
	  $wcfm_delivery_boy_messages = get_wcfmd_delivery_boys_manage_messages();
	  $has_error = false;
	  
	  if(isset($wcfm_delivery_boy_manager_form_data['user_name']) && !empty($wcfm_delivery_boy_manager_form_data['user_name'])) {
	  	if(isset($wcfm_delivery_boy_manager_form_data['user_email']) && !empty($wcfm_delivery_boy_manager_form_data['user_email'])) {
	  		
	  		if ( ! is_email( $wcfm_delivery_boy_manager_form_data['user_email'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
					die;
				}
				
				if ( ! validate_username( $wcfm_delivery_boy_manager_form_data['user_name'] ) ) {
					echo '{"status": false, "message": "' . __( 'Please enter a valid account username.', 'woocommerce' ) . '"}';
					die;
				}
				
				$delivery_boy_id = 0;
				$is_update = false;
				if( isset($wcfm_delivery_boy_manager_form_data['delivery_boy_id']) && $wcfm_delivery_boy_manager_form_data['delivery_boy_id'] != 0 ) {
					$delivery_boy_id = absint( $wcfm_delivery_boy_manager_form_data['delivery_boy_id'] );
					$is_update = true;
				} else {
					if( username_exists( $wcfm_delivery_boy_manager_form_data['user_name'] ) ) {
						$has_error = true;
						echo '{"status": false, "message": "' . $wcfm_delivery_boy_messages['username_exists'] . '"}';
					} else {
						if( email_exists( $wcfm_delivery_boy_manager_form_data['user_email'] ) == false ) {
							
						} else {
							$has_error = true;
							echo '{"status": false, "message": "' . $wcfm_delivery_boy_messages['email_exists'] . '"}';
						}
					}
				}
				
				$password = wp_generate_password( $length = 12, $include_standard_special_chars=false );
				if( !$has_error ) {
					$delivery_boy_user_role = apply_filters( 'wcfm_delivery_boy_user_role', 'wcfm_delivery_boy' );
					
					$user_data = array( 'user_login'     => $wcfm_delivery_boy_manager_form_data['user_name'],
															'user_email'     => $wcfm_delivery_boy_manager_form_data['user_email'],
															'display_name'   => $wcfm_delivery_boy_manager_form_data['user_name'],
															'nickname'       => $wcfm_delivery_boy_manager_form_data['user_name'],
															'first_name'     => $wcfm_delivery_boy_manager_form_data['first_name'],
															'last_name'      => $wcfm_delivery_boy_manager_form_data['last_name'],
															'user_pass'      => $password,
															'role'           => $delivery_boy_user_role,
															'ID'             => $delivery_boy_id
															);
					if( $is_update ) {
						unset( $user_data['user_login'] );
						unset( $user_data['display_name'] );
						unset( $user_data['nickname'] );
						unset( $user_data['user_pass'] );
						unset( $user_data['role'] );
						$delivery_boy_id = wp_update_user( $user_data ) ;
					} else {
						$delivery_boy_id = wp_insert_user( $user_data ) ;
						
						// Delivery Person Real Author
						update_user_meta( $delivery_boy_id, '_wcfm_delivery_boy_author', get_current_user_id() );
					}
						
					if( !$delivery_boy_id ) {
						$has_error = true;
					} else {
						
						if( !$is_update ) {
							// Sending Mail to new user
							define( 'DOING_WCFM_EMAIL', true );
							
							$mail_to = $wcfm_delivery_boy_manager_form_data['user_email'];
							$new_account_mail_subject = "{site_name}: New Account Created";
							$new_account_mail_body = __( 'Dear', 'wc-frontend-manager-delivery' ) . ' {first_name}' .
																			 ',<br/><br/>' . 
																			 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-manager-delivery' ) .
																			 '<br/><br/>' . 
																			 __( 'Site', 'wc-frontend-manager-delivery' ) . ': {site_url}' . 
																			 '<br/>' .
																			 __( 'Login', 'wc-frontend-manager-delivery' ) . ': {username}' .
																			 '<br/>' . 
																			 __( 'Password', 'wc-frontend-manager-delivery' ) . ': {password}' .
																			 '<br /><br/>Thank You';
																			 
							$wcfmgs_new_account_mail_subject = wcfm_get_option( 'wcfmd_new_account_mail_subject' );
							if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
							$wcfmgs_new_account_mail_body = wcfm_get_option( 'wcfmd_new_account_mail_body' );
							if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;
							
							$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
							$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
							$message = str_replace( '{site_url}', get_bloginfo( 'url' ), $new_account_mail_body );
							$message = str_replace( '{first_name}', $wcfm_delivery_boy_manager_form_data['first_name'], $message );
							$message = str_replace( '{username}', $wcfm_delivery_boy_manager_form_data['user_name'], $message );
							$message = str_replace( '{password}', $password, $message );
							$message = str_replace( '{user_role}', __( 'Delivery Boy', 'wc-frontend-manager-delivery' ), $message );
							$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
							
							wp_mail( $mail_to, $subject, $message );
							
							// Desktop notification message for New delivery Boy
							if( wcfm_is_vendor() ) {
								$author_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
								$author_is_admin = 0;
								$author_is_vendor = 1;
								$message_to = 0;
								$wcfm_messages = sprintf( __( 'A new delivery boy <b>%s</b> added to the store by <b>%s</b>', 'wc-frontend-manager-delivery' ), $wcfm_delivery_boy_manager_form_data['first_name'] . ' ' . $wcfm_delivery_boy_manager_form_data['last_name'], get_user_by( 'id', $author_id )->display_name );
								$WCFM->wcfm_notification->wcfm_send_direct_message( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, 'new_delivery_boy' );
							}
						}
						
						// Store Phone
						if( isset( $wcfm_delivery_boy_manager_form_data['user_phone'] ) ) {
							update_user_meta( $delivery_boy_id, 'billing_phone', $wcfm_delivery_boy_manager_form_data['user_phone'] );
						} else {
							update_user_meta( $delivery_boy_id, 'billing_phone', '' );
						}
						
						// Staff Vendor
						if( wcfm_is_marketplace() && isset( $wcfm_delivery_boy_manager_form_data['wcfm_vendor'] ) && !empty( $wcfm_delivery_boy_manager_form_data['wcfm_vendor'] ) ) {
							update_user_meta( $delivery_boy_id, '_wcfm_vendor', $wcfm_delivery_boy_manager_form_data['wcfm_vendor'] );
						} else {
							delete_user_meta( $delivery_boy_id, '_wcfm_vendor' );
						}
						
						// Update User capability
						if( isset( $wcfm_delivery_boy_manager_form_data['has_custom_capability'] ) ) {
							update_user_meta( $delivery_boy_id, '_wcfm_user_has_custom_capability', 'yes' );
							
							if( isset( $wcfm_delivery_boy_manager_form_data['wcfmgs_capability_manager_options'] ) ) {
								update_user_meta( $delivery_boy_id, '_wcfm_user_capability_options', $wcfm_delivery_boy_manager_form_data['wcfmgs_capability_manager_options'] );
							} else {
								delete_user_meta( $delivery_boy_id, '_wcfm_user_capability_options' );
							}
						} else {
							update_user_meta( $delivery_boy_id, '_wcfm_user_has_custom_capability', 'no' );
							delete_user_meta( $delivery_boy_id, '_wcfm_user_capability_options' );
						}
						
						// Update general restriction
						update_user_meta( $delivery_boy_id, 'show_admin_bar_front', false );
						update_user_meta( $delivery_boy_id, 'wcemailverified', 'true' );	
							
						do_action( 'wcfm_delivery_boys_manage', $delivery_boy_id );
					}
							
					if(!$has_error) { echo '{"status": true, "message": "' . $wcfm_delivery_boy_messages['delivery_boy_saved'] . '", "redirect": "' . apply_filters( 'wcfm_delivery_boy_manage_redirect', get_wcfm_delivery_boys_manage_url($delivery_boy_id), $delivery_boy_id ) . '"}'; }
					else { echo '{"status": false, "message": "' . $wcfm_delivery_boy_messages['delivery_boy_failed'] . '"}'; }
				}
			} else {
				echo '{"status": false, "message": "' . $wcfm_delivery_boy_messages['no_email'] . '"}';
			}
	  	
	  } else {
			echo '{"status": false, "message": "' . $wcfm_delivery_boy_messages['no_username'] . '"}';
		}
		
		die;
	}
}