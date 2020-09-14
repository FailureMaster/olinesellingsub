<?php
/**
 * WCFM plugin controllers
 *
 * Plugin Capabiity Setings Controller
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/controllers
 * @version   1.0.0
 */

class WCFMgs_Capability_Controller {
	
	public function __construct() {
		global $WCFM;
		
		add_action( 'wcfm_capability_update', array( &$this, 'wcfmgs_capability_update' ), 50 );
	}
	
	public function wcfmgs_capability_update( $wcfm_capability_form ) {
		global $WCFM, $WCFMgs, $wpdb, $_POST;
		
	  // Save WCFM Shop Manager capability option
		if( isset( $wcfm_capability_form['wcfm_shop_manager_capability_options'] ) ) {
			update_option( 'wcfm_shop_manager_capability_options', $wcfm_capability_form['wcfm_shop_manager_capability_options'] );
		} else {
			update_option( 'wcfm_shop_manager_capability_options', array() ); 
		}
		$WCFMgs->shop_managers->wcfmgs_shop_manager_capability_option_updates();
		
		// Save WCFM Shop Staff capability option
		if( isset( $wcfm_capability_form['wcfm_shop_staff_capability_options'] ) ) {
			update_option( 'wcfm_shop_staff_capability_options', $wcfm_capability_form['wcfm_shop_staff_capability_options'] );
		} else {
			update_option( 'wcfm_shop_staff_capability_options', array() ); 
		}
	  $WCFMgs->shop_staffs->wcfmgs_shop_staff_capability_option_updates();
	}
}