<?php
/**
 * WCFM Listings_stats plugin core
 *
 * Plugin WPJM Stats Controler
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.0
 */
 
class WCFMa_Listings_Stats {
	
	public function __construct() {
		global $WCFM, $WCFMa;
		
		
		// WCFM Shop Managrs End Points
 		add_filter( 'wcfm_query_vars', array( &$this, 'wcfma_listings_stats_wcfm_query_vars' ), 90 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfma_listings_stats_endpoint_title' ), 90, 2 );
		add_action( 'init', array( &$this, 'wcfma_listings_stats_init' ), 90 );
		
		// WCFM Appointments Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfma_listings_stats_endpoints_slug' ) );
		
		// WCFM Menu Filter
		add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfma_listings_stats_menu_dependancy_map' ) );
		
		// Listings Actions
		add_action( 'wcfm_listings_head_actions', array( &$this, 'wcfma_listings_stats_head_actions' ) );
		
		// WP Job Manager Stats redirect
		add_action( 'template_redirect', array(&$this, 'wcfma_listings_stats_redirect' ));
	}
	
	/**
   * WCFM Listings_stats Query Var
   */
  function wcfma_listings_stats_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_listings_stats_vars = array(
			'wcfm-listings-stats'          => ! empty( $wcfm_modified_endpoints['wcfm-listings-stats'] ) ? $wcfm_modified_endpoints['wcfm-listings-stats'] : 'listings-stats',
		);
		
		$query_vars = array_merge( $query_vars, $query_listings_stats_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Listings_stats End Point Title
   */
  function wcfma_listings_stats_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-listings-stats' :
				$title = __( 'Listings Stats', 'wc-frontend-manager-analytics' );
			break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Listings_stats Endpoint Intialize
   */
  function wcfma_listings_stats_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfma_listings_stats' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfma_listings_stats', 1 );
		}
  }
  
  /**
	 * WCFM Listings_stats Endpoiint Edit
	 */
	function wcfma_listings_stats_endpoints_slug( $endpoints ) {
		
		$wcfma_listings_stats_endpoints = array(
													'wcfm-listings-stats'           => 'listings-stats',
													);
		
		$endpoints = array_merge( $endpoints, $wcfma_listings_stats_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Listings_stats Menu Dependency
   */
  function wcfma_listings_stats_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-listings-stats'] = 'wcfm-analytics';
  	return $menu_dependency_mapping;
  }
  
  /**
   * WCFM Listings Head Stats Actions Menu
   */
  function wcfma_listings_stats_head_actions() {
  	$wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true );
		$is_wcfm_analytics_enable = is_wcfm_analytics();
		if( !$is_wcfm_analytics_enable || !$wcfm_is_allow_analytics ) return;
		
  	if ( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
			if( WCFMa_Dependencies::wpjms_plugin_active_check() ) {
				if( $is_allow_wpjm_stats = apply_filters( 'is_allow_wpjm_stats', true ) ) {
					?>
					<a class="add_new_wcfm_ele_dashboard text_tip" href="<?php echo get_wcfm_listings_stats_url(); ?>" data-tip="<?php _e( 'Listings Stats', 'wc-frontend-manager-analytics' ); ?>"><span class="wcfmfa fa-chart-line"></span></a>
					<?php
				}
			}
		}
  }
  
  function wcfma_listings_stats_redirect() {
  	global $post;
  	
  	$wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true );
		$is_wcfm_analytics_enable = is_wcfm_analytics();
		if( !$is_wcfm_analytics_enable || !$wcfm_is_allow_analytics ) return;
  	
  	$page_id = get_option( 'wp_job_manager_stats_page_id' );
  	if( $page_id == $post->ID ) {
  		if( isset( $_GET['job_id'] )) {
  			wp_safe_redirect(  get_wcfm_listings_stats_url( $_GET['job_id'] ) );
  		} else {
  			wp_safe_redirect(  get_wcfm_listings_stats_url( ) );
  		}
  		exit();
  	}
  }
	
}