<?php

/**
 * WCFMmp plugin Install
 *
 * Plugin install script which adds default pages, taxonomies, and database tables to WordPress. Runs on activation and upgrade.
 *
 * @author 		WC Lovers
 * @package 	wcfmd/helpers
 * @version   1.0.0
 */
 
class WCFMd_Install {

	public $arr = array();

	public function __construct() {
		global $WCFM, $WCFMd, $WCFM_Query;
		
		if ( !get_option( 'wcfmd_table_install' ) ) {
			$this->wcfmd_create_tables();
			update_option("wcfmd_table_install", 1);
		}
		
		self::wcfmd_user_role();
		
	}
	
	/**
	 * Create WCFM Delivery tables
	 * @global object $wpdb
	 * From Version 1.0.0
	 */
	function wcfmd_create_tables() {
		global $wpdb;
		$collate = '';
		if ($wpdb->has_cap('collation')) {
				$collate = $wpdb->get_charset_collate();
		}
		$create_tables_query = array();
		
		$create_tables_query[] = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wcfm_delivery_orders` (
															`ID` bigint(20) NOT NULL AUTO_INCREMENT,
															`order_id` bigint(20) NOT NULL,
															`vendor_id` bigint(20) NOT NULL,
															`customer_id` bigint(20) NOT NULL,
															`payment_method` varchar(255) NOT NULL,
															`product_id` bigint(20) NOT NULL,
															`variation_id` bigint(20) NOT NULL DEFAULT 0,
															`product_price` varchar(255) NULL DEFAULT 0,
															`quantity` bigint(20) NOT NULL DEFAULT 1,
															`item_id` bigint(20) NOT NULL,
															`item_sub_total` varchar(255) NULL DEFAULT 0,
															`item_total` varchar(255) NULL DEFAULT 0,
															`delivery_boy` bigint(20) NOT NULL,
															`delivery_status` varchar(255) NOT NULL DEFAULT 'pending',
															`delivery_date` timestamp NULL,
															`commission_amount` varchar(255) NOT NULL DEFAULT 0,
															`commission_status` varchar(100) NOT NULL DEFAULT 'pending',
															`commission_paid_date` timestamp NULL,
															`is_trashed` tinyint(1) NOT NULL default 0,		
															`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,				
															PRIMARY KEY (`ID`),
															CONSTRAINT delivery_orders UNIQUE (order_id, vendor_id, item_id)
															) $collate;";
		
		$create_tables_query[] = "CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . "wcfm_delivery_orders_meta` (
															`ID` bigint(20) NOT NULL AUTO_INCREMENT,
															`order_delivery_id` bigint(20) NOT NULL default 0,
															`key` VARCHAR(200) NOT NULL,
															`value` longtext NOT NULL,
															PRIMARY KEY (`ID`)
															) $collate;";
															
		foreach ($create_tables_query as $create_table_query) {
			$wpdb->query($create_table_query);
		}
	}
	
	/**
	 * Register Delivery Boy user role
	 *
	 * @access public
	 * @return void
	 */
	public static function wcfmd_user_role() {

		add_role( 'wcfm_delivery_boy', __( 'Delivery Boy', 'wc-frontend-manager-delivery' ), array(
			'level_0'                	=> true,

			'read'                   	=> false,

			'read_private_posts'     	=> false,
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

			'read_private_products'     => false,
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