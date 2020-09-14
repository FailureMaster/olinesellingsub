<?php
/**
 * WCFM Analytics plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.0
 */
 
class WCFMa_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMa;
		
		// Fetching Top Referrers Stats
		add_action( 'wp_ajax_wcfm_top_referrers_analytics_data', array( &$this, 'wcfm_top_referrers_analytics_data' ) );
		
		// Fetching Most Viewd Products Stats
		add_action( 'wp_ajax_wcfm_top_products_analytics_data', array( &$this, 'wcfm_top_products_analytics_data' ) );
		
		// Fetching Product Stats
		add_action( 'wp_ajax_wcfm_product_analytics_data', array( &$this, 'wcfm_product_analytics_data' ) );
		
		// Fetching Category Stats
		add_action( 'wp_ajax_wcfm_category_analytics_data', array( &$this, 'wcfm_category_analytics_data' ) );
		
	}
	
	/**
	 * Fetching Top Referrers list
	 */
	public function wcfm_top_referrers_analytics_data() {
		global $WCFM, $WCFMa, $wpdb, $_POST, $wp_locale;
		
		$current_range = '7day';
		if( isset($_POST['range']) ) $current_range = $_POST['range'];
		
		// Generate Data for total earned commision
		$select = "SELECT COUNT(commission.ID) AS count, commission.referer";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_detailed_analysis AS commission";
		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND commission.author_id = %d";
			$sql .= " AND commission.is_store = 1";
		} else {
			$sql .= " AND commission.is_shop = 1";
		}
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );

		$sql .= " GROUP BY commission.referer ORDER BY count DESC LIMIT 10";
		
		// SELECT COUNT(analytics.ID) AS count, analytics.referer FROM wp_wcfm_detailed_analysis AS analytics WHERE 1=1 AND analytics.is_shop = 1 AND MONTH( analytics.visited ) = MONTH( NOW() ) GROUP BY analytics.referer ORDER BY count DESC LIMIT 10
		
		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
		
		if( wcfm_is_vendor() ) {
			$top_referrers = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
		} else { 
			$top_referrers = $wpdb->get_results( $sql );
		}
		
		$top_referrer_array = '';
		$colors_arr = apply_filters( 'wcfm_sales_by_product_pie_chart_colors', array( '#00897b', '#D15600', '#356AA0', '#C79810', '#B02B2C', '#D01F3C' ) );
		if( !empty($top_referrers) ) {
			foreach( $top_referrers as $index => $top_referrer ) {
				if(!$top_referrer_array) $top_referrer_array = '[';
				else $top_referrer_array .= ',';
				$top_referrer_array .= '{"label":"' . substr( $top_referrer->referer, 0, 100 ) . ' ...", "href":"' . $top_referrer->referer . '", "data":' . $top_referrer->count . '}';
			}
		} else {
			$top_referrer_array .= '[{"label":"' . __( 'No referrer yet ..!!!', 'wc-frontend-manager-analytics' ) . '", "data": 0}';
		}
		$top_referrer_array .= ']';
		
		echo $top_referrer_array;
		
		die;
	}
	
	/**
	 * Fetching Most Viewd Products list
	 */
	public function wcfm_top_products_analytics_data() {
		global $WCFM, $WCFMa, $wpdb, $_POST, $wp_locale;
		
		$current_range = '7day';
		if( isset($_POST['range']) ) $current_range = $_POST['range'];
		
		// Generate Data for total earned commision
		$select = "SELECT SUM(commission.count) AS count, commission.product_id";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_daily_analysis AS commission";
		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND commission.author_id = %d";
		}
		$sql .= " AND commission.is_product = 1";
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );

		$sql .= " GROUP BY commission.product_id ORDER BY count DESC LIMIT 10";
		
		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
		
		if( wcfm_is_vendor() ) {
			$top_views = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
		} else { 
			$top_views = $wpdb->get_results( $sql );
		}
		
		$top_viewd_array = '';
		$colors_arr = apply_filters( 'wcfm_sales_by_product_pie_chart_colors', array( '#00897b', '#D15600', '#356AA0', '#C79810', '#B02B2C', '#D01F3C' ) );
		$top_view_pro = '';
		$top_view_labels = '';
		$top_view_datas = '';
		$top_view_data_count = 0;
		if( !empty($top_views) ) {
			foreach( $top_views as $index => $top_view ) {
				
				if ( !get_post_status ( $top_view->product_id ) ) continue;
				
				if($top_view_labels) $top_view_labels .= ',';
				if($top_view_datas) $top_view_datas .= ',';
				
				$top_view_labels .= '"' . get_the_title( $top_view->product_id ) . '"';
				$top_view_datas  .= '"' . $top_view->count . '"';
				
				$top_view_data_count++;
				if( $top_view_data_count > 5 ) break;
			}
		}
		
		if($top_view_labels && $top_view_datas) {
			$top_viewd_array = '{"labels": [' . $top_view_labels . '], "datas": [' . $top_view_datas . ']}';
		} else {
			$top_viewd_array = '{"labels": ["' . __( 'No views yet ..!!!', 'wc-frontend-manager-analytics' ) . '"], "datas": [1] }';
		}
		
		echo $top_viewd_array;
		
		die;
	}
	
	/**
	 * Fetching Product Analytics data
	 */
	public function wcfm_product_analytics_data() {
		global $WCFM, $WCFMa, $wpdb, $_POST, $wp_locale;
		
		$_GET = $_POST;
		
		$current_range = '7day';
		if( isset($_POST['range']) ) $current_range = $_POST['range'];
		
		$product_id = 0;
		if( isset($_POST['productid']) ) $product_id = absint( $_POST['productid'] );
		
		// Generate Data for total earned commision
		$select = "SELECT commission.count AS count, commission.visited";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_daily_analysis AS commission";
		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND commission.author_id = %d";
		}
		$sql .= " AND commission.is_product = 1";
		$sql .= " AND commission.product_id = {$product_id}";
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );

		$sql .= " GROUP BY DATE( commission.visited )";
			
		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
		
		if( wcfm_is_vendor() ) {
			$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', get_current_user_id() ) ) );
		} else { 
			$results = $wpdb->get_results( $sql );
		}
		
		// Prepare data for report
		include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
		$WC_Admin_Report = new WC_Admin_Report();
		$WC_Admin_Report->calculate_current_range( $current_range );
		$view_count         = $WC_Admin_Report->prepare_chart_data( $results, 'visited', 'count', $WC_Admin_Report->chart_interval, $WC_Admin_Report->start_date, $WC_Admin_Report->chart_groupby );
		$chart_data         = $WCFM->wcfm_prepare_chart_data( $view_count );
		
		echo $chart_data;
		
		die;
	}
		
	/**
	 * Fetching Category Analytics data
	 */
	public function wcfm_category_analytics_data() {
		global $WCFM, $WCFMa, $wpdb, $_POST, $wp_locale;
		
		$_GET = $_POST;
		
		$current_range = '7day';
		if( isset($_POST['range']) ) $current_range = $_POST['range'];
		
		$category_id = 0;
		if( isset($_POST['categoryid']) ) $category_id = absint( $_POST['categoryid'] );
		
		// Generate Data for total earned commision
		$select = "SELECT commission.count AS count, commission.visited";

		$sql = $select;
		$sql .= " FROM {$wpdb->prefix}wcfm_daily_analysis AS commission";
		$sql .= " WHERE 1=1";
		
		$sql .= " AND commission.is_shop = 9";
		$sql .= " AND commission.product_id = {$category_id}";
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );
			
		$sql .= " GROUP BY DATE( commission.visited )";
			
		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );
		
		$results = $wpdb->get_results( $sql );
		
		// Prepare data for report
		include_once( WC()->plugin_path() . '/includes/admin/reports/class-wc-admin-report.php' );
		$WC_Admin_Report = new WC_Admin_Report();
		$WC_Admin_Report->calculate_current_range( $current_range );
		$view_count         = $WC_Admin_Report->prepare_chart_data( $results, 'visited', 'count', $WC_Admin_Report->chart_interval, $WC_Admin_Report->start_date, $WC_Admin_Report->chart_groupby );
		$chart_data         = $WCFM->wcfm_prepare_chart_data( $view_count );
		
		echo $chart_data;
		
		die;
	}
}