<?php
/**
 * WCFM Affiliate plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.0
 */
 
class WCFMaf_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMaf;
		
		if( !apply_filters( 'wcfm_is_pref_affiliate', true ) ) return;
		
		
		// WCFM Shop Managrs End Points
 		add_filter( 'wcfm_query_vars', array( &$this, 'wcfmaf_affiliate_wcfm_query_vars' ), 90 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfmaf_affiliate_endpoint_title' ), 90, 2 );
		add_action( 'init', array( &$this, 'wcfmaf_affiliate_init' ), 90 );
		
		// WCFM Affiliate Page
		add_filter( 'wcfm_settings_fields_pages', array( $this, 'wcfmaf_settings_fields_pages' ), 20 ); 
		
		// WCFM Affiliate Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfmaf_affiliate_endpoints_slug' ) );
		
		// WCFM Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfmaf_affiliate_menus' ), 300 );
		add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfmaf_affiliate_menu_dependancy_map' ) );
		
		// Binding Affiliate User Role for WCFM Dashboard Access
		add_filter( 'wcfm_allwoed_user_roles', array( &$this, 'allow_affiliate_user_role' ) );
		
		// Popup Product Disable for Affiliate Pages
		add_filter( 'wcfm_blocked_product_popup_views', array( &$this, 'wcfmaf_blocked_product_popup_views' ) );
		
		if( !wcfm_is_vendor() ) {
			// Set Affiliate Home Page
			add_filter( 'wcfm_login_redirect', array( &$this, 'wcfmaf_affiliate_login_redirect' ), 50, 2 );
			//add_filter( 'wcfm_dashboard_home', array( &$this, 'wcfmaf_affiliate_home_page' ) );
			
			// Disable Affiliate Dashboard Elements
			add_filter( 'wcfm_is_allow_home_in_menu', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_affiliate', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_affiliate', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_delivery', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_delivery_boys', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			//add_filter( 'wcfm_is_allow_notifications', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			//add_filter( 'wcfm_is_allow_direct_message', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_enquiry', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_notice', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_knowledgebase', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_address_profile', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_social_profile', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_settings', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_capability_controller', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_articles', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_coupons', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_customer', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_listings', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_orders', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_products', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_reports', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_vendors', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_payments', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_withdrawal_requets', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_refund_requests', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_reviews', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_followers', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_support', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_subscriptions', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_membership', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_groups', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_manager', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_staff', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_media', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			
			// Quick Access and Edit Options Restrict
			add_filter( 'wcfm_is_allow_catalog_quick_access', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_catalog_product_manage', array( &$this, 'wcfmaf_is_allow_dashboard_element' ), 750 );
			
			// My Account Dashboard Menu
			add_filter( 'woocommerce_account_menu_items', array( &$this, 'wcfmaf_my_account_menu_items' ), 210 );
			add_filter( 'woocommerce_get_endpoint_url', array( &$this,  'wcfmaf_my_account_endpoint_redirect'), 10, 4 );
			
			// Restrict Affiliates to see only their attachments
			add_action('pre_get_posts', array( &$this, 'wcfm_affiliate_only_attachments' ) );
			
			if( apply_filters( 'wcfmaf_is_allow_manage_registration_additional_infos', true ) ) {
				if( wcfm_is_affiliate() ) {
					add_action( 'end_wcfm_user_profile', array( &$this, 'wcfmaf_profile_additional_info' ), 75 );
					add_action( 'wcfm_profile_update', array( &$this, 'wcfmaf_profile_additional_info_update' ), 75, 2 );
				}
			}
		}
		
		// Affiliate Message Types
		add_filter( 'wcfm_message_types', array( &$this, 'wcfm_affiliate_message_types' ), 150 );
			
		if( apply_filters( 'wcfm_is_allow_commission_manage', true ) ) {
			// Affiliate Global Setting
			add_action( 'end_wcfm_settings', array( &$this, 'wcfmaf_affiliate_settings' ), 15 );
			add_action( 'wcfm_settings_update', array( &$this, 'wcfmaf_affiliate_settings_update' ), 20 );
			
			// Affiliate Membership Setting
			add_action( 'wcfm_memberships_manage_form_after_commission', array( &$this, 'wcfmaf_affiliate_membership_settings' ), 15 );
			add_action( 'wcfm_memberships_manage_from_process', array( &$this, 'wcfmaf_affiliate_membership_settings_update' ), 20, 2 );
		}
		
		if( apply_filters( 'wcfm_is_allow_product_affiliate_commission_manage', true ) ) {
			add_action( 'after_wcfm_products_manage_tabs_content', array( &$this, 'wcfmaf_affiliate_product_commission_settings' ), 510, 4 );
			add_action( 'after_wcfm_products_manage_meta_save', array( &$this, 'wcfmaf_affiliate_product_commission_save' ), 510, 2 );
		}
		
		// Vendor Registration Affiliate Tracking
		add_action( 'wcfm_membership_registration', array( &$this, 'wcfmaf_affiliate_vendor_registration' ), 200, 2 );
		
		// Non-vendor Order Affiliate Commission Generation
		add_action( 'woocommerce_checkout_order_processed', array(&$this, 'wcfmaf_affiliate_admin_order_commission'), 20, 3 );
		
		// Vendor Order Affiliate Commission Generation
		add_action( 'wcfmmp_order_item_processed', array( &$this, 'wcfmaf_affiliate_vendor_order_commission' ), 200, 10 );
		
		// Affiliate Capability Setting
		//add_action( 'wcfm_capability_settings_miscellaneous', array( &$this, 'wcfmaf_capability_settings_affiliate' ), 9 );
		
		// Affiliate enqueue scripts
		add_action('wp_enqueue_scripts', array(&$this, 'wcfmaf_scripts'));
		
		// Affiliate enqueue styles
		add_action('wp_enqueue_scripts', array(&$this, 'wcfmaf_styles'));
	}
	
	/**
   * WCFM Affiliate Query Var
   */
  function wcfmaf_affiliate_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = get_option( 'wcfm_endpoints', array() );
  	
		$query_affiliate_vars = array(
			'wcfm-affiliates'             => ! empty( $wcfm_modified_endpoints['wcfm-affiliates'] ) ? $wcfm_modified_endpoints['wcfm-affiliates'] : 'affiliates',
			'wcfm-affiliate'          => ! empty( $wcfm_modified_endpoints['wcfm-affiliate'] ) ? $wcfm_modified_endpoints['wcfm-affiliate'] : 'affiliate',
			'wcfm-affiliate-manage'   => ! empty( $wcfm_modified_endpoints['wcfm-affiliate-manage'] ) ? $wcfm_modified_endpoints['wcfm-affiliate-manage'] : 'affiliate-manage',
			'wcfm-affiliate-stats'    => ! empty( $wcfm_modified_endpoints['wcfm-affiliate-stats'] ) ? $wcfm_modified_endpoints['wcfm-affiliate-stats'] : 'affiliate-stats',
		);
		
		$query_vars = array_merge( $query_vars, $query_affiliate_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Affiliate End Point Title
   */
  function wcfmaf_affiliate_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-affiliates' :
				$title = __( 'Affiliate Stats', 'wc-frontend-manager-affiliate' );
			break;
			
			case 'wcfm-affiliate' :
				$title = __( 'Affiliate', 'wc-frontend-manager-affiliate' );
			break;
			
			case 'wcfm-affiliate-manage' :
				$title = __( 'Affiliate Manage', 'wc-frontend-manager-affiliate' );
			break;
			
			case 'wcfm-affiliate-stats' :
				$title = __( 'Affiliate Stats', 'wc-frontend-manager-affiliate' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Affiliate Endpoint Intialize
   */
  function wcfmaf_affiliate_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfma_affiliate' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfma_affiliate', 1 );
		}
  }
  
  /**
	 * WCFM Affiliate Pages Edit
	 */
  function wcfmaf_settings_fields_pages( $wcfm_pages ) {
  	$wcfm_page_options = get_option( 'wcfm_page_options', array() );
  	$wcfm_pages["wcfm_affiliate_registration_page_id"] = array( 'label' => __('Affiliate Registration', 'wc-frontend-manager-affiliate'), 'type' => 'select', 'name' => 'wcfm_page_options[wcfm_affiliate_registration_page_id]', 'options' => $wcfm_pages["wc_frontend_manager_page_id"]['options'], 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'value' => isset($wcfm_page_options['wcfm_affiliate_registration_page_id']) ? $wcfm_page_options['wcfm_affiliate_registration_page_id'] : '', 'desc_class' => 'wcfm_page_options_desc', 'desc' => __( 'Affiliate registration page. This page should have shortcode - wcfm_affiliate_registration', 'wc-frontend-manager-affiliate') );
  	
  	return $wcfm_pages;
  }
  
  /**
	 * WCFM Affiliate Endpoiint Edit
	 */
	function wcfmaf_affiliate_endpoints_slug( $endpoints ) {
		
		$wcfma_affiliate_endpoints = array(
													'wcfm-affiliates'          => 'affiliates',
													'wcfm-affiliate'           => 'affiliate',
													'wcfm-affiliate-manage'    => 'wcfm-affiliate-manage',
													'wcfm-affiliate-stats'     => 'wcfm-affiliate-stats',
													);
		
		$endpoints = array_merge( $endpoints, $wcfma_affiliate_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Affiliate Menu
   */
  function wcfmaf_affiliate_menus( $menus ) {
  	global $WCFM;
  	
  	if( apply_filters( 'wcfm_is_allow_affiliate', true ) && !wcfm_is_vendor() ) {
		
			$menus = array_slice($menus, 0, 3, true) +
											array( 'wcfm-affiliate' => array(  'label'      => __( 'Affiliate', 'wc-frontend-manager-affiliate'),
																														 'url'        => get_wcfm_affiliate_dashboard_url(),
																														 'icon'       => 'user-friends',
																														 'has_new'    => 'yes',
																														 'new_class'  => 'wcfm_sub_menu_items_affiliate_manage',
																														 'new_url'    => get_wcfm_affiliate_manage_url(),
																														 'priority'   => 53
																													) )	 +
														array_slice($menus, 3, count($menus) - 3, true) ;
		}
		
		if( wcfm_is_affiliate() && !wcfm_is_vendor() ) {
			$menus = array( 'wcfm-affiliates' => array(  'label'      => __( 'Affiliate Stats', 'wc-frontend-manager-affiliate'),
																									 'url'        => get_wcfm_affiliates_url(),
																									 'icon'       => 'user-friends',
																									 'priority'   => 53
																								 ) );
		} elseif( wcfm_is_affiliate() && wcfm_is_vendor() ) {
			$menus = array_slice($menus, 0, 3, true) +
			               array( 'wcfm-affiliates' => array(  'label'      => __( 'Affiliate Stats', 'wc-frontend-manager-affiliate'),
																									 'url'        => get_wcfm_affiliates_url(),
																									 'icon'       => 'user-friends',
																									 'priority'   => 53
																								 ) )	 +
														array_slice($menus, 3, count($menus) - 3, true) ;
		} elseif( wcfm_is_vendor() && wcfm_is_allow_vendor_as_affiliate() ) {
			$menus = array_slice($menus, 0, 3, true) +
											 array( 'wcfm-affiliates' => array(  'label'      => __( 'Become Affiliate', 'wc-frontend-manager-affiliate'),
																									 'url'        => get_wcfm_affiliate_registration_page(),
																									 'icon'       => 'user-friends',
																									 'new_tab'    => 'yes',
																									 'priority'   => 53
																								 ) )	 +
														array_slice($menus, 3, count($menus) - 3, true) ;
		}
		
		
		
  	return $menus;
  }
  
  /**
   * WCFM Affiliate Menu Dependency
   */
  function wcfmaf_affiliate_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-affiliate-manage'] = 'wcfm-affiliate';
  	$menu_dependency_mapping['wcfm-affiliate-stats'] = 'wcfm-affiliate';
  	return $menu_dependency_mapping;
  }
  
  /**
	 * WCFM Allow Affiliate Users
	 */
 	function allow_affiliate_user_role( $allowed_roles ) {
  	$allowed_roles[] = 'wcfm_affiliate';
  	return $allowed_roles;
  }
  
  /**
   * Product Popup Disable for Affiliate pages
   */
  function wcfmaf_blocked_product_popup_views( $blocked_views ) {
  	$blocked_views[] = 'wcfm-affiliates';
  	$blocked_views[] = 'wcfm-affiliate-manage';
  	$blocked_views[] = 'wcfm-affiliate-stats';
  	return $blocked_views;
  }
  
  /**
   * Affiliate Login Redirect
   */
  function wcfmaf_affiliate_login_redirect( $redirect_to, $user ) {
  	if ( $user && !is_wp_error( $user ) && $user->roles && !in_array( apply_filters( 'wcfm_vendor_user_role', 'wcfm_vendor' ), (array) $user->roles ) ) {
			if ( $user && !is_wp_error( $user ) && $user->roles && in_array( apply_filters( 'wcfm_affiliate_user_role', 'wcfm_affiliate' ), (array) $user->roles ) ) {
				$redirect_to = get_wcfm_affiliates_url();
			}
		}
  	return $redirect_to;
  }
  
  /**
   * Set Home URL for Affiliate
   */
  function wcfmaf_affiliate_home_page( $home_url ) {
  	if( wcfm_is_affiliate() && !wcfm_is_vendor() ) $home_url = get_wcfm_affiliates_url();
  	return $home_url;
  }
  
  /**
   * Disable Home Menu for Affiliate
   */
  function wcfmaf_is_allow_dashboard_element( $is_allow ) {
  	if( wcfm_is_affiliate()  && !wcfm_is_vendor() ) $is_allow = false;
  	return $is_allow;
  }
  
  /**
   * Affiliate Message Types
   */
  function wcfm_affiliate_message_types( $message_types ) {
  	if( apply_filters( 'wcfm_is_allow_affiliate', true ) && !wcfm_is_vendor() ) {
  		$message_types['affiliate_approval']      = __( 'Approve Affiliate', 'wc-frontend-manager-affiliate' );
  		$message_types['new_affiliate']           = __( 'New Affiliate', 'wc-frontend-manager-affiliate' );
  		//$message_types['affiliate_complete']      = __( 'Affiliate Complete', 'wc-frontend-manager-affiliate' );
  	}
  	if( wcfm_is_affiliate() ) {
  		$message_types = array();
  		$message_types['direct']        = __( 'Direct Message', 'wc-frontend-manager' );
  	}
  	$message_types['affiliate_commission']        = __( 'Affiliate Commission', 'wc-frontend-manager-affiliate' );
  	$message_types['affiliate_commission_paid']   = __( 'Affiliate Commission Paid', 'wc-frontend-manager-affiliate' );
  	$message_types['affiliate-disable']           = __( 'Disable Affiliate Account', 'wc-frontend-manager-affiliate' );
  	$message_types['affiliate-enable']            = __( 'Enable Affiliate Account', 'wc-frontend-manager-affiliate' );
  	return $message_types;
  }
  
  /**
	 * WC My Account Dashboard Link
	 */
	function wcfmaf_my_account_menu_items( $items ) {
		global $WCFM, $WCFMmp;
		
		if( wcfm_is_affiliate() ) {
			$dashboard_page_title = __( 'Affiliate Dashboard', 'wc-frontend-manager-affiliate' );
			$dashboard_page_title = apply_filters( 'wcfmaf_wcmy_dashboard_page_title', $dashboard_page_title ); 
			
			$items = array_slice($items, 0, count($items) - 2, true) +
																		array(
																					"wcfm-affiliate-manager" => $dashboard_page_title
																					) +
																		array_slice($items, count($items) - 2, count($items) - 1, true) ;
		}
																	
		return $items;
	}
	
	function wcfmaf_my_account_endpoint_redirect( $url, $endpoint, $value, $permalink ) {
		if( $endpoint == 'wcfm-affiliate-manager')
      $url = get_wcfm_affiliates_url();
    return $url;
	}
	
	/**
	 * Restrict Affiliate to see only their attachments
	 */
	function wcfm_affiliate_only_attachments( $wp_query_obj ) {
		global $current_user, $pagenow;
		
		if( !wcfm_is_affiliate() ) 
			  return;

    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');

    if( !$is_attachment_request )
        return;

    if( !is_a( $current_user, 'WP_User') )
        return;

    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
        return;

    //if( !current_user_can('delete_pages') )
    $wp_query_obj->set('author', $current_user->ID );

    return;
	}
	
	/**
	 * Affiliate Profile Additional Info
	 */
	function wcfmaf_profile_additional_info( $affiliate_id ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$wcfmaf_addition_info_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
		if( empty( $wcfmaf_addition_info_fields ) ) return;
		
		$has_addition_field = false;
		if( !empty( $wcfmaf_addition_info_fields ) ) {
			foreach( $wcfmaf_addition_info_fields as $wcfmaf_registration_custom_field ) {
				if( !isset( $wcfmaf_registration_custom_field['enable'] ) ) continue;
				if( !$wcfmaf_registration_custom_field['label'] ) continue;
				$has_addition_field = true;
				break;
			}
		}
		if( !$has_addition_field ) return;
		$wcfmaf_custom_infos = (array) get_user_meta( $affiliate_id, 'wcfmaf_custom_infos', true );
		
		?>
		
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_profile_form_additional_info_head">
			<label class="wcfmfa fa-star"></label>
			<?php echo apply_filters( 'wcfm_vendor_additional_info_heading', __('Additional Info', 'wc-multivendor-marketplace') ); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_profile_form_additional_info_expander" class="wcfm-content">
		
			  <?php
			  if( !empty( $wcfmaf_addition_info_fields ) ) {
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
				}
				?>
			
			</div>
		</div>
		<?php
	}
	
	/**
	 * Affiliate Profile Additional Info Update
	 */
	function wcfmaf_profile_additional_info_update( $vendor_id, $wcfm_profile_form ){
		global $WCFM, $WCFMmp, $wpdb;
		
		if( isset( $wcfm_profile_form['wcfmaf_custom_infos'] ) ) {
			update_user_meta( $vendor_id, 'wcfmaf_custom_infos', $wcfm_profile_form['wcfmaf_custom_infos'] );
			
			// Toolset User Fields Compatibility added
			$wcfmmp_addition_info_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
			$wcfmvm_custom_infos = (array) $wcfm_profile_form['wcfmaf_custom_infos'];
			
			if( !empty( $wcfmmp_addition_info_fields ) ) {
				foreach( $wcfmmp_addition_info_fields as $wcfmvm_registration_custom_field ) {
					if( !isset( $wcfmvm_registration_custom_field['enable'] ) ) continue;
					if( !$wcfmvm_registration_custom_field['label'] ) continue;
					$field_value = '';
					$wcfmvm_registration_custom_field['name'] = sanitize_title( $wcfmvm_registration_custom_field['label'] );
				
					if( !empty( $wcfmvm_custom_infos ) ) {
						if( $wcfmvm_registration_custom_field['type'] == 'checkbox' ) {
							$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : 'no';
						} else {
							$field_value = isset( $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] ) ? $wcfmvm_custom_infos[$wcfmvm_registration_custom_field['name']] : '';
						}
					}
					if( !$field_value ) $field_value = '';
					update_user_meta( $vendor_id, $wcfmvm_registration_custom_field['name'], $field_value );
				}
			}
		}
	}
  
  /**
   * Affiliate Admin Setting 
   */
  function wcfmaf_affiliate_settings( $wcfm_options ) {
		global $WCFM, $WCFMaf;
		
		if( !apply_filters( 'wcfm_is_allow_affiliate', true ) ) return;
		
		$commission = get_option( 'wcfm_affiliate_commission', array() );
		
		$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
		
		$affiliate_reject_rules = array();
		if( isset( $wcfm_affiliate_options['affiliate_reject_rules'] ) ) $affiliate_reject_rules = $wcfm_affiliate_options['affiliate_reject_rules'];
		$required_approval = isset( $affiliate_reject_rules['required_approval'] ) ? $affiliate_reject_rules['required_approval'] : 'no';
		
		$vendor_as_affiliate = isset( $wcfm_affiliate_options['vendor_as_affiliate'] ) ? $wcfm_affiliate_options['vendor_as_affiliate'] : 'no';
		
		$vendor_allow_pm_commission = isset( $wcfm_affiliate_options['vendor_allow_pm_commission'] ) ? $wcfm_affiliate_options['vendor_allow_pm_commission'] : 'no';
		
		$affiliate_type_settings = array();
		if( isset( $wcfm_affiliate_options['affiliate_type_settings'] ) ) $affiliate_type_settings = $wcfm_affiliate_options['affiliate_type_settings'];
		$email_verification = isset( $affiliate_type_settings['email_verification'] ) ? 'yes' : 'no';
		$sms_verification = isset( $affiliate_type_settings['sms_verification'] ) ? 'yes' : 'no';
		
		$wcfmaf_registration_static_fields = wcfm_get_option( 'wcfmaf_registration_static_fields', array() );
		$enable_address = isset( $wcfmaf_registration_static_fields['address'] ) ? 'yes' : '';
		
		$field_types = apply_filters( 'wcfm_product_custom_filed_types', array( 'text' => 'Text', 'number' => 'Number', 'textarea' => 'textarea', 'datepicker' => 'Date Picker', 'timepicker' => 'Time Picker', 'checkbox' => 'Check Box', 'select' => 'Select', 'upload' => 'File/Image' ) );
		$wcfmaf_registration_custom_fields = wcfm_get_option( 'wcfmaf_registration_custom_fields', array() );
		
		$new_account_mail_subject = "[{site_name}] New Account Created";
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
		
		$approved_thankyou_content = "<strong>Welcome,</strong>
																	<br /><br />
																	You have successfully subscribed to our affiliate program. 
																	<br /><br />
																	Your account already setup and ready to configure.
																	<br /><br />
																	Kindly follow the below the link to visit your dashboard.
																	<br /><br />
																	Thank You";
	  $wcfmaf_approved_thankyou_content = wcfm_get_option( 'wcfmaf_approved_thankyou_content', '' );
	  if( !$wcfmaf_approved_thankyou_content ) $wcfmaf_approved_thankyou_content = $approved_thankyou_content;
	  
	  $non_approved_thankyou_content = "<strong>Welcome,</strong>
																		<br /><br />
																		You have successfully submitted your Affiliate Account request. 
																		<br /><br />
																		Your Affiliate application is still under review.
																		<br /><br />
																		You will receive details about our decision in your email very soon!
																		<br /><br />
																		Thank You";
		$wcfmaf_non_approved_thankyou_content = wcfm_get_option( 'wcfmaf_non_approved_thankyou_content', '' );			
		if( !$wcfmaf_non_approved_thankyou_content ) $wcfmaf_non_approved_thankyou_content = $non_approved_thankyou_content;
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_affiliate_head">
			<label class="wcfmfa fa-user-friends"></label>
			<?php _e('Affiliate', 'wc-frontend-manager-affiliate'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_affiliate_expander" class="wcfm-content">
			
				<div class="wcfm_clearfix"></div><br />
			  <h2><?php _e( 'Affiliate Registration Settings', 'wc-frontend-manager-affiliate' ); ?></h2>
			  <?php wcfm_video_tutorial( 'https://docs.wclovers.com/wcfm-affiliate/' ); ?>
				<div class="wcfm_clearfix"></div>
				<div class="store_address">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmaf_setting_approval_fields', array(  
																																			"wcfmaf_required_approval" => array( 'label' => __( 'Required Approval', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $required_approval, 'desc' => __( 'Whether user required Admin Approval to become affiliate or not!', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																			"wcfmaf_vendor_as_affiliate" => array( 'label' => __( 'Vendor as Affiliate?', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_as_affiliate, 'desc' => __( 'Whether vendors are allowed to become affiliate or not!', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																			"wcfmaf_vendor_allow_pm_commission" => array( 'label' => __( 'Product Commission by Vendor?', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $vendor_allow_pm_commission, 'desc' => __( 'Whether vendors are allowed to manage product specific affiliate commission or not!', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																			) ) );
					
					//if( WCFMmp_Dependencies::wcfm_sms_alert_plugin_active_check() || WCFMmp_Dependencies::wcfm_twilio_plugin_active_check() || WCFMmp_Dependencies::wcfm_msg91_plugin_active_check() ) {
						//$WCFM->wcfm_fields->wcfm_generate_form_field( array(  
																																//"wcfmaf_sms_verification" => array( 'label' => __( 'SMS (via OTP) Verification', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $sms_verification ),
																																//) );
					//}
					
					?>
				</div>
			
			  <h2><?php _e('Affiliate Commission Setting', 'wc-frontend-manager-affiliate' ); ?></h2>
				<div class="wcfm_clearfix"></div>
				<div class="store_address">
					<?php
					$wcfm_commission_types = array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'percent' => __( 'Percent', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) );
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_global_fields_commission_vendor', array(
																																							"vendoraf_commission_mode" => array('label' => __('New Vendor', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor][mode]', 'type' => 'select', 'options' => array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor']['mode'] ) ? $commission['vendor']['mode'] : ''), 'hints' => __( 'Commission for new vendor registration using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"vendoraf_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['percent'] ) ? $commission['vendor']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[vendor][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['fixed'] ) ? $commission['vendor']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							) ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_global_fields_commission_vendor_order', array(
																																							"vendoraf_order_commission_mode" => array('label' => __('Referred Vendor Order', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['mode'] : ''), 'hints' => __( 'Commission for referred vendor\'s product sell.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"vendoraf_order_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['percent'] ) ? $commission['vendor_order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[vendor_order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['fixed'] ) ? $commission['vendor_order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['cal_mode'] ) ? $commission['vendor_order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																							) ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_global_fields_commission_order', array(
																																							"orderaf_commission_mode" => array('label' => __('Other Orders', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : ''), 'hints' => __( 'Commission for any sell on site using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"orderaf_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['cal_mode'] ) ? $commission['order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																							) ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<h2><?php _e( 'Registration Form Fields', 'wc-frontend-manager-affiliate' ); ?></h2>
				<div class="wcfm_clearfix"></div>
				<div class="store_address">
					<?php
					$pages = get_pages(); 
					$pages_array = array( '' => __( '-- Choose Terms Page --', 'wc-frontend-manager-affiliate' ) );
					$woocommerce_pages = array ( wc_get_page_id('shop'), wc_get_page_id('cart'), wc_get_page_id('checkout'), wc_get_page_id('myaccount'));
					foreach ( $pages as $page ) {
						if(!in_array($page->ID, $woocommerce_pages)) {
							$pages_array[$page->ID] = $page->post_title;
						}
					}
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmaf_registration_static_fields', array(
																																																								"af_first_name"  => array( 'label' => __( 'First Name', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[first_name]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['first_name'] ) ? 'yes' : '' ),
																																																								"af_last_name"   => array( 'label' => __( 'Last Name', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[last_name]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['last_name'] ) ? 'yes' : '' ),
																																																								"af_user_name"   => array( 'label' => __( 'User Name', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[user_name]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['user_name'] ) ? 'yes' : '' ),
																																																								"af_address"     => array( 'label' => __( 'Address', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[address]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['address'] ) ? 'yes' : '' ),
																																																								"af_phone"       => array( 'label' => __( 'Phone', 'wc-frontend-manager' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[phone]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['phone'] ) ? 'yes' : '' ),
																																																								"af_terms"       => array( 'label' => __( 'Terms & Conditions', 'wc-frontend-manager-affiliate' ), 'type' => 'checkbox', 'name' => 'wcfmaf_registration_static_fields[terms]', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes', 'dfvalue' => isset( $wcfmaf_registration_static_fields['terms'] ) ? 'yes' : '' ),
																																																								"af_terms_page"  => array( 'label' => __( 'Terms Page', 'wc-frontend-manager-affiliate' ), 'type' => 'select', 'name' => 'wcfmaf_registration_static_fields[terms_page]', 'options' => $pages_array, 'class' => 'wcfm-select wcfm_ele terms_page_ele', 'label_class' => 'wcfm_title terms_page_ele', 'value' => isset( $wcfmaf_registration_static_fields['terms_page'] ) ? $wcfmaf_registration_static_fields['terms_page'] : '' )
																																																								)
																																			) );
					
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_registration_custom_fields', array(
																																															"wcfmaf_registration_custom_fields" => array('label' => __( 'Registration Form Custom Fields', 'wc-frontend-manager-affiliate'), 'type' => 'multiinput', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_full_title', 'value' => $wcfmaf_registration_custom_fields, 'options' => array(
																																																							"enable"   => array('label' => __('Enable', 'wc-frontend-manager'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox-title', 'value' => 'yes'),
																																																							"type" => array( 'label' => __('Field Type', 'wc-frontend-manager'), 'type' => 'select', 'options' => $field_types, 'class' => 'wcfm-select wcfm_ele field_type_options', 'label_class' => 'wcfm_title'),           
																																																							"label" => array( 'label' => __('Label', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title'),
																																																							"options" => array( 'label' => __('Options', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele field_type_select_options', 'label_class' => 'wcfm_title field_type_select_options', 'placeholder' => __( 'Insert option values | separated', 'wc-frontend-manager' ) ),
																																																							"help_text" => array( 'label' => __('Help Content', 'wc-frontend-manager'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title' ),
																																																							"required" => array( 'label' => __('Required?', 'wc-frontend-manager'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes' ),
																																																) )
																																												) ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
			  <h2><?php _e('Affiliate Welcome Email', 'wc-frontend-manager-affiliate'); ?></h2>
			  <div class="wcfm_clearfix"></div>
			  <div class="store_address">
					<?php
						$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
						$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
						if( $wpeditor && $rich_editor ) {
							$rich_editor = 'wcfm_wpeditor';
						} else {
							$wpeditor = 'textarea';
						}
						
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmaf_settings_fields_email', array(
																																																	"wcfmaf_new_account_mail_subject" => array('label' => __('New account mail subject', 'wc-frontend-manager-affiliate'), 'name' => 'wcfmaf_new_account_mail_subject', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $new_account_mail_subject ),
																																																	"wcfmaf_new_account_mail_content" => array('label' => __('New account mail body', 'wc-frontend-manager-affiliate'), 'name' => 'wcfmaf_new_account_mail_content', 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_custom_field_editor wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title wcfm_full_ele', 'desc_class' => 'instructions', 'value' => $new_account_mail_body, 'desc' => __('Allowed dynamic variables are: {site_url}, {user_role}, {username}, {first_name}, {password}', 'wc-frontend-manager-affiliate') ),
																																																	) ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
			  <h2><?php _e('Affiliate Thank You Page Content', 'wc-frontend-manager-affiliate'); ?></h2>
			  <div class="wcfm_clearfix"></div>
			  <div class="store_address">
					<?php
						$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
						$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
						if( $wpeditor && $rich_editor ) {
							$rich_editor = 'wcfm_wpeditor';
						} else {
							$wpeditor = 'textarea';
						}
						
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmaf_settings_fields_thankyou', array(
																																																	"wcfmaf_approved_thankyou_content" => array('label' => __('Approved affilite thank you page content', 'wc-frontend-manager-affiliate'), 'name' => 'wcfmaf_approved_thankyou_content', 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_custom_field_editor wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title wcfm_full_ele', 'desc_class' => 'instructions', 'value' => $wcfmaf_approved_thankyou_content ),
																																																	"wcfmaf_non_approved_thankyou_content" => array('label' => __('Require approval affilite thank you page content', 'wc-frontend-manager-affiliate'), 'name' => 'wcfmaf_non_approved_thankyou_content', 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_custom_field_editor wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title wcfm_full_ele', 'desc_class' => 'instructions', 'value' => $wcfmaf_non_approved_thankyou_content ),
																																																	) ) );
					?>
				</div>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
	}
	
	function wcfmaf_affiliate_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMaf, $_POST;
		
		if( isset( $wcfm_settings_form['affiliate_commission'] ) ) {
			update_option( 'wcfm_affiliate_commission', $wcfm_settings_form['affiliate_commission'] );
		}
		
		$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
		
		if( isset( $wcfm_settings_form['wcfmaf_required_approval'] ) ) {
			$wcfm_affiliate_options['affiliate_reject_rules']['required_approval'] = 'yes';
		} else {
			$wcfm_affiliate_options['affiliate_reject_rules']['required_approval'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_vendor_as_affiliate'] ) ) {
			$wcfm_affiliate_options['vendor_as_affiliate'] = 'yes';
		} else {
			$wcfm_affiliate_options['vendor_as_affiliate'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_vendor_allow_pm_commission'] ) ) {
			$wcfm_affiliate_options['vendor_allow_pm_commission'] = 'yes';
		} else {
			$wcfm_affiliate_options['vendor_allow_pm_commission'] = 'no';
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_email_verification'] ) ) {
			$wcfm_affiliate_options['affiliate_type_settings']['email_verification'] = 'yes';
		} else {
			$wcfm_affiliate_options['affiliate_type_settings']['email_verification'] = 'no';
			unset( $wcfm_affiliate_options['affiliate_type_settings']['email_verification'] );
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_sms_verification'] ) ) {
			$wcfm_affiliate_options['affiliate_type_settings']['sms_verification'] = 'yes';
		} else {
			$wcfm_affiliate_options['affiliate_type_settings']['sms_verification'] = 'no';
			unset( $wcfm_affiliate_options['affiliate_type_settings']['sms_verification'] );
		}
		
	  update_option( 'wcfm_affiliate_options', $wcfm_affiliate_options );
		
		if( isset( $wcfm_settings_form['wcfmaf_registration_static_fields'] ) ) {
	  	wcfm_update_option( 'wcfmaf_registration_static_fields', $wcfm_settings_form['wcfmaf_registration_static_fields'] );
	  } else {
	  	wcfm_update_option( 'wcfmaf_registration_static_fields', array() );
	  }
	  
	  if( isset( $wcfm_settings_form['wcfmaf_registration_custom_fields'] ) ) {
	  	wcfm_update_option( 'wcfmaf_registration_custom_fields', $wcfm_settings_form['wcfmaf_registration_custom_fields'] );
	  }
		
		if( isset( $wcfm_settings_form['wcfmaf_new_account_mail_subject'] ) ) {
			$new_account_mail_subject = $wcfm_settings_form['wcfmaf_new_account_mail_subject'];
			wcfm_update_option( 'wcfmaf_new_account_mail_subject',  $new_account_mail_subject );
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_new_account_mail_content'] ) ) {
			$new_account_mail_body = stripslashes( html_entity_decode( $wcfm_settings_form['wcfmaf_new_account_mail_content'], ENT_QUOTES, 'UTF-8' ) );
			wcfm_update_option( 'wcfmaf_new_account_mail_body',  $new_account_mail_body );
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_approved_thankyou_content'] ) ) {
			$wcfmaf_approved_thankyou_content = stripslashes( html_entity_decode( $wcfm_settings_form['wcfmaf_approved_thankyou_content'], ENT_QUOTES, 'UTF-8' ) );
			wcfm_update_option( 'wcfmaf_approved_thankyou_content',  $wcfmaf_approved_thankyou_content );
		}
		
		if( isset( $wcfm_settings_form['wcfmaf_non_approved_thankyou_content'] ) ) {
			$wcfmaf_non_approved_thankyou_content = stripslashes( html_entity_decode( $wcfm_settings_form['wcfmaf_non_approved_thankyou_content'], ENT_QUOTES, 'UTF-8' ) );
			wcfm_update_option( 'wcfmaf_non_approved_thankyou_content',  $wcfmaf_non_approved_thankyou_content );
		}
	}
	
	/**
   * Affiliate Membership Setting 
   */
  function wcfmaf_affiliate_membership_settings( $membership_id = '' ) {
		global $wp, $WCFM, $WCFMaf;
		
		if( !apply_filters( 'wcfm_is_allow_affiliate', true ) ) return;
		
		$commission = get_option( 'wcfm_affiliate_commission', array() );
		
		if( !$membership_id ) {
			if( isset( $wp->query_vars['wcfm-memberships-manage'] ) && !empty( $wp->query_vars['wcfm-memberships-manage'] ) ) {
				$membership_id = absint( $wp->query_vars['wcfm-memberships-manage'] );
			}
		}
		
		if( $membership_id ) {
			$membership_affiliate_commission = get_post_meta( $membership_id, 'wcfm_affiliate_commission', true );
			if( $membership_affiliate_commission ) {
				$commission = $membership_affiliate_commission;
			}
		}
		
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_affiliate_head">
			<label class="wcfmfa fa-user-friends"></label>
			<?php _e('Affiliate Commission', 'wc-frontend-manager-affiliate'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_affiliate_expander" class="wcfm-content">
			  <h2><?php _e('Affiliate Commission Setting', 'wc-frontend-manager-affiliate' ); ?></h2>
			  <?php wcfm_video_tutorial( 'https://docs.wclovers.com/wcfm-affiliate/' ); ?>
				<div class="wcfm_clearfix"></div>
				
				<div class="store_address">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_membership_fields_commission_vendor_rule', array(
																																							"vendoraf_commission_rule" => array('label' => __('Commission Rule', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[rule]', 'type' => 'select', 'options' => array( 'global' => __( 'By Global Rules', 'wc-frontend-manager-affiliate' ), 'personal' => __( 'Personalize', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => ( isset( $commission['rule'] ) ? $commission['rule'] : '' ) ),
																																							), $membership_id ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address affiliate_commission_rule_personal">
					<?php
					$wcfm_commission_types = array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'percent' => __( 'Percent', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) );
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_membership_fields_commission_vendor', array(
																																							"vendoraf_commission_mode" => array('label' => __('New Vendor', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor']['mode'] ) ? $commission['vendor']['mode'] : ''), 'hints' => __( 'Commission for new vendor registration using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"vendoraf_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['percent'] ) ? $commission['vendor']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[vendor][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor']['fixed'] ) ? $commission['vendor']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							), $membership_id ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address affiliate_commission_rule_personal">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_membership_fields_commission_vendor_order', array(
																																							"vendoraf_order_commission_mode" => array('label' => __('Referred Vendor Order', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['mode'] : ''), 'hints' => __( 'Commission for referred vendor\'s product sell.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"vendoraf_order_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['percent'] ) ? $commission['vendor_order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[vendor_order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['vendor_order']['fixed'] ) ? $commission['vendor_order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['vendor_order']['cal_mode'] ) ? $commission['vendor_order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																							), $membership_id ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address affiliate_commission_rule_personal">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_membership_fields_commission_order', array(
																																							"orderaf_commission_mode" => array('label' => __('Other Orders', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : ''), 'hints' => __( 'Commission for any sell on site using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"orderaf_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_percent commission_mode_percent_fixed', 'value' => (isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'label_class' => 'wcfm_title wcfm_ele commission_mode_field commission_mode_fixed commission_mode_percent_fixed', 'value' => (isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => (isset( $commission['order']['cal_mode'] ) ? $commission['order']['cal_mode'] : ''), 'desc' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'wcfm_page_options_desc' ),
																																							), $membership_id ) );
					?>
				</div>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		<?php
	}
	
	/**
   * Affiliate Membership Setting 
   */
  function wcfmaf_affiliate_membership_settings_update( $new_membership_id, $wcfm_membership_manager_form_data ) {
  	if( isset( $wcfm_membership_manager_form_data['affiliate_commission'] ) ) {
			update_post_meta( $new_membership_id, 'wcfm_affiliate_commission', $wcfm_membership_manager_form_data['affiliate_commission'] );
		}
  }
  
  /**
   * Affiliate Product Commission Setting 
   */
  function wcfmaf_affiliate_product_commission_settings( $product_id, $product_type, $wcfm_is_translated_product = false, $wcfm_wpml_edit_disable_element = '' ) {
		global $wp, $WCFM, $WCFMaf;
		
		$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
		$vendor_allow_pm_commission = isset( $wcfm_affiliate_options['vendor_allow_pm_commission'] ) ? $wcfm_affiliate_options['vendor_allow_pm_commission'] : 'no';
		
		if( wcfm_is_vendor() && ( ( $vendor_allow_pm_commission != 'yes' ) || !apply_filters( 'wcfm_is_allow_vendors_to_manage_product_affiliate_commission', true ) ) ) return;
		
		$commission = get_option( 'wcfm_affiliate_commission', array() );
		
		if( $product_id ) {
			$product_affiliate_commission = get_post_meta( $product_id, '_wcfm_affiliate_commission', true );
			if( $product_affiliate_commission ) {
				$commission = $product_affiliate_commission;
			}
		}
		
		$wcfm_commission_types = array( '' => __( 'No Commission', 'wc-frontend-manager-affiliate' ), 'percent' => __( 'Percent', 'wc-frontend-manager-affiliate' ), 'fixed' => __( 'Fixed', 'wc-frontend-manager-affiliate' ) );
		?>
		<!-- collapsible -->
		<div class="page_collapsible product_manage_affiliate_commission simple variable external grouped booking" id="wcfm_settings_form_affiliate_head">
			<label class="wcfmfa fa-user-friends"></label>
			<?php _e('Affiliate Commission', 'wc-frontend-manager-affiliate'); ?><span></span>
		</div>
		<div class="wcfm-container simple variable external grouped booking">
			<div id="wcfm_settings_form_affiliate_expander" class="wcfm-content">
			  <h2><?php _e('Affiliate Commission Setting', 'wc-frontend-manager-affiliate' ); ?></h2>
				<div class="wcfm_clearfix"></div>
				
				<div class="store_address">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_product_fields_commission_vendor_rule', array(
																																							"vendoraf_commission_rule" => array('label' => __('Commission Rule', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[rule]', 'type' => 'select', 'options' => array( 'global' => __( 'By Global Rules', 'wc-frontend-manager-affiliate' ), 'personal' => __( 'Personalize', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => ( isset( $commission['rule'] ) ? $commission['rule'] : '' ) ),
																																							), $product_id ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address affiliate_commission_rule_personal">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_product_fields_commission_vendor_order', array(
																																							"vendoraf_order_commission_mode" => array('label' => __('Referred Vendor Order', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => (isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['mode'] : ''), 'hints' => __( 'Commission for referred vendor\'s product sell.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"vendoraf_order_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele aff_commission_mode_field aff_commission_mode_percent aff_commission_mode_percent_fixed simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele aff_commission_mode_field aff_commission_mode_percent aff_commission_mode_percent_fixed simple variable external grouped booking', 'value' => (isset( $commission['vendor_order']['percent'] ) ? $commission['vendor_order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[vendor_order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele aff_commission_mode_field aff_commission_mode_fixed commission_mode_percent_fixed simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele aff_commission_mode_field aff_commission_mode_fixed aff_commission_mode_percent_fixed simple variable external grouped booking', 'value' => (isset( $commission['vendor_order']['fixed'] ) ? $commission['vendor_order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"vendoraf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[vendor_order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => (isset( $commission['vendor_order']['cal_mode'] ) ? $commission['vendor_order']['cal_mode'] : ''), 'hints' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ) ),
																																							), $product_id ) );
					?>
				</div>
				
				<div class="wcfm_clearfix"></div><br />
				<div class="store_address affiliate_commission_rule_personal">
					<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_product_fields_commission_order', array(
																																							"orderaf_commission_mode" => array('label' => __('Other Orders', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][mode]', 'type' => 'select', 'options' => $wcfm_commission_types, 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => (isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : ''), 'hints' => __( 'Commission for any sell on site using Affiliate referral code.', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"orderaf_commission_percent" => array('label' => __('Commission Percent(%)', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][percent]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele aff_commission_mode_field aff_commission_mode_percent aff_commission_mode_percent_fixed simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele aff_commission_mode_field aff_commission_mode_percent aff_commission_mode_percent_fixed simple variable external grouped booking', 'value' => (isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_commission_fixed" => array('label' => __('Commission Fixed', 'wc-frontend-manager-affiliate') . '(' . get_woocommerce_currency_symbol() . ')', 'name' => 'affiliate_commission[order][fixed]', 'type' => 'number', 'placeholder' => 0, 'class' => 'wcfm-text wcfm_ele aff_commission_mode_field aff_commission_mode_fixed aff_commission_mode_percent_fixed simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele aff_commission_mode_field aff_commission_mode_fixed aff_commission_mode_percent_fixed simple variable external grouped booking', 'value' => (isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : ''), 'attributes' => array( 'min' => '0.1', 'step' => '0.1') ),
																																							"orderaf_order_calculation_mode" => array('label' => __('Calculate commission on?', 'wc-frontend-manager-affiliate'), 'name' => 'affiliate_commission[order][cal_mode]', 'type' => 'select', 'options' => array( 'on_item' => __( 'On Item Cost', 'wc-frontend-manager-affiliate' ), 'on_commission' => __( 'On Commission', 'wc-frontend-manager-affiliate' ) ), 'class' => 'wcfm-select wcfm_ele simple variable external grouped booking', 'label_class' => 'wcfm_title wcfm_ele simple variable external grouped booking', 'value' => (isset( $commission['order']['cal_mode'] ) ? $commission['order']['cal_mode'] : ''), 'hints' => __( 'If you set this \'On Commission\' then Affiliate commission will be calculated on vendor\'s commission amount and will be deducted from commission. Affiliate commission deduction will be visible under vendor\'s commission invoice as well.', 'wc-frontend-manager-affiliate' ) ),
																																							), $product_id ) );
					?>
				</div>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		<?php
	}
	
	/**
   * Affiliate Product Commission Setting 
   */
  function wcfmaf_affiliate_product_commission_save( $new_product_id, $wcfm_products_manage_form_data ) {
  	if( isset( $wcfm_products_manage_form_data['affiliate_commission'] ) ) {
  		update_post_meta( $new_product_id, '_wcfm_affiliate_commission', $wcfm_products_manage_form_data['affiliate_commission'] );
		}
  }
	
	/**
	 * Affiliate Capability Setting 
	 */
	function wcfmaf_capability_settings_affiliate( $wcfm_capability_options ) {
		global $WCFM, $WCFMu;
	
		$affiliate = ( isset( $wcfm_capability_options['affiliate'] ) ) ? $wcfm_capability_options['affiliate'] : 'no';
		
		?>
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Affiliate', 'wc-frontend-manager-affiliate' ); ?></h3></div>
		
		<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_affiliate', array(  
																																	 "affiliate" => array('label' => __('Affiliate', 'wc-frontend-manager-affiliate') , 'name' => 'wcfm_capability_options[affiliate]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $affiliate),
																										) ) );
		
	}
	
	
	/**
	 * Vendor Registration Affiliate Tracking
	 */
	function wcfmaf_affiliate_vendor_registration( $member_id, $wcfm_membership_registration_form_data ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		if( !WC()->session ) return;
		
		$wcfm_affiliate = WC()->session->get( 'wcfm_affiliate' );
		if( $wcfm_affiliate ) {
			$wcfm_affiliate = absint( $wcfm_affiliate );
			
			if( $wcfm_affiliate ) {
				wcfm_aff_log( "WCFMAF Save in User Meta:: Vendor => " . $member_id . " Affiliate => " . $wcfm_affiliate );
				update_user_meta( $member_id, '_wcfm_affiliate', $wcfm_affiliate );
				
				// Affiliate Unset from Session
				if( apply_filters( 'wcfmmp_is_allow_reset_affiliate_after_vendor_registration', true ) && WC()->session && WC()->session->get( 'wcfm_affiliate' ) ) {
					WC()->session->__unset( 'wcfm_affiliate' );
				}
			}
		}
	}
	
	function wcfmaf_affiliate_admin_order_commission( $order_id, $order_posted, $order ) {
		global $WCFM, $WCFMmp, $WCFMaf, $wpdb;
		
		if( !$order_id ) return;
		if ( get_post_meta( $order_id, '_wcfmaf_order_processed', true ) ) return;
		
		if (!$order)
      $order = wc_get_order( $order_id );
    
    if( !is_a( $order , 'WC_Order' ) ) return;
    
    if( !WC()->session ) return;
		
		$wcfm_affiliate = WC()->session->get( 'wcfm_affiliate' );
		if( $wcfm_affiliate ) {
			$wcfm_affiliate = absint( $wcfm_affiliate );
			
			if( $wcfm_affiliate && wcfm_affiliate_is_active( $wcfm_affiliate ) ) {
    
				$items = $order->get_items( 'line_item' );
				if( !empty( $items ) ) {
					foreach( $items as $item_id => $item ) {
						
						$order_item_id = $item->get_id();
						
						// Check whether order item already processed or not
						$order_item_affiliate_processed = wc_get_order_item_meta( $order_item_id, '_wcfm_affiliate_id', true );
						if( $order_item_affiliate_processed ) return;
						
						$line_item = new WC_Order_Item_Product( $item );
						$product  = $line_item->get_product();
						$product_id = $line_item->get_product_id();
						$variation_id = $line_item->get_variation_id();
						
						if( $product_id ) {
							$vendor_id = wcfm_get_vendor_id_by_post( $product_id );
							
							if( !$vendor_id ) {
								$vendor_id = $commission_id = 0;
								$commission_amount = 0;
								
								// Product Affiliate Commission Check
								$commission = (array) get_post_meta( $product_id, '_wcfm_affiliate_commission', true );
								
								// Affiliate User Commission Rule Check
								$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
								if( $commission_rule == 'global' ) {
									$commission = (array) get_user_meta( $wcfm_affiliate, 'wcfm_affiliate_commission', true );
								}
								
								// Commission Rule Check
								$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
								if( $commission_rule == 'global' ) {
									$commission = get_option( 'wcfm_affiliate_commission', array() );
								}
				
								$mode = isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : '';
								if( $mode ) {
									wcfm_aff_log( "WCFMAF Order Commission Generate:: Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
									
									$percent = isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : '';
									$fixed = isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : '';
									$cal_mode = 'on_item'; // Fixed for Admin Products
									$commission_rule = array( 'mode' => $mode, 'percent' => $percent, 'fixed' => $fixed, 'cal_mode' => $cal_mode );
									if( $mode && ( $mode == 'fixed' ) ) {
										$commission_amount = $fixed;
									}	else {
										$commission_amount = $percent;
									}
								
									if( $commission_amount ) {
										$line_item = new WC_Order_Item_Product( $order_item_id );
										$product  = $line_item->get_product();
										$product_id = $line_item->get_product_id();
										$variation_id = $line_item->get_variation_id();
										
										if( $mode == 'percent' ) {
											$commission_amount = wc_format_decimal( $line_item->get_total() * ($commission_amount/100) );
										}
						
										$wpdb->query(
													$wpdb->prepare(
														"INSERT INTO `{$wpdb->prefix}wcfm_affiliate_orders` 
																( affiliate_id
																, vendor_id
																, order_id
																, order_commission_id
																, product_id
																, variation_id
																, quantity
																, product_price
																, item_id
																, item_type
																, item_sub_total
																, item_total
																, commission_type
																, commission_amount
																, created
																) VALUES ( %d
																, %d
																, %d
																, %d
																, %d
																, %d 
																, %d
																, %s
																, %d
																, %s
																, %s
																, %s
																, %s
																, %s
																, %s
																)"
														, $wcfm_affiliate
														, $vendor_id
														, $order_id
														, $commission_id
														, $product_id
														, $variation_id
														, $line_item->get_quantity()
														, $product->get_price()
														, $order_item_id
														, $line_item->get_type()
														, $line_item->get_subtotal()
														, $line_item->get_total()
														, 'order'
														, round($commission_amount,2)
														, date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )
											)
										);
										$affiliate_order_id = $wpdb->insert_id;
										$WCFMaf->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'commission_rule', serialize( $commission_rule ) );
						
										// Update Oder Item Meta by Affiliate Commmission Reference
										wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_id', $wcfm_affiliate );
										wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_order', $affiliate_order_id );
										wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission', $commission_amount );
										wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission_rule', serialize( $commission_rule )  );
						
										$wcfm_affiliate_user = get_userdata( $wcfm_affiliate );
										$affiliate_user_name = $wcfm_affiliate_user->display_name;
										if( $wcfm_affiliate_user->first_name && $wcfm_affiliate_user->last_name ) {
											$affiliate_user_name = $wcfm_affiliate_user->first_name . ' ' . $wcfm_affiliate_user->last_name;
										}
						
										// Affiliate Notifiction
										$wcfm_messages = sprintf( __( 'You have received commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), wc_price( $commission_amount ), '#<span class="wcfm_dashboard_item_title">' . $order->get_order_number() . '</span>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
										$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $wcfm_affiliate, 1, 0, $wcfm_messages, 'affiliate_commission' );
						
										// Admin Notifiction
										$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $commission_amount ), '#<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_view_order_url($order->get_id()) . '">' . $order->get_order_number() . '</a>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
										$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 0, 0, $wcfm_messages, 'affiliate_commission' );
						
										// Order Note
										$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $commission_amount ), '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
										$comment_id = $order->add_order_note( $wcfm_messages, 0 );
									}
								} else {
									wcfm_aff_log( "NO Affiliate Commission for this Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
								}
							}
						}
					}
				}
			}
		}
    
		update_post_meta( $order_id, '_wcfmaf_order_processed', 'yes' );
	}
	
	function wcfmaf_affiliate_vendor_order_commission( $commission_id, $order_id, $order, $vendor_id, $product_id, $order_item_id, $grosse_total, $total_commission, $is_auto_withdrawal, $order_commission_rule ) {
		global $WCFM, $WCFMmp, $WCFMaf, $wpdb;
		
		$order = wc_get_order( $order_id ); 
		if( !is_a( $order, 'WC_Order' ) ) return;
		
		$order_item_affiliate_processed = wc_get_order_item_meta( $order_item_id, '_wcfm_affiliate_id', true );
		if( $order_item_affiliate_processed ) return;
		
		$vendor_affiliate_process = false;
		
		$vendor_affiliate = get_user_meta( $vendor_id, '_wcfm_affiliate', true );
		
		$vendor_affiliate_commission = 0;
		if( $vendor_affiliate ) {
			$vendor_affiliate = absint( $vendor_affiliate );
			if( $vendor_affiliate && wcfm_affiliate_is_active( $vendor_affiliate ) ) {
				// Product Affiliate Commission Check
				$commission = (array) get_post_meta( $product_id, '_wcfm_affiliate_commission', true );
				
				// Affiliate User Commission Rule Check
				$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
				if( $commission_rule == 'global' ) {
					$commission = (array) get_user_meta( $vendor_affiliate, 'wcfm_affiliate_commission', true );
				}
				
				$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
				if( $commission_rule == 'global' ) {
					$commission = get_option( 'wcfm_affiliate_commission', array() );
					
					// Membership Affiliate Commission Check
					$wcfm_membership = get_user_meta( $vendor_id, 'wcfm_membership', true );
					if( $wcfm_membership ) {
						if( ( $wcfm_membership != -1 ) && ( $wcfm_membership != '-1' ) ) {
							$affiliate_membership_commission = get_post_meta( $wcfm_membership, 'wcfm_affiliate_commission', true );
							if( $affiliate_membership_commission ) {
								$membership_commission_rule = isset( $affiliate_membership_commission['rule'] ) ? $affiliate_membership_commission['rule'] : '';
								if( $membership_commission_rule == 'personal' ) {
									$commission = $affiliate_membership_commission;
								}
							}
						}
					}
				}
				
				$mode = isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['mode'] : '';
				if( $mode ) {
					wcfm_aff_log( "WCFMAF Vendor Order Commission Generate:: Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
					
					$percent = isset( $commission['vendor_order']['percent'] ) ? $commission['vendor_order']['percent'] : '';
					$fixed = isset( $commission['vendor_order']['fixed'] ) ? $commission['vendor_order']['fixed'] : '';
					$cal_mode = isset( $commission['vendor_order']['mode'] ) ? $commission['vendor_order']['cal_mode'] : '';
					$commission_rule = array( 'mode' => $mode, 'percent' => $percent, 'fixed' => $fixed, 'cal_mode' => $cal_mode );
					if( $mode && ( $mode == 'fixed' ) ) {
						$vendor_affiliate_commission = $fixed;
					}	else {
						$vendor_affiliate_commission = $percent;
					}
				
					if( $vendor_affiliate_commission ) {
						$line_item = new WC_Order_Item_Product( $order_item_id );
						$product  = $line_item->get_product();
						$product_id = $line_item->get_product_id();
						$variation_id = $line_item->get_variation_id();
						
						if( $mode == 'percent' ) {
							if( $cal_mode == 'on_commission' ) {
								$commission_tax              = (float) $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, 'commission_tax' );
								$total_commission            = (float) $total_commission + $commission_tax;
								$vendor_affiliate_commission = wc_format_decimal( $total_commission * ($vendor_affiliate_commission/100) );
							} else {
								$vendor_affiliate_commission = wc_format_decimal( $line_item->get_total() * ($vendor_affiliate_commission/100) );
							}
						}
						
						$wpdb->query(
									$wpdb->prepare(
										"INSERT INTO `{$wpdb->prefix}wcfm_affiliate_orders` 
												( affiliate_id
												, vendor_id
												, order_id
												, order_commission_id
												, product_id
												, variation_id
												, quantity
												, product_price
												, item_id
												, item_type
												, item_sub_total
												, item_total
												, commission_type
												, commission_amount
												, created
												) VALUES ( %d
												, %d
												, %d
												, %d
												, %d
												, %d 
												, %d
												, %s
												, %d
												, %s
												, %s
												, %s
												, %s
												, %s
												, %s
												)"
										, $vendor_affiliate
										, $vendor_id
										, $order_id
										, $commission_id
										, $product_id
										, $variation_id
										, $line_item->get_quantity()
										, $product->get_price()
										, $order_item_id
										, $line_item->get_type()
										, $line_item->get_subtotal()
										, $line_item->get_total()
										, 'vendor_order'
										, round($vendor_affiliate_commission,2)
										, date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )
							)
						);
						$affiliate_order_id = $wpdb->insert_id;
						$WCFMaf->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'vendor_commission', $commission_id );
						$WCFMaf->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'commission_rule', serialize( $commission_rule ) );
						$vendor_affiliate_process = true;
						
						// Update Order Item Meta by Affiliate Commmission Reference
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_id', $vendor_affiliate );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_order', $affiliate_order_id );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission', $vendor_affiliate_commission );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission_rule', serialize( $commission_rule )  );
						
						// Upidate Commission Item Meta by Affiliate Commission Reference
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_id', $vendor_affiliate );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_order', $affiliate_order_id );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_commission', round($vendor_affiliate_commission,2) );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_commission_rule', serialize( $commission_rule ) );
						
						// Update Vendor's Total Commission
						if( $cal_mode == 'on_commission' ) {
							$total_commission = (float) $total_commission - (float) $vendor_affiliate_commission;
							if( isset( $order_commission_rule['tax_enable'] ) && ( $order_commission_rule['tax_enable'] == 'yes' ) ) {
								$commission_tax = $total_commission * ( (float)$order_commission_rule['tax_percent'] / 100 );
								$commission_tax = apply_filters( 'wcfmmp_commission_deducted_tax', $commission_tax, $vendor_id, $product_id, $order_id, $total_commission, $order_commission_rule );
								$total_commission -= (float) $commission_tax;
								
								$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, 'commission_tax' );
								$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, 'commission_tax', round($commission_tax, 2) );
							}
							$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array( 'total_commission' => round( $total_commission, 2 ) ), array('ID' => $commission_id), array('%s'), array('%d'));
						}
						
						$wcfm_affiliate_user = get_userdata( $vendor_affiliate );
						$affiliate_user_name = $wcfm_affiliate_user->display_name;
						if( $wcfm_affiliate_user->first_name && $wcfm_affiliate_user->last_name ) {
							$affiliate_user_name = $wcfm_affiliate_user->first_name . ' ' . $wcfm_affiliate_user->last_name;
						}
						
						// Affiliate Notifiction
						$wcfm_messages = sprintf( __( 'You have received commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), wc_price( $vendor_affiliate_commission ), '#<span class="wcfm_dashboard_item_title">' . $order->get_order_number() . '</span>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $vendor_affiliate, 1, 0, $wcfm_messages, 'affiliate_commission' );
						
						// Admin Notifiction
						$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $vendor_affiliate_commission ), '#<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_view_order_url($order->get_id()) . '">' . $order->get_order_number() . '</a>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 0, 0, $wcfm_messages, 'affiliate_commission' );
						
						// Order Note
						$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $vendor_affiliate_commission ), '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product->get_id() ) . '">' . get_the_title( $product->get_id() ) . '</a>' );
						$comment_id = $order->add_order_note( $wcfm_messages, 0 );
					}
				} else {
					wcfm_aff_log( "NO Affiliate Commission for this Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $vendor_affiliate . " Rule => " . json_encode( $commission ) );
				}
			}
		}
		
		
		if( !WC()->session ) return;
		if( $vendor_affiliate_process ) return;
		
		$wcfm_affiliate = WC()->session->get( 'wcfm_affiliate' );
		if( $wcfm_affiliate ) {
			$wcfm_affiliate = absint( $wcfm_affiliate );
			
			if( !apply_filters( 'wcfmmp_is_allow_vendor_own_product_affiliate_commission', false ) ) {
				if( $vendor_id == $wcfm_affiliate ) {
					wcfm_aff_log( "WCFMAF Order - Own Product Commission generate Not Allowed! Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate );
					return;
				}
			}
			
			if( $wcfm_affiliate && wcfm_affiliate_is_active( $wcfm_affiliate ) ) {
				$commission_amount = 0;
				
				// Product Affiliate Commission Check
				$commission = (array) get_post_meta( $product_id, '_wcfm_affiliate_commission', true );
				
				// Affiliate User Commission Rule Check
				$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
				if( $commission_rule == 'global' ) {
					$commission = (array) get_user_meta( $wcfm_affiliate, 'wcfm_affiliate_commission', true );
				}
				
				// Commission Rule Check
				$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'global';
				if( $commission_rule == 'global' ) {
					$commission = get_option( 'wcfm_affiliate_commission', array() );
					
					// Membership Affiliate Commission Check
					$wcfm_membership = get_user_meta( $vendor_id, 'wcfm_membership', true );
					if( $wcfm_membership ) {
						if( ( $wcfm_membership != -1 ) && ( $wcfm_membership != '-1' ) ) {
							$affiliate_membership_commission = get_post_meta( $wcfm_membership, 'wcfm_affiliate_commission', true );
							if( $affiliate_membership_commission ) {
								$membership_commission_rule = isset( $affiliate_membership_commission['rule'] ) ? $affiliate_membership_commission['rule'] : '';
								if( $membership_commission_rule == 'personal' ) {
									$commission = $affiliate_membership_commission;
								}
							}
						}
					}
				}
				
				$mode = isset( $commission['order']['mode'] ) ? $commission['order']['mode'] : '';
				if( $mode ) {
					wcfm_aff_log( "WCFMAF Order Commission Generate:: Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
					
					$percent = isset( $commission['order']['percent'] ) ? $commission['order']['percent'] : '';
					$fixed = isset( $commission['order']['fixed'] ) ? $commission['order']['fixed'] : '';
					$cal_mode = isset( $commission['order']['cal_mode'] ) ? $commission['order']['cal_mode'] : 'on_item';
					$commission_rule = array( 'mode' => $mode, 'percent' => $percent, 'fixed' => $fixed, 'cal_mode' => $cal_mode );
					if( $mode && ( $mode == 'fixed' ) ) {
						$commission_amount = $fixed;
					}	else {
						$commission_amount = $percent;
					}
				
					if( $commission_amount ) {
						$line_item = new WC_Order_Item_Product( $order_item_id );
						$product  = $line_item->get_product();
						$product_id = $line_item->get_product_id();
						$variation_id = $line_item->get_variation_id();
						
						if( $mode == 'percent' ) {
							if( $cal_mode == 'on_commission' ) {
								$commission_tax    = (float) $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, 'commission_tax' );
								$total_commission  = (float) $total_commission + $commission_tax;
								$commission_amount = wc_format_decimal( $total_commission * ($commission_amount/100) );
							} else {
								$commission_amount = wc_format_decimal( $line_item->get_total() * ($commission_amount/100) );
							}
						}
						
						$wpdb->query(
									$wpdb->prepare(
										"INSERT INTO `{$wpdb->prefix}wcfm_affiliate_orders` 
												( affiliate_id
												, vendor_id
												, order_id
												, order_commission_id
												, product_id
												, variation_id
												, quantity
												, product_price
												, item_id
												, item_type
												, item_sub_total
												, item_total
												, commission_type
												, commission_amount
												, created
												) VALUES ( %d
												, %d
												, %d
												, %d
												, %d
												, %d 
												, %d
												, %s
												, %d
												, %s
												, %s
												, %s
												, %s
												, %s
												, %s
												)"
										, $wcfm_affiliate
										, $vendor_id
										, $order_id
										, $commission_id
										, $product_id
										, $variation_id
										, $line_item->get_quantity()
										, $product->get_price()
										, $order_item_id
										, $line_item->get_type()
										, $line_item->get_subtotal()
										, $line_item->get_total()
										, 'order'
										, round($commission_amount,2)
										, date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )
							)
						);
						$affiliate_order_id = $wpdb->insert_id;
						$WCFMaf->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'vendor_commission', $commission_id );
						$WCFMaf->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'commission_rule', serialize( $commission_rule ) );
						
						// Update Oder Item Meta by Affiliate Commmission Reference
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_id', $wcfm_affiliate );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_order', $affiliate_order_id );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission', $commission_amount );
						wc_update_order_item_meta( $order_item_id, '_wcfm_affiliate_commission_rule', serialize( $commission_rule )  );
						
						// Upidate Commission Item Meta by Affiliate Commission Reference
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_id', $wcfm_affiliate );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_order', $affiliate_order_id );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_commission', round($commission_amount,2) );
						$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, '_wcfm_affiliate_commission_rule', serialize( $commission_rule ) );
						
						// Update Vendor's Total Commission
						if( $cal_mode == 'on_commission' ) {
							$total_commission = (float) $total_commission - (float) $commission_amount;
							if( isset( $order_commission_rule['tax_enable'] ) && ( $order_commission_rule['tax_enable'] == 'yes' ) ) {
								$commission_tax = $total_commission * ( (float)$order_commission_rule['tax_percent'] / 100 );
								$commission_tax = apply_filters( 'wcfmmp_commission_deducted_tax', $commission_tax, $vendor_id, $product_id, $order_id, $total_commission, $order_commission_rule );
								$total_commission -= (float) $commission_tax;
								
								$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, 'commission_tax' );
								$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, 'commission_tax', round($commission_tax, 2) );
							}
							$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array( 'total_commission' => round( $total_commission, 2 ) ), array('ID' => $commission_id), array('%s'), array('%d'));
						}
						
						$wcfm_affiliate_user = get_userdata( $wcfm_affiliate );
						$affiliate_user_name = $wcfm_affiliate_user->display_name;
						if( $wcfm_affiliate_user->first_name && $wcfm_affiliate_user->last_name ) {
							$affiliate_user_name = $wcfm_affiliate_user->first_name . ' ' . $wcfm_affiliate_user->last_name;
						}
						
						// Affiliate Notifiction
						$wcfm_messages = sprintf( __( 'You have received commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), wc_price( $commission_amount ), '#<span class="wcfm_dashboard_item_title">' . $order->get_order_number() . '</span>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $wcfm_affiliate, 1, 0, $wcfm_messages, 'affiliate_commission' );
						
						// Admin Notifiction
						$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> for order <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $commission_amount ), '#<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_view_order_url($order->get_id()) . '">' . $order->get_order_number() . '</a>', '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 0, 0, $wcfm_messages, 'affiliate_commission' );
						
						// Order Note
						$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> item <b>%s</b>', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $commission_amount ), '<a class="wcfm_dashboard_item_title" target="_blank" href="' . get_permalink( $product_id ) . '">' . get_the_title( $product_id ) . '</a>' );
						$comment_id = $order->add_order_note( $wcfm_messages, 0 );
					}
				} else {
					wcfm_aff_log( "NO Affiliate Commission for this Order => " . $order_id . " Vendor => " . $vendor_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
				}
			}
		}
	}
	
	/**
	 * WCFM AFFILIATE Core JS
	 */
	function wcfmaf_scripts() {
 		global $WCFM, $WCFMaf, $wp, $WCFM_Query;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
	  if( is_wcfm_affiliate_registration_page() ) {
			$WCFM->library->load_select2_lib();
			wp_enqueue_script( 'wc-country-select' );
			wp_enqueue_script( 'wcfm_affiliate_registration_js', $WCFMaf->library->js_lib_url . 'registration/wcfmaf-script-affiliate-registration.js', array('jquery' ), $WCFMaf->version, true );
			
			$wcfm_affiliate_registration_params = array( 'is_strength_check' => apply_filters( 'wcfm_is_allow_password_strength_check', true ), 'short' => __( 'Too short', 'wc-frontend-manager' ), 'weak' => __( 'Weak', 'wc-frontend-manager' ), 'good' => __( 'Good', 'wc-frontend-manager' ), 'strong' => __( 'Strong', 'wc-frontend-manager' ), 'Password_failed' => __( 'Password strength should be atleast "Good".', 'wc-frontend-manager' ) );
			wp_localize_script( 'wcfm_affiliate_registration_js', 'wcfm_affiliate_registration_params', $wcfm_affiliate_registration_params );
			
			if( apply_filters( 'wcfm_is_allow_affiliate_registration_recaptcha', true ) ) {
				if ( class_exists( 'anr_captcha_class' ) && function_exists( 'anr_captcha_form_field' ) && function_exists( 'anr_get_option' ) ) {
					$site_key = trim( anr_get_option( 'site_key' ) );
					$theme    = anr_get_option( 'theme', 'light' );
					$size     = anr_get_option( 'size', 'normal' );
					$language = trim( anr_get_option( 'language' ) );
					$badge    = esc_js( anr_get_option( 'badge', 'bottomright' ) );
					
					$wcfm_affiliate_registration_captcha_params = array( 'site_key' => $site_key, 'theme' => $theme, 'size' => $size, 'language' => $language, 'badge' => $badge );
					wp_localize_script( 'wcfm_affiliate_registration_js', 'wcfm_affiliate_registration_captcha_params', $wcfm_affiliate_registration_captcha_params );
				}
			}
		}
 	}
 	
 	/**
 	 * WCFM Affiliate Core CSS
 	 */
 	function wcfmaf_styles() {
 		global $WCFM, $WCFMaf, $wp, $WCFM_Query;
 		
 		if( isset( $_REQUEST['fl_builder'] ) ) return;
 		
 		$wcfm_options = $WCFM->wcfm_options;
 		
		if( is_wcfm_affiliate_registration_page() ) {
			wp_enqueue_style( 'wcfm_affiliate_registration_css',  $WCFMaf->library->css_lib_url . 'registration/wcfmaf-style-affiliate-registration.css', array(), $WCFMaf->version );
			
			if( is_rtl() ) {
				wp_enqueue_style( 'wcfm_affiliate_registration_rtl_css',  $WCFMaf->library->css_lib_url . 'registration/wcfmaf-style-affiliate-registration-rtl.css', array( 'wcfm_affiliate_registration_css' ), $WCFMaf->version );
			}
		}
 	}
}