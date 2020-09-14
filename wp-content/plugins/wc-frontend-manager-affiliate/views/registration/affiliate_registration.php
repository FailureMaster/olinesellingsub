<?php
/**
 * WCFM plugin view
 *
 * WCFM Affiliate Template
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/templates
 * @version   1.1.0
 */

global $WCFM, $WCFMaf;

$user_id = 0;
$user_name = '';
$user_email = '';
$first_name = '';
$last_name = '';
$wcfmaf_static_infos = array();
$wcfmaf_custom_infos = array();

$email_verification = '';
$sms_verification   = '';

if( is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$current_user = get_userdata( $user_id );
	// Fetching User Data
	if( $current_user && !empty( $current_user ) ) {
		$user_name  = $current_user->user_login;
		$user_email = $current_user->user_email;
		$first_name = $current_user->first_name;
		$last_name  = $current_user->last_name;
		$store_name = get_user_meta( $user_id, 'store_name', true );
		$wcfmaf_static_infos = get_user_meta( $user_id, 'wcfmaf_static_infos', true );
		$wcfmaf_custom_infos = (array) get_user_meta( $user_id, 'wcfmaf_custom_infos', true );
	}
}

$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
$affiliate_type_settings = array();
if( isset( $wcfm_affiliate_options['affiliate_type_settings'] ) ) $affiliate_type_settings = $wcfm_affiliate_options['affiliate_type_settings'];

if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
	$email_verification = isset( $affiliate_type_settings['email_verification'] ) ? 'yes' : '';
}

if( apply_filters( 'wcfm_is_allow_sms_verification', true ) && function_exists( 'wcfm_is_store_page' ) ) {
	$sms_verification = isset( $affiliate_type_settings['sms_verification'] ) ? 'yes' : '';
}

$wcfmaf_registration_static_fields = wcfm_get_option( 'wcfmaf_registration_static_fields', array() );

$wcfmaf_registration_custom_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );

?>

<div id="wcfm_affiliate_container">
  <form id="wcfm_affiliate_registration_form" class="wcfm">
		<div class="wcfm-container">
	    <div id="wcfm_affiliate_registration_form_expander" class="wcfm-content">
				<?php
				do_action( 'begin_wcfm_affiliate_registration_form' );
				 if( $user_id ) {
				 	 $registratio_user_fields = array( 
																						"user_name" => array( 'label' => __('Username', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ),
																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'attributes' => array( 'readonly' => true ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email ),
																						"member_id" => array( 'type' => 'hidden', 'value' => $user_id )
																						);
					} else {
						$registratio_user_fields = array( 
																							"user_name" => array( 'label' => __('Username', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ),
																							"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email )
																							);
					}
					
					$is_user_name = isset( $wcfmaf_registration_static_fields['user_name'] ) ? 'yes' : '';
					if( !$is_user_name ) unset( $registratio_user_fields['user_name'] );
					
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_registration_fields_username', $registratio_user_fields ) );
					
					if( apply_filters( 'wcfm_is_allow_email_verification', true ) ) {
						if( $email_verification ) {
							$email_verified = false;
							if( $user_id ) {
								$email_verified = get_user_meta( $user_id, '_wcfm_email_verified', true );
								$wcfm_email_verified_for = get_user_meta( $user_id, '_wcfm_email_verified_for', true );
								if( $user_email != $wcfm_email_verified_for ) $email_verified = false;
							}
							
							if( $user_id && $email_verified ) {
								?>
								<div class="wcfm_email_verified">
									<span class="wcfmfa fa-envelope wcfm_email_verified_icon">
										<span class="wcfm_email_verified_text"><?php _e( 'Email already verified', 'wc-frontend-manager-affiliate' ); ?></span>
										<input type="hidden" name="email_verified" value="true" />
									</span>
								</div>
								<div class="wcfm_clearfix"></div>
								<?php
							} else {
								?>
								<div class="wcfm_email_verified">
									<input type="number" name="wcfm_email_verified_input" data-required="1" data-required_message="<?php _e( 'Email Verification Code: ', 'wc-frontend-manager-affiliate' ) . _e( 'This field is required.', 'wc-frontend-manager' ); ?>" class="wcfm-text wcfm_email_verified_input" placeholder="<?php _e( 'Verification Code', 'wc-frontend-manager-affiliate' ); ?>" value="" />
									<input type="button" name="wcfm_email_verified_button" class="wcfm-text wcfm_submit_button wcfm_email_verified_button" value="<?php _e( 'Re-send Code', 'wc-frontend-manager-affiliate' ); ?>" />
									<div class="wcfm_clearfix"></div>
								</div>
								<div class="wcfm-message22 email_verification_message" tabindex="-1"></div>
								<div class="wcfm_clearfix"></div>
								<?php
							}
						}
					}
					
					if( apply_filters( 'wcfm_is_allow_sms_verification', true ) ) {
						if( $sms_verification ) {
							$sms_verified = false;
							$user_phone   = '';
							if( $user_id ) {
								$user_phone   = get_user_meta( $user_id, 'billing_phone', true );
								$sms_verified = get_user_meta( $user_id, '_wcfm_sms_verified', true );
								$wcfm_sms_verified_for = get_user_meta( $user_id, '_wcfm_sms_verified_for', true );
								if( $user_phone != $wcfm_sms_verified_for ) $sms_verified = false;
							}
							
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_registration_fields_phone', array(
																																																														"user_phone" => array('label' => __('Phone', 'wc-frontend-manager') , 'type' => 'text', 'name' => 'wcfmaf_static_infos[phone]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $user_phone ),
																																																														) ) );
							
							if( $user_id && $sms_verified ) {
								?>
								<div class="wcfm_sms_verified">
									<span class="wcfmfa fa-phone wcfm_sms_verified_icon">
										<span class="wcfm_sms_verified_text"><?php _e( 'Phone already verified', 'wc-frontend-manager-affiliate' ); ?></span>
										<input type="hidden" name="sms_verified" value="true" />
									</span>
								</div>
								<div class="wcfm_clearfix"></div>
								<?php
							} else {
								?>
								<div class="wcfm_sms_verified">
									<input type="number" name="wcfm_sms_verified_input" data-required="1" data-required_message="<?php _e( 'Phone Verification Code: ', 'wc-frontend-manager-affiliate' ) . _e( 'This field is required.', 'wc-frontend-manager' ); ?>" class="wcfm-text wcfm_sms_verified_input" placeholder="<?php _e( 'OTP', 'wc-frontend-manager-affiliate' ); ?>" value="" />
									<input type="button" name="wcfm_sms_verified_button" class="wcfm-text wcfm_submit_button wcfm_sms_verified_button" value="<?php _e( 'Re-send Code', 'wc-frontend-manager-affiliate' ); ?>" />
									<div class="wcfm_clearfix"></div>
								</div>
								<div class="wcfm-message22 sms_verification_message" tabindex="-1"></div>
								<div class="wcfm_clearfix"></div>
								<?php
							}
						}
					}
					
					$is_first_name = isset( $wcfmaf_registration_static_fields['first_name'] ) ? 'yes' : '';
					$is_last_name = isset( $wcfmaf_registration_static_fields['last_name'] ) ? 'yes' : '';
					
					$registration_name_fields = apply_filters( 'wcfm_affiliate_registration_fields', array(  
																																									"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name ),
																																									"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name ),
																																	   ) );
					
					if( !$is_first_name ) unset( $registration_name_fields['first_name'] );
					if( !$is_last_name ) unset( $registration_name_fields['last_name'] );
					
					$WCFM->wcfm_fields->wcfm_generate_form_field( $registration_name_fields );
					
					// Registration Static Field Support - 1.1.0
					$terms = '';
					$terms_page = '';
					if( !empty( $wcfmaf_registration_static_fields ) ) {
						foreach( $wcfmaf_registration_static_fields as $wcfmaf_registration_static_field => $wcfmaf_registration_static_field_val ) {
							$field_value = array();
							$field_name = 'wcfmaf_static_infos[' . $wcfmaf_registration_static_field . ']';
							
							if( $wcfmaf_static_infos && is_array( $wcfmaf_static_infos ) && !empty( $wcfmaf_static_infos ) && !array_filter( $wcfmaf_static_infos ) ) {
								$field_value = isset( $wcfmaf_static_infos[$wcfmaf_registration_static_field] ) ? $wcfmaf_static_infos[$wcfmaf_registration_static_field] : array();
							} elseif( $user_id ) {
								$billing_address_fields = array( 	
																						'billing_address_1'  => 'addr_1',
																						'billing_address_2'  => 'addr_2',
																						'billing_country'    => 'country',
																						'billing_city'       => 'city',
																						'billing_state'      => 'state',
																						'billing_postcode'   => 'zip',
																					);
			
								foreach( $billing_address_fields as $billing_address_field_key => $billing_address_field ) {
									$field_value[$billing_address_field] = get_user_meta( $user_id, $billing_address_field_key, true );
								}
							}
							
							switch( $wcfmaf_registration_static_field ) {
							  case 'address':
							  	
							  	// GEO Locate Support
							  	if( is_user_logged_in() && ( !isset( $field_value['country'] ) || ( isset( $field_value['country'] ) && empty( $field_value['country'] ) ) ) ) {
										$user_location = get_user_meta( $user_id, 'wcfm_user_location', true );
										if( $user_location ) {
											$field_value['country'] = $user_location['country'];
											$field_value['state']   = $user_location['state'];
											$field_value['city']    = $user_location['city'];
										}
									}
											
									if( apply_filters( 'wcfm_is_allow_wc_geolocate', true ) && class_exists( 'WC_Geolocation' ) && ( !isset( $field_value['country'] ) || ( isset( $field_value['country'] ) && empty( $field_value['country'] ) ) ) ) {
										$user_location          = WC_Geolocation::geolocate_ip();
										$field_value['country'] = $user_location['country'];
										$field_value['state']   = $user_location['state'];
									}
							  	
								  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_registration_fields_address', array(
																																			"addr_1" => array('label' => __('Address 1', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[addr_1]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['addr_1']) ? $field_value['addr_1'] : '' ),
																																			"addr_2" => array('label' => __('Address 2', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[addr_2]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['addr_2']) ? $field_value['addr_2'] : '' ),
																																			"country" => array('label' => __('Country', 'wc-frontend-manager') , 'type' => 'country', 'name' => $field_name . '[country]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-select wcfm_ele wcfmaf_country_to_select', 'label_class' => 'wcfm_title wcfm_ele', 'attributes' => array( 'style' => 'width: 60%;' ), 'value' => isset($field_value['country']) ? $field_value['country'] : '' ),
																																			"city" => array('label' => __('City/Town', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[city]', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['city']) ? $field_value['city'] : '' ),
																																			"state" => array('label' => __('State/County', 'wc-frontend-manager') , 'type' => 'select', 'name' => $field_name . '[state]', 'class' => 'wcfm-select wcfm_ele wcfmaf_state_to_select', 'label_class' => 'wcfm_title wcfm_ele', 'options' => isset($field_value['state']) ? array($field_value['state'] => $field_value['state']) : array(), 'value' => isset($field_value['state']) ? $field_value['state'] : '' ),
																																			"zip" => array('label' => __('Postcode/Zip', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name . '[zip]', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => isset($field_value['zip']) ? $field_value['zip'] : '' ),
																																			) ) );
								break;
								
								case 'phone':
									if( !apply_filters( 'wcfm_is_allow_sms_verification', true ) || !$sms_verification ) {
										if( is_array( $field_value ) ) $field_value = '';
										if( !$field_value && $user_id ) {
											$field_value = get_user_meta( $user_id, 'billing_phone', true );
										}
										$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_registration_fields_phone', array(
																																				"phone" => array('label' => __('Phone', 'wc-frontend-manager') , 'type' => 'text', 'name' => $field_name, 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $field_value ),
																																				) ) );
									}
								break;
								
								case 'terms':
									$terms = 'active';
								break;
								
								case 'terms_page':
									$terms_page = $wcfmaf_registration_static_field_val;
								break;
								
								default:
									do_action( 'wcfmaf_registration_static_field_show', $wcfmaf_registration_static_field, $field_name, $field_value );
								break;
							}
						}
					}
					
					
					// Registration Custom Field Support - 1.1.0
					if( !empty( $wcfmaf_registration_custom_fields ) ) {
						foreach( $wcfmaf_registration_custom_fields as $wcfmaf_registration_custom_field ) {
							if( !isset( $wcfmaf_registration_custom_field['enable'] ) ) continue;
							if( !$wcfmaf_registration_custom_field['label'] ) continue;
							$field_value = '';
							$wcfmaf_registration_custom_field['name'] = sanitize_title( $wcfmaf_registration_custom_field['label'] );
							$field_name = 'wcfmaf_custom_infos[' . $wcfmaf_registration_custom_field['name'] . ']';
							$field_id   = md5( $field_name );
						
							if( !empty( $wcfmaf_custom_infos ) ) {
								if( $wcfmaf_registration_custom_field['type'] == 'checkbox' ) {
									$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] : 'no';
								} else {
									$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_registration_custom_field['name']] : '';
								}
							}
							
							// Is Required
							$custom_attributes = array();
							if( isset( $wcfmaf_registration_custom_field['required'] ) && $wcfmaf_registration_custom_field['required'] ) $custom_attributes = array( 'required' => 1 );
								
							switch( $wcfmaf_registration_custom_field['type'] ) {
								case 'text':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'number':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'textarea':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'datepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'timepicker':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'checkbox':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'upload':
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'custom_attributes' => $custom_attributes, 'type' => 'file', 'class' => 'wcfm_ele', 'label_class' => 'wcfm_title', 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
								
								case 'select':
									$select_opt_vals = array();
									$select_options = explode( '|', $wcfmaf_registration_custom_field['options'] );
									$is_first = true;
									if( !empty ( $select_options ) ) {
										foreach( $select_options as $select_option ) {
											if( $select_option ) {
												$select_opt_label = __( ucfirst( str_replace( "-", " " , $select_option ) ), 'wc-frontend-manager-affiliate' );
												$select_opt_label = apply_filters( 'wcfm_registration_custom_field_select_label', $select_opt_label, $select_option );
												$select_opt_vals[$select_option] = $select_opt_label;
											} elseif( $is_first ) {
												$select_opt_vals[''] = __( "-Select-", "wc-frontend-manager" );
											}
											$is_first = false;
										}
									}
									$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_registration_custom_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'select', 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __($wcfmaf_registration_custom_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
								break;
							}
						}
					}
					
					if( !$user_id ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field(  array( 
																																	"passoword" => array( 'label' => __('Password', 'wc-frontend-manager-affiliate') , 'type' => 'password', 'custom_attributes' => array( 'required' => 1, 'mismatch_message' => __( 'Password and Confirm-password are not same.', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '' ),
																																	"password_strength" => array( 'type' => 'html', 'value' => '<div id="password-strength-status"></div>' ),
																																	"confirm_pwd" => array( 'label' => __('Confirm Password', 'wc-frontend-manager-affiliate') , 'type' => 'password', 'custom_attributes' => array( 'required' => 1 ), 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '' ),
																																) ) ;
					}
					
					do_action( 'end_wcfm_affiliate_registration_form' );
					
					// Terms & Conditions support add - 1.1.9
					if( $terms == 'active' ) {
						?>
						<input type="checkbox" id="terms" name="wcfmaf_static_infos[terms]" class="wcfm-checkbox" value="<?php _e( 'Agree', 'wc-frontend-manager-affiliate' ); ?>" data-required="1" data-required_message="<?php _e( 'Terms & Conditions', 'wc-frontend-manager-affiliate' ); ?>: <?php _e( 'This field is required.', 'wc-frontend-manager' ); ?>">
						<p class="terms_title wcfm_title">
							<strong>
								<span class="required">*</span>
								<?php 
								_e( 'Agree', 'wc-frontend-manager-affiliate' );
								echo '&nbsp;&nbsp;';
								if( $terms_page ) {
									?><a target="_blank" href="<?php echo get_permalink( $terms_page ); ?>"><?php _e( 'Terms & Conditions', 'wc-frontend-manager-affiliate' ); ?></a><?php
								} else {
									_e( 'Terms & Conditions', 'wc-frontend-manager-affiliate' );
								}
								?>
							</strong>
						</p>
						<?php
					}
				?>
			</div>
			<div class="wcfm-clearfix"></div>
		</div>
		
		<?php if( apply_filters( 'wcfm_is_allow_affiliate_registration_recaptcha', true ) ) { ?>
			<?php if ( function_exists( 'gglcptch_init' ) ) { ?>
				<div class="wcfm_clearfix"></div>
				<div class="wcfm_gglcptch_wrapper" style="float:right;">
					<?php echo apply_filters( 'gglcptch_display_recaptcha', '', 'wcfm_registration_form' ); ?>
				</div>
			<?php } elseif ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) ) { ?>
				<div class="wcfm_clearfix"></div>
				<div class="wcfm_gglcptch_wrapper" style="float:right;">
					<div class="anr_captcha_field"><div id="anr_captcha_field_99"></div></div>
						
					<?php
					$language = trim( anr_get_option( 'language' ) );
					$lang = '';
					if ( $language ) {
						$lang = "&hl=$language";
					}
					?>
					<script src="https://www.google.com/recaptcha/api.js?render=explicit<?php echo esc_js( $lang ); ?>"
						async defer>
					</script>
				</div>
			<?php } ?>
		<?php } ?>
		<div class="wcfm-clearfix"></div>
		<div class="wcfm-message" tabindex="-1"></div>
			
		<div id="wcfm_affiliate_registration_submit" class="wcfm_form_simple_submit_wrapper">
		  <?php if( wcfm_is_allowed_affiliate() ) { ?>
			  <input type="submit" name="save-data" value="<?php if( $user_id ) { _e( 'Confirm', 'wc-frontend-manager-affiliate' ); } else { _e( 'Register', 'wc-frontend-manager-affiliate' ); } ?>" id="wcfm_affiliate_register_button" class="wcfm_submit_button" />
			<?php } else { ?>
				<?php _e( 'Your user role not allowed for affiliation!', 'wc-frontend-manager-affiliate' ); ?>
			<?php } ?>
		</div>
		<div class="wcfm-clearfix"></div>
	</form>
</div>