<?php

/**
 * WCFM Delivery plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	wcfmd/core
 * @version   1.0.0
 */
 
class WCFMd_Library {
	
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
    global $WCFMd;
		
	  $this->lib_path = $WCFMd->plugin_path . 'assets/';

    $this->lib_url = $WCFMd->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->views_path = $WCFMd->plugin_path . 'views/';
    
    // Load WCFMd Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load WCFMd Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
    
    // Load WCFMd views
    add_action( 'wcfm_load_views', array( &$this, 'load_views' ) );
  }
  
  public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMd;
    
	  switch( $end_point ) {
	  	
	    case 'wcfm-delivery-boys':
	    	$WCFM->library->load_datatable_lib();
	    	$WCFM->library->load_select2_lib();
	    	wp_enqueue_script( 'wcfmd_delivery_boys_js', $this->js_lib_url . 'wcfmd-script-delivery-boys.js', array('jquery', 'dataTables_js'), $WCFMd->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager_data = array();
	    	if( wcfm_is_vendor() || !wcfm_is_marketplace() ) {
	    		$wcfm_screen_manager_data = array( 1  => __( 'Store', 'wc-frontend-manager' ) );
	    	}
	    	wp_localize_script( 'wcfmd_delivery_boys_js', 'wcfm_delivery_boys_screen_manage', $wcfm_screen_manager_data );
	  	break;
	  	
	  	case 'wcfm-delivery-boys-manage':
	  		$WCFM->library->load_datepicker_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_multiinput_lib();
	    	wp_enqueue_script( 'wcfmd_delivery_boys_manage_js', $this->js_lib_url . 'wcfmd-script-delivery-boys-manage.js', array('jquery'), $WCFMd->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfmd_delivery_boys_manage_messages();
			  wp_localize_script( 'wcfmd_delivery_boys_manage_js', 'wcfm_delivery_boys_manage_messages', $wcfm_messages );
      break;
      
      case 'wcfm-deliveries':
      case 'wcfm-delivery-boys-stats':
	    	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmd_delivery_boys_stats_js', $this->js_lib_url . 'wcfmd-script-delivery-boys-stats.js', array('jquery', 'dataTables_js'), $WCFMd->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager_data = array();
	    	$wcfm_screen_manager_data[1] = 'yes';
	    	if( ( wcfm_is_delivery_boy() && wcfm_is_vendor_delivery_boy() ) || wcfm_is_vendor() || !wcfm_is_marketplace() ) {
	    		$wcfm_screen_manager_data = array( 3  => __( 'Store', 'wc-frontend-manager' ) );
	    	}
	    	if( !apply_filters( 'wcfm_allow_order_customer_details', true ) ) {
	    		//$wcfm_screen_manager_data[4] = 'yes';
	    	}
	    	if( !apply_filters( 'wcfm_allow_customer_shipping_details', true ) ) {
	    		$wcfm_screen_manager_data[5] = 'yes';
	    	}
	    	$wcfm_screen_manager_data = apply_filters( 'wcfm_screen_manager_data_columns', $wcfm_screen_manager_data, 'deliveries' );
	    	wp_localize_script( 'wcfmd_delivery_boys_stats_js', 'wcfm_delivery_boy_stats_screen_manage', $wcfm_screen_manager_data );
	    	
	    	wp_localize_script( 'wcfmd_delivery_boys_stats_js', 'wcfm_delivery_boy_stats_messages', array( 'delivery_confirm' => __( "Are you sure and want to mark this as 'Delivered'?\nYou can't undo this action ...", 'wc-frontend-manager-delivery' ) ) );
	  	break;
      
      case 'wcfm-orders':
      	if( !WCFM_Dependencies::wcfmu_plugin_active_check() || !apply_filters( 'wcfm_is_pref_shipment_tracking', true ) || !apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) {
      		wp_enqueue_script( 'wcfmd_orders_js', $this->js_lib_url . 'wcfmd-script-orders.js', array('jquery'), $WCFMd->version, true );
      	}
      break;
      
      case 'wcfm-orders-details':
      	if( !WCFM_Dependencies::wcfmu_plugin_active_check() || !apply_filters( 'wcfm_is_pref_shipment_tracking', true ) || !apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) {
      		wp_enqueue_script( 'wcfmd_orders_details_js', $this->js_lib_url . 'wcfmd-script-orders-details.js', array('jquery'), $WCFMd->version, true );
      	}
      break;
      
    }
  }
  
  public function load_styles( $end_point ) {
	  global $WCFM, $WCFMd;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-delivery-boys':
	  		wp_enqueue_style( 'wcfmd_delivery_boys_css',  $this->css_lib_url . 'wcfmd-style-delivery-boys.css', array(), $WCFMd->version );
		  break;
		  
		  case 'wcfm-delivery-boys-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMd->version );
	    	wp_enqueue_style( 'wcfmd_delivery_boys_manage_css',  $this->css_lib_url . 'wcfmd-style-delivery-boys-manage.css', array(), $WCFMd->version );
		  break;
		  
		  case 'wcfm-deliveries':
		  case 'wcfm-delivery-boys-stats':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
				wp_enqueue_style( 'wcfm_dashboard_css',  $WCFM->library->css_lib_url . 'dashboard/wcfm-style-dashboard.css', array(), $WCFM->version );
	  		wp_enqueue_style( 'wcfmd_delivery_boys_stats_css',  $this->css_lib_url . 'wcfmd-style-delivery-boys-stats.css', array(), $WCFMd->version );
		  break;
		  
		  case 'wcfm-orders-details':
		  	if( !WCFM_Dependencies::wcfmu_plugin_active_check() || !apply_filters( 'wcfm_is_pref_shipment_tracking', true ) || !apply_filters( 'wcfm_is_allow_shipping_tracking', true ) ) {
		  		wp_enqueue_style( 'wcfmd_orders_details_css',  $this->css_lib_url . 'wcfmd-style-orders-details.css', array(), $WCFMd->version );
		  	}
		  break;
		  
		}
	}
	
	public function load_views( $end_point ) {
	  global $WCFM, $WCFMd;
	  
	  switch( $end_point ) {
	  	
	  	case 'wcfm-delivery-boys':
        $WCFMd->template->get_template( 'wcfmd-view-delivery-boys.php' );
      break;
      
      case 'wcfm-delivery-boys-manage':
				$WCFMd->template->get_template( 'wcfmd-view-delivery-boys-manage.php' );
      break;
      
      case 'wcfm-deliveries':
      case 'wcfm-delivery-boys-stats':
				$WCFMd->template->get_template( 'wcfmd-view-delivery-boys-stats.php' );
      break;
    }
  }
  
}