<?php

/**
 * WCFM Groups & Staffs plugin
 *
 * WCFM Groups & Staffs Core
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.0
 */

class WCFMgs {

	public $plugin_base_name;
	public $plugin_url;
	public $plugin_path;
	public $version;
	public $token;
	public $text_domain;
	public $library;
	public $shortcode;
	public $admin;
	public $frontend;
	public $shop_managers;
	public $vendor_groups;
	public $shop_staffs;
	public $ajax;
	private $file;
	public $settings;
	public $license;
	public $templat;
	public $wcfmgs_fields;
	public $is_marketplace;
	public $wcfmgs_marketplace;
	public $wcfmgs_capability;
	public $wcfmgs_non_ajax;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_base_name = plugin_basename( $file );
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCFMgs_TOKEN;
		$this->text_domain = WCFMgs_TEXT_DOMAIN;
		$this->version = WCFMgs_VERSION;
		
		add_action( 'init', array(&$this, 'init'), 7 );
		
		// WCFM User Specific Capability Load
		add_filter( 'wcfmgs_user_capability', array( &$this, 'wcfmgs_user_capability_rules' ), 500, 2 );
		
		// WCFM Vendor Staff Capability Load
		add_filter( 'wcfmgs_vendor_staff_capability', array( &$this, 'wcfmgs_vendor_staff_capability_rules' ), 500 );
		
		// WCFM Groups & Staffs User Capability Load
		add_filter( 'wcfm_capability_options_rules', array( &$this, 'wcfmgs_capability_options_rules' ), 500 );
		
		// MyListings Job Package Compatibililty added
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'wcfmgs_job_package_product_data_html' ), 20 );
		add_filter( 'woocommerce_process_product_meta_job_package', array( $this, 'wcfmgs_job_package_save_product_data' ), 20 );
		add_filter( 'woocommerce_process_product_meta_job_package_subscription', array( $this, 'wcfmgs_job_package_save_product_data' ), 20 );
		add_action( 'save_post_case27_user_package', array( $this, 'wcfmgs_job_package_subscription_group_association' ), 20, 3 );
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCFM, $WCFMgs;
		
		// Register Vendor Groups Post Type
		register_post_type( 'wcfm_vendor_groups', array( 'public' => apply_filters( 'wcfm_is_allow_groups_public', true ), 'exclude_from_search' => apply_filters( 'wcfm_is_allow_groups_exclude_from_search', false ),'publicly_queryable' => true, 'has_archive' => apply_filters( 'wcfm_is_allow_groups_slug', true ), 'show_in_menu' => false, 'rewrite' => array( 'slug' => apply_filters( 'wcfm_groups_slug', 'vendor-groups' ) ) ) );
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		if( ( version_compare( WC_VERSION, '3.0', '<' ) ) ) {
			//add_action( 'admin_notices', 'wcfm_woocommerce_version_notice' );
			return;
		}
		
		// Capability Controller
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class( 'capability' );
			$this->wcfmgs_capability = new WCFMgs_Capability();
		}
		
		// Check Marketplace
		$this->is_marketplace = wcfm_is_marketplace();
		if( $this->is_marketplace ) {
			if ( !is_admin() || defined('DOING_AJAX') ) {
				$this->load_class( 'vendor-support' );
				$this->wcfmgs_vendor_support = new WCFMgs_Vendor_Support();
			}
		}

		if ( !is_admin() || defined('DOING_AJAX') || defined('WCFM_REST_API_CALL') ) {
			if( $this->is_marketplace ) {
				if( wcfm_is_vendor()) {
					$this->load_class( $this->is_marketplace );
					if( $this->is_marketplace == 'wcvendors' ) $this->wcfmgs_marketplace = new WCFMgs_WCVendors();
					if( $this->is_marketplace == 'wcpvendors' ) $this->wcfmgs_marketplace = new WCFMgs_WCPVendors();
					if( $this->is_marketplace == 'wcmarketplace' ) $this->wcfmgs_marketplace = new WCFMgs_WCMarketplace();
					if( $this->is_marketplace == 'dokan' ) $this->wcfmgs_marketplace = new WCFMgs_Dokan();
					if( $this->is_marketplace == 'wcfmmarketplace' ) $this->wcfmgs_marketplace = new WCFMgs_Marketplace();
				}
			}
		}
		
		// Init library
		$this->load_class('library');
		$this->library = new WCFMgs_Library();

		// Init ajax
		if (defined('DOING_AJAX')) {
			$this->load_class('ajax');
			$this->ajax = new WCFMgs_Ajax();
		}

		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('frontend');
			$this->frontend = new WCFMgs_Frontend();
		}
		
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('vendor-groups');
			$this->vendor_groups = new WCFMgs_Vendor_Groups();
		}
		
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('shop-managers');
			$this->shop_managers = new WCFMgs_Shop_Managers();
		}
		
		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('shop-staffs');
			$this->shop_staffs = new WCFMgs_Shop_Staffs();
		}
		
		// template loader
		$this->load_class( 'template' );
		$this->template = new WCFMgs_Template();
		
		// DC License Activation
		if (is_admin()) {
			$this->load_class('license');
			$this->license = WCFMgs_LICENSE();
		}
		
		if( !defined('DOING_AJAX') ) {
			$this->load_class( 'non-ajax' );
			$this->wcfmgs_non_ajax = new WCFMgs_Non_Ajax();
		}
		
		$this->wcfmgs_fields = $WCFM->wcfm_fields;
		
		// Restrict Shop Manager for wp-admin access
		if( is_admin() && !defined('DOING_AJAX') ) {
			$this->restrict_wcfm_shop_manager_backend();
		}
		
		// Restrict Staff for wp-admin access
		if( is_admin() && !defined('DOING_AJAX') ) {
			$this->restrict_wcfm_shop_staff_backend();
		}
		
	}
	
	/**
	 * WCFM Groups & Staffs Capability Load as per User
	 */
	function wcfmgs_user_capability_rules( $wcfm_capability_options, $user_id = 0 ) {
		
		if( !$user_id )
			$user_id = get_current_user_id();
		
		$wcfm_user_has_custom_capability = get_user_meta( $user_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $user_id, '_wcfm_user_has_custom_capability', true ) : 'no';
		
		if( ( $wcfm_user_has_custom_capability == 'yes' ) && !wcfm_is_group_manager() ) {
			$user_capability_options = (array) get_user_meta( $user_id, '_wcfm_user_capability_options', true );
			if( $user_capability_options ) $wcfm_capability_options = $user_capability_options;
		} else {
			$wcfm_user_groups = (array) get_user_meta( $user_id, '_wcfm_vendor_group', true );
			if( !empty( array_filter( $wcfm_user_groups ) ) ) {
				$user_capability_options = array();
				$allowed_categories = array();
				foreach( $wcfm_user_groups as $wcfm_user_group ) {
					if($wcfm_user_group) {
						$group_capability_options = (array) get_post_meta( $wcfm_user_group, '_group_capability_options', true );
						if( !empty( array_filter( $group_capability_options ) ) ) {
							if( isset( $group_capability_options['allowed_categories'] ) && is_array( $group_capability_options['allowed_categories'] ) ) {
								$allowed_categories = array_merge( $allowed_categories, $group_capability_options['allowed_categories'] );
							}
							if( empty( $user_capability_options ) ) {
								$user_capability_options = $group_capability_options;
							} else {
								$user_capability_options = array_intersect_key( $user_capability_options, $group_capability_options );
							}
						}
					}
				}
				$wcfm_capability_options = $user_capability_options;
				$wcfm_capability_options['allowed_categories'] = $allowed_categories;
			}
		}
		return $wcfm_capability_options;
	}
	
	/**
	 * Vendor Staff Capability Load
	 */
	function wcfmgs_vendor_staff_capability_rules( $wcfm_capability_options ) {
		
		if( wcfm_is_vendor_staff() ) {
		  $vendor_id = wcfm_get_staff_vendor();
			
		  $vendor_capability_options = array();
		  $wcfm_user_has_custom_capability = get_user_meta( $vendor_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $vendor_id, '_wcfm_user_has_custom_capability', true ) : 'no';
		
			if( $wcfm_user_has_custom_capability == 'yes' ) {
				$vendor_capability_options = (array) get_user_meta( $vendor_id, '_wcfm_user_capability_options', true );
			} else {
				$wcfm_user_groups = (array) get_user_meta( $vendor_id, '_wcfm_vendor_group', true );
				if( !empty( array_filter( $wcfm_user_groups ) ) ) {
					$allowed_categories = array();
					foreach( $wcfm_user_groups as $wcfm_user_group ) {
						if($wcfm_user_group) {
							$group_capability_options = (array) get_post_meta( $wcfm_user_group, '_group_capability_options', true );
							if( !empty( array_filter( $group_capability_options ) ) ) {
								if( isset( $group_capability_options['allowed_categories'] ) && is_array( $group_capability_options['allowed_categories'] ) ) {
									$allowed_categories = array_merge( $allowed_categories, $group_capability_options['allowed_categories'] );
								}
								if( empty( $user_capability_options ) ) {
									$vendor_capability_options = $group_capability_options;
								} else {
									$vendor_capability_options = array_intersect_key( $user_capability_options, $group_capability_options );
								}
							}
						}
					}
					$vendor_capability_options['allowed_categories'] = $allowed_categories;
				} else {
					$vendor_capability_options = get_option( 'wcfm_capability_options', array() );
				}
			}
		}
		
		$wcfm_capability_options['spacelimit']                = ( !empty( $vendor_capability_options['spacelimit'] ) ) ? $vendor_capability_options['spacelimit'] : '';
		$wcfm_capability_options['articlelimit']              = ( !empty( $vendor_capability_options['articlelimit'] ) ) ? $vendor_capability_options['articlelimit'] : '';
		$wcfm_capability_options['productlimit']              = ( !empty( $vendor_capability_options['productlimit'] ) ) ? $vendor_capability_options['productlimit'] : '';
		$wcfm_capability_options['featured_product_limit']    = ( !empty( $vendor_capability_options['featured_product_limit'] ) ) ? $vendor_capability_options['featured_product_limit'] : '';
		$wcfm_capability_options['featured_product_limit']    = ( !empty( $vendor_capability_options['featured_product_limit'] ) ) ? $vendor_capability_options['gallerylimit'] : '';
		
		$wcfm_capability_options['allowed_article_category']  = ( !empty( $vendor_capability_options['allowed_article_category'] ) ) ? $vendor_capability_options['allowed_article_category'] : array();
		$wcfm_capability_options['article_catlimit']          = ( !empty( $vendor_capability_options['article_catlimit'] ) ) ? $vendor_capability_options['article_catlimit'] : '';
		$wcfm_capability_options['allowed_categories']        = ( !empty( $vendor_capability_options['allowed_categories'] ) ) ? $vendor_capability_options['allowed_categories'] : array();
		$wcfm_capability_options['catlimit']                  = ( !empty( $vendor_capability_options['catlimit'] ) ) ? $vendor_capability_options['catlimit'] : '';
		
		$wcfm_capability_options['allowed_attributes']        = ( !empty( $vendor_capability_options['allowed_attributes'] ) ) ? $vendor_capability_options['allowed_attributes'] : array();
		$wcfm_capability_options['allowed_custom_fields']     = ( !empty( $vendor_capability_options['allowed_custom_fields'] ) ) ? $vendor_capability_options['allowed_custom_fields'] : array();
		
		return $wcfm_capability_options;
	}
	
	/**
	 * WCFM Groups & Staffs Capability Load as per User Role
	 */
	function wcfmgs_capability_options_rules( $wcfm_capability_options ) {
		$user = wp_get_current_user();
		
		if ( in_array( 'vendor', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options' ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'seller', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options' ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'wcfm_vendor', $user->roles ) ) {
			$wcfm_capability_options = apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options', array() ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'dc_vendor', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options' ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'wc_product_vendors_admin_vendor', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_capability_options' ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'shop_manager', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_shop_manager_capability_options' ) );
			if( wcfm_is_group_manager() ) {
				$wcfm_capability_options['productlimit'] = '';
				$wcfm_capability_options['view_reports'] = 'yes';
				$wcfm_capability_options['wp_admin_view'] = 'yes';
				$wcfm_capability_options['manage_settings'] = 'yes';
				$wcfm_capability_options['capability_controller'] = 'yes';
				$wcfm_capability_options['manage_groups'] = 'yes';
				$wcfm_capability_options['manage_managers'] = 'yes';
				$wcfm_capability_options['manage_staffs'] = 'yes';
				$wcfm_capability_options['membership'] = 'yes';
			}
		} elseif ( in_array( 'wc_product_vendors_manager_vendor', $user->roles ) ) {
			$wcfm_capability_options = (array) apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_shop_staff_capability_options' ) );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_settings'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['manage_staffs'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} elseif ( in_array( 'shop_staff', $user->roles ) ) {
			$wcfm_capability_options = apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_shop_staff_capability_options', array() ) );
			$wcfm_capability_options = apply_filters( 'wcfmgs_vendor_staff_capability', $wcfm_capability_options );
			$wcfm_capability_options['manage_commission'] = 'yes';
			$wcfm_capability_options['wp_admin_view'] = 'yes';
			$wcfm_capability_options['manage_settings'] = 'yes';
			$wcfm_capability_options['capability_controller'] = 'yes';
			$wcfm_capability_options['manage_vendors'] = 'yes';
			$wcfm_capability_options['manage_groups'] = 'yes';
			$wcfm_capability_options['manage_managers'] = 'yes';
			$wcfm_capability_options['manage_staffs'] = 'yes';
			$wcfm_capability_options['membership'] = 'yes';
		} else {
			$wcfm_capability_options = array();
		}
		
		return $wcfm_capability_options;
	}
	
	/**
	 * My Listings Job Package group Field
	 */
	function wcfmgs_job_package_product_data_html() {
		global $post;
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFM_Dependencies::wcfm_products_mylistings_active_check() ) {
				$post_id = $post->ID;
				$args = array(
									'posts_per_page'   => -1,
									'offset'           => 0,
									'category'         => '',
									'category_name'    => '',
									'orderby'          => 'date',
									'order'            => 'ASC',
									'include'          => '',
									'exclude'          => '',
									'meta_key'         => '',
									'meta_value'       => '',
									'post_type'        => 'wcfm_vendor_groups',
									'post_mime_type'   => '',
									'post_parent'      => '',
									//'author'	   => get_current_user_id(),
									'post_status'      => array('draft', 'pending', 'publish'),
									'suppress_filters' => true 
								);
				
				$args = apply_filters( 'wcfm_vendor_groups_args', $args );
				
				$wcfm_groups = get_posts( $args );
				$wcfm_groups_array = array( '' => __( 'Choose your preferred group ...', 'wc-frontend-manager-groups-staffs' ) );
				if(!empty($wcfm_groups)) {
					foreach($wcfm_groups as $wcfm_groups_single) {
						$wcfm_groups_array[$wcfm_groups_single->ID] = $wcfm_groups_single->post_title;
					}
				}
				?>
				<div class="options_group show_if_job_package <?php echo esc_attr( class_exists( '\WC_Subscriptions' ) ? 'show_if_job_package_subscription' : '' );?>">
					<?php
					if(!empty($wcfm_groups_array)) {
						woocommerce_wp_select( array(
							'id' => '_job_listing_package_wcfm_group',
							'label' => __( 'Capability Group', 'wc-frontend-manager-groups-staffs' ),
							'description' => __( 'Choose WCfM capability group. User will automatically associate with this group when subscribe to this packgae.', 'wc-frontend-manager-groups-staffs' ),
							'value' => get_post_meta( $post_id, '_package_wcfm_group', true ),
							'desc_tip' => true,
							'options' => $wcfm_groups_array
						) );
					}
					?>
				</div>
				<?php
			}
		}
	}
	
	/**
	 * My Listings Job Package group Field Save
	 */
	function wcfmgs_job_package_save_product_data( $post_id ) {
		// Capability Group
		if ( isset( $_POST['_job_listing_package_wcfm_group'] ) ) {
			update_post_meta( $post_id, '_package_wcfm_group', $_POST['_job_listing_package_wcfm_group'] );
		}
	}
	
	/**
	 * My Listings Job Package subscription group association
	 */
	function wcfmgs_job_package_subscription_group_association( $post_ID, $post, $update ) {
		
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFM_Dependencies::wcfm_products_mylistings_active_check() ) {
				$vendor_id = get_post_meta( $post_ID, '_user_id', true );
				$product_id = get_post_meta( $post_ID, '_product_id', true );
				if( $vendor_id && $product_id ) {
					$package_wcfm_group = get_post_meta( $product_id, '_package_wcfm_group', true );
					
					if( $package_wcfm_group ) {
						update_user_meta( $vendor_id, '_wcfm_vendor_group', array($package_wcfm_group) );
						update_user_meta( $vendor_id, '_wcfm_vendor_group_list', implode( ",", array($package_wcfm_group) ) );
						$group_vendors = (array) get_post_meta( $package_wcfm_group, '_group_vendors', true );
						$group_vendors[] = $vendor_id;
						update_post_meta( $package_wcfm_group, '_group_vendors', $group_vendors );
					} else {
						delete_user_meta( $vendor_id, '_wcfm_vendor_group' );
					}
				}
			}
		}
	}

	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-frontend-manager-groups-staffs' );

		//load_textdomain( 'wc-frontend-manager-groups-staffs', WP_LANG_DIR . "/wc-frontend-manager-groups-staffs/wc-frontend-manager-groups-staffs-$locale.mo");
		load_textdomain( 'wc-frontend-manager-groups-staffs', $this->plugin_path . "lang/wc-frontend-manager-groups-staffs-$locale.mo");
		load_textdomain( 'wc-frontend-manager-groups-staffs', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-groups-staffs-$locale.mo");
	}

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}

	// End load_class()

	/**
	 * Install upon activation.
	 *
	 * @access public
	 * @return void
	 */
	static function activate_wcfmgs() {
		global $WCFM, $WCFMgs, $wp_roles;

		// License Activation
		$WCFMgs->load_class('license');
		WCFMgs_LICENSE()->activation();
		
		if( !WCFMgs_Dependencies::wcfm_wc_appointments_active_check() ) {
			// Capabilities.
			if ( class_exists( 'WP_Roles' ) ) {
				if ( ! isset( $wp_roles ) ) {
					$wp_roles = new WP_Roles();
				}
			}
	
			// Shop staff role.
			add_role( 'shop_staff', __( 'Shop Staff', 'woocommerce-appointments' ), array(
				'level_0'                	=> true,
	
				'read'                   	=> true,
	
				'read_private_posts'     	=> true,
				'edit_posts'             	=> true,
				'edit_published_posts'   	=> true,
				'edit_private_posts'     	=> true,
				'edit_others_posts'      	=> false,
				'publish_posts'         	=> true,
				'delete_private_posts'   	=> true,
				'delete_posts'           	=> true,
				'delete_published_posts' 	=> true,
				'delete_others_posts'    	=> false,
	
				'read_private_pages'     	=> true,
				'edit_pages'             	=> true,
				'edit_published_pages'   	=> true,
				'edit_private_pages'     	=> true,
				'edit_others_pages'      	=> false,
				'publish_pages'          	=> true,
				'delete_pages'           	=> true,
				'delete_private_pages'   	=> true,
				'delete_published_pages' 	=> true,
				'delete_others_pages'    	=> false,
	
				'read_private_products'     => true,
				'edit_products'             => true,
				'edit_published_products'   => true,
				'edit_private_products'     => true,
				'edit_others_products'    	=> false,
				'publish_products'         	=> true,
				'delete_products'           => true,
				'delete_private_products'   => true,
				'delete_published_products' => true,
				'delete_others_products'    => false,
	
				'manage_categories'      	=> false,
				'manage_links'           	=> false,
				'moderate_comments'      	=> true,
				'unfiltered_html'        	=> true,
				'upload_files'           	=> true,
				'export'                 	=> false,
				'import'                 	=> false,
	
				'edit_users'             	=> true,
				'list_users'             	=> true,
			) );
		}

		update_option('wcfmgs_installed', 1);
	}

	/**
	 * UnInstall upon deactivation.
	 *
	 * @access public
	 * @return void
	 */
	static function deactivate_wcfmgs() {
		global $WCFM, $WCFMgs;
		
		// License Deactivation
		$WCFMgs->load_class('license');
		WCFMgs_LICENSE()->uninstall();
        
		delete_option('wcfmgs_installed');
	}
	
	/**
	 * Restrict Shop Managers to access wp-admin
	 */
	function restrict_wcfm_shop_manager_backend() {
	  global $WCFM, $_GET;
		if(is_user_logged_in()) {
			$user = wp_get_current_user();
			$wcfm_shop_manager_capability_options = get_option( 'wcfm_shop_manager_capability_options' );
			$wcfm_sm_wpadmin = ( isset( $wcfm_shop_manager_capability_options['sm_wpadmin'] ) ) ? $wcfm_shop_manager_capability_options['sm_wpadmin'] : 'no';
			$is_export = false;
			$is_import = false;
			if( isset($_GET['page']) && ( $_GET['page'] == 'product_exporter' ) ) { $is_export = true; }
			if( isset($_GET['page']) && ( $_GET['page'] == 'product_importer' ) ) { $is_import = true; }
			
			if( is_admin() && ( 'yes' == $wcfm_sm_wpadmin ) && !$is_export && !$is_import && in_array( 'shop_manager', (array) $user->roles ) && !defined('DOING_AJAX')) {
				if( isset( $_GET['wc_gcal_oauth'] ) || isset( $_GET['wc_gcal_logout'] ) ) {
					// WC Appointments Gcal OAuth support
					wp_redirect( get_wcfm_profile_url() . '#wcfm_profile_manage_form_apt_gcal_sync_head' );
				} else {
					wp_redirect( get_wcfm_url() );
				}
				exit;
			}
		}
	}
	
	/**
	 * Restrict Shop Staff to access wp-admin
	 */
	function restrict_wcfm_shop_staff_backend() {
	  global $WCFM, $_GET;
		if(is_user_logged_in()) {
			$user = wp_get_current_user();
			$wcfm_shop_staff_capability_options = get_option( 'wcfm_shop_staff_capability_options' );
			$wcfm_ss_wpadmin = ( isset( $wcfm_shop_staff_capability_options['ss_wpadmin'] ) ) ? $wcfm_shop_staff_capability_options['ss_wpadmin'] : 'no';
			$is_export = false;
			$is_import = false;
			if( isset($_GET['page']) && ( $_GET['page'] == 'product_exporter' ) ) { $is_export = true; }
			if( isset($_GET['page']) && ( $_GET['page'] == 'product_importer' ) ) { $is_import = true; }
			
			if( is_admin() && ( 'yes' == $wcfm_ss_wpadmin ) && !$is_export && !$is_import && in_array( 'shop_staff', (array) $user->roles ) && !defined('DOING_AJAX')) {
				if( isset( $_GET['wc_gcal_oauth'] ) || isset( $_GET['wc_gcal_logout'] ) ) {
					// WC Appointments Gcal OAuth support
					wp_redirect( get_wcfm_profile_url() . '#wcfm_profile_manage_form_apt_gcal_sync_head' );
				} else {
					wp_redirect( get_wcfm_url() );
				}
				exit;
			}
		}
	}
}