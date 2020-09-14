<?php

/**
 * WCFM Affiliate plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.0
 */
 
class WCFMaf_Library {
	
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
    global $WCFMaf;
		
	  $this->lib_path = $WCFMaf->plugin_path . 'assets/';

    $this->lib_url = $WCFMaf->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->js_lib_url_min = $this->lib_url . 'js/min/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->css_lib_url_min = $this->lib_url . 'css/min/';
    
    $this->views_path = $WCFMaf->plugin_path . 'views/';
    
    // Load WCFMaf Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load WCFMaf Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
    
    // Load WCFMaf views
    add_action( 'wcfm_load_views', array( &$this, 'load_views' ) );
  }
  
  public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMaf;
    
	  switch( $end_point ) {
	  	
	    case 'wcfm-affiliate':
	    	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmaf_affiliate_js', $this->js_lib_url . 'wcfmaf-script-affiliate.js', array('jquery', 'dataTables_js'), $WCFMaf->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager_data = array();
	    	if( wcfm_is_vendor() || !wcfm_is_marketplace() ) {
	    		$wcfm_screen_manager_data = array( 1  => __( 'Store', 'wc-frontend-manager' ) );
	    	}
	    	wp_localize_script( 'wcfmaf_affiliate_js', 'wcfm_affiliate_screen_manage', $wcfm_screen_manager_data );
	    	
	    	wp_enqueue_script( 'wcfmaf_affiliate_generate_js', $this->js_lib_url . 'wcfmaf-script-affiliate-generate.js', array('jquery'), $WCFMaf->version, true );
	  	break;
	  	
	  	case 'wcfm-affiliate-manage':
	  		$WCFM->library->load_datepicker_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_upload_lib();
      	$WCFM->library->load_multiinput_lib();
	    	wp_enqueue_script( 'wcfmaf_affiliate_manage_js', $this->js_lib_url . 'wcfmaf-script-affiliate-manage.js', array('jquery'), $WCFMaf->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfmaf_affiliate_manage_messages();
			  wp_localize_script( 'wcfmaf_affiliate_manage_js', 'wcfm_affiliate_manage_messages', $wcfm_messages );
			  
			  wp_enqueue_script( 'wcfmaf_affiliate_generate_js', $this->js_lib_url . 'wcfmaf-script-affiliate-generate.js', array('jquery'), $WCFMaf->version, true );
      break;
      
      case 'wcfm-affiliates':
      case 'wcfm-affiliate-stats':
	    	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmaf_affiliate_stats_js', $this->js_lib_url . 'wcfmaf-script-affiliate-stats.js', array('jquery', 'dataTables_js'), $WCFMaf->version, true );
	    	
	    	wp_enqueue_script( 'wcfmaf_affiliate_generate_js', $this->js_lib_url . 'wcfmaf-script-affiliate-generate.js', array('jquery'), $WCFMaf->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager_data = array();
	    	wp_localize_script( 'wcfmaf_affiliate_stats_js', 'wcfm_affiliate_stats_screen_manage', $wcfm_screen_manager_data );
	    	
	    	wp_localize_script( 'wcfmaf_affiliate_stats_js', 'wcfm_affiliate_stats_messages', array( 'mark_confirm' => __( "Are you sure and want to mark this as 'Paid'?\nYou can't undo this action ...", 'wc-frontend-manager-affiliate' ), 'mark_reject' => __( "Are you sure and want to mark this as 'Reject'?\nYou can't undo this action ...", 'wc-frontend-manager-affiliate' ) ) );
	  	break;
	  	
	  	case 'wcfm-memberships-manage':
      	wp_enqueue_script( 'wcfmaf_affiliate_membership_js', $this->js_lib_url . 'wcfmaf-script-affiliate-membership-manage.js', array('jquery' ), $WCFMaf->version, true );
      break;
      
      case 'wcfm-products-manage':
      	wp_enqueue_script( 'wcfmaf_affiliate_product_js', $this->js_lib_url . 'wcfmaf-script-affiliate-products-manage.js', array('jquery' ), $WCFMaf->version, true );
      break;
	  	
	  	case 'wcfm-messages':
      	wp_enqueue_script( 'wcfmaf_messages_js', $this->js_lib_url . 'registration/wcfmaf-script-affiliate-approval.js', array('jquery', 'wcfm_messages_js' ), $WCFMaf->version, true );
      break;
      
      case 'wcfm-settings':
      	wp_enqueue_script( 'wcfmaf_settings_js', $this->js_lib_url . 'wcfmaf-script-affiliate-setting.js', array('jquery' ), $WCFMaf->version, true );
      break;
      
    }
  }
  
  public function load_styles( $end_point ) {
	  global $WCFM, $WCFMaf;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-affiliate':
	  		wp_enqueue_style( 'wcfmaf_affiliate_css',  $this->css_lib_url . 'wcfmaf-style-affiliate.css', array(), $WCFMaf->version );
	  		
	  		wp_enqueue_style( 'wcfmaf_affiliate_generate_css',  $this->css_lib_url . 'wcfmaf-style-affiliate-generate.css', array(), $WCFMaf->version );
		  break;
		  
		  case 'wcfm-affiliate-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMaf->version );
	    	wp_enqueue_style( 'wcfmaf_affiliate_manage_css',  $this->css_lib_url . 'wcfmaf-style-affiliate-manage.css', array(), $WCFMaf->version );
	    	
	    	wp_enqueue_style( 'wcfmaf_affiliate_generate_css',  $this->css_lib_url . 'wcfmaf-style-affiliate-generate.css', array(), $WCFMaf->version );
		  break;
		  
		  case 'wcfm-affiliates':
		  case 'wcfm-affiliate-stats':
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFM->version );
				wp_enqueue_style( 'wcfm_dashboard_css',  $WCFM->library->css_lib_url . 'dashboard/wcfm-style-dashboard.css', array(), $WCFM->version );
	  		wp_enqueue_style( 'wcfmaf_affiliate_stats_css',  $this->css_lib_url . 'wcfmaf-style-affiliate-stats.css', array(), $WCFMaf->version );
	  		
	  		wp_enqueue_style( 'wcfmaf_affiliate_generate_css',  $this->css_lib_url . 'wcfmaf-style-affiliate-generate.css', array(), $WCFMaf->version );
		  break;
		  
		}
	}
	
	public function load_views( $end_point ) {
	  global $WCFM, $WCFMaf;
	  
	  switch( $end_point ) {
	  	
	  	case 'wcfm-affiliate':
        $WCFMaf->template->get_template( 'wcfmaf-view-affiliate.php' );
      break;
      
      case 'wcfm-affiliate-manage':
				$WCFMaf->template->get_template( 'wcfmaf-view-affiliate-manage.php' );
      break;
      
      case 'wcfm-affiliates':
      case 'wcfm-affiliate-stats':
				$WCFMaf->template->get_template( 'wcfmaf-view-affiliate-stats.php' );
      break;
    }
  }
  
}