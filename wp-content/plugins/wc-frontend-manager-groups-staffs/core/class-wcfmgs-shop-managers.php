<?php

/**
 * WCFM Groups & Staffs Shop Managers Class
 *
 * @version		1.0.0
 * @package		wcfmgs/core
 * @author 		WC Lovers
 */
class WCFMgs_Shop_Managers {
	
	private $manager_id = ''; 
	
 	public function __construct() {
 		
 		// WCFM Shop Managrs End Points
 		add_filter( 'wcfm_query_vars', array( &$this, 'wcfmgs_sm_wcfm_query_vars' ), 90 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfmgs_sm_endpoint_title' ), 90, 2 );
		add_action( 'init', array( &$this, 'wcfmgs_sm_init' ), 90 );
		
		// WCFM Appointments Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfmgs_sm_endpoints_slug' ) );
		
		// WCFM Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfmgs_sm_menus' ), 250 );
		add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfmgs_sm_menu_dependancy_map' ) );
		
 		add_action( 'wcfm_shop_manager_capability_settings', array( &$this, 'wcfmgs_shop_manager_capability_fields' ) );
 		
 		// Manager Delete
		add_action( 'wp_ajax_delete_wcfm_manager', array( &$this, 'delete_wcfm_manager' ) );
		
		// Group manager Specific content filter
		if( wcfm_is_group_manager() ) {
			$this->manager_id = get_current_user_id();
			
			// Vendor Filter
			add_filter( 'wcfm_allow_vendors_list', array( &$this, 'wcfmgs_group_manager_allow_vendors_list' ), 100, 2 );
			
			// Dashboard Filters
			add_filter( 'wcfm_is_pref_stats_box', array( &$this, 'wcfmgs_group_manager_default_stats_box_disable' ), 100 );
			add_action( 'wcfm_after_dashboard_stats_box', array( &$this, 'wcfmgs_group_manager_stats_box' ), 100 );
			
			// Product, Listings & Coupon filter
			add_filter( 'wcfm_products_args', array( &$this, 'wcfmgs_group_manager_products_args' ), 100 );
			add_filter( 'wcfm_listing_args', array( &$this, 'wcfmgs_group_manager_listings_args' ), 100 );
			add_filter( 'wcfm_coupons_args', array( &$this, 'wcfmgs_group_manager_coupons_args' ), 100 );
			
			// Product Quick Edit Filter
			add_filter( 'wcfm_is_allow_quick_edit_product', array( &$this, 'wcfmgs_group_manager_quick_edit' ), 100 );
			
			// Orders Filter
			add_filter( 'wcfm_is_show_marketplace_orders', array( &$this, 'wcfmgs_group_manager_show_marketplace_orders' ), 100 );
			add_filter( 'wcfm_orders_group_manager_filter', array( &$this, 'wcfmgs_group_manager_orders_filter' ), 100, 2 );
			
			// Enquery Filter
			add_filter( 'wcfm_enquery_count_query', array( &$this, 'wcfmgs_group_manager_enquery_filter' ), 100 );
			add_filter( 'wcfm_enquery_list_query', array( &$this, 'wcfmgs_group_manager_enquery_filter' ), 100 );
			
			// Notification Filter
			add_filter( 'wcfm_notification_group_manager_filter', array( &$this, 'wcfmgs_group_manager_notification_filter' ), 100 );
			
			// New Vendor Associate with Manager's Groups
			add_action( 'wcfm_vendors_new', array( &$this, 'wcfmgs_group_manager_new_vendor' ), 100, 2 );
		}
		
		// WCFM Marketplace Store New Order Email - add Group Managers in CC
		add_filter( 'wcfmmp_store_new_order_email_header', array( &$this, 'wcfmgs_store_new_order_email_manager_cc' ), 100, 2 );
 	}
 	
 	 	/**
   * WCFM Managers Query Var
   */
  function wcfmgs_sm_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_managers_vars = array(
			'wcfm-managers'          => ! empty( $wcfm_modified_endpoints['wcfm-managers'] ) ? $wcfm_modified_endpoints['wcfm-managers'] : 'managers',
			'wcfm-managers-manage'   => ! empty( $wcfm_modified_endpoints['wcfm-managers-manage'] ) ? $wcfm_modified_endpoints['wcfm-managers-manage'] : 'managers-manage',
		);
		
		$query_vars = array_merge( $query_vars, $query_managers_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Managers End Point Title
   */
  function wcfmgs_sm_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-managers' :
				$title = __( 'Shop Managers', 'wc-frontend-manager-groups-staffs' );
			break;
			case 'wcfm-managers-manage' :
				$title = __( 'Shop Managers Manage', 'wc-frontend-manager-groups-staffs' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Managers Endpoint Intialize
   */
  function wcfmgs_sm_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfmgs_managers' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfmgs_managers', 1 );
		}
  }
  
  /**
	 * WCFM Managers Endpoiint Edit
	 */
	function wcfmgs_sm_endpoints_slug( $endpoints ) {
		
		$wcfmgs_managers_endpoints = array(
													'wcfm-managers'           => 'managers',
													'wcfm-managers-manage'    => 'managers-manage',
													);
		
		$endpoints = array_merge( $endpoints, $wcfmgs_managers_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Managers Menu
   */
  function wcfmgs_sm_menus( $menus ) {
  	global $WCFM;
  	
		$menus = array_slice($menus, 0, 6, true) +
												array( 'wcfm-managers' => array(   'label'  => __( 'Managers', 'wc-frontend-manager-groups-staffs'),
																										 'url'     => get_wcfm_shop_managers_dashboard_url(),
																										 'icon'    => 'user-secret',
																										 'has_new'    => 'yes',
																										 'new_class'  => 'wcfm_sub_menu_items_shop_managers_manage',
																										 'new_url'    => get_wcfm_shop_managers_manage_url(),
																										 'priority'   => 50
																										) )	 +
													array_slice($menus, 6, count($menus) - 6, true) ;
		
  	return $menus;
  }
  
  /**
   * WCFM Managers Menu Dependency
   */
  function wcfmgs_sm_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-managers-manage'] = 'wcfm-managers';
  	return $menu_dependency_mapping;
  }
  
 	/**
	 * WCFM Shop Manager Capability Fields
	 */
	function wcfmgs_shop_manager_capability_fields( ) {
		global $WCFM, $WCFMgs;
		include_once( $WCFMgs->library->views_path . 'wcfmgs-view-shop-manager-capability.php' );
	}
	
	/**
	 * WCFM Shop Manager Capability Options update
	 */
  function wcfmgs_shop_manager_capability_option_updates( $options = array() ) {
  	
		$options = get_option( 'wcfm_shop_manager_capability_options' );
		$shop_manager_role = get_role( 'shop_manager' );
		
		// Delete Media Capability
		if( isset( $options['delete_media'] ) && $options[ 'delete_media' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'delete_attachments' );
			$shop_manager_role->remove_cap( 'delete_posts' );
		} else {
			$shop_manager_role->add_cap( 'delete_attachments' );
			$shop_manager_role->add_cap( 'delete_posts' );
		}
			
		// Booking Capability
		if( wcfm_is_booking() ) {
			if( isset( $options['manage_booking'] ) && $options[ 'manage_booking' ] == 'yes' ) {
				$shop_manager_role->remove_cap( 'manage_bookings' );
			} else {
				$shop_manager_role->add_cap( 'manage_bookings' );
			}
		}
		
		// Appointment Capability
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
				if( isset( $options['manage_appointment'] ) && $options[ 'manage_appointment' ] == 'yes' ) {
					$shop_manager_role->remove_cap( 'manage_appointments' );
					$shop_manager_role->remove_cap( 'manage_others_appointments' );
				} else {
					$shop_manager_role->add_cap( 'manage_appointments' );
					$shop_manager_role->add_cap( 'manage_others_appointments' );
				}
			}
		}
		
		// Submit Products
		if( isset( $options[ 'submit_products' ] ) && $options[ 'submit_products' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'edit_products' );
			$shop_manager_role->remove_cap( 'manage_products' );
			$shop_manager_role->remove_cap( 'read_products' );
		} else {
			$shop_manager_role->add_cap( 'edit_products' );
			$shop_manager_role->add_cap( 'manage_products' );
			$shop_manager_role->add_cap( 'read_products' );
		}
		
		// Publish Coupon
		if( isset( $options[ 'publish_products' ] ) && $options[ 'publish_products' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'publish_products' );
		} else {
			$shop_manager_role->add_cap( 'publish_products' );
		}
		
		// Live Products Edit
		if( isset( $options[ 'edit_live_products' ] ) && $options[ 'edit_live_products' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'edit_published_products' );
		} else {
			$shop_manager_role->add_cap( 'edit_published_products' );
		}
		
		// Delete Products
		if( isset( $options[ 'delete_products' ] ) && $options[ 'delete_products' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'delete_published_products' );
			$shop_manager_role->remove_cap( 'delete_products' );
		} else {
			$shop_manager_role->add_cap( 'delete_published_products' );
			$shop_manager_role->add_cap( 'delete_products' );
		}
		
		// Submit Cuopon
		if( isset( $options[ 'submit_coupons' ] ) && $options[ 'submit_coupons' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'edit_shop_coupons' );
			$shop_manager_role->remove_cap( 'manage_shop_coupons' );
			$shop_manager_role->remove_cap( 'read_shop_coupons' );
		} else {
			$shop_manager_role->add_cap( 'edit_shop_coupons' );
			$shop_manager_role->add_cap( 'manage_shop_coupons' );
			$shop_manager_role->add_cap( 'read_shop_coupons' );
		}
		
		// Publish Coupon
		if( isset( $options[ 'publish_coupons' ] ) && $options[ 'publish_coupons' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'publish_shop_coupons' );
		} else {
			$shop_manager_role->add_cap( 'publish_shop_coupons' );
		}
		
		// Live Coupon Edit
		if( isset( $options[ 'edit_live_coupons' ] ) && $options[ 'edit_live_coupons' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'edit_published_shop_coupons' );
		} else {
			$shop_manager_role->add_cap( 'edit_published_shop_coupons' );
		}
		
		// Delete Coupon
		if( isset( $options[ 'delete_coupons' ] ) && $options[ 'delete_coupons' ] == 'yes' ) {
			$shop_manager_role->remove_cap( 'delete_published_shop_coupons' );
			$shop_manager_role->remove_cap( 'delete_shop_coupons' );
		} else {
			$shop_manager_role->add_cap( 'delete_published_shop_coupons' );
			$shop_manager_role->add_cap( 'delete_shop_coupons' );
		}
  }
  
  /**
   * Handle Manager Delete
   */
  public function delete_wcfm_manager() {
  	global $WCFM, $WCFMgs;
  	
  	$managerid = $_POST['managerid'];
		
		if($managerid) {
			if(wp_delete_user($managerid)) {
				echo 'success';
				die;
			}
			die;
		}
  }
  
  /**
   * Group manager allow vendors
   */
  function wcfmgs_group_manager_allow_vendors_list( $allow_vendors = array(0), $is_marketplace = '', $is_term = true ) {
  	global $WCFM, $WCFMgs;
  	
  	if( !$allow_vendors || !is_array( $allow_vendors ) ) $allow_vendors = array(0);
  	
  	if( $is_marketplace == '' ) {
  		$is_marketplace = wcfm_is_marketplace();
  	}
  	
		$wcfm_vendor_groups = get_user_meta( $this->manager_id, '_wcfm_vendor_group', true );
		if( !empty( $wcfm_vendor_groups ) ) {
			foreach( $wcfm_vendor_groups as $wcfm_vendor_group ) {
				$group_vendors = get_post_meta( $wcfm_vendor_group, '_group_vendors', true );
				if( $group_vendors && is_array( $group_vendors ) && !empty( $group_vendors ) ) {
					foreach( $group_vendors as $group_vendor ) {
						if( $is_marketplace == 'wcpvendors' ) {
							if( $is_term ) {
								$term_id = get_user_meta( $group_vendor, '_wcpv_active_vendor', true );
								if( $term_id ) $allow_vendors[] = $term_id;
							} else {
								$allow_vendors[] = $group_vendor;
							}
						} else {
							$allow_vendors[] = $group_vendor;
						}
					}
				}
			}
		}
		
		return $allow_vendors;
  }
  
  /**
   * Group Manager dashbord default stats box
   */
  function wcfmgs_group_manager_default_stats_box_disable( $is_pref ) {
  	return false;
  }
  
  /**
   * Group Manager dashbord stats box
   */
  function wcfmgs_group_manager_stats_box() {
  	global $WCFM, $wpdb, $WCFMgs;
  	
  	$is_marketplace = wcfm_is_marketplace();
		if( !$is_marketplace ) return;
  	
  	$manager_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace );
  	
  	$gross_sales = 0;
  	$total_commission = 0;
  	$total_products = 0;
  	$total_item_sales = 0;
  	
  	if( $manager_vendors && is_array( $manager_vendors ) && !empty( $manager_vendors ) ) {
  		// Getting Products
			$products_args = array(
									'posts_per_page'   => -1,
									'offset'           => 0,
									'category'         => '',
									'category_name'    => '',
									'orderby'          => 'date',
									'order'            => 'DESC',
									'include'          => '',
									'exclude'          => '',
									'meta_key'         => '',
									'meta_value'       => '',
									'post_type'        => 'product',
									'post_mime_type'   => '',
									'post_parent'      => '',
									//'author'	   => get_current_user_id(),
									'post_status'      => array('draft', 'pending', 'publish'),
									'suppress_filters' => 0 
								);
			$products_args = apply_filters( 'wcfm_products_args', $products_args );
			$wcfm_products_array = get_posts( $products_args );
			$total_products = count( $wcfm_products_array );
			
  		foreach( $manager_vendors as $manager_vendor ) {
  			if( $manager_vendor ) {
					$gross_sales += $WCFM->wcfm_vendor_support->wcfm_get_gross_sales_by_vendor( $manager_vendor );
					$total_commission += $WCFM->wcfm_vendor_support->wcfm_get_commission_by_vendor( $manager_vendor );
					$total_item_sales += $WCFM->wcfm_vendor_support->wcfm_get_total_sell_by_vendor( $manager_vendor );
				}
  		}
  	}
  	
  	?>
		<div class="wcfm_dashboard_stats">
		
		  <?php if ( apply_filters( 'wcfm_is_allow_orders', true ) && current_user_can( 'edit_shop_orders' ) ) { ?>
				<div class="wcfm_dashboard_stats_block">
					<a href="<?php echo get_wcfm_orders_url(); ?>">
						<span class="wcfmfa fa-currency"><?php echo get_woocommerce_currency_symbol() ; ?></span>
						<div>
							<strong><?php echo wc_price( $gross_sales ); ?></strong><br />
							<?php _e( 'gross sales in last 7 days', 'wc-frontend-manager' ); ?>
						</div>
					</a>
				</div>
			<?php } ?>
		
			<?php if ( apply_filters( 'wcfm_is_allow_orders', true ) && current_user_can( 'edit_shop_orders' ) ) { ?>
				<?php
				$admin_fee_mode = false;
				if( $is_marketplace == 'wcfmmarketplace' ) {
					global $WCFMp;
					if (isset($WCFMp->wcfmmp_commission_options['commission_for'])) {
						if ($WCFMp->wcfmmp_commission_options['commission_for'] == 'admin') {
							$admin_fee_mode = true;
							$total_commission = $gross_sales - $total_commission;
						}
					}
				} elseif( $is_marketplace == 'wcmarketplace' ) {
					global $WCMp;
					if (isset($WCMp->vendor_caps->payment_cap['revenue_sharing_mode'])) {
						if ($WCMp->vendor_caps->payment_cap['revenue_sharing_mode'] == 'admin') {
							$admin_fee_mode = true;
							$total_commission = $gross_sales - $total_commission;
						}
					}
				} elseif( $is_marketplace == 'dokan' ) {
					$total_commission = $gross_sales - $total_commission;
				}
				?>
				<div class="wcfm_dashboard_stats_block">
					<a href="<?php echo get_wcfm_orders_url( ); ?>">
						<span class="wcfmfa fa-money"></span>
						<div>
							<strong><?php echo wc_price( $total_commission ); ?></strong><br />
							<?php if( $admin_fee_mode ) { _e( 'admin fees in last 7 days', 'wc-frontend-manager' ); } else { _e( 'commission in last 7 days', 'wc-frontend-manager' ); } ?>
						</div>
					</a>
				</div>
			<?php } ?>
		
			<?php if ( current_user_can( 'edit_products' ) && apply_filters( 'wcfm_is_allow_manage_products', true ) ) { ?>
				<div class="wcfm_dashboard_stats_block">
					<a href="<?php echo get_wcfm_products_url(); ?>">
						<span class="wcfmfa fa-cubes"></span>
						<div>
							<strong><?php echo $total_products; ?></strong><br />
							<?php _e( 'total products posted', 'wc-frontend-manager-groups-staffs' ); ?>
						</div>
					</a>
				</div>
			<?php } ?>
			
			<?php if ( apply_filters( 'wcfm_is_allow_orders', true ) && current_user_can( 'edit_shop_orders' ) ) { ?>
				<div class="wcfm_dashboard_stats_block">
					<a href="<?php echo get_wcfm_orders_url(); ?>">
						<span class="wcfmfa fa-cart-plus"></span>
						<div>
							<?php printf( _n( "<strong>%s item</strong><br />", "<strong>%s items</strong><br />", $total_item_sales, 'wc-frontend-manager' ), $total_item_sales ); ?>
							<?php _e( 'sold in last 7 days', 'wc-frontend-manager' ); ?>
						</div>
					</a>
				</div>
		  <?php } ?>
		</div>
		<div class="wcfm-clearfix"></div>
		<?php
  }
  
  /**
   * Group Manager products
   */
  function wcfmgs_group_manager_products_args( $args ) {
  	
  	if( !isset( $_POST['product_vendor'] ) || empty( $_POST['product_vendor'] ) ) {
			$is_marketplace = wcfm_is_marketplace();
			if( $is_marketplace ) {
				$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace );
				if( $is_marketplace == 'wcpvendors' ) {
					$args['tax_query'][] = array(
																				'taxonomy' => WC_PRODUCT_VENDORS_TAXONOMY,
																				'field' => 'term_id',
																				'terms' => $allow_vendors,
																			);
				} elseif( $is_marketplace == 'wcvendors' ) {
					$args['author__in'] = $allow_vendors;
				} elseif( $is_marketplace == 'wcmarketplace' ) {
					$vendor_terms = array();
					if( !empty( $allow_vendors ) ) {
						foreach( $allow_vendors as $allow_vendor ) {
							$vendor_terms[] = absint( get_user_meta( $allow_vendor, '_vendor_term_id', true ) );
						}
					}
					if( empty($vendor_terms) ) $vendor_terms = array(0);
					$args['tax_query'][] = array(
																				'taxonomy' => 'dc_vendor_shop',
																				'field' => 'term_id',
																				'terms' => $vendor_terms,
																				'operator' => 'IN',
																			);
					$args['tax_query']['relation'] = 'AND';
				} elseif( $is_marketplace == 'dokan' ) {
					$args['author__in'] = $allow_vendors;
				} elseif( $is_marketplace == 'wcfmmarketplace' ) {
					$args['author__in'] = $allow_vendors;
				}
			}
		}
  	
  	return $args;
  }
  
  /**
   * Group Manager Listings
   */
  function wcfmgs_group_manager_listings_args( $args ) {
  	
  	$is_marketplace = wcfm_is_marketplace();
		if( $is_marketplace ) {
			$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace, false );
			$args['author__in'] = $allow_vendors;
		}
  	return $args;
  }
  
  /**
   * Group Manager Coupons
   */
  function wcfmgs_group_manager_coupons_args( $args ) {
  	
  	$is_marketplace = wcfm_is_marketplace();
		if( $is_marketplace ) {
			$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace, false );
			$args['author__in'] = $allow_vendors;
		}
  	return $args;
  }
  
  /**
   * Group Manager
   */
  function wcfmgs_group_manager_quick_edit( $allow ) {
  	global $WCFM, $post, $product, $woocommerce_loop;
  	
  	$allow = false;
  	if ( is_object( $product ) ) { 
			$products_args = array(
										'posts_per_page'   => -1,
										'offset'           => 0,
										'category'         => '',
										'category_name'    => '',
										'orderby'          => 'date',
										'order'            => 'DESC',
										'include'          => '',
										'exclude'          => '',
										'meta_key'         => '',
										'meta_value'       => '',
										'post_type'        => 'product',
										'post_mime_type'   => '',
										'post_parent'      => '',
										//'author'	   => get_current_user_id(),
										'post_status'      => array('draft', 'pending', 'publish'),
										'suppress_filters' => 0 
									);
			$products_args = apply_filters( 'wcfm_products_args', $products_args );
			$wcfm_products_array = get_posts( $products_args );
			
			if( !empty( $wcfm_products_array ) ) {
				foreach( $wcfm_products_array as $wcfm_product ) {
					if( $wcfm_product->ID == $product->get_id() ) $allow = true;
				}
			}
		}
		return $allow;
  }
  
  /**
   * Group Manager Order Type
   */
  function wcfmgs_group_manager_show_marketplace_orders( $is_show ) {
  	return true;
  }
  
  /**
   * Group Manager Orders
   */
  function wcfmgs_group_manager_orders_filter( $group_manager_filter, $vendor_column ) {
  	
  	$is_marketplace = wcfm_is_marketplace();
		if( $is_marketplace ) {
			$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace );
			$group_manager_filter = " AND `" . $vendor_column . "` IN (" . implode( ',', $allow_vendors ) . ")";
		}
  	return $group_manager_filter;
  }
  
  /**
   * Group Manager Enquery filter
   */
  function wcfmgs_group_manager_enquery_filter( $sql ) {
  	
  	if( !wcfm_is_vendor() && empty( $_POST['enquiry_vendor'] ) ) {
  		$is_marketplace = wcfm_is_marketplace();
			if( $is_marketplace ) {
				$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace );
				$sql .= " AND `vendor_id` IN (" . implode( ',', $allow_vendors ) . ")";
			}
  	}
  	
  	return $sql;
  }
  
  /**
   * Group Manager Notification filter
   */
  function wcfmgs_group_manager_notification_filter( $group_manager_filter ) {
  	
  	if( !wcfm_is_vendor() ) {
  		$is_marketplace = wcfm_is_marketplace();
			if( $is_marketplace ) {
				$allow_vendors = $this->wcfmgs_group_manager_allow_vendors_list( array(0), $is_marketplace );
				$group_manager_filter = " AND ( `author_id` IN (" . implode( ',', $allow_vendors ) . ")" . " OR `message_to` = -1 OR `message_to` IN (" . implode( ',', $allow_vendors ) . ")" . " )";
			}
  	}
  	
  	return $group_manager_filter;
  }
  
  /**
   * Group Manager new Vendor associate with manager's group
   */
  function wcfmgs_group_manager_new_vendor( $vendor_id, $wcfm_vendor_form_data ) {
  	$associated_groups = get_user_meta( $this->manager_id, '_wcfm_vendor_group', true );
  	
  	if( !empty( $associated_groups ) ) {
  		foreach( $associated_groups as $associated_group ) {
				$associated_group = absint( $associated_group );
				$group_vendors = (array) get_post_meta( $associated_group, '_group_vendors', true );
				$group_vendors[] = $vendor_id;
				update_post_meta( $associated_group, '_group_vendors', $group_vendors );
			}
				
			// User Group update
			update_user_meta( $vendor_id, '_wcfm_vendor_group', $associated_groups  );
			update_user_meta( $vendor_id, '_wcfm_vendor_group_list', implode( ",", array_unique( $associated_groups ) ) );
		}
  }
  
  /**
   * WCFM Marketplace Store New Order Email - add Group Managers in CC
   */
  function wcfmgs_store_new_order_email_manager_cc( $headers, $vendor_id ) {
  	global $WCFM, $WCFMmp;
  	
  	if( !apply_filters( 'wcfmmp_is_allow_store_new_order_email_manager_cc', true ) ) return;
  	
  	$managers_emails = '';
  	
  	$associated_groups = get_user_meta( $vendor_id, '_wcfm_vendor_group', true  );
  	if( $associated_groups && is_array( $associated_groups ) && !empty( $associated_groups ) ) {
  		foreach( $associated_groups as $associated_group ) {
  			$group_managers = get_post_meta( $associated_group, '_group_managers', true );
  			if( $group_managers && is_array( $group_managers ) && !empty( $group_managers ) ) {
  				foreach( $group_managers as $group_manager ) {
  					$manager_user = new WP_User(absint($group_manager));
  					if( $manager_user ) {
  						if( $managers_emails ) $managers_emails .= ',';
  						$managers_emails .= $manager_user->user_email;
  					}
  				}
  			}
  		}
  	}
  	
  	if( $managers_emails ) {
  		if( is_array( $headers ) ) {
  			$headers[] = 'Content-Type:  text/html';
  			$headers[] = 'cc: '.$managers_emails;
  		} else {
  			$headers .= 'cc: '.$managers_emails."\r\n";
  		}
  		
  		
  		if( !defined( 'DOING_WCFM_RESTRICTED_EMAIL' ) )
  			define( 'DOING_WCFM_RESTRICTED_EMAIL', TRUE );
  	}
  	
  	return $headers;
  }
}