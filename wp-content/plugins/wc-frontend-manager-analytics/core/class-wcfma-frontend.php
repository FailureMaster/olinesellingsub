<?php
/**
 * WCFM Analytics plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.0
 */
 
class WCFMa_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMa;
		
		
		// WCFM Shop Managrs End Points
 		add_filter( 'wcfm_query_vars', array( &$this, 'wcfma_analytics_wcfm_query_vars' ), 90 );
		add_filter( 'wcfm_endpoint_title', array( &$this, 'wcfma_analytics_endpoint_title' ), 90, 2 );
		add_action( 'init', array( &$this, 'wcfma_analytics_init' ), 90 );
		
		// WCFM Appointments Endpoint Edit
		add_filter( 'wcfm_endpoints_slug', array( $this, 'wcfma_analytics_endpoints_slug' ) );
		
		// WCFM Menu Filter
		add_filter( 'wcfm_menus', array( &$this, 'wcfma_analytics_menus' ), 300 );
		//add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfma_analytics_menu_dependancy_map' ) );
		
		add_action( 'end_wcfm_settings', array( &$this, 'wcfma_analytics_settings' ), 25 );
		add_action( 'end_wcfm_vendor_settings', array( &$this, 'wcfma_analytics_settings' ), 25 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfma_analytics_settings_update' ), 20 );
		add_action( 'wcfm_vendor_settings_update', array( &$this, 'wcfma_analytics_vendor_settings_update' ), 20, 2 );
		
		// Analytics Capability Setting
		add_action( 'wcfm_capability_settings_miscellaneous', array( &$this, 'wcfma_capability_settings_analytics' ), 8 );
		
	}
	
	/**
   * WCFM Analytics Query Var
   */
  function wcfma_analytics_wcfm_query_vars( $query_vars ) {
  	$wcfm_modified_endpoints = (array) get_option( 'wcfm_endpoints' );
  	
		$query_analytics_vars = array(
			'wcfm-analytics'          => ! empty( $wcfm_modified_endpoints['wcfm-analytics'] ) ? $wcfm_modified_endpoints['wcfm-analytics'] : 'analytics',
		);
		
		$query_vars = array_merge( $query_vars, $query_analytics_vars );
		
		return $query_vars;
  }
  
  /**
   * WCFM Analytics End Point Title
   */
  function wcfma_analytics_endpoint_title( $title, $endpoint ) {
  	
  	switch ( $endpoint ) {
			case 'wcfm-analytics' :
				$title = __( 'Store Analytics', 'wc-frontend-manager-analytics' );
			break;
			//case 'wcfm-analytics-manage' :
				//$title = __( 'Shop Analytics Manage', 'wc-frontend-manager-analytics' );
			//break;
  	}
  	
  	return $title;
  }
  
  /**
   * WCFM Analytics Endpoint Intialize
   */
  function wcfma_analytics_init() {
  	global $WCFM_Query;
	
		// Intialize WCFM End points
		$WCFM_Query->init_query_vars();
		$WCFM_Query->add_endpoints();
		
		if( !get_option( 'wcfm_updated_end_point_wcfma_analytics' ) ) {
			// Flush rules after endpoint update
			flush_rewrite_rules();
			update_option( 'wcfm_updated_end_point_wcfma_analytics', 1 );
		}
  }
  
  /**
	 * WCFM Analytics Endpoiint Edit
	 */
	function wcfma_analytics_endpoints_slug( $endpoints ) {
		
		$wcfma_analytics_endpoints = array(
													'wcfm-analytics'           => 'analytics',
													//'wcfm-analytics-manage'    => 'wcfm-analytics-manage',
													);
		
		$endpoints = array_merge( $endpoints, $wcfma_analytics_endpoints );
		
		return $endpoints;
	}
  
  /**
   * WCFM Analytics Menu
   */
  function wcfma_analytics_menus( $menus ) {
  	global $WCFM;
  	
  	$wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true );
		$is_wcfm_analytics_enable = is_wcfm_analytics();
		if( !$is_wcfm_analytics_enable || !$wcfm_is_allow_analytics ) return $menus;
		
		$menus = array_slice($menus, 0, 3, true) +
										array( 'wcfm-analytics' => array(   'label'      => __( 'Analytics', 'wc-frontend-manager-analytics'),
																												 'url'        => get_wcfm_analytics_url( 'month' ),
																												 'icon'       => 'chart-line',
																												 'priority'   => 68
																												) )	 +
													array_slice($menus, 3, count($menus) - 3, true) ;
		
  	return $menus;
  }
  
  /**
   * WCFM Analytics Menu Dependency
   */
  function wcfma_analytics_menu_dependancy_map( $menu_dependency_mapping ) {
  	$menu_dependency_mapping['wcfm-analytics-manage'] = 'wcfm-analytics';
  	return $menu_dependency_mapping;
  }
  
  function wcfma_analytics_settings( $wcfm_options ) {
		global $WCFM, $WCFMa;
		
		if( !apply_filters( 'wcfm_is_allow_analytics', true ) ) return;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$wcfm_analytics_region = get_user_meta( $user_id, 'wcfm_analytics_region', true );
		if( !$wcfm_analytics_region ) $wcfm_analytics_region = 'world';
		
		$analytics_regions = array();
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_analytics_region_head">
			<label class="wcfmfa fa-chart-line"></label>
			<?php _e('Analytics Region', 'wc-frontend-manager-analytics'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_analytics_region_expander" class="wcfm-content">
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfma_settings_fields_general', array(
																																																"wcfm_analytics_region" => array('label' => __('Preferred Analytics Region', 'wc-frontend-manager-groups-staffs'), 'name' => 'wcfm_analytics_region', 'type' => 'select', 'options' => get_wcfma_country_list(), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $wcfm_analytics_region ),
																																																) ) );
				?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
	}
	
	function wcfma_analytics_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMa, $_POST;
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		if( isset( $wcfm_settings_form['wcfm_analytics_region'] ) ) {
			update_user_meta( $user_id, 'wcfm_analytics_region', $wcfm_settings_form['wcfm_analytics_region'] );
		} else {
			update_user_meta( $user_id, 'wcfm_analytics_region', 'world' );
		}
	}
	
	function wcfma_analytics_vendor_settings_update( $user_id, $wcfm_settings_form ) {
		$this->wcfma_analytics_settings_update( $wcfm_settings_form );
	}
	
	/**
	 * Analytics Capability Setting 
	 */
	function wcfma_capability_settings_analytics( $wcfm_capability_options ) {
		global $WCFM, $WCFMu;
	
		$analytics = ( isset( $wcfm_capability_options['analytics'] ) ) ? $wcfm_capability_options['analytics'] : 'no';
	
		?>
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Analytics', 'wc-frontend-manager-analytics' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_capability_analytics', array(  
																																 "analytics" => array('label' => __('Analytics', 'wc-frontend-manager-analytics') , 'name' => 'wcfm_capability_options[analytics]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $analytics),
																									) ) );
		
	}
	
}