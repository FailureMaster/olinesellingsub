<?php
/**
 * WCFM Product Hub plugin core
 *
 * Plugin Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */
 
class WCFMph_Ajax {
	
	public $controllers_path;

	public function __construct() {
		global $WCFM, $WCFMph;
		
		// WCFMph Ajax Controller
		add_action( 'after_wcfm_ajax_controller', array( &$this, 'wcfmph_ajax_controller' ) );
		
		add_action('wp_ajax_wcfmph_check_product_type', array( &$this, 'wcfmph_check_product_type' ) );
		
	}
	
	/**
   * Product Hub Ajax Controllers
   */
  public function wcfmph_ajax_controller() {
  	global $WCFM, $WCFMph;
  	
  	$controllers_path = $WCFMph->plugin_path . 'controllers/';
  	
  	$controller = '';
  	if( isset( $_POST['controller'] ) ) {
  		$controller = $_POST['controller'];
  		
  		switch( $controller ) {
  			case 'wcfm-products-manage':
					
					// WooCommerce Product Bundles
					if( $wcfm_allow_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
						if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
							require_once( $controllers_path . 'wcfmph-controller-wc-product-bundles-products-manage.php' );
							new WCFMph_WC_Product_Bundles_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Force Sales
					if( $wcfm_is_allow_wc_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
						if ( WCFMph_Dependencies::wc_force_sales_plugin_active_check() ) {
							require_once( $controllers_path . 'wcfmph-controller-wc-force-sales-products-manage.php' );
							new WCFMph_WC_Force_Sales_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Chained Product
					if( $wcfm_is_allow_wc_chained_product = apply_filters( 'wcfm_is_allow_wc_chained_product', true ) ) {
						if ( WCFMph_Dependencies::wc_chained_product_plugin_active_check() ) {
							require_once( $controllers_path . 'wcfmph-controller-wc-chained-product-products-manage.php' );
							new WCFMph_WC_Chained_Product_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Composite Product
					if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true ) ) {
						if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
							require_once( $controllers_path . 'wcfmph-controller-wc-composite-product-products-manage.php' );
							new WCFMph_WC_Composite_Product_Products_Manage_Controller();
						}
					}
					
					// WooCommerce Group Buy
					if( apply_filters( 'wcfm_is_allow_wc_groupbuy_product', true ) ) {
						if ( WCFMph_Dependencies::wc_groupbuy_product_plugin_active_check() ) {
							require_once( $controllers_path . 'wcfmph-controller-wc-groupbuy-product-products-manage.php' );
							new WCFMph_WC_Groupbuy_Product_Products_Manage_Controller();
						}
					}
  			break;
  		}
  	}
  }
  
  public function wcfmph_check_product_type() { 
  	global $WCFM, $WCFMph;
  	
		$product_id = $_POST['productid'];
		
		$variable_product_items = array();
		$res = 1;
		$product = wc_get_product( $product_id );
		$variable_product_items['subscription'] = 0;
		if($product->is_type('subscription') || $product->is_type('variable-subscription')) {
			$variable_product_items['subscription'] = 1;
		}
		if ($product->is_type('variable') || $product->is_type('variable-subscription')) {
			$variable_product_items['variableproduct'] = 1;
		  $res = 0;
			$variations = $WCFMph->library->wcfm_get_all_variations( $product_id );
		
			$variation_atts = array();
			$product_attributes = get_post_meta($product_id, '_product_attributes', true);

			foreach ($product_attributes as $key => $val) {
				$atr_val = str_replace(' ', '', $val['value']);
				$variation_atts[$key] = explode("|",$atr_val);
			}
			$variable_product_items['attr_array'] = $variation_atts;
			foreach ( $variations as $variation_id ) {
				$product_variation = wc_get_product( $variation_id );
				$attrs = array_keys($product_variation->get_data()['attributes']);
				$variation         = $product->get_available_variation( $product_variation );
				$variable_product_items["title"][$variation_id] =array(wp_strip_all_tags($product_variation->get_formatted_name())); 

			}
		}	else {
			$variable_product_items['variableproduct'] = 0;
		}		
		
		print_r(json_encode($variable_product_items));
		die;
	}
}