<?php
/**
 * WCFM plugin core
 *
 * Plugin Capability Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   2.3.1
 */
 
class WCFMgs_Capability {
	
	private $wcfm_capability_options = array();

	public function __construct() {
		global $WCFM, $WCFMgs;
		
		$this->wcfm_capability_options = apply_filters( 'wcfm_capability_options_rules', get_option( 'wcfm_capability_options', array() ) );
		
		// Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfmcap_wcfm_menus' ), 500 );
		
		// Limit
		add_filter( 'wcfm_gallerylimit', array( &$this, 'wcfmcap_gallerylimit' ), 500 );
		add_filter( 'wcfm_catlimit', array( &$this, 'wcfmcap_catlimit' ), 500 );
		add_filter( 'wcfm_allowed_taxonomies', array( &$this, 'wcfmcap_allowed_taxonomies' ), 500, 3 );
		
		add_filter( 'wcfm_is_allow_manage_settings', array( &$this, 'wcfmcap_is_allow_manage_settings' ), 500 );
		
		// Profile
		add_filter( 'wcfm_is_allow_address_profile', array( &$this, 'wcfmcap_is_allow_address_profile' ), 500 );
		add_filter( 'wcfm_is_allow_social_profile', array( &$this, 'wcfmcap_is_allow_social_profile' ), 500 );
		
		// Settings
		add_filter( 'wcfm_is_allow_brand_settings', array( &$this, 'wcfmcap_is_allow_brand_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vshipping_settings', array( &$this, 'wcfmcap_is_allow_vshipping_settings' ), 500 );
		add_filter( 'wcfm_is_allow_billing_settings', array( &$this, 'wcfmcap_is_allow_billing_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vseo_settings', array( &$this, 'wcfmcap_is_allow_vseo_settings' ), 500 );
		add_filter( 'wcfm_is_allow_policy_settings', array( &$this, 'wcfmcap_is_allow_policy_settings' ), 500 );
		add_filter( 'wcfm_is_allow_vacation_settings', array( &$this, 'wcfmcap_is_allow_vacation_settings' ), 500 );
		
		// Header Panels
		add_filter( 'wcfm_is_allow_notice', array( &$this, 'wcfmcap_is_allow_notice' ), 500 );
		add_filter( 'wcfm_is_allow_notice_reply', array( &$this, 'wcfmcap_is_allow_notice_reply' ), 500 );
		add_filter( 'wcfm_is_allow_notifications', array( &$this, 'wcfmcap_is_allow_notifications' ), 500 );
		add_filter( 'wcfm_is_allow_direct_message', array( &$this, 'wcfmcap_is_allow_direct_message' ), 500 );
		add_filter( 'wcfm_is_allow_enquiry', array( &$this, 'wcfmcap_is_allow_enquiry' ), 500 );
		add_filter( 'wcfm_is_allow_knowledgebase', array( &$this, 'wcfmcap_is_allow_knowledgebase' ), 500 );
		add_filter( 'wcfm_is_allow_profile', array( &$this, 'wcfmcap_is_allow_profile' ), 500 );
		
		// Groups & Staffs
		add_filter( 'wcfm_is_allow_vendors', array( &$this, 'wcfmcap_is_allow_vendors' ), 500 );
		add_filter( 'wcfm_is_allow_manage_groups', array( &$this, 'wcfmcap_is_allow_manage_groups' ), 500 );
		add_filter( 'wcfm_is_allow_manage_manager', array( &$this, 'wcfmcap_is_allow_manage_manager' ), 500 );
		add_filter( 'wcfm_is_allow_manage_staff', array( &$this, 'wcfmcap_is_allow_manage_staff' ), 500 );
		add_filter( 'wcfm_is_allow_staff_limit', array( &$this, 'wcfmcap_is_allow_staff_limit' ), 500 );
		add_filter( 'wcfm_staffs_limit_label', array( &$this, 'wcfmcap_staffs_limit_label' ), 50 );
		
		add_filter( 'wcfm_is_allow_capability_controller', array( &$this, 'wcfmcap_is_allow_capability_controller' ), 500 );
		
		// Analytics
		add_filter( 'wcfm_is_allow_analytics', array( &$this, 'wcfmcap_is_allow_analytics' ), 500 );	
		
		// Product Filter by allowed Categories
		add_filter ( 'wcfm_products_args', array( &$this, 'wcfmcap_products_args' ), 500 );
	}
	
	// WCFM wcfmcap Menu
  function wcfmcap_wcfm_menus( $menus ) {
  	global $WCFM;
  	
  	$add_products = ( isset( $this->wcfm_capability_options['add_products'] ) ) ? $this->wcfm_capability_options['add_products'] : 'no';
  	
  	if( isset( $menus['wcfm-products'] ) && $add_products == 'yes' ) {
  		unset( $menus['wcfm-products']['has_new'] );
  	}
  	
  	$manage_settings = ( isset( $this->wcfm_capability_options['manage_settings'] ) ) ? $this->wcfm_capability_options['manage_settings'] : 'no';
  	$manage_groups = ( isset( $this->wcfm_capability_options['manage_groups'] ) ) ? $this->wcfm_capability_options['manage_groups'] : 'no';
  	$manage_managers = ( isset( $this->wcfm_capability_options['manage_managers'] ) ) ? $this->wcfm_capability_options['manage_managers'] : 'no';
  	$manage_staffs = ( isset( $this->wcfm_capability_options['manage_staffs'] ) ) ? $this->wcfm_capability_options['manage_staffs'] : 'no';
  	
  	if( $manage_settings == 'yes' ) unset( $menus['wcfm-settings'] );
  	if( $manage_groups == 'yes' ) unset( $menus['wcfm-groups'] );
  	if( $manage_managers == 'yes' ) unset( $menus['wcfm-managers'] );
  	if( $manage_staffs == 'yes' ) unset( $menus['wcfm-staffs'] );
  	
  	// Bookings
  	$booking = ( isset( $this->wcfm_capability_options['booking'] ) ) ? $this->wcfm_capability_options['booking'] : 'no';
  	$manual_booking = ( isset( $this->wcfm_capability_options['manual_booking'] ) ) ? $this->wcfm_capability_options['manual_booking'] : 'no';
  	$manage_resource = ( isset( $this->wcfm_capability_options['manage_resource'] ) ) ? $this->wcfm_capability_options['manage_resource'] : 'no';
  	$booking_list = ( isset( $this->wcfm_capability_options['booking_list'] ) ) ? $this->wcfm_capability_options['booking_list'] : 'no';
  	$booking_calendar = ( isset( $this->wcfm_capability_options['booking_calendar'] ) ) ? $this->wcfm_capability_options['booking_calendar'] : 'no';
  	
  	if( ( $booking == 'yes' ) && ( $manual_booking == 'yes' ) && ( $manage_resource == 'yes' ) && ( $booking_list == 'yes' ) && ( $booking_calendar == 'yes' ) ) unset( $menus['wcfm-bookings-dashboard'] );
  	
  	// Appointments
  	$appointment = ( isset( $this->wcfm_capability_options['appointment'] ) ) ? $this->wcfm_capability_options['appointment'] : 'no';
  	$manual_appointment = ( isset( $this->wcfm_capability_options['manual_appointment'] ) ) ? $this->wcfm_capability_options['manual_appointment'] : 'no';
  	$manage_appointment_staff = ( isset( $this->wcfm_capability_options['manage_appointment_staff'] ) ) ? $this->wcfm_capability_options['manage_appointment_staff'] : 'no';
  	$appointment_list = ( isset( $this->wcfm_capability_options['appointment_list'] ) ) ? $this->wcfm_capability_options['appointment_list'] : 'no';
  	$appointment_calendar = ( isset( $this->wcfm_capability_options['appointment_calendar'] ) ) ? $this->wcfm_capability_options['appointment_calendar'] : 'no';
  	
  	if( ( $appointment == 'yes' ) && ( $manual_appointment == 'yes' ) && ( $manage_appointment_staff == 'yes' ) && ( $appointment_list == 'yes' ) && ( $appointment_calendar == 'yes' ) ) unset( $menus['wcfm-appointments-dashboard'] );
  	
  	return $menus;
  }
  
  // Gallery limit
  function wcfmcap_gallerylimit( $gallerylimit ) {
  	$gallerylimit = ( !empty( $this->wcfm_capability_options['gallerylimit'] ) ) ? $this->wcfm_capability_options['gallerylimit'] : '-1';
  	return $gallerylimit;
  }
  
  // Category Limits
  function wcfmcap_catlimit( $catlimit ) {
  	$catlimit = ( !empty( $this->wcfm_capability_options['catlimit'] ) ) ? $this->wcfm_capability_options['catlimit'] : '-1';
  	return $catlimit;
  }
  
  // Allowed Taxonomies
  function wcfmcap_allowed_taxonomies( $allow, $taxonomy, $term_id ) {
		global $WCFM, $WCFMu;
		
		if( $taxonomy == 'product_cat' ) $taxonomy = 'categories';
		
		$allowed_taxonomies    = ( !empty( $this->wcfm_capability_options['allowed_' . $taxonomy] ) ) ? $this->wcfm_capability_options['allowed_' . $taxonomy] : array();
		
		if( is_array( $allowed_taxonomies ) && !empty( $allowed_taxonomies ) ) {
			if( !in_array( $term_id, $allowed_taxonomies ) ) {
				$allow = false;
			}
		}
		
		return $allow;
	}
	
	// Profile Address
  function wcfmcap_is_allow_address_profile( $allow ) {
		$address = ( isset( $this->wcfm_capability_options['address'] ) ) ? $this->wcfm_capability_options['address'] : 'no';
		if ( $address == 'yes' ) return false;
		return $allow;
  }
  
  // Profile Social
  function wcfmcap_is_allow_social_profile( $allow ) {
		$social = ( isset( $this->wcfm_capability_options['social'] ) ) ? $this->wcfm_capability_options['social'] : 'no';
		if ( $social == 'yes' ) return false;
		return $allow;
  }
  
  // WCFM wcfmcap Manage Settings
  function wcfmcap_is_allow_manage_settings( $allow ) {
  	$manage_settings = ( isset( $this->wcfm_capability_options['manage_settings'] ) ) ? $this->wcfm_capability_options['manage_settings'] : 'no';
  	if( $manage_settings == 'yes' ) return false;
  	return $allow;
  }
  
  // Settings Brand
  function wcfmcap_is_allow_brand_settings( $allow ) {
		$brand = ( isset( $this->wcfm_capability_options['brand'] ) ) ? $this->wcfm_capability_options['brand'] : 'no';
		if ( $brand == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Shipping
  function wcfmcap_is_allow_vshipping_settings( $allow ) {
		$vshipping = ( isset( $this->wcfm_capability_options['vshipping'] ) ) ? $this->wcfm_capability_options['vshipping'] : 'no';
		if ( $vshipping == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Billing
  function wcfmcap_is_allow_billing_settings( $allow ) {
		$billing = ( isset( $this->wcfm_capability_options['billing'] ) ) ? $this->wcfm_capability_options['billing'] : 'no';
		if ( $billing == 'yes' ) return false;
		return $allow;
  }
  
  // Setting SEO
  function wcfmcap_is_allow_vseo_settings( $allow ) {
		$store_seo = ( isset( $this->wcfm_capability_options['store_seo'] ) ) ? $this->wcfm_capability_options['store_seo'] : 'no';
		if ( $store_seo == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Policy
  function wcfmcap_is_allow_policy_settings( $allow ) {
		$policy = ( isset( $this->wcfm_capability_options['policy'] ) ) ? $this->wcfm_capability_options['policy'] : 'no';
		if ( $policy == 'yes' ) return false;
		return $allow;
  }
  
  // Settings Vacation
  function wcfmcap_is_allow_vacation_settings( $allow ) {
		$vacation = ( isset( $this->wcfm_capability_options['vacation'] ) ) ? $this->wcfm_capability_options['vacation'] : 'no';
		if ( $vacation == 'yes' ) return false;
		return $allow;
  }
  
  // Notice
  function wcfmcap_is_allow_notice( $allow ) {
		$notice = ( isset( $this->wcfm_capability_options['notice'] ) ) ? $this->wcfm_capability_options['notice'] : 'no';
		if ( $notice == 'yes' ) return false;
		return $allow;
  }
  
  // Notice Reply
  function wcfmcap_is_allow_notice_reply( $allow ) {
		$notice_reply = ( isset( $this->wcfm_capability_options['notice_reply'] ) ) ? $this->wcfm_capability_options['notice_reply'] : 'no';
		if ( $notice_reply == 'yes' ) return false;
		return $allow;
  }
  
  // Notification
  function wcfmcap_is_allow_notifications( $allow ) {
		$notification = ( isset( $this->wcfm_capability_options['notification'] ) ) ? $this->wcfm_capability_options['notification'] : 'no';
		if ( $notification == 'yes' ) return false;
		return $allow;
  }
  
  // Direct Message
  function wcfmcap_is_allow_direct_message( $allow ) {
		$direct_message = ( isset( $this->wcfm_capability_options['direct_message'] ) ) ? $this->wcfm_capability_options['direct_message'] : 'no';
		if ( $direct_message == 'yes' ) return false;
		return $allow;
  }
  
  // Enquiry
  function wcfmcap_is_allow_enquiry( $allow ) {
		$enquiry = ( isset( $this->wcfm_capability_options['enquiry'] ) ) ? $this->wcfm_capability_options['enquiry'] : 'no';
		if ( $enquiry == 'yes' ) return false;
		return $allow;
  }
  
  // Knowledgebase
  function wcfmcap_is_allow_knowledgebase( $allow ) {
		$knowledgebase = ( isset( $this->wcfm_capability_options['knowledgebase'] ) ) ? $this->wcfm_capability_options['knowledgebase'] : 'no';
		if ( $knowledgebase == 'yes' ) return false;
		return $allow;
  }
  
  // Profie
  function wcfmcap_is_allow_profile( $allow ) {
		$profile = ( isset( $this->wcfm_capability_options['profile'] ) ) ? $this->wcfm_capability_options['profile'] : 'no';
		if ( $profile == 'yes' ) return false;
		return $allow;
  }
  
  // WCFM wcfmcap Manage Vendors
  function wcfmcap_is_allow_vendors( $allow ) {
  	$manage_vendors = ( isset( $this->wcfm_capability_options['manage_vendors'] ) ) ? $this->wcfm_capability_options['manage_vendors'] : 'no';
  	if( $manage_vendors == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Groups
  function wcfmcap_is_allow_manage_groups( $allow ) {
  	$manage_groups = ( isset( $this->wcfm_capability_options['manage_groups'] ) ) ? $this->wcfm_capability_options['manage_groups'] : 'no';
  	if( $manage_groups == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Manager
  function wcfmcap_is_allow_manage_manager( $allow ) {
  	$manage_managers = ( isset( $this->wcfm_capability_options['manage_managers'] ) ) ? $this->wcfm_capability_options['manage_managers'] : 'no';
  	if( $manage_managers == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Manage Staff
  function wcfmcap_is_allow_manage_staff( $allow ) {
  	$manage_staffs = ( isset( $this->wcfm_capability_options['manage_staffs'] ) ) ? $this->wcfm_capability_options['manage_staffs'] : 'no';
  	if( $manage_staffs == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Add Staff Limit
  function wcfmcap_is_allow_staff_limit( $allow ) {
  	$manage_staffs = ( isset( $this->wcfm_capability_options['manage_staffs'] ) ) ? $this->wcfm_capability_options['manage_staffs'] : 'no';
  	if( $manage_staffs == 'yes' ) return false;
  	
  	// Limit Restriction
  	$stafflimit = ( isset( $this->wcfm_capability_options['stafflimit'] ) ) ? $this->wcfm_capability_options['stafflimit'] : '';
  	if( ( $stafflimit == -1 ) || ( $stafflimit == '-1' ) ) {
  		return false;
  	} else {
			if( $stafflimit ) $stafflimit = absint($stafflimit);
			if( $stafflimit && ( $stafflimit >= 0 ) ) {
				$current_user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
				$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
				$args = array(
											'role__in'     => array( $staff_user_role ),
											'orderby'      => 'ID',
											'order'        => 'ASC',
											'offset'       => 0,
											'number'       => -1,
											'count_total'  => false,
											'meta_key'     => '_wcfm_vendor',
											'meta_value'   => $current_user_id
										 );
				$wcfm_staffs_array = get_users( $args );
				$count_staffs  = count($wcfm_staffs_array);
				if( $stafflimit <= $count_staffs ) return false;
			}
		}
  	return $allow;
  }
  
  // WCFM Staff Limit Label
  function wcfmcap_staffs_limit_label( $label ) {
  	
  	if( current_user_can( 'administrator' ) || !apply_filters( 'wcfm_is_allow_limit_label', true ) || !apply_filters( 'wcfm_is_allow_staff_limit_label', true ) ) return'';
  	
  	$label = __( 'Staffs Limit: ', 'wc-frontend-manager-groups-staffs' );
  	
  	$stafflimit = ( isset( $this->wcfm_capability_options['stafflimit'] ) ) ? $this->wcfm_capability_options['stafflimit'] : '';
  	if( ( $stafflimit == -1 ) || ( $stafflimit == '-1' ) ) {
  		$label .= ' 0 ' . __( 'remaining', 'wc-frontend-manager' );
  	} else {
			if( $stafflimit ) $stafflimit = absint($stafflimit);
			if( $stafflimit && ( $stafflimit >= 0 ) ) {
				if( $stafflimit == 1989 ) {
					$label .= ' 0 ' . __( 'remaining', 'wc-frontend-manager' );
				} else {
					$current_user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
					$staff_user_role = apply_filters( 'wcfm_staff_user_role', 'shop_staff' );
					$args = array(
												'role__in'     => array( $staff_user_role ),
												'orderby'      => 'ID',
												'order'        => 'ASC',
												'offset'       => 0,
												'number'       => -1,
												'count_total'  => false,
												'meta_key'     => '_wcfm_vendor',
												'meta_value'   => $current_user_id
											 );
					$wcfm_staffs_array = get_users( $args );
					$count_staffs  = count($wcfm_staffs_array);
					$label .= ' ' . ( $stafflimit - $count_staffs ) . ' ' . __( 'remaining', 'wc-frontend-manager' );
				}
			} else {
				$label .= __( 'Unlimited', 'wc-frontend-manager' );
			}
		}
  	
  	$label = '<span class="wcfm_staffs_limit_label">' . $label . '</span>';
  	
  	return $label;
  }
  
  // WCFM wcfmcap Manage Capability
  function wcfmcap_is_allow_capability_controller( $allow ) {
  	$capability_controller = ( isset( $this->wcfm_capability_options['capability_controller'] ) ) ? $this->wcfm_capability_options['capability_controller'] : 'no';
  	if( $capability_controller == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM wcfmcap Analytics
  function wcfmcap_is_allow_analytics( $allow ) {
  	$analytics = ( isset( $this->wcfm_capability_options['analytics'] ) ) ? $this->wcfm_capability_options['analytics'] : 'no';
  	if( $analytics == 'yes' ) return false;
  	return $allow;
  }
  
  // WCFM product filter by allowed Categories
  public function wcfmcap_products_args( $args ) {
  	global $WCFM, $WCFMgs, $_POST, $wp;
  	
  	if( ( wcfm_is_manager() && !wcfm_is_group_manager() ) || wcfm_is_staff() ) {
			if( !isset($_POST['product_cat']) || empty($_POST['product_cat']) ) {
				$allowed_categories    = ( !empty( $this->wcfm_capability_options['allowed_categories'] ) ) ? $this->wcfm_capability_options['allowed_categories'] : array();
				if( !empty( $allowed_categories ) ) {
					$args['tax_query'][] = array(
																				'taxonomy' => 'product_cat',
																				'field' => 'term_id',
																				'terms' => $allowed_categories,
																				'operator' => 'IN'
																			);
				}
			}
		}
		
		return $args;
  }
}