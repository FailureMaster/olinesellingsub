<?php

/**
 * WCFM Groups & Staffs Shop Staffs Class
 *
 * @version		1.0.0
 * @package		wcfmgs/core
 * @author 		WC Lovers
 */
class WCFMgs_Shop_Staffs {
	
 	public function __construct() {
 		
 		// WCFM Shop Managrs End Points
 		add_filter( 'wcfm_query_vars', array( &$this, 'wcfmgs_ss_wcfm_query_vars' ), 90 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfmgs_ss_endpoint_title' ), 90, 2 );
		add_action( 'init', array( &$this, 'wcfmgs_ss_init' ), 90 );
		
		// WCFM Appointments Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfmgs_ss_endpoints_slug' ) );
		
		// WCFM Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfmgs_ss_menus' ), 300 );
		add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfmgs_ss_menu_dependancy_map' ) );
 		
		// Binding Staff User Role for WCFM Dashboard Access
		add_filter( 'wcfm_allwoed_user_roles', array( &$this, 'allow_shop_staff_user_role' ) );
		
		// Is Logged in Staff is Vendor staff then load Vendor
		add_filter( 'wcfm_is_vendor', array( &$this, 'wcfm_is_staff_vendor' ) );
		add_filter( 'wcfm_current_vendor_id', array( &$this, 'wcfm_load_staff_vendor' ), 50 );
		
		// Restrict Staffs to see only their attachments
		add_action('pre_get_posts', array( &$this, 'wcfm_staff_only_attachments' ) );
		
		// Shop Staff Capability Controller
 		add_action( 'wcfm_shop_staff_capability_settings', array( &$this, 'wcfmgs_shop_staff_capability_fields' ) );
 		
 		// Shop Staff Notification
 		add_action( 'after_wcfm_notification', array( &$this, 'wcfmgs_shop_staff_notification' ), 50, 6 );
 		
 		// Shop Staff Appointment Availability
 		if( wcfm_is_staff() ) {
 			if( apply_filters( 'wcfm_is_allow_shop_staff_availability', true ) ) {
				if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
					if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
						add_action( 'end_wcfm_user_profile', array( &$this, 'wcfmgs_shop_staff_availability_fields' ), 25 );
						add_action( 'wcfm_profile_update', array( &$this, 'wcfmgs_shop_staff_availability_save' ), 25, 2 );
					}
				}
			}
		}
 		
 		// Appointment Staff FIlter
		add_filter( 'wcfm_appointments_args', array( &$this,  'wcfm_appointments_staff_filter_args' ) );
		
		// Staff Delete
		add_action( 'wp_ajax_delete_wcfm_staff', array( &$this, 'delete_wcfm_staff' ) );
		
		add_filter( 'wcfm_message_types', array( &$this, 'wcfm_staff_message_types' ), 30 );
 	}
 	
 	/**
   * WCFM Staffs Query Var
   */
  function wcfmgs_ss_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_staffs_vars = array(
			'wcfm-staffs'          => ! empty( $wcfm_modified_endpoints['wcfm-staffs'] ) ? $wcfm_modified_endpoints['wcfm-staffs'] : 'staffs',
			'wcfm-staffs-manage'   => ! empty( $wcfm_modified_endpoints['wcfm-staffs-manage'] ) ? $wcfm_modified_endpoints['wcfm-staffs-manage'] : 'staffs-manage',
		);
		
		$query_vars = array_merge( $query_vars, $query_staffs_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Staffs End Point Title
   */
  function wcfmgs_ss_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-staffs' :
				$title = __( 'Shop Staffs', 'wc-frontend-manager-groups-staffs' );
			break;
			case 'wcfm-staffs-manage' :
				$title = __( 'Shop Staffs Manage', 'wc-frontend-manager-groups-staffs' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Staffs Endpoint Intialize
   */
  function wcfmgs_ss_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfmgs_staffs' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfmgs_staffs', 1 );
		}
  }
  
  /**
	 * WCFM Staffs Endpoiint Edit
	 */
	function wcfmgs_ss_endpoints_slug( $endpoints ) {
		
		$wcfmgs_staffs_endpoints = array(
													'wcfm-staffs'           => 'staffs',
													'wcfm-staffs-manage'    => 'staffs-manage',
													);
		
		$endpoints = array_merge( $endpoints, $wcfmgs_staffs_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Staffs Menu
   */
  function wcfmgs_ss_menus( $menus ) {
  	global $WCFM;
  	
		$menus = array_slice($menus, 0, 7, true) +
												array( 'wcfm-staffs' => array(   'label'  => __( 'Staff', 'wc-frontend-manager-groups-staffs'),
																										 'url'     => get_wcfm_shop_staffs_dashboard_url(),
																										 'icon'    => 'user',
																										 'has_new'    => 'yes',
																										 'new_class'  => 'wcfm_sub_menu_items_shop_staffs_manage',
																										 'new_url'    => get_wcfm_shop_staffs_manage_url(),
																										 'priority'   => 55
																										) )	 +
													array_slice($menus, 7, count($menus) - 7, true) ;
		
  	return $menus;
  }
  
  /**
   * WCFM Staffs Menu Dependency
   */
  function wcfmgs_ss_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-staffs-manage'] = 'wcfm-staffs';
  	return $menu_dependency_mapping;
  }
 	
 	/**
	 * WCFM Allow Shop Staff Users
	 */
 	function allow_shop_staff_user_role( $allowed_roles ) {
  	$allowed_roles[] = 'shop_staff';
  	return $allowed_roles;
  }
  
  /**
	 * WCFM IS Shop Staff Vendor
	 */
 	function wcfm_is_staff_vendor( $is_vendor ) {
 		
 		if( wcfm_is_vendor_staff() ) $is_vendor = true;
 		
 		return $is_vendor;
 	}
 	
 	/**
	 * WCFM Load Shop Staff Vendor
	 */
 	function wcfm_load_staff_vendor( $vendor_id ) {
 		
 		$staff_vendor_id = wcfm_get_staff_vendor();
 		if( $staff_vendor_id ) $vendor_id = $staff_vendor_id;
 		
 		return $vendor_id;
 	}
 	
 	/**
	 * Restrict Staff to see only their attachments
	 */
	function wcfm_staff_only_attachments( $wp_query_obj ) {
		global $current_user, $pagenow;
		
		if( !wcfm_is_vendor_staff() ) 
			  return;

    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');

    if( !$is_attachment_request )
        return;

    if( !is_a( $current_user, 'WP_User') )
        return;

    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;

    //if( !current_user_can('delete_pages') )
    $wp_query_obj->set('author', wcfm_get_staff_vendor() );

    return;
	}
  
 	/**
	 * WCFM Shop Staff Capability Fields
	 */
	function wcfmgs_shop_staff_capability_fields() {
		global $WCFM, $WCFMgs;
		include_once( $WCFMgs->library->views_path . 'wcfmgs-view-shop-staff-capability.php' );
	}
	
	/**
	 * Shop Staff Notification
	 */
	function wcfmgs_shop_staff_notification( $author_id, $message_to, $author_is_admin, $author_is_vendor, $wcfm_messages, $wcfm_messages_type ) {
		global $WCFM, $wpdb;
		
		if( $wcfm_messages_type == 'direct' ) return;
		
		if( $message_to == -1 || $message_to == '-1' ) return;
		
		$message_to = absint($message_to);
		if( !$message_to ) return;
		
		$is_notice = 0;
		$is_direct_message = 1;
		
		$notification_messages  = esc_sql( $wcfm_messages );
		$current_time           = date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) );
		
		$args = array(
									'role__in'     => array( apply_filters( 'wcfm_staff_user_role', 'shop_staff' ) ),
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => 0,
									'number'       => -1,
									'count_total'  => false,
									'meta_key'     => '_wcfm_vendor',
									'meta_value'   => $message_to
								 ); 
		
		$wcfm_shop_staffs_array = get_users( $args );
		
		if(!empty($wcfm_shop_staffs_array)) {
			foreach( $wcfm_shop_staffs_array as $wcfm_shop_staffs_single ) {
				
				if( !apply_filters( 'wcfm_is_allow_staff_notification', true, $wcfm_messages_type, $wcfm_shop_staffs_single->ID ) ) return;
    		
				// Desktop Nitification
				if( apply_filters( 'wcfm_is_allow_notification_message', true, $wcfm_messages_type, $message_to ) && apply_filters( 'wcfm_is_allow_staff_notification_message', true, $wcfm_messages_type, $wcfm_shop_staffs_single->ID ) ) {
					
					$wcfm_create_message     = "INSERT into {$wpdb->prefix}wcfm_messages 
																			(`message`, `author_id`, `author_is_admin`, `author_is_vendor`, `is_notice`, `is_direct_message`, `message_to`, `message_type`, `created`)
																			VALUES
																			('{$notification_messages}', {$author_id}, {$author_is_admin}, {$author_is_vendor}, {$is_notice}, {$is_direct_message}, {$wcfm_shop_staffs_single->ID}, '{$wcfm_messages_type}', '{$current_time}')";
																			
					$wpdb->query($wcfm_create_message);
					
					$messageid = $wpdb->insert_id;
					$todate = date('Y-m-d H:i:s');
					if( $messageid && ( $author_id > 0 ) ) {
						$wcfm_read_message     = "INSERT into {$wpdb->prefix}wcfm_messages_modifier 
																				(`message`, `is_read`, `read_by`, `read_on`)
																				VALUES
																				({$messageid}, 1, {$author_id}, '{$todate}')";
						$wpdb->query($wcfm_read_message);
					}
				}
		
				// Email Notification
				if( apply_filters( 'wcfm_is_allow_notification_email', true, $wcfm_messages_type, $message_to ) && apply_filters( 'wcfm_is_allow_staff_notification_email', true, $wcfm_messages_type, $wcfm_shop_staffs_single->ID ) ) {
					if( !defined( 'DOING_WCFM_EMAIL' ) ) 
						define( 'DOING_WCFM_EMAIL', true );
					
					$message_types = get_wcfm_message_types();
					$message_type = isset( $message_types[$wcfm_messages_type] ) ? $message_types[$wcfm_messages_type] : ucfirst($wcfm_messages_type);
								
					$notificaton_mail_subject = "{site_name}: " . apply_filters( 'wcfm_notification_mail_subject', __( "Notification", "wc-frontend-manager" ) . " - " . $message_type, $wcfm_messages_type );
					$notification_mail_body =  '<br/>' .
																		 __( 'Hi', 'wc-frontend-manager' ) .
																		 ',<br/><br/>' . 
																		 __( 'You have received a new notification:', 'wc-frontend-manager' ) .
																		 '<br/><br/>' .
																		 '{notification_message}' .
																		 '<br/><br/>' .
																		 sprintf( __( 'Check more details %shere%s.', 'wc-frontend-manager' ), '<a href="{notification_url}">', '</a>' ) .
																		 '<br /><br/>' . __( 'Thank You', 'wc-frontend-manager' ) .
																		 '<br/><br/>';
															 
					$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $notificaton_mail_subject );
					$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
					$message = str_replace( '{notification_message}', $wcfm_messages, $notification_mail_body );
					$message = str_replace( '{notification_url}', get_wcfm_messages_url(), $message );
					$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( "Notification", "wc-frontend-manager" ) . " - " . $message_type );
					
					wp_mail( $wcfm_shop_staffs_single->user_email, $subject, $message );
						
				}
			}
		}
	}
	
	/**
	 * WCFM Shop Staff Appointment Availability
	 */
	function wcfmgs_shop_staff_availability_fields() {
		global $WCFM, $WCFMu;
		
		$staff_id = get_current_user_id();
		
		$appointment_staff_qty = '';
		$availability_rule_values = array();
		$availability_default_rules = array(  "type"         => 'custom',
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
																					"qty"          => ''
																				);
		$availability_rule_values[0] = $availability_default_rules;
		
		$appointment_staff_qty = get_user_meta( $staff_id, '_wc_appointment_staff_qty', true );
		$availability_rules = get_user_meta( $staff_id, '_wc_appointment_availability', true );
			
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
				} elseif($availability_rule['type'] == 'time:range' ) {
					$availability_rule_values[$a_index]['from_custom'] = $availability_rule['from_date'];
					$availability_rule_values[$a_index]['to_custom']   = $availability_rule['to_date'];
					$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
					$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
				} else {
					$availability_rule_values[$a_index]['from_time'] = $availability_rule['from'];
					$availability_rule_values[$a_index]['to_time']   = $availability_rule['to'];
				}
				$availability_rule_values[$a_index]['qty'] = isset ( $availability_rule['qty'] ) ? $availability_rule['qty'] : '';
				$availability_rule_values[$a_index]['appointable'] = $availability_rule['appointable'];
			}
		}
		
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
													'months'     => __( 'Range of months', 'woocommerce-appointments' ),
													'weeks'      => __( 'Range of weeks', 'woocommerce-appointments' ),
													'days'       => __( 'Range of days', 'woocommerce-appointments' ),
													'time'       => '&nbsp;&nbsp;&nbsp;' .  __( 'Time Range (all week)', 'woocommerce-appointments' ),
													'time:range' => '&nbsp;&nbsp;&nbsp;' . __( 'Date Range with time', 'woocommerce-appointments' )
												);
				
		foreach ( $intervals['days'] as $key => $label ) :
			$range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
			$availability_range_types['time:' . $key] = '&nbsp;&nbsp;&nbsp;' . $label;
		endforeach;
		
		?>
		<div class="page_collapsible profile_manage_shop_staff_availability" id="wcfm_profile_manage_form_shop_staff_availability_head"><label class="wcfmfa fa-clock-o"></label><?php _e( 'Availability', 'wc-frontend-manager-ultimate' ); ?><span></span></div>
		<div class="wcfm-container">
			<div id="wcfm_profile_manage_form_shop_staff_availability_expander" class="wcfm-content">
			  <?php
			  $WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_staff_manager_fields_availability', array(  
												"_wc_appointment_staff_qty"        => array('label' => __('Capacity', 'wc-frontend-manager-ultimate') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $appointment_staff_qty, 'hints' => __( 'The maximum number of appointments per slot at any given time for any product assigned. Overrides product capacity.', 'wc-frontend-manager-ultimate' ) ),
												"_wc_appointment_availability"     => array('label' => __('Custom Availability', 'wc-frontend-manager-ultimate') , 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'desc' => esc_attr( get_wc_appointment_rules_explanation() ), 'desc_class' => 'avail_rules_desc', 'value' => $availability_rule_values, 'options' => array(
																																"type" => array('label' => __('Type', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $availability_range_types, 'class' => 'wcfm-select wcfm_ele avail_range_type appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label appointment' ),
																																"from_custom" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																																"to_custom" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'text', 'placeholder' => 'YYYY-MM-DD', 'custom_attributes' => array( 'date_format' => 'yy-mm-dd'), 'class' => 'wcfm-text wcfm_datepicker avail_rule_field avail_rule_custom avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_custom avail_rules_ele avail_rules_label' ),
																																"from_months" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																																"to_months" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['months'], 'class' => 'wcfm-select avail_rule_field avail_rule_months avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_months avail_rules_ele avail_rules_label' ),
																																"from_weeks" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																																"to_weeks" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['weeks'], 'class' => 'wcfm-select avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_weeks avail_rules_ele avail_rules_label' ),
																																"from_days" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																																"to_days" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'select', 'options' => $intervals['days'], 'class' => 'wcfm-select avail_rule_field avail_rule_days avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_days avail_rules_ele avail_rules_label' ),
																																"from_time" => array('label' => __('From', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-select avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																																"to_time" => array('label' => __('To', 'wc-frontend-manager-ultimate'), 'type' => 'time', 'placeholder' => 'HH:MM', 'class' => 'wcfm-select avail_rule_field avail_rule_time avail_rules_ele avail_rules_text', 'label_class' => 'wcfm_title avail_rule_field avail_rule_time avail_rules_ele avail_rules_label' ),
																																"qty" => array('label' => __('Capacity', 'wc-frontend-manager-ultimate'), 'type' => 'number', 'class' => 'wcfm-text wcfm_ele avail_rules_ele avail_rule_capacity avail_rules_text appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label appointment', 'hints' => __( 'The maximum number of appointments per slot. Overrides general product capacity.', 'wc-frontend-manager-ultimate' ) ),
																																"appointable" => array('label' => __('Appointable', 'woocommerce-appointments'), 'type' => 'select', 'options' => array( 'yes' => __( 'YES', 'woocommerce-appointments'), 'no' => __( 'NO', 'woocommerce-appointments') ), 'class' => 'wcfm-select wcfm_ele avail_rules_ele avail_rules_text appointment', 'label_class' => 'wcfm_title avail_rules_ele avail_rules_label', 'hints' => __( 'If not appointable, users won\'t be able to choose slots in this range for their appointment.', 'woocommerce-appointments' ) ),
																																)	)
																																
																															), $staff_id ) );
				?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<?php
	}
	
	/**
	 * WCFM Shop Staff Appointment Availability
	 */
	function wcfmgs_shop_staff_availability_save( $staff_id, $wcfm_profile_form ) {
		global $WCFM;
		
		$availability_rule_index = 0;
		$availability_rules = array();
		$availability_default_rules = array(  "type"        => 'custom',
																					"from"        => '',
																					"to"          => '',
																					"appointable" => '',
																					"qty"         => ''
																				);
		if( isset($wcfm_profile_form['_wc_appointment_availability']) && !empty($wcfm_profile_form['_wc_appointment_availability']) ) {
			foreach( $wcfm_profile_form['_wc_appointment_availability'] as $availability_rule ) {
				$availability_rules[$availability_rule_index] = $availability_default_rules;
				$availability_rules[$availability_rule_index]['type'] = $availability_rule['type'];
				if( $availability_rule['type'] == 'custom' ) {
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_custom'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_custom'];
				} elseif( $availability_rule['type'] == 'months' ) {
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_months'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_months'];
				} elseif($availability_rule['type'] == 'weeks' ) {
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_weeks'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_weeks'];
				} elseif($availability_rule['type'] == 'days' ) {
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_days'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_days'];
				} elseif($availability_rule['type'] == 'time:range' ) {
					$availability_rules[$availability_rule_index]['from_date'] = $availability_rule['from_custom'];
					$availability_rules[$availability_rule_index]['to_date']   = $availability_rule['to_custom'];
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
				} else {
					$availability_rules[$availability_rule_index]['from'] = $availability_rule['from_time'];
					$availability_rules[$availability_rule_index]['to']   = $availability_rule['to_time'];
				}
				$availability_rules[$availability_rule_index]['appointable'] = $availability_rule['appointable'];
				$availability_rules[$availability_rule_index]['qty'] = $availability_rule['qty'];
				$availability_rule_index++;
			}
		}
		update_user_meta( $staff_id, '_wc_appointment_availability', $availability_rules );
		update_user_meta( $staff_id, '_wc_appointment_staff_qty', $wcfm_profile_form['_wc_appointment_staff_qty'] );
	}
	
	/**
	 * WCFM Shop Staff Appoinment filter
	 */
	function wcfm_appointments_staff_filter_args( $args ) {
		
		if( wcfm_is_staff() ) {
			$args['meta_query'] = array(
																	array(
																				'key'   => '_appointment_staff_id',
																				'value' => get_current_user_id()
																			)
																	);
		}
		
		return $args;
	}
	
	/**
	 * WCFM Shop Staff Capability Options update
	 */
  function wcfmgs_shop_staff_capability_option_updates( $options = array() ) {
  	
		$options = get_option( 'wcfm_shop_staff_capability_options' );
		$shop_staff_role = get_role( 'shop_staff' );
		
		if( !$shop_staff_role || is_wp_error( $shop_staff_role ) ) return;
		
		// Delete Media Capability
		if( isset( $options['delete_media'] ) && $options[ 'delete_media' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'delete_attachments' );
			$shop_staff_role->remove_cap( 'delete_posts' );
		} else {
			$shop_staff_role->add_cap( 'delete_attachments' );
			$shop_staff_role->add_cap( 'delete_posts' );
		}
			
		// Booking Capability
		if( wcfm_is_booking() ) {
			if( isset( $options['manage_booking'] ) && $options[ 'manage_booking' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'manage_bookings' );
			} else {
				$shop_staff_role->add_cap( 'manage_bookings' );
			}
		}
		
		// Appointment Capability
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
				if( isset( $options['manage_appointment'] ) && $options[ 'manage_appointment' ] == 'yes' ) {
					$shop_staff_role->remove_cap( 'manage_appointments' );
					$shop_staff_role->remove_cap( 'manage_options' );
				} else {
					$shop_staff_role->add_cap( 'manage_appointments' );
					$shop_staff_role->add_cap( 'manage_options' );
				}
			}
		}
		
		// Submit Products
		if( isset( $options[ 'submit_products' ] ) && $options[ 'submit_products' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'edit_products' );
			$shop_staff_role->remove_cap( 'manage_products' );
			$shop_staff_role->remove_cap( 'read_products' );
		} else {
			$shop_staff_role->add_cap( 'edit_products' );
			$shop_staff_role->add_cap( 'manage_products' );
			$shop_staff_role->add_cap( 'read_products' );
		}
		
		// Publish Coupon
		if( isset( $options[ 'publish_products' ] ) && $options[ 'publish_products' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'publish_products' );
		} else {
			$shop_staff_role->add_cap( 'publish_products' );
		}
		
		// Live Products Edit
		if( isset( $options[ 'edit_live_products' ] ) && $options[ 'edit_live_products' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'edit_published_products' );
		} else {
			$shop_staff_role->add_cap( 'edit_published_products' );
		}
		
		// Delete Products
		if( isset( $options[ 'delete_products' ] ) && $options[ 'delete_products' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'delete_published_products' );
			$shop_staff_role->remove_cap( 'delete_products' );
		} else {
			$shop_staff_role->add_cap( 'delete_published_products' );
			$shop_staff_role->add_cap( 'delete_products' );
		}
		
		// Submit Cuopon
		if( isset( $options[ 'submit_coupons' ] ) && $options[ 'submit_coupons' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'edit_shop_coupons' );
			$shop_staff_role->remove_cap( 'manage_shop_coupons' );
			$shop_staff_role->remove_cap( 'read_shop_coupons' );
		} else {
			$shop_staff_role->add_cap( 'edit_shop_coupons' );
			$shop_staff_role->add_cap( 'manage_shop_coupons' );
			$shop_staff_role->add_cap( 'read_shop_coupons' );
		}
		
		// Publish Coupon
		if( isset( $options[ 'publish_coupons' ] ) && $options[ 'publish_coupons' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'publish_shop_coupons' );
		} else {
			$shop_staff_role->add_cap( 'publish_shop_coupons' );
		}
		
		// Live Coupon Edit
		if( isset( $options[ 'edit_live_coupons' ] ) && $options[ 'edit_live_coupons' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'edit_published_shop_coupons' );
		} else {
			$shop_staff_role->add_cap( 'edit_published_shop_coupons' );
		}
		
		// Delete Coupon
		if( isset( $options[ 'delete_coupons' ] ) && $options[ 'delete_coupons' ] == 'yes' ) {
			$shop_staff_role->remove_cap( 'delete_published_shop_coupons' );
			$shop_staff_role->remove_cap( 'delete_shop_coupons' );
		} else {
			$shop_staff_role->add_cap( 'delete_published_shop_coupons' );
			$shop_staff_role->add_cap( 'delete_shop_coupons' );
		}
		
		// Only for Product Vendors
		$is_marketplace = wcfm_is_marketplace();
		if( $is_marketplace && ( $is_marketplace == 'wcpvendors' ) ) {
			$shop_staff_role = get_role( 'wc_product_vendors_manager_vendor' );
			
			// Booking Capability
			if( wcfm_is_booking() ) {
				if( isset( $options['manage_booking'] ) && $options[ 'manage_booking' ] == 'yes' ) {
					$shop_staff_role->remove_cap( 'manage_bookings' );
				} else {
					$shop_staff_role->add_cap( 'manage_bookings' );
				}
			}
			
			// Appointment Capability
			if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
				if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
					if( isset( $options['manage_appointment'] ) && $options[ 'manage_appointment' ] == 'yes' ) {
						$shop_staff_role->remove_cap( 'manage_appointments' );
					} else {
						$shop_staff_role->add_cap( 'manage_appointments' );
					}
				}
			}
			
			// Submit Products
			if( isset( $options[ 'submit_products' ] ) && $options[ 'submit_products' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'edit_products' );
				$shop_staff_role->remove_cap( 'manage_products' );
				$shop_staff_role->remove_cap( 'read_products' );
			} else {
				$shop_staff_role->add_cap( 'edit_products' );
				$shop_staff_role->add_cap( 'manage_products' );
				$shop_staff_role->add_cap( 'read_products' );
			}
			
			// Publish Coupon
			if( isset( $options[ 'publish_products' ] ) && $options[ 'publish_products' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'publish_products' );
			} else {
				$shop_staff_role->add_cap( 'publish_products' );
			}
			
			// Live Products Edit
			if( isset( $options[ 'edit_live_products' ] ) && $options[ 'edit_live_products' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'edit_published_products' );
			} else {
				$shop_staff_role->add_cap( 'edit_published_products' );
			}
			
			// Delete Products
			if( isset( $options[ 'delete_products' ] ) && $options[ 'delete_products' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'delete_published_products' );
				$shop_staff_role->remove_cap( 'delete_products' );
			} else {
				$shop_staff_role->add_cap( 'delete_published_products' );
				$shop_staff_role->add_cap( 'delete_products' );
			}
			
			// Submit Cuopon
			if( isset( $options[ 'submit_coupons' ] ) && $options[ 'submit_coupons' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'edit_shop_coupons' );
				$shop_staff_role->remove_cap( 'manage_shop_coupons' );
				$shop_staff_role->remove_cap( 'read_shop_coupons' );
			} else {
				$shop_staff_role->add_cap( 'edit_shop_coupons' );
				$shop_staff_role->add_cap( 'manage_shop_coupons' );
				$shop_staff_role->add_cap( 'read_shop_coupons' );
			}
			
			// Publish Coupon
			if( isset( $options[ 'publish_coupons' ] ) && $options[ 'publish_coupons' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'publish_shop_coupons' );
			} else {
				$shop_staff_role->add_cap( 'publish_shop_coupons' );
			}
			
			// Live Coupon Edit
			if( isset( $options[ 'edit_live_coupons' ] ) && $options[ 'edit_live_coupons' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'edit_published_shop_coupons' );
			} else {
				$shop_staff_role->add_cap( 'edit_published_shop_coupons' );
			}
			
			// Delete Coupon
			if( isset( $options[ 'delete_coupons' ] ) && $options[ 'delete_coupons' ] == 'yes' ) {
				$shop_staff_role->remove_cap( 'delete_published_shop_coupons' );
				$shop_staff_role->remove_cap( 'delete_shop_coupons' );
			} else {
				$shop_staff_role->add_cap( 'delete_published_shop_coupons' );
				$shop_staff_role->add_cap( 'delete_shop_coupons' );
			}
		}
  }
  
  /**
   * Handle Staff Delete
   */
  public function delete_wcfm_staff() {
  	global $WCFM, $WCFMu;
  	
  	$staffid = $_POST['staffid'];
		
		if($staffid) {
			if(wp_delete_user($staffid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  function wcfm_staff_message_types( $message_types ) {
  	if( apply_filters( 'wcfm_is_allow_manage_staff', true ) ) {
  		$message_types['new_staff'] = __( 'New Staff', 'wc-frontend-manager-groups-staffs' );
  	}
  	return $message_types;
  }
}