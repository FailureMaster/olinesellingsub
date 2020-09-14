<?php

/**
 * WCFM Affiliate plugin Install
 *
 * Plugin install script which adds default pages, taxonomies, and database tables to WordPress. Runs on activation and upgrade.
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/helpers
 * @version   1.0.0
 */
 
class WCFMaf_Install {

	public $arr = array();

	public function __construct() {
		global $WCFM, $WCFMaf, $WCFM_Query;
		
		if ( get_option("wcfmaf_page_install") == 1 ) {
			$wcfm_page_options = get_option( 'wcfm_page_options', array() );
			if( isset($wcfm_page_options['wcfm_affiliate_registration_page_id']) ) {
				wp_update_post(array('ID' => $wcfm_page_options['wcfm_affiliate_registration_page_id'], 'post_content' => '[wcfm_affiliate_registration]'));
			}
			//update_option('wcfm_page_options', $wcfm_page_options);
		}
		
		if ( !get_option("wcfmaf_page_install") ) {
			$this->wcfmaf_create_pages();
			update_option("wcfmaf_page_install", 1);
		}
		
		if ( !get_option( 'wcfmaf_table_install' ) ) {
			$this->wcfmaf_create_tables();
			update_option("wcfmaf_table_install", 1);
		}
		
		self::wcfmaf_user_role();
		
	}
	
	/**
	 * Create a page
	 *
	 * @access public
	 * @param mixed $slug Slug for the new page
	 * @param mixed $option Option name to store the page's ID
	 * @param string $page_title (default: '') Title for the new page
	 * @param string $page_content (default: '') Content for the new page
	 * @param int $post_parent (default: 0) Parent for the new page
	 * @return void
	 */
	function wcfmaf_create_page($slug, $option, $page_title = '', $page_content = '', $post_parent = 0) {
		global $wpdb;
		$option_value = get_option($option);
		if ($option_value > 0 && get_post($option_value))
				return;
		$page_found = $wpdb->get_var("SELECT ID FROM " . $wpdb->posts . " WHERE post_name = '$slug' LIMIT 1;");
		if ($page_found) :
				if (!$option_value)
						update_option($option, $page_found);
				return;
		endif;
		$page_data = array(
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_author' => 1,
				'post_name' => $slug,
				'post_title' => $page_title,
				'post_content' => $page_content,
				'post_parent' => $post_parent,
				'comment_status' => 'closed'
		);
		$page_id = wp_insert_post($page_data);
		update_option($option, $page_id);
	}

	/**
	 * Create pages that the plugin relies on, storing page id's in variables.
	 *
	 * @access public
	 * @return void
	 */
	function wcfmaf_create_pages() {
		global $WCFM, $WCFMaf;

		// WCFM page
		$this->wcfmaf_create_page(esc_sql(_x('affiliate-register', 'page_slug', 'affiliate-register')), 'wcfm_affiliate_registration_page_id', __('Affiliate Registration', 'wc-frontend-manager-affiliate'), '[wcfm_affiliate_registration]');
		
		$array_pages = get_option( 'wcfm_page_options', array() );
		$array_pages['wcfm_affiliate_registration_page_id'] = get_option('wcfm_affiliate_registration_page_id');

		update_option('wcfm_page_options', $array_pages);
	}
	
	/**
	 * Create WCFM Affiliate tables
	 * @global object $wpdb
	 * From Version 1.0.0
	 */
	function wcfmaf_create_tables() {
		global $wpdb;
		$collate = '';
		if ($wpdb->has_cap('collation')) {
				$collate = $wpdb->get_charset_collate();
		}
		$create_tables_query = array();
		
		$create_tables_query[] = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wcfm_affiliate_orders` (
															`ID` bigint(20) NOT NULL AUTO_INCREMENT,
															`affiliate_id` bigint(20) NOT NULL,
															`vendor_id` bigint(20) NOT NULL,
															`order_id` bigint(20) NOT NULL,
															`order_commission_id` bigint(20) NOT NULL default 0,
															`product_id` bigint(20) NOT NULL DEFAULT 0,
															`variation_id` bigint(20) NOT NULL DEFAULT 0,
															`quantity` bigint(20) NOT NULL DEFAULT 1,
															`product_price` varchar(255) NULL DEFAULT 0,
															`item_id` bigint(20) NOT NULL DEFAULT 0,
															`item_type` varchar(255) NULL,
															`item_sub_total` varchar(255) NULL DEFAULT 0,
															`item_total` varchar(255) NULL DEFAULT 0,
															`commission_type` varchar(255) NOT NULL DEFAULT 'pending',
															`commission_amount` varchar(255) NOT NULL DEFAULT 0,
															`withdrawal_id` bigint(20) NOT NULL DEFAULT 0,
															`withdraw_charges` varchar(255) NOT NULL DEFAULT 0,
															`commission_status` varchar(100) NOT NULL DEFAULT 'pending',
															`withdraw_status` varchar(100) NOT NULL DEFAULT 'pending',
															`is_refunded` tinyint(1) NOT NULL default 0,
															`is_partially_refunded` tinyint(1) NOT NULL default 0,
															`is_trashed` tinyint(1) NOT NULL default 0,			
															`commission_paid_date` timestamp NULL,
															`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,				
															PRIMARY KEY (`ID`)
															) $collate;";
															
		$create_tables_query[] = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wcfm_affiliate_orders_meta` (
															`ID` bigint(20) NOT NULL AUTO_INCREMENT,
															`order_affiliate_id` bigint(20) NOT NULL default 0,
															`key` VARCHAR(200) NOT NULL,
															`value` longtext NOT NULL,
															PRIMARY KEY (`ID`)
															) $collate;";
															
		foreach ($create_tables_query as $create_table_query) {
			$wpdb->query($create_table_query);
		}
		
		$delete_tables_query[] = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "wcfm_marketplace_affiliate_orders`";
		$delete_tables_query[] = "DROP TABLE IF EXISTS `" . $wpdb->prefix . "wcfm_marketplace_affiliate_orders_meta`";
		
		foreach ($delete_tables_query as $delete_table_query) {
			$wpdb->query($delete_table_query);
		}
	}
	
	/**
	 * Register Affiliate Boy user role
	 *
	 * @access public
	 * @return void
	 */
	public static function wcfmaf_user_role() {

		add_role( 'wcfm_affiliate', __( 'Store Affiliates', 'wc-frontend-manager-affiliate' ), array(
			'level_7'                	=> true,
			'level_6'                	=> true,
			'level_5'                	=> true,
			'level_4'                	=> true,
			'level_3'                	=> true,
			'level_2'                	=> true,
			'level_1'                	=> true,
			'level_0'                	=> true,

			'read'                   	=> false,

			'read_private_posts'     	=> true,
			'edit_posts'             	=> false,
			'edit_published_posts'   	=> false,
			'edit_private_posts'     	=> false,
			'edit_others_posts'      	=> false,
			'publish_posts'         	=> false,
			'delete_private_posts'   	=> false,
			'delete_posts'           	=> false,
			'delete_published_posts' 	=> false,
			'delete_others_posts'    	=> false,

			'read_private_pages'     	=> false,
			'edit_pages'             	=> false,
			'edit_published_pages'   	=> false,
			'edit_private_pages'     	=> false,
			'edit_others_pages'      	=> false,
			'publish_pages'          	=> false,
			'delete_pages'           	=> false,
			'delete_private_pages'   	=> false,
			'delete_published_pages' 	=> false,
			'delete_others_pages'    	=> false,

			'read_private_products'     => true,
			'edit_products'             => false,
			'edit_published_products'   => false,
			'edit_private_products'     => false,
			'edit_others_products'    	=> false,
			'publish_products'         	=> false,
			'delete_products'           => false,
			'delete_private_products'   => false,
			'delete_published_products' => false,
			'delete_others_products'    => false,

			'manage_categories'      	=> false,
			'manage_links'           	=> false,
			'moderate_comments'      	=> false,
			'unfiltered_html'        	=> true,
			'upload_files'           	=> true,
			'export'                 	=> false,
			'import'                 	=> false,

			'edit_users'             	=> false,
			'list_users'             	=> false,
		) );
	}
}

?>