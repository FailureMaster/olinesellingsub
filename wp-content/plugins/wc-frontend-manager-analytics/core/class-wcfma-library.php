<?php

/**
 * WCFMa plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	wcfma/core
 * @version   1.0.0
 */
 
class WCFMa_Library {
	
	public $lib_path;
  
  public $lib_url;
  
  public $php_lib_path;
  
  public $php_lib_url;
  
  public $js_lib_path;
  
  public $js_lib_url;
  
  public $css_lib_path;
  
  public $css_lib_url;
  
  public $views_path;
	
	public function __construct() {
    global $WCFMa;
		
	  $this->lib_path = $WCFMa->plugin_path . 'assets/';

    $this->lib_url = $WCFMa->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->views_path = $WCFMa->plugin_path . 'views/';
    
    // Load wcfma Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load wcfma Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    
    // Load wcfma views
    add_action( 'wcfm_load_views', array( &$this, 'load_views' ) );
  }
  
  public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMa;
    
	  switch( $end_point ) {
	  	
	    case 'wcfm-dashboard':
	    	if ( $is_wcfm_analytics_enable = is_wcfm_analytics() ) {
	    		if ( $wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true ) ) {
						$this->load_jvectormap_lib();
						wp_enqueue_script( 'wcfma_analytics_js', $this->js_lib_url . 'wcfma-script-analytics-dashboard.js', array('jquery'), $WCFMa->version, true );
					}
				}
	  	break;
	  	
	  	case 'wcfm-analytics':
	  		$this->load_jvectormap_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_chartjs_lib();
      	$WCFM->library->load_daterangepicker_lib();
	    	wp_enqueue_script( 'wcfma_analytics_js', $this->js_lib_url . 'wcfma-script-analytics.js', array('jquery'), $WCFMa->version, true );
	    	wp_enqueue_script( 'wcfma_analytics_dashboard_js', $this->js_lib_url . 'wcfma-script-analytics-dashboard.js', array('jquery'), $WCFMa->version, true );
      break;
      
    }
  }
  
  public function load_styles( $end_point ) {
	  global $WCFM, $WCFMa;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-analytics':
	  		wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMa->version );
	  		wp_enqueue_style( 'wcfm_reports_css',  $WCFM->library->css_lib_url . 'reports/wcfm-style-reports-sales-by-date.css', array(), $WCFMa->version );
	    	wp_enqueue_style( 'wcfma_analytics_css',  $this->css_lib_url . 'wcfma-style-analytics.css', array(), $WCFMa->version );
		  break;
		  
		  case 'wcfm-listings-stats':
		  	wp_enqueue_style( 'wpjms-dashboard' );
		  break;
		}
	}
	
	public function load_views( $end_point ) {
	  global $WCFM, $WCFMa;
	  
	  switch( $end_point ) {
	  	
	  	case 'wcfm-analytics':
        $WCFMa->template->get_template( 'wcfma-view-analytics.php' );
      break;
      
      case 'wcfm-listings-stats':
        $WCFMa->template->get_template( 'wcfma-view-listings-stats.php' );
      break;
      
      case 'wcfm-capability':
      	if( !wcfm_is_vendor() ) {
					//include_once( $this->views_path . 'wcfma-view-capability.php' );
				}
      break;
    }
  }
  
  public function world_map_analytics_data() {
  	global $WCFM, $WCFMa, $wpdb;
  	
  	$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$wcfm_analytics_region = get_user_meta( $user_id, 'wcfm_analytics_region', true );
		if( !$wcfm_analytics_region ) $wcfm_analytics_region = 'world';
  	
  	$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';

		if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ) ) ) {
			$current_range = '7day';
		}
		
		$sql = "SELECT COUNT(ID) as views, country, state FROM {$wpdb->prefix}wcfm_detailed_analysis AS commission";

		$sql .= " WHERE 1=1";
		
		if( wcfm_is_vendor() ) {
			$sql .= " AND commission.author_id = %d";
		}
		$sql = wcfm_query_time_range_filter( $sql, 'visited', $current_range );	
		
		if( $wcfm_analytics_region != 'world' ) {
			$sql .= " AND commission.country = '" . $wcfm_analytics_region . "' GROUP BY commission.state ORDER BY views DESC";
		} else {
			$sql .= " GROUP BY commission.country ORDER BY views DESC";
		}

		// Enable big selects for reports
		$wpdb->query( 'SET SESSION SQL_BIG_SELECTS=1' );

		if( wcfm_is_vendor() ) {
			$is_marketplace = wcfm_is_marketplace();
			if( $is_marketplace == 'wcpvendors' ) {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, apply_filters( 'wcfm_current_vendor_id', WC_Product_Vendors_Utils::get_logged_in_vendor() ) ) );
			} else {
				$results = $wpdb->get_results( $wpdb->prepare( $sql, $user_id ) );
			}
		} else {
			$results = $wpdb->get_results( $sql );
		}
		
		$country_arr_full = array( "AF" => 0, "AL" => 0, "DZ" => 0, "AO" => 0, "AG" => 0, "AR" => 0, "AM" => 0, "UK" => 0, "AU" => 0, "AT" => 0, "AZ" => 0, "BS" => 0, "BH" => 0, "BD" => 0, "BB" => 0, "BY" => 0, "BE" => 0, "BZ" => 0, "BJ" => 0, "BT" => 0, "BO" => 0, "BA" => 0, "BW" => 0, "BR" => 0, "BN" => 0, "BG" => 0, "BF" => 0, "BI" => 0, "KH" => 0, "CM" => 0, "CA" => 0, "CV" => 0, "CF" => 0, "TD" => 0, "CL" => 0, "CN" => 0, "CO" => 0, "KM" => 0, "CD" => 0, "CG" => 0, "CR" => 0, "CI" => 0, "HR" => 0, "CY" => 0, "CZ" => 0, "DK" => 0, "DJ" => 0, "DM" => 0, "DO" => 0, "EC" => 0, "EG" => 0, "SV" => 0, "GQ" => 0, "ER" => 0, "EE" => 0, "ET" => 0, "FJ" => 0, "FI" => 0, "FR" => 0, "GA" => 0, "GL" => 0, "GM" => 0, "GE" => 0, "DE" => 0, "GH" => 0, "GR" => 0, "GD" => 0, "GT" => 0, "GN" => 0, "GW" => 0, "GY" => 0, "HT" => 0, "HN" => 0, "HK" => 0, "HU" => 0, "IS" => 0, "IN" => 0, "ID" => 0, "IR" => 0, "IQ" => 0, "IE" => 0, "IL" => 0, "IT" => 0, "JM" => 0, "JP" => 0, "JO" => 0, "KZ" => 0, "KE" => 0, "KI" => 0, "KR" => 0, "KW" => 0, "KG" => 0, "LA" => 0, "LV" => 0, "LB" => 0, "LS" => 0, "LR" => 0, "LY" => 0, "LT" => 0, "LU" => 0, "MK" => 0, "MG" => 0, "MW" => 0, "MY" => 0, "MV" => 0, "ML" => 0, "MT" => 0, "MR" => 0, "MU" => 0, "MX" => 0, "MD" => 0, "MN" => 0, "ME" => 0, "MA" => 0, "MZ" => 0, "MM" => 0, "NA" => 0, "NP" => 0, "NL" => 0, "NZ" => 0, "NI" => 0, "NE" => 0, "NG" => 0, "NO" => 0, "OM" => 0, "PK" => 0, "PA" => 0, "PG" => 0, "PY" => 0, "PE" => 0, "PH" => 0, "PL" => 0, "PT" => 0, "QA" => 0, "RO" => 0, "RU" => 0, "RW" => 0, "WS" => 0, "ST" => 0, "SA" => 0, "SN" => 0, "RS" => 0, "SC" => 0, "SL" => 0, "SG" => 0, "SK" => 0, "SI" => 0, "SB" => 0, "ZA" => 0, "ES" => 0, "LK" => 0, "KN" => 0, "LC" => 0, "VC" => 0, "SD" => 0, "SR" => 0, "SZ" => 0, "SE" => 0, "CH" => 0, "SY" => 0, "TW" => 0, "TJ" => 0, "TZ" => 0, "TH" => 0, "TL" => 0, "TG" => 0, "TO" => 0, "TT" => 0, "TN" => 0, "TR" => 0, "TM" => 0, "UG" => 0, "UA" => 0, "AE" => 0, "GB" => 0, "US" => 0, "UY" => 0, "UZ" => 0, "VU" => 0, "VE" => 0, "VN" => 0, "YE" => 0, "ZM" => 0, "ZW" => 0, "KP" => 0, "CU" => 0, "PR" => 0, "FK" => 0, "SO" => 0, "SS" => 0, "EH" => 0, "XK" => 0, "XS" => 0, "NC" => 0, "PS" => 0 );
		$country_arr_analytics = array();
		$total_views = 0;
		if( !empty( $results ) ) {
			foreach( $results as $result ) {
				if( $result->country && $result->country == 'UK' ) $result->country = 'GB';
				if( $wcfm_analytics_region == 'world' ) {
					if( $result->views && $result->country ) {
						if( isset( $country_arr_full[$result->country] ) ) $country_arr_full[$result->country] = $result->views;
						$country_arr_analytics[$result->country] = $result->views;
						$total_views += $result->views;
					}
				} else {
					if( $result->views && $result->state ) {
						$country_arr_full[$result->country . '-' . $result->state] = $result->views;
						$country_arr_analytics[$result->country . '-' . $result->state] = $result->views;
						$total_views += $result->views;
					}
				}
			}
		}
		
		if( $wcfm_analytics_region == 'world' ) {
			$countries = WC()->countries->get_allowed_countries();
		} else {
			$countries = get_wcfma_state_list_country( $wcfm_analytics_region );
		}
    $region_analytics = array();
    if( !empty( $countries ) ) {
			if( !empty( $country_arr_analytics ) ) {
				foreach( $country_arr_analytics as $country_code => $view_count ) {
					if( isset( $countries[$country_code] ) ) {
						$region_analytics[$country_code] = array( 'country' => $countries[$country_code], 'view_count' => $view_count, 'view_percent' => round( ( ( $view_count / $total_views ) * 100 ), 2) );
					}
				}
			}
			uasort( $region_analytics, array( &$this, 'wcfma_sort_by_count' ) );
			$region_analytics = array_reverse( $region_analytics, true);
			
			if( $wcfm_analytics_region != 'world' ) {
				foreach( $countries as $country_code => $label ) {
					if( !isset( $country_arr_full[$country_code] ) ) {
						$country_arr_full[$country_code] = 0;
					}
				}
			}
		}
		
		$user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$wcfm_analytics_region = get_user_meta( $user_id, 'wcfm_analytics_region', true );
		if( !$wcfm_analytics_region ) $wcfm_analytics_region = 'world';
		$wcfm_analytics_region = strtolower( $wcfm_analytics_region );
		$wcfma_map_name = $wcfm_analytics_region . '_mill';
		$get_wcfma_map_name_list = get_wcfma_map_name_list_country();
		if( isset( $get_wcfma_map_name_list[$wcfm_analytics_region] ) ) $wcfma_map_name = $get_wcfma_map_name_list[$wcfm_analytics_region]; 
		
		wp_localize_script( 'wcfma_analytics_js', 'wcfminsights_countries', $country_arr_full );
		wp_localize_script( 'wcfma_analytics_js', 'wcfma_map_name', $wcfma_map_name );
		wp_localize_script( 'wcfma_analytics_js', 'wcfminsights_viewname', __( 'views', 'wc-frontend-manager-analytics' ) );
		
		
		return $region_analytics;
  }
  
  function wcfma_sort_by_count( $a, $b ) {
		if ( ! isset( $a['view_count'] ) || ! isset( $b['view_count'] ) || $a['view_count'] === $b['view_count'] ) {
			return 0;
		}
		return ( $a['view_count'] < $b['view_count'] ) ? -1 : 1;
	}
  
  /**
	 * Jquery VectorMap library
	*/
	public function load_jvectormap_lib() {
	  global $WCFM, $WCFMa;
	  wp_enqueue_script( 'jquery-jvectormap_js', $WCFMa->plugin_url . 'includes/jvectormap/jquery-jvectormap-2.0.3.min.js', array('jquery'), $WCFMa->version, true );
	  
	  $user_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		$wcfm_analytics_region = get_user_meta( $user_id, 'wcfm_analytics_region', true );
		if( !$wcfm_analytics_region ) $wcfm_analytics_region = 'world';
		$wcfm_analytics_region = strtolower( $wcfm_analytics_region );
		
	  wp_enqueue_script( 'jquery-jvectormap-world_js', $WCFMa->plugin_url . 'includes/jvectormap/'.$wcfm_analytics_region.'-mill.js', array('jquery', 'jquery-jvectormap_js'), $WCFMa->version, true );
	  
	  wp_enqueue_style( 'wcfm_timepicker_css',  $WCFMa->plugin_url . 'includes/jvectormap/jquery-jvectormap-2.0.3.css', array(), $WCFMa->version );
	}
  
}