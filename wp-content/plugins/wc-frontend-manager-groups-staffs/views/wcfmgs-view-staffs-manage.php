<?php
/**
 * WCFM plugin views
 *
 * Plugin Shop Staffs Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/views
 * @version   1.0.0
 */
global $wp, $WCFM, $WCFMu;

$wcfm_is_allow_manage_staff = apply_filters( 'wcfm_is_allow_manage_staff', true );
if( !$wcfm_is_allow_manage_staff ) {
	wcfm_restriction_message_show( "Staffs Manage" );
	return;
}

$staff_id = 0;
$user_name = '';
$user_email = '';
$first_name = '';
$last_name = '';
$has_custom_capability = 'no';

$wcfm_vendor = 0;
$wcfm_vendor_class = '';
$vendor_arr = array();
$is_marketplace = wcfm_is_marketplace();
if( !$is_marketplace || wcfm_is_vendor() ) {
	$wcfm_vendor_class = 'wcfm_custom_hide';
}

$appointment_staff_qty = '';
$availability_rule_values = array();
$availability_default_rules = array(  "type"         => 'custom',
	                                    "avail_id"     => '',
	                                    "kind_id"      => '',
																			"from_custom"  => '',
																			"to_custom"    => '',
																			"from_months"  => '',
																			"to_months"    => '',
																			"from_weeks"   => '',
																			"to_weeks"     => '',
																			"from_days"    => '',
																			"to_days"      => '', 
																			"from_time"    => '',
																			"to_time"      => '', 
																			"appointable"  => '',
																			"priority"     => '10'
																		);
$availability_rule_values[0] = $availability_default_rules;

if( isset( $wp->query_vars['wcfm-staffs-manage'] ) && empty( $wp->query_vars['wcfm-staffs-manage'] ) ) {
	if( !apply_filters( 'wcfm_is_allow_add_staff', true ) ) {
		wcfm_restriction_message_show( "Add Staff" );
		return;
	}
	if( !apply_filters( 'wcfm_is_allow_staff_limit', true ) ) {
		wcfm_restriction_message_show( "Staff Limit Reached" );
		return;
	}
}

if( isset( $wp->query_vars['wcfm-staffs-manage'] ) && !empty( $wp->query_vars['wcfm-staffs-manage'] ) ) {
	$staff_user = get_userdata( $wp->query_vars['wcfm-staffs-manage'] );
	
	// Fetching Staff Data
	if($staff_user && !empty($staff_user)) {
		
		if ( !in_array( 'shop_staff', (array) $staff_user->roles ) ) {
			wcfm_restriction_message_show( "Invalid Staff" );
			return;
		}
		
		$staff_id = $wp->query_vars['wcfm-staffs-manage'];
		$user_name = $staff_user->user_login;
		$user_email = $staff_user->user_email;
		$first_name = $staff_user->first_name;
		$last_name = $staff_user->last_name;
		
		if( !wcfm_is_vendor() ) {
			$wcfm_vendor = get_user_meta( $staff_id, '_wcfm_vendor', true );
		} else {
			$wcfm_vendor_class = 'wcfm_custom_hide';
			$wcfm_vendor = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		}
		if( $wcfm_vendor ) {
			$vendor_arr[$wcfm_vendor] = get_user_by( 'id', $wcfm_vendor )->display_name;
		}
		
		$has_custom_capability = get_user_meta( $staff_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $staff_id, '_wcfm_user_has_custom_capability', true ) : 'no';
		
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
				//$appointment_staff_qty = get_user_meta( $staff_id, '_wc_appointment_staff_qty', true );
				//$availability_rules = get_user_meta( $staff_id, '_wc_appointment_availability', true );
				
				$availability_rules = WC_Data_Store::load( 'appointments-availability' )->get_all(
					array(
						array(
							'key'     => 'kind',
							'compare' => '=',
							'value'   => 'availability#staff',
						),
						array(
							'key'     => 'kind_id',
							'compare' => '=',
							'value'   => $staff_id,
						),
					)
				);
					
				if( !empty( $availability_rules ) ) {
					foreach( $availability_rules as $a_index => $availability_rule ) {
						$availability_rule_values[$a_index] = $availability_default_rules;
						$availability_rule_values[$a_index]['type'] = $availability_rule['type'];
						if($availability_rule['type'] == 'custom' ) {
							$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to'];
						} elseif($availability_rule['type'] == 'months' ) {
							$availability_rule_values[$a_index]['from_months'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_months']   = $availability_rule['to'];
						} elseif($availability_rule['type'] == 'weeks' ) {
							$availability_rule_values[$a_index]['from_weeks'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_weeks']   = $availability_rule['to'];
						} elseif($availability_rule['type'] == 'days' ) {
							$availability_rule_values[$a_index]['from_days'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_days']   = $availability_rule['to'];
						} elseif($availability_rule['type'] == 'custom:daterange' ) {
							$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from_date'];
							$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to_date'];
							$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
						} elseif($availability_rule['type'] == 'time:range' ) {
							$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from_date'];
							$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to_date'];
							$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
						} else {
							$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
							$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
						}
						$availability_rule_values[$a_index]['avail_id'] = isset( $availability_rule['ID'] ) ? $availability_rule['ID'] : '';
						$availability_rule_values[$a_index]['kind_id'] = $staff_id;
						$availability_rule_values[$a_index]['title'] = isset( $availability_rule['title'] ) ? $availability_rule['title'] : '';
						$availability_rule_values[$a_index]['priority'] = isset( $availability_rule['priority'] ) ? $availability_rule['priority'] : '10';
						$availability_rule_values[$a_index]['appointable'] = $availability_rule['appointable'];
					}
				}
			}
		}
	} else {
		wcfm_restriction_message_show( "Invalid Staff" );
		return;
	}
}

if( wcfm_is_vendor() ) {
	$is_staff_for_vendor = $WCFM->wcfm_vendor_support->wcfm_is_component_for_vendor( $staff_id, 'staff' );
	if( !$is_staff_for_vendor ) {
		if( apply_filters( 'wcfm_is_show_staff_restrict_message', true, $staff_id ) ) {
			wcfm_restriction_message_show( "Restricted Staff" );
		} else {
			echo apply_filters( 'wcfm_show_custom_staff_restrict_message', '', $staff_id );
		}
		return;
	}
}

if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
  if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
		$intervals = array();
		
		$intervals['months'] = array(
			'1'  => __( 'January', 'woocommerce-appointments' ),
			'2'  => __( 'February', 'woocommerce-appointments' ),
			'3'  => __( 'March', 'woocommerce-appointments' ),
			'4'  => __( 'April', 'woocommerce-appointments' ),
			'5'  => __( 'May', 'woocommerce-appointments' ),
			'6'  => __( 'June', 'woocommerce-appointments' ),
			'7'  => __( 'July', 'woocommerce-appointments' ),
			'8'  => __( 'August', 'woocommerce-appointments' ),
			'9'  => __( 'September', 'woocommerce-appointments' ),
			'10' => __( 'October', 'woocommerce-appointments' ),
			'11' => __( 'November', 'woocommerce-appointments' ),
			'12' => __( 'December', 'woocommerce-appointments' ),
		);
		
		$intervals['days'] = array(
			'1' => __( 'Monday', 'woocommerce-appointments' ),
			'2' => __( 'Tuesday', 'woocommerce-appointments' ),
			'3' => __( 'Wednesday', 'woocommerce-appointments' ),
			'4' => __( 'Thursday', 'woocommerce-appointments' ),
			'5' => __( 'Friday', 'woocommerce-appointments' ),
			'6' => __( 'Saturday', 'woocommerce-appointments' ),
			'7' => __( 'Sunday', 'woocommerce-appointments' ),
		);
		
		for ( $i = 1; $i <= 53; $i ++ ) {
			$intervals['weeks'][ $i ] = sprintf( __( 'Week %s', 'woocommerce-appointments' ), $i );
		}
		
		$range_types = array(
													'custom'     => __( 'Date range', 'woocommerce-appointments' ),
													'custom:daterange' => __( 'Date range with time', 'woocommerce-appointments' ),
													'months'     => __( 'Range of months', 'woocommerce-appointments' ),
													'weeks'      => __( 'Range of weeks', 'woocommerce-appointments' ),
													'days'       => __( 'Range of days', 'woocommerce-appointments' ),
													'quant'      => __( 'Capacity count', 'woocommerce-appointments' ),
													//'slots'     => __( 'Slot count', 'woocommerce-appointments' ),
													'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range', 'woocommerce-appointments' ),
													'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'woocommerce-appointments' )
												);
		
		$availability_range_types = array(
													'custom'     => __( 'Date range', 'woocommerce-appointments' ),
													'custom:daterange' => __( 'Date range with time', 'woocommerce-appointments' ),
													'months'     => __( 'Range of months', 'woocommerce-appointments' ),
													'weeks'      => __( 'Range of weeks', 'woocommerce-appointments' ),
													'days'       => __( 'Range of days', 'woocommerce-appointments' ),
													'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Recurring Time (all week)', 'woocommerce-appointments' ),
													'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Recurring Time (all week)', 'woocommerce-appointments' )
												);
				
		foreach ( $intervals['days'] as $key => $label ) :
			$range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
			$availability_range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
		endforeach;
	}
}

$staff_capability_options = apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_shop_staff_capability_options', array() ), $staff_id );
$staff_capability_options = apply_filters( 'wcfmgs_vendor_staff_capability', $staff_capability_options );

do_action( 'before_wcfm_staffs_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Staff', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	    <h2><?php if( $staff_id ) { _e('Edit Staff', 'wc-frontend-manager-groups-staffs' ); } else { _e('Add Staff', 'wc-frontend-manager-groups-staffs' ); } ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('user-new.php'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-groups-staffs' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_shop_staffs_dashboard_url().'" data-tip="' . __('Manage Staff', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-user"></span></a>';
			
			if( $has_new = apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_shop_staffs_manage_url().'" data-tip="' . __('Add New Staff', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	    
	  <?php do_action( 'begin_wcfm_staffs_manage' ); ?>
	  
		<form id="wcfm_staffs_manage_form" class="wcfm">
			
		  <?php do_action( 'begin_wcfm_staffs_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="staffs_manage_general_expander" class="wcfm-content">
						<?php
						  if( $staff_id ) {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  } else {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  }
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_staff_manager_fields_general', array(  
																																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email),
																																						"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name),
																																						"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name),
																																						"wcfm_vendor" => array( 'label' => apply_filters( 'wcfm_sold_by_label', $wcfm_vendor, __( 'Store', 'wc-frontend-manager' ) ), 'type' => 'select', 'options' => $vendor_arr, 'class' => 'wcfm-select wcfm_ele ' .$wcfm_vendor_class, 'label_class' => 'wcfm_title ' . $wcfm_vendor_class, 'value' => $wcfm_vendor ),
																																						"has_custom_capability" => array( 'label' => __('Custom Capability', 'wc-frontend-manager-groups-staffs') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $has_custom_capability),
																																						"staff_id" => array('type' => 'hidden', 'value' => $staff_id )
																																					) ) );
							
							
							if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
								if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
									$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_staff_manager_fields_availability', array(  
												//"_wc_appointment_staff_qty"        => array('label' => __('Capacity', 'wc-frontend-manager-groups-staffs') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $appointment_staff_qty, 'hints' => __( 'The maximum number of appointments per slot at any given time for any product assigned. Overrides product capacity.', 'wc-frontend-manager-groups-staffs' ) ),
												"_wc_appointment_availability"     => array('label' => __('Custom Availability', 'woocommerce-appointments') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'desc' => esc_attr( get_wc_appointment_rules_explanation() ), 'desc_class' => 'avail_rules_desc', 'value' => $availability_rule_values, 'options' => array(
																																"type" => array('label' => __('Type', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $availability_range_types, 'class' => 'wcfm-select wcfm_ele avail_range_type appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label appointment' ),
																																"avail_id" => array( 'type' => 'hidden', 'class' => 'avail_id' ),
																																"kind_id" => array( 'type' => 'hidden', 'class' => 'kind_id' ),
																																"title" => array('label' => __('Title', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele avail_rule_title appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label appointment' ),
																																"from_custom" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => __( 'YYYY-MM-DD', 'wc-frontend-manager-ultimate' ), 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																																"to_custom" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => __( 'YYYY-MM-DD', 'wc-frontend-manager-ultimate' ), 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																																"from_months" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																																"to_months" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																																"from_weeks" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																																"to_weeks" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																																"from_days" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																																"to_days" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																																"from_time" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																																"to_time" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-text avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																																"priority" => array('label' => __('Priority', 'wc-frontend-manager-ultimate'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele avail_rules_ele avail_rule_capacity avail_rules_text appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label appointment', 'hints' => esc_attr( get_wc_appointment_priority_explanation() ) ),
																																"appointable" => array('label' => __('Appointable', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => array( 'yes' => __( 'Yes', 'woocommerce-appointments'), 'no' => __( 'No', 'woocommerce-appointments') ), 'class' => 'wcfm-select wcfm_ele avail_rules_ele avail_rules_text appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label', 'hints' => __( 'If not appointable, users won\'t be able to choose slots in this range for their appointment.', 'wc-frontend-manager-groups-staffs' ) ),
																																)	)
																																
																															), $staff_id ) );
								}
							}
						?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div>
			<!-- end collapsible -->
			
			<div class="user_custom_capability" style="display: none;">
			  <?php do_action( 'wcfmgs_capability_manager', $staff_capability_options, 'staff' ); ?>
			</div>
			 
			<?php do_action( 'end_wcfm_staffs_manage_form' ); ?>
			
			<div id="wcfm_staff_manager_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="submit-data" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" id="wcfm_staff_manager_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_staffs_manage' );
			?>
		</form>
	</div>
</div>