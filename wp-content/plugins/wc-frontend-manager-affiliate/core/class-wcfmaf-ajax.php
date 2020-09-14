<?php
/**
 * WCFM Affiliate plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.0
 */
 
class WCFMaf_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMaf;
		
		$this->controllers_path = $WCFMaf->plugin_path . 'controllers/';
		
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmaf_ajax_controller' ) );
		add_action( 'wp_ajax_nopriv_wcfm_ajax_controller', array( &$this, 'wcfmaf_ajax_controller' ) );
		
		// Generate Affiliate Approval Response Html
    add_action( 'wp_ajax_wcfmaf_affiliate_approval_html', array( &$this, 'wcfmaf_affiliate_approval_html' ) );
    
    // Update Affiliate Approval Response
    add_action( 'wp_ajax_wcfmaf_affiliate_approval_response_update', array( &$this, 'wcfmaf_affiliate_approval_response_update' ) );
    
    // Generate Affiliate Commission Details Response Html
    add_action( 'wp_ajax_wcfmaff_show_coomission_details', array( &$this, 'wcfmaff_show_coomission_details' ) );
		
    // Mark Affiliate Order Paid
		add_action( 'wp_ajax_mark_wcfm_affiliate_order_paid', array( &$this, 'wcfmaf_mark_affiliate_order_paid' ) );
		
		// Mark Affiliate Order Reject
		add_action( 'wp_ajax_mark_wcfm_affiliate_order_reject', array( &$this, 'wcfmaf_mark_affiliate_order_reject' ) );
		
		// Affiliate Delete
		add_action( 'wp_ajax_wcfm_affiliate_url_generate', array( &$this, 'wcfmaf_affiliate_url_generate' ) );
		
		// Affiliate disable
    add_action( 'wp_ajax_wcfm_affiliate_disable', array( &$this, 'wcfm_affiliate_disable' ) );
    
    // Affiliate disable
    add_action( 'wp_ajax_wcfm_affiliate_enable', array( &$this, 'wcfm_affiliate_enable' ) );
		
		// Affiliate Delete
		add_action( 'wp_ajax_delete_wcfm_affiliate', array( &$this, 'delete_wcfm_affiliate' ) );
		
		// Email Verification Code
		//add_action( 'wp_ajax_wcfmaf_email_verification_code', array( &$this, 'wcfmaf_email_verification_code' ) );
		//add_action( 'wp_ajax_nopriv_wcfmaf_email_verification_code', array( &$this, 'wcfmaf_email_verification_code' ) );
		
		// SMS Verification Code
		//add_action( 'wp_ajax_wcfmaf_sms_verification_code', array( &$this, 'wcfmaf_sms_verification_code' ) );
		//add_action( 'wp_ajax_nopriv_wcfmaf_sms_verification_code', array( &$this, 'wcfmaf_sms_verification_code' ) );
		
	}
	

	/**
   * WCFM Affiliate Ajax Controllers
   */
  public function wcfmaf_ajax_controller() {
  	global $WCFM, $WCFMgs;
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
	  	
				case 'wcfm-affiliate':
					include_once( $this->controllers_path . 'wcfmaf-controller-affiliate.php' );
					new WCFMaf_Affiliate_Controller();
				break;
				
				case 'wcfm-affiliate-manage':
					include_once( $this->controllers_path . 'wcfmaf-controller-affiliate-manage.php' );
					new WCFMaf_Affiliate_Manage_Controller();
				break;
				
				case 'wcfm-affiliates':
				case 'wcfm-affiliate-stats':
					include_once( $this->controllers_path . 'wcfmaf-controller-affiliate-stats.php' );
					new WCFMaf_Affiliate_Stats_Controller();
				break;
				
				case 'wcfm-affiliate-registration':
					include_once( $this->controllers_path . 'registration/wcfmaf-controller-affiliate-registration.php' );
					new WCFMaf_Affiliate_Registration_Controller();
				break;
				
			}
		}
	}
	
	/**
	 * Generate Affiliate Approval HTMl
	 */
	function wcfmaf_affiliate_approval_html() {
		global $WCFM, $WCFMaf;
		
		if( isset( $_POST['messageid'] ) && isset($_POST['member_id']) ) {
			$message_id = absint( $_POST['messageid'] );
			$member_id = absint( $_POST['member_id'] );
			
			if( $member_id && $message_id ) {
				
				$member_data = get_userdata( $member_id );
				
				$wcfmaf_registration_static_fields = wcfm_get_option( 'wcfmaf_registration_static_fields', array() );
				$wcfmaf_static_infos = (array) get_user_meta( $member_id, 'wcfmaf_static_infos', true );
				
				$wcfmaf_registration_custom_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
				$wcfmaf_custom_infos = (array) get_user_meta( $member_id, 'wcfmaf_custom_infos', true );
				
				?>
				<form id="wcfm_affiliate_approval_response_form" class="wcfm_popup_wrapper">
				  <div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Affiliate Application', 'wc-frontend-manager-affiliate' ); ?></h2></div>
					<table>
						<tbody>
						  <?php if( isset( $wcfmaf_registration_static_fields['first_name'] ) ) { ?>
								<tr>
									<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'First Name', 'wc-frontend-manager-affiliate' ); ?></td>
									<td><?php echo $member_data->first_name; ?></td>
								</tr>
							<?php } ?>
							<?php if( isset( $wcfmaf_registration_static_fields['last_name'] ) ) { ?>
								<tr>
									<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Last Name', 'wc-frontend-manager-affiliate' ); ?></td>
									<td><?php echo $member_data->last_name; ?></td>
								</tr>
							<?php } ?>
							<tr>
								<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Login', 'wc-frontend-manager-affiliate' ); ?></td>
								<td><?php echo $member_data->user_login; ?></td>
							</tr>
							<tr>
								<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Email', 'wc-frontend-manager-affiliate' ); ?></td>
								<td><?php echo $member_data->user_email; ?></td>
							</tr>
							
							<?php
							// Registration Static Field Support
							if( !empty( $wcfmaf_registration_static_fields ) ) {
								foreach( $wcfmaf_registration_static_fields as $wcfmaf_registration_static_field => $wcfmaf_registration_static_field_val ) {
									$field_value = array();
									$field_name = 'wcfmaf_static_infos[' . $wcfmaf_registration_static_field . ']';
									
									if( !empty( $wcfmaf_static_infos ) ) {
										$field_value = isset( $wcfmaf_static_infos[$wcfmaf_registration_static_field] ) ? $wcfmaf_static_infos[$wcfmaf_registration_static_field] : array();
									}
									
									switch( $wcfmaf_registration_static_field ) {
										case 'address':
											if( isset($field_value['addr_1']) ) {
												$state_code = $field_value['state'];
												$country_code = $field_value['country'];
												$state   = isset( WC()->countries->states[ $country_code ][ $state_code ] ) ? WC()->countries->states[ $country_code ][ $state_code ] : $state_code;
												$country = isset( WC()->countries->countries[ $country_code ] ) ? WC()->countries->countries[ $country_code ] : $country_code;
												
												$address = $field_value['addr_1'] . ' ' . $field_value['addr_2']. '<br/>' . $field_value['city']. ', ' . $state. '<br />' . $field_value['zip']. '<br />' . $country;
											  ?>
												<tr>
													<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Address', 'wc-frontend-manager' ); ?></td>
													<td><?php echo $address; ?></td>
												</tr>
												<?php
											}
										break;
										
										case 'phone':
											?>
											<tr>
												<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Phone', 'wc-frontend-manager' ); ?></td>
												<td><?php echo $field_value; ?></td>
											</tr>
											<?php
										break;
										
										default:
											do_action( 'wcfmaf_registration_static_field_popup_show', $member_id, $wcfmaf_registration_static_field, $field_value );
										break;
									}
								}
							}
							
							
							// Registration Custom Field Support - 1.0.5
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
									?>
									<tr>
										<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( $wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate'); ?></td>
										<td>
											<?php 
											if( $field_value && $wcfmaf_registration_custom_field['type'] == 'upload' ) {
												echo '<a class="wcfm-wp-fields-uploader wcfm_linked_attached" target="_blank" style="width: 32px; height: 32px;" href="' . $field_value . '"><span style="width: 32px; height: 32px; display: inline-block;" class="placeHolderDocs"></span></a>';
											} else {
												if( !$field_value ) $field_value = '&ndash;';
												echo $field_value;
											}
											?>
										</td>
									</tr>
									<?php
								}
							}
							?>
							<tr>
								<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Rejection Reason', 'wc-frontend-manager-affiliate' ); ?></td>
								<td>
								  <textarea id="wcfm_affiliate_rejection_reason" class="wcfm_popup_input wcfm_popup_textarea" name="wcfm_affiliate_rejection_reason" style="width: 95%;"></textarea>
								</td>
							</tr>
							<tr>
								<td class="wcfm_affiliate_approval_response_form_label wcfm_popup_label"><?php _e( 'Status Update', 'wc-frontend-manager-affiliate' ); ?></td>
								<td>
								  <label for="wcfm_affiliate_approval_response_status_approve"><input type="radio" id="wcfm_affiliate_approval_response_status_approve" name="wcfm_affiliate_approval_response_status" value="approve" checked /><?php _e( 'Approve', 'wc-frontend-manager-affiliate' ); ?></label>
								  <label for="wcfm_affiliate_approval_response_status_reject"><input type="radio" id="wcfm_affiliate_approval_response_status_reject" name="wcfm_affiliate_approval_response_status" value="reject" /><?php _e( 'Reject', 'wc-frontend-manager-affiliate' ); ?></label>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="wcfm_affiliate_approval_member_id" value="<?php echo $member_id; ?>" />
					<input type="hidden" name="wcfm_affiliate_approval_message_id" value="<?php echo $message_id; ?>" />
					<div class="wcfm-message" tabindex="-1"></div>
					<input type="button" class="wcfm_affiliate_approval_response_button wcfm_submit_button wcfm_popup_button" id="wcfm_affiliate_approval_response_button" value="<?php _e( 'Update', 'wc-frontend-manager-affiliate' ); ?>" />
				</form>
				<?php
			}
		}
		die;
	}
	
	function wcfmaf_affiliate_approval_response_update() {
		global $WCFM, $WCFMvm, $_POST, $wpdb;
		
		$wcfm_affiliate_approval_response_form_data = array();
	  parse_str($_POST['wcfm_affiliate_approval_response_form'], $wcfm_affiliate_approval_response_form_data);
		
		if( isset( $wcfm_affiliate_approval_response_form_data['wcfm_affiliate_approval_message_id'] ) && isset($wcfm_affiliate_approval_response_form_data['wcfm_affiliate_approval_member_id']) ) {
			$message_id = absint( $wcfm_affiliate_approval_response_form_data['wcfm_affiliate_approval_message_id'] );
			$member_id  = absint( $wcfm_affiliate_approval_response_form_data['wcfm_affiliate_approval_member_id'] );
			
			if( $member_id && $message_id ) {
				$member_user = new WP_User(absint($member_id));
				$approval_status = $wcfm_affiliate_approval_response_form_data['wcfm_affiliate_approval_response_status'];
				
				delete_user_meta( $member_id, 'wcfm_affiliate_application_status' );
				
				if( $approval_status == 'approve' ) {
					// Set Affiliate User Role
					$affiliate_user_role = apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' );
					if( wcfm_is_vendor( $member_id ) ) {
					  $member_user->add_role( $affiliate_user_role );
					} else {
						$member_user->set_role( $affiliate_user_role );
					}
					
					update_user_meta( $member_id, 'wcfm_affiliate_application_status', 'approved' );
					
					// Sending Mail to new user
					define( 'DOING_WCFM_EMAIL', true );
					
					$new_account_mail_subject = "{site_name}: New Account Created";
					$new_account_mail_body = __( 'Dear', 'wc-frontend-manager-affiliate' ) . ' {first_name}' .
																	 ',<br/><br/>' . 
																	 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-manager-affiliate' ) .
																	 '<br/><br/>' . 
																	 __( 'Site', 'wc-frontend-manager-affiliate' ) . ': {site_url}' . 
																	 '<br/>' .
																	 __( 'Login', 'wc-frontend-manager-affiliate' ) . ': {username}' .
																	 '<br /><br/>Thank You';
																	 
					$wcfmgs_new_account_mail_subject = wcfm_get_option( 'wcfmaf_new_account_mail_subject' );
					if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
					$wcfmgs_new_account_mail_body = wcfm_get_option( 'wcfmaf_new_account_mail_body' );
					if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;
					
					$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $new_account_mail_subject );
					$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
					$message = str_replace( '{site_url}', get_bloginfo( 'url' ), $new_account_mail_body );
					$message = str_replace( '{first_name}', $member_user->first_name, $message );
					$message = str_replace( '{username}', $member_user->user_login, $message );
					$message = str_replace( '{password}', __( 'Set by you.', 'wc-frontend-manager-affiliate' ), $message );
					$message = str_replace( '{user_role}', __( 'Affiliate', 'wc-frontend-manager-affiliate' ), $message );
					$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'New Account', 'wc-frontend-manager' ) );
					
					wp_mail( $member_user->user_email, $subject, $message );
					
					// Admin Desktop Notification
					$wcfm_messages = sprintf( __( '<b>%s</b> successfully registered as <b>Affiliate</b> to our site.', 'wc-multivendor-membership' ), '<a class="wcfm_dashboard_item_title" href="' . get_wcfm_affiliate_manage_url($member_id) . '" target="_blank">' . $member_user->first_name . '</a>' );
					$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 1, 0, $wcfm_messages, 'new_affiliate', false );
					
				} else {
					$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
					$affiliate_reject_rules = array();
					if( isset( $wcfm_affiliate_options['affiliate_reject_rules'] ) ) $affiliate_reject_rules = $wcfm_affiliate_options['affiliate_reject_rules'];
					$affiliate_reject_rule = isset( $affiliate_reject_rules['affiliate_reject_rule'] ) ? $affiliate_reject_rules['affiliate_reject_rule'] : 'same';
					$send_notification = isset( $affiliate_reject_rules['send_notification'] ) ? $affiliate_reject_rules['send_notification'] : 'yes';
					
					if( $send_notification == 'yes' ) {
						if( !defined( 'DOING_WCFM_EMAIL' ) ) 
							  define( 'DOING_WCFM_EMAIL', true );
						
						$rejection_reason = wcfm_stripe_newline( $wcfm_affiliate_approval_response_form_data['wcfm_affiliate_rejection_reason'] );
						$rejection_reason = esc_sql( $rejection_reason );
					
						$reject_notication_subject = wcfm_get_option( 'wcfm_affiliate_reject_notication_subject', '{site_name}: Affiliate Application Rejected' );
						$reject_notication_content = wcfm_get_option( 'wcfm_affiliate_reject_notication_content', '' );
						if( !$reject_notication_content ) {
							$reject_notication_content = "Hi {first_name},
																						<br /><br />
																						Sorry to inform you that, your affiliate application has been rejected. 
																						<br /><br />
																						<strong><i>{rejection_reason}</i></strong>
																						<br /><br />
																						Thank You";
						}
						
						$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $reject_notication_subject );
						$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
						$message = str_replace( '{first_name}', $member_user->first_name, $reject_notication_content );
						$message = str_replace( '{rejection_reason}', $rejection_reason, $message );
						$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Affiliate Application Rejected', 'wc-frontend-manager-affiliate' ) );
						
						wp_mail( $member_user->user_email, $subject, $message );
						
					}
					
					// Delete Affiliate Rejected User
					if( !wcfm_is_vendor( $member_id ) && apply_filters( 'wcfm_is_allow_delete_affiliate_reject_user', true ) ) {
						wp_delete_user( $member_id );
					}
				}
				
				// Vendor Approval message mark read
				$author_id = apply_filters( 'wcfm_message_author', get_current_user_id() );
				$todate = date('Y-m-d H:i:s');
				
				$wpdb->query( "DELETE FROM {$wpdb->prefix}wcfm_messages WHERE ID = {$message_id}" );
				
				echo '{"status": true, "message": "' . __( 'Affiliate Approval ststus successfully updated.', 'wc-frontend-manager-affiliate' ) . '"}';
				die;
			}
		}
		echo '{"status": false, "message": "' . __( 'Affiliate Approval ststus update failed.', 'wc-frontend-manager-affiliate' ) . '"}';
		die;
	}
	
	/**
	 * General Affiliate Commission Details Popup
	 */
	function wcfmaff_show_coomission_details() {
		global $WCFM, $WCFMaf, $wpdb;
		
		if( isset( $_POST['affiliate_id'] ) && isset($_POST['affiliate_id']) ) {
			
			$affiliate_id = absint( $_POST['affiliate_id'] );
		
			if( $affiliate_id ) {
				$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
				$sql .= " WHERE 1=1";
				$sql .= " AND ID = {$affiliate_id}";
				$affiliate_details = $wpdb->get_results( $sql );
				
				
				$WCFMaf->template->get_template( 'wcfmaf-view-affiliate-stats-details.php', array( 'affiliate_id' => $affiliate_id, 'affiliate_details' => $affiliate_details ) );
			}
		}
		die;
	}
	
	/**
	 * Mark Affiliate Order Paid
	 */
	function wcfmaf_mark_affiliate_order_paid() {
		global $WCFM, $WCFMaf, $wpdb;
		
		$affiliate_id = absint( $_POST['affiliate_id'] );
		
		if( $affiliate_id ) {
			$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
			$sql .= " WHERE 1=1";
			$sql .= " AND ID = {$affiliate_id}";
			$affiliate_details = $wpdb->get_results( $sql );
			
			if( !empty( $affiliate_details ) ) {
				foreach( $affiliate_details as $affiliate_detail ) {
					
					// Update Affiliate Order Status Update
					$wpdb->update("{$wpdb->prefix}wcfm_affiliate_orders", array('commission_status' => 'paid', 'commission_paid_date' => date('Y-m-d H:i:s', current_time( 'timestamp', 0 ))), array('ID' => $affiliate_id), array('%s', '%s'), array('%d'));
					
					// Affliate Notification
					if( $affiliate_detail->commission_type == 'vendor' ) {
						$wcfm_messages = sprintf( __( '<b>%s</b> commission paid for vendor <b>%s</b> registration.', 'wc-frontend-manager-affiliate' ), wc_price( $affiliate_detail->commission_amount ), '<span class="wcfm_dashboard_item_title">' . wcfm_get_vendor_store_name( $affiliate_detail->vendor_id ) . '</span>' );
					} else {
						$wcfm_messages = sprintf( __( '<b>%s</b> commission paid for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), wc_price( $affiliate_detail->commission_amount ), '#<span class="wcfm_dashboard_item_title">' . $affiliate_detail->order_id . '</a>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $affiliate_detail->product_id ) . '">' . get_the_title( $affiliate_detail->product_id ) . '</a>' );
					}
					$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $affiliate_detail->affiliate_id, 1, 0, $wcfm_messages, 'affiliate_commission_paid' );
				}
			}
		}
		
		die;
	}
	
	/**
	 * Mark Affiliate Order Reject
	 */
	function wcfmaf_mark_affiliate_order_reject() {
		global $WCFM, $WCFMaf, $WCFMmp, $wpdb;
		
		$affiliate_id = absint( $_POST['affiliate_id'] );
		
		if( $affiliate_id ) {
			$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
			$sql .= " WHERE 1=1";
			$sql .= " AND ID = {$affiliate_id}";
			$affiliate_details = $wpdb->get_results( $sql );
			
			if( !empty( $affiliate_details ) ) {
				foreach( $affiliate_details as $affiliate_detail ) {
					
					do_action( 'wcfmaf_before_affiliate_order_reject', $affiliate_id );
					
					// Update Affiliate Order Status Update
					$wpdb->update("{$wpdb->prefix}wcfm_affiliate_orders", array( 'is_trashed' => 1 ), array('ID' => $affiliate_id), array('%d'), array('%d') );
					
					$WCFMaf->wcfmaf_vendor_commission_reset_on_affiliate_order_delete( $affiliate_id );
					
					do_action( 'wcfmaf_after_affiliate_order_reject', $affiliate_id );
				}
			}
		}
		
		die;
	}
	
	public function wcfmaf_affiliate_url_generate() {
		global $WCFM, $WCFMu;
		
		$wcfm_affiliate_url_form_data = array();
	  parse_str($_POST['wcfm_affiliate_url_form'], $wcfm_affiliate_url_form_data);
	  
	  $affiliate_id = $wcfm_affiliate_url_form_data['affiliate_user'];
		$normal_url   = $wcfm_affiliate_url_form_data['normal_url'];	
		
		$generated_url = wcfm_get_affiliate_url( $affiliate_id, $normal_url );
		
		if( $generated_url ) {
			echo '{"status": true, "generated_url": "' . $generated_url . '"}';
		} else {
			echo '{"status": false, "message": "' . __( 'Invalid URL!', 'wc-frontend-manager-affiliate' ) . '"}';
		}
		die;
	}
	
	/**
	 * Affiliate acount disable
	 */
	function wcfm_affiliate_disable() {
		global $WCFM, $_POST, $wpdb;
		
		if( isset( $_POST['memberid'] ) ) {
			$member_id = absint( $_POST['memberid'] );
			//$member_user = new WP_User( $member_id );
			//$vendor_store = wcfm_get_vendor_store( $member_id );
			
			//$member_user->set_role('disable_vendor');
			
			update_user_meta( $member_id, '_disable_affiliate', true );
			
			// Affiliate Notification
			$wcfm_messages = __( 'Your account has been disabled.', 'wc-frontend-manager-affiliate' );
			$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $member_id, 1, 0, $wcfm_messages, 'affiliate-disable' );
			
			do_action( 'wcfm_affiliate_disable_after', $member_id );
				
			echo '{"status": true, "message": "' . __( 'Affiliate successfully disabled.', 'wc-frontend-manager-affiliate' ) . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Affiliate can not be disabled right now, please try after sometime.', 'wc-frontend-manager-affiliate' ) . '"}';
		die;
	}
	
	/**
	 * Affiliate acount enable
	 */
	function wcfm_affiliate_enable() {
		global $WCFM, $_POST, $wpdb;
		
		if( isset( $_POST['memberid'] ) ) {
			$member_id = absint( $_POST['memberid'] );
			//$member_user = new WP_User( $member_id );
			//$vendor_store = wcfm_get_vendor_store( $member_id );
			
			delete_user_meta( $member_id, '_disable_affiliate' );
			
			// Affiliate Notification
			$wcfm_messages = __( 'Your account has been enabled.', 'wc-frontend-manager-affiliate' );
			$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $member_id, 1, 0, $wcfm_messages, 'affiliate-enable' );
			
			do_action( 'wcfm_affiliate_enable_after', $member_id );
				
			echo '{"status": true, "message": "' . __( 'Affiliate successfully enabled.', 'wc-frontend-manager-affiliate' ) . '"}';
			die;
		}
		echo '{"status": false, "message": "' . __( 'Affiliate can not be enabled right now, please try after sometime.', 'wc-frontend-manager-affiliate' ) . '"}';
		die;
	}
	
	/**
   * Handle Affiliate Delete
   */
  public function delete_wcfm_affiliate() {
  	global $WCFM, $WCFMu;
  	
  	$affiliateid = $_POST['affiliateid'];
		
		if($affiliateid) {
			if( wcfm_is_vendor( $affiliateid ) ) {
				$affiliate_user_role = apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' );
				$member_user = new WP_User(absint($affiliateid));
				$member_user->remove_role( $affiliate_user_role );
				echo 'success';
			} elseif(wp_delete_user($affiliateid)) {
				echo 'success';
			}
			die;
		}
  }
  
  /**
   * WCfM Registration email verification code send
   */
  function wcfmaf_email_verification_code() {
  	global $WCFM, $WCFMvm, $_SESSION;
  	
  	if ( empty( $_POST['user_email'] ) || ! is_email( $_POST['user_email'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please provide a valid email address.', 'woocommerce' ) . '"}';
			die;
		}
  	
  	$user_email = $_POST['user_email'];
		
		if( $user_email ) {
			if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['email_verification_code'] ) ) {
				$verification_code = $_SESSION['wcfm_membership']['email_verification_code'];
			} else {
				$verification_code = rand( 100000, 999999 );
			}
			// Session store
			$_SESSION['wcfm_membership']['email_verification_code'] = $verification_code;
			$_SESSION['wcfm_membership']['email_verification_for'] = $user_email;
			
			// Sending verification code in email
			if( !defined( 'DOING_WCFM_EMAIL' ) ) 
			  define( 'DOING_WCFM_EMAIL', true );
			$verification_mail_subject = "{site_name}: " . __( "Email Verification Code", "wc-frontend-manager" ) . " - " . $verification_code;
			$verification_mail_body = __( 'Hi', 'wc-frontend-manager-affiliate' ) .
																	 ',<br/><br/>' . 
																	 sprintf( __( 'Here is your email verification code - <b>%s</b>', 'wc-frontend-manager-affiliate' ), '{verification_code}' ) .
																	 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager-affiliate' );
													 
			$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $verification_mail_subject );
			$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
			$subject = str_replace( '{verification_code}', $verification_code, $subject );
			$message = str_replace( '{verification_code}', $verification_code, $verification_mail_body );
			$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Email Verification', 'wc-frontend-manager-affiliate' ) );
			
			wp_mail( $user_email, $subject, $message );
			
			echo '{"status": true, "message": "' . sprintf( __( 'Verification code sent to your email: %s.', 'wc-frontend-manager-affiliate' ), $user_email ) . '"}';
		} else {
			echo '{"status": false, "message": "' . __( 'Email verification not working right now, please try after sometime.', 'wc-frontend-manager-affiliate' ) . '"}';
		}
		die;
  }
  
  /**
   * WCfM Affiliate Registration SMS - OTP verification code send
   */
  function wcfmaf_sms_verification_code() {
  	global $WCFM, $WCFMvm, $WCFMmp, $_SESSION;
  	
  	$has_error = false;
  	if( !WCFMmp_Dependencies::wcfm_sms_alert_plugin_active_check() && !WCFMmp_Dependencies::wcfm_twilio_plugin_active_check() && !WCFMmp_Dependencies::wcfm_msg91_plugin_active_check() ) {
  		$has_error = true;
  	}
  	
  	if (  ! isset( $_POST['user_phone'] ) || empty( $_POST['user_phone'] ) ) {
			echo '{"status": false, "message": "' . __( 'Please provide a valid phone number.', 'woocommerce' ) . '"}';
			die;
		}
  	
  	$user_phone = $_POST['user_phone'];
		
		if( $user_phone ) {
			if( isset( $_SESSION['wcfm_membership'] ) && isset( $_SESSION['wcfm_membership']['sms_verification_code'] ) ) {
				$verification_code = $_SESSION['wcfm_membership']['sms_verification_code'];
			} else {
				$verification_code = rand( 1000, 9999 );
			}
			// Session store
			$_SESSION['wcfm_membership']['sms_verification_code'] = $verification_code;
			$_SESSION['wcfm_membership']['sms_verification_for'] = $user_phone;
			
			//$sms_messages = $verification_code . ' - ' . __( "verification code (OPT) for registration at ", "wc-frontend-manager" ) . get_bloginfo( 'name' );
			$sms_messages = __( "Your verification code is", "wc-frontend-manager-affiliate" ) . ' ' . $verification_code;
			
			if( WCFMmp_Dependencies::wcfm_sms_alert_plugin_active_check() ) {
				if( class_exists( 'SmsAlertcURLOTP' ) ) {
				
					$sms_messages  = esc_sql( $sms_messages );
					$sms_messages  = strip_tags( $sms_messages );
					
					$sms_data = array( 'number' => '' );
					$sms_data['number']   = $user_phone;
					
					$sms_data['sms_body'] = $sms_messages;
					
					wcfm_log( "OPT:: " . $sms_data['number'] . ": " . $sms_messages );
					
					if( !empty( $sms_data['number'] ) ) {
						$admin_response       = SmsAlertcURLOTP::sendsms( $sms_data );
						$response             = json_decode($admin_response,true);
						if( $response['status'] == 'success' ) {
							wcfm_log( "OPT:: " . $sms_data['number'] . ": " . __( 'SMS Sent Successfully.', 'smsalert' ) );
						} else {
							if( is_array( $response['description'] ) && array_key_exists( 'desc', $response['description'] ) ) {
								wcfm_log( "OPT:: " . $sms_data['number'] . ": " . __($response['description']['desc'], 'smsalert' ) );
								echo '{"status": false, "message": "' . __($response['description']['desc'], 'smsalert' ) . '"}';
								die;
							} else {
								wcfm_log( "OPT:: " . $sms_data['number'] . ": " . __($response['description'], 'smsalert' ) );
								echo '{"status": false, "message": "' . __($response['description'], 'smsalert' ) . '"}';
								die;
							}
						}
					}
				} else {
					$has_error = true;
				}
			}
			
			if( WCFMmp_Dependencies::wcfm_twilio_plugin_active_check() ) {
				if( !class_exists('WCFMmp_Twilio_SMS_Notification') ) {
					include_once( $WCFMmp->plugin_path . 'includes/sms-gateways/class-wcfmmp-twilio-sms-notification.php' );
				}
				
				$twillio_notification = new WCFMmp_Twilio_SMS_Notification( 9999 );
				
				$sms_messages  = esc_sql( $sms_messages );
				$sms_messages  = strip_tags( $sms_messages );
				
				
				$recipient   = $user_phone;
				$country_obj = new WC_Countries();
				if( $recipient ) {
					wcfm_log( "OPT:: " . $recipient . ": " . $sms_messages );
					$twillio_notification->send_sms( $recipient, $sms_messages, false, $country_obj->get_base_country() );
				}
			}
			
			if( WCFMmp_Dependencies::wcfm_msg91_plugin_active_check() ) {
				$sms_messages  = esc_sql( $sms_messages );
				$sms_messages  = strip_tags( $sms_messages );
				
				$recipient   = $user_phone;
				if( $recipient ) {
					wcfm_log( "OPT:: " . $recipient . ": " . $sms_messages );
					$adminfile = @wp_remote_get("https://control.msg91.com/api/sendhttp.php?authkey=". get_option('msg_api_key') ."&mobiles=". $recipient ."&message=".urlencode($sms_messages)."&sender=". get_option('sms_sender_id') ."&route=4&country=0")['body'];
					$output = fgets($adminfile);
					fclose($adminfile);
				}
			}
			
			echo '{"status": true, "message": "' . sprintf( __( 'Verification code sent to your phone: %s.', 'wc-frontend-manager-affiliate' ), $user_phone ) . '"}';
		} else {
			$has_error = true;
		}
		
		if( $has_error ) {
			echo '{"status": false, "message": "' . __( 'Phone verification not working right now, please try after sometime.', 'wc-frontend-manager-affiliate' ) . '"}';
		}
		die;
  }
}