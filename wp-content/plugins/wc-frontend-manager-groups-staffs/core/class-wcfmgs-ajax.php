<?php
/**
 * WCFM Groups & Staffs plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.0
 */
 
class WCFMgs_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMgs;
		
		$this->controllers_path = $WCFMgs->plugin_path . 'controllers/';
		
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmgs_ajax_controller' ) );
	}
		
	/**
   * WCFM Groups & Staffs Ajax Controllers
   */
  public function wcfmgs_ajax_controller() {
  	global $WCFM, $WCFMgs;
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
	  	
				case 'wcfm-capability':
					include_once( $this->controllers_path . 'wcfmgs-controller-capability.php' );
					new WCFMgs_Capability_Controller();
				break;
				
				case 'wcfm-groups':
					include_once( $this->controllers_path . 'wcfmgs-controller-groups.php' );
					new WCFMgs_Groups_Controller();
				break;
				
				case 'wcfm-groups-manage':
					include_once( $this->controllers_path . 'wcfmgs-controller-groups-manage.php' );
					new WCFMgs_Groups_Manage_Controller();
				break;
				
				case 'wcfm-managers':
					include_once( $this->controllers_path . 'wcfmgs-controller-managers.php' );
					new WCFMgs_Managers_Controller();
				break;
				
				case 'wcfm-managers-manage':
					include_once( $this->controllers_path . 'wcfmgs-controller-managers-manage.php' );
					new WCFMgs_Managers_Manage_Controller();
				break;
				
				case 'wcfm-staffs':
					include_once( $this->controllers_path . 'wcfmgs-controller-staffs.php' );
					new WCFMgs_Staffs_Controller();
				break;
				
				case 'wcfm-staffs-manage':
					include_once( $this->controllers_path . 'wcfmgs-controller-staffs-manage.php' );
					new WCFMgs_Staffs_Manage_Controller();
				break;
			}
		}
	}
}