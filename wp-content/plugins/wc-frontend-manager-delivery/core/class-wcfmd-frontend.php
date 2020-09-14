<?php
/**
 * WCFM Delivery plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmd/core
 * @version   1.0.0
 */
 
class WCFMd_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMd;
		
		if( apply_filters( 'wcfm_is_pref_delivery', true ) ) {
			// WCFM Shop Managrs End Points
			add_filter( 'wcfm_query_vars', array( &$this, 'wcfmd_delivery_wcfm_query_vars' ), 90 );
			add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfmd_delivery_endpoint_title' ), 90, 2 );
			add_action( 'init', array( &$this, 'wcfmd_delivery_init' ), 90 );
			
			// WCFM Appointments Endpoint Edit
			add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfmd_delivery_endpoints_slug' ) );
			
			// WCFM Menu Filter
			add_filter( 'wcfm_menus', array( &$this, 'wcfmd_delivery_menus' ), 300 );
			add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfmd_delivery_menu_dependancy_map' ) );
			
			// Binding Delivery Boy User Role for WCFM Dashboard Access
			add_filter( 'wcfm_allwoed_user_roles', array( &$this, 'allow_delivery_boy_user_role' ) );
			
			// Set Delivery Boy Home Page
			add_filter( 'wcfm_login_redirect', array( &$this, 'wcfmd_delivery_boy_login_redirect' ), 50, 2 );
			//add_filter( 'wcfm_dashboard_home', array( &$this, 'wcfmd_delivery_boy_home_page' ) );
			
			// Disable Delivery Boy Dashboard Elements
			add_filter( 'wcfm_is_allow_home_in_menu', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_direct_message', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_enquiry', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_notice', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_knowledgebase', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_address_profile', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_social_profile', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_settings', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_capability_controller', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_articles', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_coupons', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_customer', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_listings', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_orders', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_products', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_reports', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_vendors', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_payments', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_withdrawal_requets', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_refund_requests', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_reviews', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_followers', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_support', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_subscriptions', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_membership', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_groups', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_manager', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_staff', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_media', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_affiliate', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_affiliate', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_delivery', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_manage_delivery_boys', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			
			// Quick Access and Edit Options Restrict
			add_filter( 'wcfm_is_allow_catalog_quick_access', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			add_filter( 'wcfm_is_allow_catalog_product_manage', array( &$this, 'wcfmd_is_allow_dashboard_element' ), 750 );
			
			// Delivery Message Types
			add_filter( 'wcfm_message_types', array( &$this, 'wcfm_delivery_message_types' ), 150 );
			
			// My Account Dashboard Menu
			add_filter( 'woocommerce_account_menu_items', array( &$this, 'wcfmd_my_account_menu_items' ), 210 );
			add_filter( 'woocommerce_get_endpoint_url', array( &$this,  'wcfmd_my_account_endpoint_redirect'), 10, 4 );
			
			// Restrict Delivery Boys to see only their attachments
			add_action('pre_get_posts', array( &$this, 'wcfm_delivery_only_attachments' ) );
			
			// Delivery Boy Assign Option
			if( apply_filters( 'wcfm_is_allow_delivery', true ) ) {
				if( WCFM_Dependencies::wcfmu_plugin_active_check() && apply_filters( 'wcfm_is_pref_shipment_tracking', true ) && apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) { // With WCFM Ultimate
					add_filter( 'wcfm_shipment_tracking_fields', array( &$this, 'wcfmd_shipment_tracking_fields' ), 10, 3 );
					add_action( 'wcfm_after_order_mark_shipped', array( &$this, 'wcfmd_delivery_boy_update' ), 10, 6 );
				} else {
					if( !wcfm_is_vendor() ) {
						add_filter( 'wcfm_orders_actions', array( &$this, 'wcfmd_delivery_orders_actions' ), 20, 3 );
					} else {
						add_filter( 'wcfmmarketplace_orders_actions', array( &$this, 'wcfmd_wcfmmarketplace_delivery_orders_actions' ), 20, 4 );
					}
					
					// Order Details Delivery View
					add_action( 'end_wcfm_orders_details', array( &$this, 'end_wcfm_orders_details_load_views' ), 15 );
					
					// Order Delivery Boy Assigned 
					add_action( 'wcfmd_delivery_boy_assigned', array( &$this, 'wcfmd_delivery_boy_assigned' ), 10, 6 );
				}
			
				// Delivery Boy in Order Dashboard Column
				// Order Delivery Boy Filter
				add_action( 'wcfm_after_orders_filter_wrap', array( &$this, 'wcfmd_Orders_delivery_boy_filter' ) );
				
				// Orders Columns Before
				add_action( 'wcfm_order_columns_before', array( &$this, 'wcfmd_order_columns_before' ) );
				
				// Orders Screen Manager Columns
				add_action( 'wcfm_screen_manager_columns', array( &$this, 'wcfmd_screen_manager_columns' ), 10, 2 );
				
				// Orders Data Table Columns Defs
				add_action( 'wcfm_datatable_column_defs', array( &$this, 'wcfmd_datatable_column_defs' ), 10, 2 );
				
				// Orders Data Table Columns Priority
				add_action( 'wcfm_datatable_column_priority', array( &$this, 'wcfmd_datatable_column_priority' ), 10, 2 );
				
				// Orders Data Table Custom Columns Data Before
				add_filter( 'wcfm_orders_custom_columns_data_before', array( &$this, 'wcfmd_orders_custom_columns_data_before' ), 10, 5 );
			}
		}
		
		// Delivery Setting
		add_action( 'end_wcfm_settings', array( &$this, 'wcfmd_delivery_settings' ), 15 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfmd_delivery_settings_update' ), 20 );
		
		// Delivery Capability Setting
		add_action( 'wcfm_capability_settings_miscellaneous', array( &$this, 'wcfmd_capability_settings_delivery' ), 9 );
		
		//add_action( 'end_wcfm_vendor_settings', array( &$this, 'wcfmd_delivery_settings' ), 25 );
		//add_action( 'wcfm_vendor_settings_update', array( &$this, 'wcfmd_delivery_vendor_settings_update' ), 20, 2 );
		
	}
	
	/**
   * WCFM Delivery Query Var
   */
  function wcfmd_delivery_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_delivery_vars = array(
			'wcfm-deliveries'             => ! empty( $wcfm_modified_endpoints['wcfm-deliveries'] ) ? $wcfm_modified_endpoints['wcfm-deliveries'] : 'deliveries',
			'wcfm-delivery-boys'          => ! empty( $wcfm_modified_endpoints['wcfm-delivery-boys'] ) ? $wcfm_modified_endpoints['wcfm-delivery-boys'] : 'delivery-boys',
			'wcfm-delivery-boys-manage'   => ! empty( $wcfm_modified_endpoints['wcfm-delivery-boys-manage'] ) ? $wcfm_modified_endpoints['wcfm-delivery-boys-manage'] : 'delivery-boys-manage',
			'wcfm-delivery-boys-stats'    => ! empty( $wcfm_modified_endpoints['wcfm-delivery-boys-stats'] ) ? $wcfm_modified_endpoints['wcfm-delivery-boys-stats'] : 'delivery-boys-stats',
		);
		
		$query_vars = array_merge( $query_vars, $query_delivery_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Delivery End Point Title
   */
  function wcfmd_delivery_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-deliveries' :
				$title = __( 'Deliveries', 'wc-frontend-manager-delivery' );
			break;
			
			case 'wcfm-delivery-boys' :
				$title = __( 'Delivery Boys', 'wc-frontend-manager-delivery' );
			break;
			
			case 'wcfm-delivery-boys-manage' :
				$title = __( 'Delivery Boy Manage', 'wc-frontend-manager-delivery' );
			break;
			
			case 'wcfm-delivery-boys-stats' :
				$title = __( 'Delivery Boy Stats', 'wc-frontend-manager-delivery' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Delivery Endpoint Intialize
   */
  function wcfmd_delivery_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfma_delivery' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfma_delivery', 1 );
		}
  }
  
  /**
	 * WCFM Delivery Endpoiint Edit
	 */
	function wcfmd_delivery_endpoints_slug( $endpoints ) {
		
		$wcfma_delivery_endpoints = array(
													'wcfm-deliveries'              => 'deliveries',
													'wcfm-delivery-boys'           => 'delivery-boys',
													'wcfm-delivery-boys-manage'    => 'wcfm-delivery-boys-manage',
													'wcfm-delivery-boys-stats'     => 'wcfm-delivery-boys-stats',
													);
		
		$endpoints = array_merge( $endpoints, $wcfma_delivery_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Delivery Menu
   */
  function wcfmd_delivery_menus( $menus ) {
  	global $WCFM;
  	
  	if( apply_filters( 'wcfm_is_allow_delivery', true ) ) {
		
			$menus = array_slice($menus, 0, 3, true) +
											array( 'wcfm-delivery-boys' => array(  'label'      => __( 'Delivery Persons', 'wc-frontend-manager-delivery'),
																														 'url'        => get_wcfm_delivery_boys_dashboard_url(),
																														 'icon'       => 'shipping-fast',
																														 'has_new'    => 'yes',
																														 'new_class'  => 'wcfm_sub_menu_items_delivery_boys_manage',
																														 'new_url'    => get_wcfm_delivery_boys_manage_url(),
																														 'priority'   => 53
																													) )	 +
														array_slice($menus, 3, count($menus) - 3, true) ;
		}
		
		if( wcfm_is_delivery_boy() ) {
			$menus = array( 'wcfm-deliveries' => array(  'label'      => __( 'Deliveries', 'wc-frontend-manager-delivery'),
																									 'url'        => get_wcfm_deliveries_url(),
																									 'icon'       => 'shipping-fast',
																									 'priority'   => 53
																								 ) );
		}
		
  	return $menus;
  }
  
  /**
   * WCFM Delivery Menu Dependency
   */
  function wcfmd_delivery_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-delivery-boys-manage'] = 'wcfm-delivery-boys';
  	$menu_dependency_mapping['wcfm-delivery-boys-stats'] = 'wcfm-delivery-boys';
  	return $menu_dependency_mapping;
  }
  
  /**
	 * WCFM Allow Delivery Boys Users
	 */
 	function allow_delivery_boy_user_role( $allowed_roles ) {
  	$allowed_roles[] = 'wcfm_delivery_boy';
  	return $allowed_roles;
  }
  
  /**
   * Delivery Boy Login Redirect
   */
  function wcfmd_delivery_boy_login_redirect( $redirect_to, $user ) {
  	if ( $user && !is_wp_error( $user ) && $user->roles && in_array( apply_filters( 'wcfm_delivery_boy_user_role', 'wcfm_delivery_boy' ), (array) $user->roles ) ) {
			$redirect_to = get_wcfm_deliveries_url();
		}
  	return $redirect_to;
  }
  
  /**
   * Set Home URL for Delivery Boys
   */
  function wcfmd_delivery_boy_home_page( $home_url ) {
  	if( wcfm_is_delivery_boy() ) $home_url = get_wcfm_deliveries_url();
  	return $home_url;
  }
  
  /**
   * Disable Home Menu for Delivery Boys
   */
  function wcfmd_is_allow_dashboard_element( $is_allow ) {
  	if( wcfm_is_delivery_boy() ) $is_allow = false;
  	return $is_allow;
  }
  
  /**
   * Delivery Message Types
   */
  function wcfm_delivery_message_types( $message_types ) {
  	if( apply_filters( 'wcfm_is_allow_delivery', true ) ) {
  		$message_types['new_delivery_boy'] = __( 'New Delivery Boy', 'wc-frontend-manager-delivery' );
  		$message_types['delivery_boy_assign'] = __( 'Delivery Boy Assigned', 'wc-frontend-manager-delivery' );
  		$message_types['delivery_complete'] = __( 'Delivery Complete', 'wc-frontend-manager-delivery' );
  	}
  	
  	if( wcfm_is_delivery_boy() ) {
  		$message_types = array();
  		$message_types['delivery_boy_assign'] = __( 'Delivery Boy Assigned', 'wc-frontend-manager-delivery' );
  		$message_types['delivery_complete'] = __( 'Delivery Complete', 'wc-frontend-manager-delivery' );
  	}
  	return $message_types;
  }
  
  /**
	 * WC My Account Dashboard Link
	 */
	function wcfmd_my_account_menu_items( $items ) {
		global $WCFM, $WCFMmp;
		
		if( wcfm_is_delivery_boy() ) {
			$dashboard_page_title = __( 'Delivery Dashboard', 'wc-frontend-manager-delivery' );
			$dashboard_page_title = apply_filters( 'wcfmd_wcmy_dashboard_page_title', $dashboard_page_title ); 
			
			/*$items = array_slice($items, 0, count($items) - 2, true) +
																		array(
																					"wcfm-delivery-manager" => $dashboard_page_title
																					) +
																		array_slice($items, count($items) - 2, count($items) - 1, true) ;*/
																		
			$items = array( "wcfm-delivery-manager" => $dashboard_page_title );															
		}
																	
		return $items;
	}
	
	function wcfmd_my_account_endpoint_redirect( $url, $endpoint, $value, $permalink ) {
		if( $endpoint == 'wcfm-delivery-manager')
      $url = get_wcfm_deliveries_url();
    return $url;
	}
	
	/**
	 * Restrict Delivery Boys to see only their attachments
	 */
	function wcfm_delivery_only_attachments( $wp_query_obj ) {
		global $current_user, $pagenow;
		
		if( !wcfm_is_delivery_boy() ) 
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
   * Delivery Assign - WCFM ULtimate
   */
  function wcfmd_shipment_tracking_fields( $shipment_fields, $order_id, $order_item_id ) {
  	global $WCFM, $WCFMu, $WCFMd;
  	
  	$wcfm_delivery_boys_array = wcfm_get_delivery_boys();
		$delivery_users = array( '' => __( '-Select Delivery Boy-', 'wc-frontend-manager-delivery' ) );
		
		if(!empty($wcfm_delivery_boys_array)) {
			foreach( $wcfm_delivery_boys_array as $wcfm_delivery_boys_single ) {
				$delivery_users[$wcfm_delivery_boys_single->ID] = $wcfm_delivery_boys_single->first_name . ' ' . $wcfm_delivery_boys_single->last_name . ' (' . $wcfm_delivery_boys_single->user_email . ')';
			}
		}
		
		$wcfm_delivery_boy  = wc_get_order_item_meta( $order_item_id, 'wcfm_delivery_boy', true );
		
		$delivery_fields = array( "wcfm_delivery_boy"  => array( 'label' => __( 'Delivery Boy', 'wc-frontend-manager-delivery' ), 'type' => 'select', 'options' => $delivery_users, 'class' => 'wcfm-select wcfm_popup_input', 'label_class' => 'shipment_tracking_input wcfm_popup_label', 'value' => $wcfm_delivery_boy ) );
  	$shipment_fields = array_merge( $shipment_fields, $delivery_fields );
  	
  	if( apply_filters( 'wcfmd_is_allow_shipment_tracking_optional', true ) ) {
  		if( isset( $shipment_fields['wcfm_tracking_code'] ) ) {
  			unset( $shipment_fields['wcfm_tracking_code']['custom_attributes'] );	
  		}
  		if( isset( $shipment_fields['wcfm_tracking_url'] ) ) {
  			unset( $shipment_fields['wcfm_tracking_url']['custom_attributes'] );	
  		}
  	}
  	
  	return $shipment_fields;
  }
  
  /**
   * Delivery Update - WCFM Ultimate 
   */
  function wcfmd_delivery_boy_update( $order_id, $order_item_id, $tracking_code, $tracking_url, $product_id, $wcfm_tracking_data ) {
  	global $WCFM, $WCFMu, $WCFMd, $wpdb;
  	
  	$this->wcfmd_delivery_boy_assigned( $order_id, $order_item_id, $wcfm_tracking_data, $product_id );
  }
  
  public function wcfmd_delivery_boy_assigned( $order_id, $order_item_id, $wcfm_tracking_data, $product_id ) {
  	global $WCFM, $WCFMmp, $WCFMu, $WCFMd, $wpdb;
  	
  	$wcfm_delivery_boy  = absint( $wcfm_tracking_data['wcfm_delivery_boy'] );
  	
  	if( $wcfm_delivery_boy ) {
  		$wcfm_delivery_boy_user = get_userdata( $wcfm_delivery_boy );
  		
  		// Order Item Meta Update
  		if( apply_filters( 'wcfm_is_allow_delivery_boy_as_meta', true ) ) {
  			wc_update_order_item_meta( $order_item_id, 'wcfm_delivery_boy', $wcfm_delivery_boy );
  		}
  		
  		// Order Meta Update
  		wcfm_update_order_delivery_boys_meta( $order_id );
  		
  		// Delivery Order Update
  		$order          = wc_get_order( $order_id );
  		
  		$customer_id = 0;
			if ( $order->get_user_id() ) 
				$customer_id = $order->get_user_id();
			
			$payment_method = ! empty( $order->get_payment_method() ) ? $order->get_payment_method() : '';
  		
  		$line_item    = new WC_Order_Item_Product( $order_item_id );
			$product      = $line_item->get_product();
			$product_id   = $line_item->get_product_id();
			$variation_id = $line_item->get_variation_id();
			
			$vendor_id    = 0;
			$vendor_id    = wcfm_get_vendor_id_by_post( $product_id );
			
			$sql = $wpdb->prepare(
					"INSERT INTO `{$wpdb->prefix}wcfm_delivery_orders` 
							( vendor_id
							, order_id
							, customer_id
							, payment_method
							, product_id
							, variation_id
							, quantity
							, product_price
							, item_id
							, item_sub_total
							, item_total
							, delivery_boy
							) VALUES ( %d
							, %d
							, %d
							, %s
							, %d
							, %d
							, %d
							, %s
							, %d
							, %s
							, %s
							, %d
							) ON DUPLICATE KEY UPDATE `delivery_boy` = %d"
					, $vendor_id
					, $order_id
					, $customer_id
					, $payment_method
					, $product_id
					, $variation_id
					, $line_item->get_quantity()
					, $product->get_price()
					, $order_item_id
					, $line_item->get_subtotal()
					, $line_item->get_total()
					, $wcfm_delivery_boy
					, $wcfm_delivery_boy
					);
  		
			$wpdb->query($sql);
			$delivery_id = $wpdb->insert_id;
			
			// Update Delivery Meta
			$order_item_processed_id = wc_get_order_item_meta( $order_item_id, '_wcfmmp_order_item_processed', true );
			if( $WCFMmp && $order_item_processed_id ) {
				$gross_sales_total = (float) $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $order_item_processed_id, 'gross_sales_total' );
				$this->wcfmd_update_delivery_meta( $delivery_id, 'gross_sales_total', $gross_sales_total );
			}
  		
  		
  		// Notification Update
  		$vendor_id = 0;
  		if( wcfm_is_vendor() ) {
  			$vendor_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
				$shop_name = $WCFM->wcfm_vendor_support->wcfm_get_vendor_store_by_vendor( absint($vendor_id) );
  			if( apply_filters( 'wcfm_is_allow_itemwise_notification', true ) ) {
					$wcfm_messages = sprintf( __( '<b>%s</b> has assigned <b>%s</b> as Delivery Boy for order <b>%s</b> item <b>%s</b>.', 'wc-frontend-manager-delivery' ), $shop_name, '<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_delivery_boys_stats_url($wcfm_delivery_boy) . '">' . $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name . '</a>', '#<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_view_order_url($order_id) . '">' . $order_id . '</a>', get_the_title( $product_id ) );
					$WCFM->wcfm_notification->wcfm_send_direct_message( $vendor_id, 0, 0, 1, $wcfm_messages, 'delivery_boy_assign' );
					
					// Order Note Update
					add_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
					$wcfm_messages = sprintf( __( '<b>%s</b> has assigned <b>%s</b> as Delivery Boy for order <b>%s</b> item <b>%s</b>.', 'wc-frontend-manager-delivery' ), $shop_name, $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name, '#<span class="wcfm_dashboard_item_title">' . $order_id . '</span>', get_the_title( $product_id ) );
					$comment_id = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );
					add_comment_meta( $comment_id, '_vendor_id', $vendor_id );
					remove_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
				} else {
					if( ( $vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$vendor_id, true ) ) || ( !$vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$order_id, true ) ) ) {
						$wcfm_messages = sprintf( __( '<b>%s</b> has assigned <b>%s</b> as Delivery Boy for order <b>%s</b>.', 'wc-frontend-manager-delivery' ), $shop_name, '<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_delivery_boys_stats_url($wcfm_delivery_boy) . '">' . $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name . '</a>', '#<a class="wcfm_dashboard_item_title" target="_blank" href="'.get_wcfm_view_order_url($order_id) . '">' . $order_id . '</a>' );
						$WCFM->wcfm_notification->wcfm_send_direct_message( $vendor_id, 0, 0, 1, $wcfm_messages, 'delivery_boy_assign' );
						
						// Order Note Update
						add_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
						$wcfm_messages = sprintf( __( '<b>%s</b> has assigned <b>%s</b> as Delivery Boy for order <b>%s</b>.', 'wc-frontend-manager-delivery' ), $shop_name, $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name, '#<span class="wcfm_dashboard_item_title">' . $order_id . '</span>' );
						$comment_id = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );
						add_comment_meta( $comment_id, '_vendor_id', $vendor_id );
						remove_filter( 'woocommerce_new_order_note_data', array( $WCFM->wcfm_marketplace, 'wcfm_update_comment_vendor' ), 10, 2 );
					}
				}
      } else {
      	if( apply_filters( 'wcfm_is_allow_itemwise_notification', true ) ) {
					$wcfm_messages = sprintf( __( '<b>%s</b> assigned as Delivery Boy for order <b>%s</b> item <b>%s</b>.', 'wc-frontend-manager-delivery' ), $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name, '#'.$order_id, get_the_title( $product_id ) );
					$comment_id = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );
				} else {
					if( ( $vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$vendor_id, true ) ) || ( !$vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$order_id, true ) ) ) {
						$wcfm_messages = sprintf( __( '<b>%s</b> assigned as Delivery Boy for order <b>%s</b>.', 'wc-frontend-manager-delivery' ), $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name, '#'.$order_id );
						$comment_id = $order->add_order_note( $wcfm_messages, apply_filters( 'wcfm_is_allow_delivery_note_to_customer', '1' ) );
					}
				}
      }
      
      // Deivery Boy Notification
      if( apply_filters( 'wcfm_is_allow_itemwise_notification', true ) ) {
      	$wcfm_messages = sprintf( __( 'You have assigned to order <b>%s</b> item <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<span class="wcfm_dashboard_item_title">' . $order_id . '</span>', get_the_title( $product_id ) );
      	$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $wcfm_delivery_boy, 1, 0, $wcfm_messages, 'delivery_boy_assign' );
      	
      	do_action( 'wcfmd_after_delivery_boy_assigned', $order_id, $order_item_id, $wcfm_tracking_data, $product_id, $wcfm_delivery_boy, $wcfm_messages );
      } else {
      	if( ( $vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$vendor_id, true ) ) || ( !$vendor_id && !get_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$order_id, true ) ) ) {
      		$wcfm_messages = sprintf( __( 'You have assigned to order <b>%s</b>.', 'wc-frontend-manager-delivery' ), '#<span class="wcfm_dashboard_item_title">' . $order_id . '</span>' );
      		$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $wcfm_delivery_boy, 1, 0, $wcfm_messages, 'delivery_boy_assign' );
      		
      		do_action( 'wcfmd_after_delivery_boy_assigned', $order_id, $order_item_id, $wcfm_tracking_data, $product_id, $wcfm_delivery_boy, $wcfm_messages );
      	}
      }
      
      if( $vendor_id ) {
      	update_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$vendor_id, 'yes' );
      	update_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$order_id, 'yes' );
      } else {
      	update_post_meta( $order_id, '_wcfm_order_delivery_assigned_'.$order_id, 'yes' );
      }
  	}
  }
  
  /**
	 * Update Delivery metas
	 */
	public function wcfmd_update_delivery_meta( $delivery_id, $key, $value ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$wpdb->query(
						$wpdb->prepare(
							"INSERT INTO `{$wpdb->prefix}wcfm_delivery_orders_meta` 
									( order_delivery_id
									, `key`
									, `value`
									) VALUES ( %d
									, %s
									, %s
									)"
							, $delivery_id
							, $key
							, $value
			)
		);
		$delivery_meta_id = $wpdb->insert_id;
		
		return $delivery_meta_id;
	}
	
	/**
	 * Get Delivery metas
	 */
	public function wcfmd_get_delivery_meta( $delivery_id, $key ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$delivery_meta = $wpdb->get_var( 
						$wpdb->prepare(
							"SELECT `value` FROM `{$wpdb->prefix}wcfm_delivery_orders_meta` 
							     WHERE 
							     `order_delivery_id` = %d
									  AND `key` = %s
									"
							, $delivery_id
							, $key
			)
		);
		return $delivery_meta;
	}
  
  /**
   * Admin Order Delivery Boy Assign Action
   */
  public function wcfmd_delivery_orders_actions( $actions, $user_id, $order ) {
  	global $WCFM, $WCFMd;
  	
  	// Virtual Order Handling
  	if( !$order->needs_shipping_address() || !$order->get_formatted_shipping_address() ) return $actions;
  	
  	// Renewal Order Handaling
  	if( function_exists( 'wcs_order_contains_subscription' ) && ( ( wcs_order_contains_subscription( $order->get_id(), 'renewal' ) || wcs_order_contains_subscription( $order->get_id(), 'renewal' ) ) && !apply_filters( 'wcfm_is_allow_renew_order_shipment', true ) ) ) return $actions;
  	
  	$order_status = sanitize_title( $order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
  	
		$items = $order->get_items();
		$order_item_id = 0;
		foreach ( $items as $item_id => $item ) {
			$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $item->get_product() );
		}

		if ( $needs_shipping ) {
			$actions .= '<a class="wcfm-action-icon" href="' . get_wcfm_view_order_url($order->get_id()) . '#sm_order_delivery_options"><span class="wcfmfa fa-shipping-fast text_tip" data-tip="' . esc_attr__( 'Assign Delivery Boy', 'wc-frontend-manager-delivery' ) . '"></span></a>';
		} 
  	
  	return $actions;
  }
  
  /**
   * WCFM Marketplace Order Delivery Boy Assign Action
   */
  public function wcfmd_wcfmmarketplace_delivery_orders_actions( $actions, $user_id, $order, $the_order ) {
  	global $WCFM, $WCFMd;
  	
  	// Virtual Order Handling
  	if( !$the_order->needs_shipping_address() || !$the_order->get_formatted_shipping_address() ) return $actions;
  	
  	// Renewal Order Handaling
  	if( function_exists( 'wcs_order_contains_subscription' ) && ( ( wcs_order_contains_subscription( $the_order->get_id(), 'renewal' ) || wcs_order_contains_subscription( $the_order->get_id(), 'renewal' ) ) && !apply_filters( 'wcfm_is_allow_renew_order_shipment', true ) ) ) return $actions;
  	
		$needs_shipping = true; 
		if( !$order->product_id ) return $actions;
		if( $order->refund_status   == 'requested' ) return $actions;
		if( $order->is_refunded ) return $actions;
		
		$order_status = sanitize_title( $the_order->get_status() );
		if( in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) return $actions;
		
		// See if product needs shipping 
		$shipped = $order->shipping_status;
		$product_ids = explode( ",", $order->product_id );
		foreach( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id ); 
			$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $product ); 
			if( $needs_shipping ) break;
		}

		if ( $needs_shipping ) {
			if( $order->order_item_ids ) $order->item_id = $order->order_item_ids;
			$actions .= '<a class="wcfm_wcfmmarketplace_order_delivery wcfm-action-icon" href="#" data-productid="' . $order->product_id . '" data-orderitemid="' . $order->item_id . '" data-orderid="' . $order->order_id . '"><span class="wcfmfa fa-shipping-fast text_tip" data-tip="' . esc_attr__( 'Assign Delivery Boy', 'wc-frontend-manager-delivery' ) . '"></span></a>';
		} 
		
  	return $actions;
  }
  
  public function end_wcfm_orders_details_load_views( ) {
	  global $WCFMd;
	  $WCFMd->template->get_template( 'wcfmd-view-orders-details.php' );
	}
	
	/**
	 * Orders Dasboard Delivery Boy Filter
	 */
	function wcfmd_Orders_delivery_boy_filter() {
		global $WCFM, $WCFMu, $WCFMd;
		
		if( wcfm_is_vendor() ) return;
		
		$wcfm_delivery_boys_array = wcfm_get_delivery_boys();
		$delivery_users = array( '' => __( '-Select Delivery Boy-', 'wc-frontend-manager-delivery' ) );
		
		if(!empty($wcfm_delivery_boys_array)) {
			foreach( $wcfm_delivery_boys_array as $wcfm_delivery_boys_single ) {
				$delivery_users[$wcfm_delivery_boys_single->ID] = $wcfm_delivery_boys_single->first_name . ' ' . $wcfm_delivery_boys_single->last_name . ' (' . $wcfm_delivery_boys_single->user_email . ')';
			}
		}
		
		$delivery_fields = array( "wcfm_delivery_boy"  => array( 'type' => 'select', 'options' => $delivery_users, 'class' => 'wcfm-select', 'attributes' => array( 'style' => 'width: 150px;' ), 'value' => '' ) );
		$WCFM->wcfm_fields->wcfm_generate_form_field( $delivery_fields );
	}
	
	/**
	 * Delivery Boy in Order Dashboard Column
	 */
	function wcfmd_order_columns_before() {
		global $WCFM, $WCFMu, $WCFMd;
		?>
		<th><?php _e( 'Delivery Boy', 'wc-frontend-manager-delivery' ); ?></th>
		<?php
	}
	
	function wcfmd_screen_manager_columns( $wcfm_screen_manager_data, $screen ) {
		global $WCFM, $WCFMu, $WCFMd;
		
		if( $screen == 'order' ) {
			$wcfm_screen_manager_data[11] = __( 'Delivery Boy', 'wc-frontend-manager-delivery' );
			$wcfm_screen_manager_data[12] = __( 'Date', 'wc-frontend-manager-ultimate' );
			$wcfm_screen_manager_data[13] = __( 'Actions', 'wc-frontend-manager-ultimate' );
		}
		 
		return $wcfm_screen_manager_data;
	}
	
	function wcfmd_datatable_column_defs( $wcfm_datatable_column_defs, $screen ) {
		global $WCFM, $WCFMu, $WCFMmp, $WCFMd, $wpdb;
		if( $screen == 'order' ) {
			$wcfm_datatable_column_defs = '[{ "targets": 0, "orderable" : false }, { "targets": 1, "orderable" : false }, { "targets": 2, "orderable" : false }, { "targets": 3, "orderable" : false }, { "targets": 4, "orderable" : false },{ "targets": 5, "orderable" : false },{ "targets": 6, "orderable" : false },{ "targets": 7, "orderable" : false },{ "targets": 8, "orderable" : false },{ "targets": 9, "orderable" : false },{ "targets": 10, "orderable" : false },{ "targets": 11, "orderable" : false },{ "targets": 12, "orderable" : false },{ "targets": 13, "orderable" : false }]';
		}
		return $wcfm_datatable_column_defs;
	}
	
	function wcfmd_datatable_column_priority( $wcfm_datatable_column_priority, $screen ) {
		global $WCFM, $WCFMu, $WCFMmp, $WCFMd, $wpdb;
		if( $screen == 'order' ) {
			$wcfm_datatable_column_priority = '[{ "responsivePriority": 2 },{ "responsivePriority": 1 },{ "responsivePriority": 4 },{ "responsivePriority": 10 },{ "responsivePriority": 6 },{ "responsivePriority": 5 },{ "responsivePriority": 7 },{ "responsivePriority": 11 },{ "responsivePriority": 3 },{ "responsivePriority": 12 },{ "responsivePriority": 8 },{ "responsivePriority": 2 },{ "responsivePriority": 9 },{ "responsivePriority": 1 }]';
		}
		return $wcfm_datatable_column_priority;
	}
	
	function wcfmd_orders_custom_columns_data_before( $wcfm_orders_json_arr, $index, $wcfm_orders_single_ID, $wcfm_orders_single, $the_order ) {
		global $WCFM, $WCFMu, $WCFMmp, $WCFMd, $wpdb;
		
		// Delivery Boys
		$delivery_boys = wcfm_get_order_delivery_boys_string( $the_order->get_id() );
		if( $delivery_boys ) {
			$wcfm_orders_json_arr[$index][] = $delivery_boys;
		} else {
			$wcfm_orders_json_arr[$index][] = '&ndash;';
		}
		
		return $wcfm_orders_json_arr;
	}
  
  /**
   * Delivery Admin Setting 
   */
  function wcfmd_delivery_settings( $wcfm_options ) {
		global $WCFM, $WCFMd;
		
		if( !apply_filters( 'wcfm_is_pref_delivery', true ) && !apply_filters( 'wcfm_is_pref_delivery_time', true ) ) return;
		
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
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_delivery_head">
			<label class="wcfmfa fa-shipping-fast"></label>
			<?php _e('Delivery', 'wc-frontend-manager-delivery'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_delivery_expander" class="wcfm-content">
			  <?php if( apply_filters( 'wcfm_is_pref_delivery', true ) && apply_filters( 'wcfm_is_allow_delivery', true ) ) { ?>
					<h2><?php _e('Delivery Settings', 'wc-frontend-manager-delivery'); ?></h2>
					<div class="wcfm_clearfix"></div>
					<?php
						do_action( 'wcfmd_delivery_settings_before' );
					
						$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
						$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
						if( $wpeditor && $rich_editor ) {
							$rich_editor = 'wcfm_wpeditor';
						} else {
							$wpeditor = 'textarea';
						}
						
						$group_auto_assign = '';
						if( $WCFM->is_marketplace && ( $WCFM->is_marketplace == 'wcfmmarketplace' ) ) $group_auto_assign = 'wcfm_custom_hide';
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmd_settings_fields_general', array(
																																																	"wcfmd_new_account_mail_subject" => array('label' => __('New account mail subject', 'wc-frontend-manager-delivery'), 'name' => 'wcfmd_new_account_mail_subject', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $new_account_mail_subject ),
																																																	"wcfmd_new_account_mail_content" => array('label' => __('New account mail body', 'wc-frontend-manager-delivery'), 'name' => 'wcfmd_new_account_mail_content', 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_custom_field_editor wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title', 'desc_class' => 'instructions', 'value' => $new_account_mail_body, 'desc' => __('Allowed dynamic variables are: {site_url}, {user_role}, {username}, {first_name}, {password}', 'wc-frontend-manager-delivery') ),
																																																	) ) );
				}
				?>
				
				<?php
					do_action( 'wcfmd_delivery_settings_after' );
				?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
	}
	
	function wcfmd_delivery_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMgs, $_POST;
		
		if( isset( $wcfm_settings_form['wcfmd_new_account_mail_subject'] ) ) {
			$new_account_mail_subject = $wcfm_settings_form['wcfmd_new_account_mail_subject'];
			wcfm_update_option( 'wcfmd_new_account_mail_subject',  $new_account_mail_subject );
		}
		
		if( isset( $wcfm_settings_form['wcfmd_new_account_mail_content'] ) ) {
			$new_account_mail_body = stripslashes( html_entity_decode( $wcfm_settings_form['wcfmd_new_account_mail_content'], ENT_QUOTES, 'UTF-8' ) );
			wcfm_update_option( 'wcfmd_new_account_mail_body',  $new_account_mail_body );
		}
	}
	
	/**
	 * Delivery Capability Setting 
	 */
	function wcfmd_capability_settings_delivery( $wcfm_capability_options ) {
		global $WCFM, $WCFMu;
		
		if( !apply_filters( 'wcfm_is_pref_delivery', true ) && !apply_filters( 'wcfm_is_pref_delivery_time', true ) ) return;
	
		$delivery      = ( isset( $wcfm_capability_options['delivery'] ) ) ? $wcfm_capability_options['delivery'] : 'no';
	  $delivery_time = ( isset( $wcfm_capability_options['delivery_time'] ) ) ? $wcfm_capability_options['delivery_time'] : 'no';
		
		?>
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Delivery', 'wc-frontend-manager-delivery' ); ?></h3></div>
		
		<?php
		if( apply_filters( 'wcfm_is_pref_delivery', true ) ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_delivery_person', array(  
																																	 "delivery" => array('label' => __('Delivery Person', 'wc-frontend-manager-delivery') , 'name' => 'wcfm_capability_options[delivery]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delivery ),
																										) ) );
		}
		
		if( apply_filters( 'wcfm_is_pref_delivery_time', true ) ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_delivery_time', array(  
																																	 "delivery_time" => array('label' => __('Delivery Time', 'wc-frontend-manager-delivery') , 'name' => 'wcfm_capability_options[delivery_time]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delivery_time ),
																										) ) );
		}
		
	}
	
}