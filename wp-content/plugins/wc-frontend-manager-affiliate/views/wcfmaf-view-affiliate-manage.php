<?php
/**
 * WCFM plugin views
 *
 * Plugin Deivery Boys Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/views
 * @version   1.0.0
 */
global $wp, $WCFM, $WCFMaf;

if( !apply_filters( 'wcfm_is_allow_manage_affiliate', true ) || !apply_filters( 'wcfm_is_allow_affiliate', true )  || wcfm_is_vendor() ) {
	wcfm_restriction_message_show( "Affiliate Manage" );
	return;
}

$affiliate_id = 0;
$affiliate_code = '';
$user_name = '';
$user_phone = '';
$user_email = '';
$first_name = '';
$last_name = '';

$commission = get_option( 'wcfm_affiliate_commission', array() );

if( isset( $wp->query_vars['wcfm-affiliate-manage'] ) && empty( $wp->query_vars['wcfm-affiliate-manage'] ) ) {
	if( !apply_filters( 'wcfm_is_allow_add_affiliate', true ) ) {
		wcfm_restriction_message_show( "Add Affiliate" );
		return;
	}
	if( !apply_filters( 'wcfm_is_allow_affiliate_limit', true ) ) {
		wcfm_restriction_message_show( "Affiliate Limit Reached" );
		return;
	}
}

if( isset( $wp->query_vars['wcfm-affiliate-manage'] ) && !empty( $wp->query_vars['wcfm-affiliate-manage'] ) ) {
	$affiliate_id = absint( $wp->query_vars['wcfm-affiliate-manage'] );
	$affiliate_user = get_userdata( $affiliate_id );
	
	// Fetching Affiliate Data
	if($affiliate_user && !empty($affiliate_user)) {
		
		if ( !in_array( 'wcfm_affiliate', (array) $affiliate_user->roles ) ) {
			wcfm_restriction_message_show( "Invalid Affiliate" );
			return;
		}
		
		$user_name = $affiliate_user->user_login;
		$user_email = $affiliate_user->user_email;
		$first_name = $affiliate_user->first_name;
		$last_name = $affiliate_user->last_name;
		
		$user_phone = get_user_meta( $affiliate_id, 'billing_phone', true );
		
		$affiliate_code = get_user_meta( $affiliate_id, 'affiliate_code', true );
		
		$commission = get_user_meta( $affiliate_id, 'wcfm_affiliate_commission', true );
		//$commission = get_user_meta( $affiliate_id, 'commission', true );
		
		if( !$commission ) {
			$commission = get_option( 'wcfm_affiliate_commission', array() );
		}
		
		//print_r(wcfm_get_affiliate_vendors( $affiliate_id ));
		//$has_custom_capability = get_user_meta( $affiliate_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $affiliate_id, '_wcfm_user_has_custom_capability', true ) : 'no';
	} else {
		wcfm_restriction_message_show( "Invalid Affiliate" );
		return;
	}
}

do_action( 'before_wcfm_affiliate_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user-friends"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Affiliate', 'wc-frontend-manager-affiliate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	    <h2><?php if( $affiliate_id ) { _e('Manage Affiliate', 'wc-frontend-manager-affiliate' ); } else { _e('Add Affiliate', 'wc-frontend-manager-affiliate' ); } ?></h2>
			
			<?php
			if( apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('user-new.php'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-affiliate' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
				
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_stats_url( $affiliate_id ).'" data-tip="' . __('Affiliate User Commission Stats', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-chart-line"></span></a>';
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_dashboard_url().'" data-tip="' . __('Manage Affiliate', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-user-friends"></span></a>';
			
			if( apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_manage_url().'" data-tip="' . __('Add New Affiliate', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	    
	  <?php do_action( 'begin_wcfm_affiliate_manage' ); ?>
	  
		<form id="wcfm_affiliate_manage_form" class="wcfm">
			
		  <?php do_action( 'begin_wcfm_affiliate_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="affiliate_manage_general_expander" class="wcfm-content">
						<?php
						  if( $affiliate_id ) {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  } else {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  }
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_manager_fields_general', array(  
																																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email),
																																						"user_phone" => array( 'label' => __('Phone', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_phone),
																																						"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name),
																																						"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name),
																																						"affiliate_id" => array('type' => 'hidden', 'value' => $affiliate_id )
																																					), $affiliate_id ) );
						?>
						
						<?php if( $affiliate_id ) { ?>
							<br /><br /><div class="wcfm_clearfix"></div>
							<div class="wcfm_vendor_settings_heading"><h2><?php _e('Affiliate Details', 'wc-frontend-manager-affiliate' ); ?></h2></div>
							<div class="wcfm_clearfix"></div>
							<div class="store_address">
							  <?php
							  $WCFM->wcfm_fields->wcfm_generate_form_field(  array( "affiliate_code" => array( 'label' => __('Affiliate Code', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $affiliate_code ) ) );
							  $WCFM->wcfm_fields->wcfm_generate_form_field(  array( "affiliate_url" => array( 'label' => __('Affiliate URL', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => wcfm_get_affiliate_url( $affiliate_id ) ) ) );
							  ?>
							</div>
							
							<?php
							$wcfmaf_addition_info_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
							if( !empty( $wcfmaf_addition_info_fields ) ) {
							
								$has_addition_field = false;
								if( !empty( $wcfmaf_addition_info_fields ) ) {
									foreach( $wcfmaf_addition_info_fields as $wcfmaf_registration_custom_field ) {
										if( !isset( $wcfmaf_registration_custom_field['enable'] ) ) continue;
										if( !$wcfmaf_registration_custom_field['label'] ) continue;
										$has_addition_field = true;
										break;
									}
								}
								if( $has_addition_field ) {
									$wcfmaf_custom_infos = (array) get_user_meta( $affiliate_id, 'wcfmaf_custom_infos', true );
									?>
									<br /><br /><div class="wcfm_clearfix"></div>
									<div class="wcfm_vendor_settings_heading"><h2><?php _e('Additional Info', 'wc-frontend-manager-affiliate' ); ?></h2></div>
									<div class="wcfm_clearfix"></div>
									<div class="store_address">
									  <?php
										foreach( $wcfmaf_addition_info_fields as $wcfmaf_addition_info_field ) {
											if( !isset( $wcfmaf_addition_info_field['enable'] ) ) continue;
											if( !$wcfmaf_addition_info_field['label'] ) continue;
											
											$field_class = '';
											$field_value = '';
											
											$wcfmaf_addition_info_field['name'] = sanitize_title( $wcfmaf_addition_info_field['label'] );
											$field_name = 'wcfmaf_custom_infos[' . $wcfmaf_addition_info_field['name'] . ']';
											$field_id   = md5( $field_name );
											$ufield_id  = '';
										
											if( !empty( $wcfmaf_custom_infos ) ) {
												if( $wcfmaf_addition_info_field['type'] == 'checkbox' ) {
													$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_addition_info_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_addition_info_field['name']] : 'no';
												} elseif( $wcfmaf_addition_info_field['type'] == 'upload' ) {
													$ufield_id = md5( 'wcfmaf_custom_infos[' . sanitize_title( $wcfmaf_addition_info_field['label'] ) . ']' );
													$field_value = isset( $wcfmaf_custom_infos[$ufield_id] ) ? $wcfmaf_custom_infos[$ufield_id] : '';
												} else {
													$field_value = isset( $wcfmaf_custom_infos[$wcfmaf_addition_info_field['name']] ) ? $wcfmaf_custom_infos[$wcfmaf_addition_info_field['name']] : '';
												}
											}
											
											// Is Required
											$custom_attributes = array();
											if( isset( $wcfmaf_addition_info_field['required'] ) && $wcfmaf_addition_info_field['required'] ) $custom_attributes = array( 'required' => 1 );
											
											$attributes = array();
											if( $wcfmaf_addition_info_field['type'] == 'mselect' ) {
												$field_class = 'wcfm_multi_select';
												$attributes = array( 'multiple' => 'multiple', 'style' => 'width: 60%;' );
											}
												
											switch( $wcfmaf_addition_info_field['type'] ) {
												case 'text':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'number':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'number', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'textarea':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'textarea', 'class' => 'wcfm-textarea', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'datepicker':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'timepicker':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'time', 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'checkbox':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'upload':
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => 'wcfmaf_custom_infos['.$ufield_id.']', 'custom_attributes' => $custom_attributes, 'type' => 'upload', 'class' => 'wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
												
												case 'select':
												case 'mselect':
												case 'dropdown':
													$select_opt_vals = array();
													$select_options = explode( '|', $wcfmaf_addition_info_field['options'] );
													if( !empty ( $select_options ) ) {
														foreach( $select_options as $select_option ) {
															if( $select_option ) {
																$select_opt_vals[$select_option] = __(ucfirst( str_replace( "-", " " , $select_option ) ), 'wc-frontend-manager-affiliate');
															}
														}
													}
													$WCFM->wcfm_fields->wcfm_generate_form_field(  array( $field_id => array( 'label' => __($wcfmaf_addition_info_field['label'], 'wc-frontend-manager-affiliate') , 'name' => $field_name, 'custom_attributes' => $custom_attributes, 'attributes' => $attributes, 'type' => 'select', 'class' => 'wcfm-select ' . $field_class, 'label_class' => 'wcfm_title', 'options' => $select_opt_vals, 'value' => $field_value, 'hints' => __($wcfmaf_addition_info_field['help_text'], 'wc-frontend-manager-affiliate') ) ) );
												break;
											}
										}
										?>
									</div>
									<?php
								}
								
							}
							?>
							
							<br /><br /><div class="wcfm_clearfix"></div>
							<div class="wcfm_vendor_settings_heading"><h2><?php _e('Affiliate Commission', 'wc-frontend-manager-affiliate' ); ?></h2></div>
							<div class="wcfm_clearfix"></div>
							
							<div class="store_address">
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_manager_fields_commission_vendor_rule', array(
																																										"vendor_commission_rule" => array('label' => __('Commission Rule', 'wc-frontend-manager-affiliate'), 'name' => 'commission[rule]', 'type' => 'select', 'options' => array( 'global' => __( 'By Global Rules', 'wc-frontend-manager-affiliate' ), 'personal' => __( 'Personalize', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => ( isset( $commission['rule'] ) ? $commission['rule'] : 'global' ) ),
																																										), $affiliate_id ) );
								?>
							</div>
							
							<div class="wcfm_clearfix"></div><br />
							<div class="store_address affiliate_commission_rule_personal">
								<?php
								$wcfm_commission_types = array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'percent' => __( 'Percent', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) );
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_manager_fields_commission_vendor', array(
																																										"vendor_commission_mode" => array('label' => __('New Vendor', 'wc-frontend-manager-affiliate'), 'name' => 'commission[vendor][mode]', 'type' => 'select', 'options' => array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor']['mode'] ) ? $commission['vendor']['mode'] : ''), 'hints' => __( 'Commission for new vendor registration using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																										"vendor_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'commission[vendor][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['percent'] ) ? $commission['vendor']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										"vendor_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'commission[vendor][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['fixed'] ) ? $commission['vendor']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										), $affiliate_id ) );
								?>
							</div>
							
							<div class="wcfm_clearfix"></div><br />
							<div class="store_address affiliate_commission_rule_personal">
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_manager_fields_commission_vendor_order', array(
																																										"vendor_order_commission_mode" => array('label' => __('Referred Vendor Order', 'wc-frontend-manager-affiliate'), 'name' => 'commission[vendor_order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['mode'] : ''), 'hints' => __( 'Commission for referred vendor\'s product sell.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																										"vendor_order_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'commission[vendor_order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['percent'] ) ? $commission['vendor_order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										"vendor_order_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'commission[vendor_order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['fixed'] ) ? $commission['vendor_order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										"vendor_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'commission[vendor_order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['cal_mode'] ) ? $commission['vendor_order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																										), $affiliate_id ) );
								?>
							</div>
							
							<div class="wcfm_clearfix"></div><br />
							<div class="store_address affiliate_commission_rule_personal">
								<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_manager_fields_commission_order', array(
																																										"order_commission_mode" => array('label' => __('Other Orders', 'wc-frontend-manager-affiliate'), 'name' => 'commission[order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : ''), 'hints' => __( 'Commission for any sell on site using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																										"order_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'commission[order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										"order_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'commission[order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																										"order_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'commission[order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['cal_mode'] ) ? $commission['order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																										), $affiliate_id ) );
								?>
							</div>
						<?php } ?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div>
			<!-- end collapsible -->
			
			<?php do_action( 'end_wcfm_affiliate_manage_form' ); ?>
			
			<div id="wcfm_affiliate_manager_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="submit-data" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" id="wcfm_affiliate_manager_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_affiliate_manage' );
			?>
			<div class="wcfm_clearfix"></div>
		</form>
		
		
		<!-- collapsible -->
		<?php if( $affiliate_id ) { $affiliate_users = wcfm_get_affiliate(); ?>
			<?php if( !empty( $affiliate_users ) ) { ?>
				<div class="page_collapsible affiliate_manage_code" id="wcfm_affiliate_manage_code_head"><?php _e('Generate Affiliate URL', 'wc-frontend-manager-affiliate'); ?><span></span></div>
				<div class="wcfm-container">
					<div id="affiliate_manage_code_expander" class="wcfm-content">
						<form id="wcfm_affiliate_url_form" class="wcfm">
							<?php
							$affiliate_user_array = array();
							foreach( $affiliate_users as $affiliate_user ) {
								$affiliate_code = get_user_meta( $affiliate_user->ID, 'affiliate_code', true );
								$affiliate_user_array[$affiliate_user->ID] = $affiliate_user->first_name . ' ' . $affiliate_user->last_name . ' (' . $affiliate_user->user_email . ' - ' . $affiliate_code . ')';
							}
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_url_generator', array(  
																																							"affiliate_user" => array( 'label' => __('Affiliate', 'wc-frontend-manager-affiliate') , 'type' => 'select', 'options' => $affiliate_user_array, 'class' => 'wcfm-select wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $affiliate_id ),
																																							"normal_url" => array( 'label' => __('URL', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '', 'desc' => __( 'Generate Affiliate URL for any URL for your site. e.g. Vendor Registration page, product page etc ..', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"generated_url" => array( 'label' => __('Affiliate URL', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_ele_hide', 'label_class' => 'wcfm_ele wcfm_title wcfm_ele_hide', 'value' => '' ),
																																						), $affiliate_id ) );
							?>
							<div class="wcfm_clearfix"></div><br />
							<input type="submit" name="submit-data" value="<?php _e( 'Generate', 'wc-frontend-manager-affiliate' ); ?>" id="wcfm_affiliate_url_generate_button" class="wcfm_submit_button" />
							<div class="wcfm_clearfix"></div>
						</form>
						<div class="wcfm_clearfix"></div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		
	</div>
</div>