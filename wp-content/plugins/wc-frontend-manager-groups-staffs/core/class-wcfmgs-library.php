<?php

/**
 * WCFMgs plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	WCFMgs/core
 * @version   1.0.0
 */
 
class WCFMgs_Library {
	
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
    global $WCFMgs;
		
	  $this->lib_path = $WCFMgs->plugin_path . 'assets/';

    $this->lib_url = $WCFMgs->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->views_path = $WCFMgs->plugin_path . 'views/';
    
    // Load WCFMgs Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load WCFMgs Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
    
    // Load WCFMgs views
    add_action( 'wcfm_load_views', array( &$this, 'load_views' ) );
    add_action( 'before_wcfm_load_views', array( &$this, 'load_views' ) );
  }
  
  public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMu, $WCFMgs;
    
	  switch( $end_point ) {
	  	
	  	case 'wcfm-groups':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmgs_groups_js', $this->js_lib_url . 'wcfmgs-script-groups.js', array('jquery', 'dataTables_js'), $WCFMgs->version, true );
      break;
      
      case 'wcfm-groups-manage':
      	$WCFM->library->load_collapsible_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_upload_lib();
	    	wp_enqueue_script( 'wcfmgs_groups_manage_js', $this->js_lib_url . 'wcfmgs-script-groups-manage.js', array('jquery'), $WCFMgs->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfmgs_groups_manage_messages();
			  wp_localize_script( 'wcfmgs_groups_manage_js', 'wcfm_groups_manage_messages', $wcfm_messages );
      break;
	  	
	  	case 'wcfm-managers':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmgs_managers_js', $this->js_lib_url . 'wcfmgs-script-managers.js', array('jquery', 'dataTables_js'), $WCFMgs->version, true );
      break;
      
      case 'wcfm-managers-manage':
      	$WCFM->library->load_select2_lib();
	    	wp_enqueue_script( 'wcfmgs_managers_manage_js', $this->js_lib_url . 'wcfmgs-script-managers-manage.js', array('jquery'), $WCFMgs->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfmgs_managers_manage_messages();
			  wp_localize_script( 'wcfmgs_managers_manage_js', 'wcfm_managers_manage_messages', $wcfm_messages );
      break;
	  	
	  	case 'wcfm-staffs':
      	$WCFM->library->load_datatable_lib();
	    	wp_enqueue_script( 'wcfmgs_staffs_js', $this->js_lib_url . 'wcfmgs-script-staffs.js', array('jquery', 'dataTables_js'), $WCFMgs->version, true );
	    	
	    	// Screen manager
	    	$wcfm_screen_manager_data = array();
	    	if( wcfm_is_vendor() || !wcfm_is_marketplace() ) {
	    		$wcfm_screen_manager_data = array( 1  => __( 'Store', 'wc-frontend-manager' ) );
	    	}
	    	wp_localize_script( 'wcfmgs_staffs_js', 'wcfm_staffs_screen_manage', $wcfm_screen_manager_data );
      break;
      
      case 'wcfm-staffs-manage':
      	$WCFM->library->load_collapsible_lib();
      	$WCFM->library->load_datepicker_lib();
      	$WCFM->library->load_select2_lib();
      	$WCFM->library->load_multiinput_lib();
	    	wp_enqueue_script( 'wcfmgs_staffs_manage_js', $this->js_lib_url . 'wcfmgs-script-staffs-manage.js', array('jquery'), $WCFMgs->version, true );
	    	// Localized Script
        $wcfm_messages = get_wcfmgs_staffs_manage_messages();
			  wp_localize_script( 'wcfmgs_staffs_manage_js', 'wcfm_staffs_manage_messages', $wcfm_messages );
      break;
      
      case 'wcfm-vendors-manage':
      	wp_enqueue_script( 'wcfmgs_vendors_manage_js', $this->js_lib_url . 'wcfmgs-script-vendors-manage.js', array('jquery'), $WCFMgs->version, true );
      break;
      
      case 'wcfm-profile':
      	if( wcfm_is_staff() ) {
					if( apply_filters( 'wcfm_is_allow_shop_staff_availability', true ) ) {
						if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
							if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
								$WCFM->library->load_datepicker_lib();
								wp_enqueue_script( 'wcfmu_appointments_staff_profile_js', $this->js_lib_url . 'wcfmgs-script-staffs-profile.js', array('jquery'), $WCFMgs->version, true );
							}
						}
					}
				}
      break;
    }
  }
  
  public function load_styles( $end_point ) {
	  global $WCFM, $WCFMu, $WCFMgs;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-groups':
	    	wp_enqueue_style( 'wcfmgs_groups_css',  $this->css_lib_url . 'wcfmgs-style-groups.css', array(), $WCFMgs->version );
		  break;
		  
		  case 'wcfm-groups-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
		  	wp_enqueue_style( 'wcfm_capability_css',  $WCFM->library->css_lib_url . 'capability/wcfm-style-capability.css', array(), $WCFMgs->version );
	    	wp_enqueue_style( 'wcfmgs_groups_manage_css',  $this->css_lib_url . 'wcfmgs-style-groups-manage.css', array(), $WCFMgs->version );
		  break;
	  	
	  	case 'wcfm-managers':
	    	wp_enqueue_style( 'wcfmgs_managers_css',  $this->css_lib_url . 'wcfmgs-style-managers.css', array(), $WCFMgs->version );
		  break;
		  
		  case 'wcfm-managers-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
		  	wp_enqueue_style( 'wcfm_capability_css',  $WCFM->library->css_lib_url . 'capability/wcfm-style-capability.css', array(), $WCFMgs->version );
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMgs->version );
	    	wp_enqueue_style( 'wcfmgs_managers_manage_css',  $this->css_lib_url . 'wcfmgs-style-managers-manage.css', array(), $WCFMgs->version );
		  break;
	  	
	  	case 'wcfm-staffs':
	    	wp_enqueue_style( 'wcfmgs_staffs_css',  $this->css_lib_url . 'wcfmgs-style-staffs.css', array(), $WCFMgs->version );
		  break;
		  
		  case 'wcfm-staffs-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
		  	wp_enqueue_style( 'wcfm_capability_css',  $WCFM->library->css_lib_url . 'capability/wcfm-style-capability.css', array(), $WCFMgs->version );
		  	wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMgs->version );
	    	wp_enqueue_style( 'wcfmgs_staffs_manage_css',  $this->css_lib_url . 'wcfmgs-style-staffs-manage.css', array(), $WCFMgs->version );
		  break;
		  
		  case 'wcfm-vendors-manage':
		  	$WCFM->library->load_checkbox_offon_lib();
      	wp_enqueue_style( 'wcfm_capability_css',  $WCFM->library->css_lib_url . 'capability/wcfm-style-capability.css', array(), $WCFMgs->version );
      break;
		  
		  case 'wcfm-profile':
      	if( wcfm_is_staff() ) {
					if( apply_filters( 'wcfm_is_allow_shop_staff_availability', true ) ) {
						if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
							if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
								wp_enqueue_style( 'collapsible_css',  $WCFM->library->css_lib_url . 'wcfm-style-collapsible.css', array(), $WCFMgs->version );
								wp_enqueue_style( 'wcfmu_appointments_settings_css',  $WCFMu->library->css_lib_url . 'wc_appointments/wcfmu-style-wcappointments-settings.css', array(), $WCFMgs->version );
							}
						}
					}
				}
      break;
		}
		
		if ( is_singular( 'wcfm_vendor_groups' ) || is_post_type_archive( 'wcfm_vendor_groups' ) ) {
			$woocommerce_css_src = plugins_url( 'assets/css/woocommerce.css', WC_PLUGIN_FILE );
			wp_enqueue_style( 'woocommerce_css_src',  $woocommerce_css_src, array(), $WCFMgs->version );
		}
	}
	
	public function load_views( $end_point ) {
	  global $WCFM, $WCFMgs;
	  
	  switch( $end_point ) {
	  	
	  	case 'wcfm-groups':
        include_once( $this->views_path . 'wcfmgs-view-groups.php' );
      break;
      
      case 'wcfm-groups-manage':
        include_once( $this->views_path . 'wcfmgs-view-groups-manage.php' );
      break;
	  	
	  	case 'wcfm-managers':
        include_once( $this->views_path . 'wcfmgs-view-managers.php' );
      break;
      
      case 'wcfm-managers-manage':
        include_once( $this->views_path . 'wcfmgs-view-managers-manage.php' );
      break;
	  	
	    case 'wcfm-staffs':
        include_once( $this->views_path . 'wcfmgs-view-staffs.php' );
      break;
      
      case 'wcfm-staffs-manage':
        include_once( $this->views_path . 'wcfmgs-view-staffs-manage.php' );
      break;
      
      case 'wcfm-capability':
      	if( !wcfm_is_vendor() ) {
					include_once( $this->views_path . 'wcfmgs-view-capability.php' );
				}
      break;
    }
  }
  
}