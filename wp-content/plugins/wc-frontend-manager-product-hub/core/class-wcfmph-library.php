<?php

/**
 * WCFMph plugin library
 *
 * Plugin intiate library
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */
 
class WCFMph_Library {
	
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
    global $WCFMph;
		
	  $this->lib_path = $WCFMph->plugin_path . 'assets/';

    $this->lib_url = $WCFMph->plugin_url . 'assets/';
    
    $this->php_lib_path = $this->lib_path . 'php/';
    
    $this->php_lib_url = $this->lib_url . 'php/';
    
    $this->js_lib_path = $this->lib_path . 'js/';
    
    $this->js_lib_url = $this->lib_url . 'js/';
    
    $this->css_lib_path = $this->lib_path . 'css/';
    
    $this->css_lib_url = $this->lib_url . 'css/';
    
    $this->views_path = $WCFMph->plugin_path . 'views/';
    
    // Load wcfmph Scripts
    add_action( 'wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    add_action( 'after_wcfm_load_scripts', array( &$this, 'load_scripts' ) );
    
    // Load wcfmph Styles
    add_action( 'wcfm_load_styles', array( &$this, 'load_styles' ) );
    add_action( 'after_wcfm_load_styles', array( &$this, 'load_styles' ) );
  }
  
  public function load_scripts( $end_point ) {
	  global $WCFM, $WCFMph;
    
	  switch( $end_point ) {
	  	
	  	case 'wcfm-products-manage':
      	// WooCommerce Product Bundles
		  	if( $wcfm_allow_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
		  		if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
						wp_enqueue_script( 'wcfmph_product_bundles_products_manage_js', $WCFMph->library->js_lib_url . 'wcfmph-script-wc-product-bundles-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMph->version, true );
						$default_txt = __( 'No Default','woocommerce-product-bundles' );
						wp_localize_script( 'wcfmph_product_bundles_products_manage_js', 'default_attribute', array( 'default_txt' => $default_txt ) );
					}
				}
				
				// WooCommerce Force Sales
		  	if( $wcfm_is_allow_wc_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
		  		if ( WCFMph_Dependencies::wc_force_sales_plugin_active_check() ) {
						wp_enqueue_script( 'wcfmph_force_sales_products_manage_js', $WCFMph->library->js_lib_url . 'wcfmph-script-wc-force-sales-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMph->version, true );
					}
				}
				
				// WooCommerce Chained Product
		  	if( $wcfm_is_allow_wc_chained_product = apply_filters( 'wcfm_is_allow_wc_chained_product', true ) ) {
		  		if ( WCFMph_Dependencies::wc_chained_product_plugin_active_check() ) {
						wp_enqueue_script( 'wcfmph_chained_product_products_manage_js', $WCFMph->library->js_lib_url . 'wcfmph-script-wc-chained-product-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMph->version, true );
					}
				}
				
				// WooCommerce Composite Product
		  	if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true ) ) {
		  		if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
						wp_enqueue_script( 'wcfmph_composite_product_products_manage_js', $WCFMph->library->js_lib_url . 'wcfmph-script-wc-composite-product-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMph->version, true );
						$default_txt = __( 'No Default','woocommerce-product-bundles' );
						wp_localize_script( 'wcfmph_composite_product_products_manage_js', 'default_attribute', array( 'default_txt' => $default_txt ) );
					}
				}
				
				// WooCommerce Group By Product
				if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_groupby_product', true ) ) {
					if ( WCFMph_Dependencies::wc_groupbuy_product_plugin_active_check() ) {
						$WCFM->library->load_timepicker_lib();
						wp_enqueue_script( 'wcfmph_groupbuy_products_manage_js', $WCFMph->library->js_lib_url . 'wcfmph-script-wc-groupbuy-products-manage.js', array( 'jquery', 'wcfm_products_manage_js' ), $WCFMph->version, true );
					}
				}
      break;
      
    }
  }
  
  public function load_styles( $end_point ) {
	  global $WCFM, $WCFMph;
		
	  switch( $end_point ) {
	  	
	  	case 'wcfm-products-manage':
	  		// WooCommerce Product Bundles
		  	if( $wcfm_is_allow_wc_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
		  		if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
						wp_enqueue_style( 'wcfmph_product_bundles_products_manage_css', $WCFMph->library->css_lib_url . 'wcfmph-script-wc-product-bundles-products-manage.css', array( ), $WCFMph->version );
					}
				}
				
				// WooCommerce Force Sales
		  	if( $wcfm_allow_force_sales = apply_filters( 'wcfm_allow_force_sales', true ) ) {
		  		if ( WCFMph_Dependencies::wc_force_sales_plugin_active_check() ) {
						wp_enqueue_style( 'wcfmph_force_sales_products_manage_css', $WCFMph->library->css_lib_url . 'wcfmph-script-wc-force-sales-products-manage.css', array( ), $WCFMph->version );
					}
				}
				
				// WooCommerce Chained Product
		  	if( $wcfm_is_allow_wc_chained_product = apply_filters( 'wcfm_is_allow_wc_chained_product', true ) ) {
		  		if ( WCFMph_Dependencies::wc_chained_product_plugin_active_check() ) {
						wp_enqueue_style( 'wcfmph_chained_product_products_manage_css', $WCFMph->library->css_lib_url . 'wcfmph-script-wc-chained-product-products-manage.css', array( ), $WCFMph->version );
					}
				}
				
				// WooCommerce Composite Product
		  	if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true ) ) {
		  		if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
						wp_enqueue_style( 'wcfmph_composite_product_products_manage_css', $WCFMph->library->css_lib_url . 'wcfmph-script-wc-composite-product-products-manage.css', array( ), $WCFMph->version );
					}
				}
		  break;
		  
		}
	}
	
	/**
   * Filters variation query args to get variations for a variable product.
   *
   * @param array $args get_posts args
   */
  function wcfm_get_all_variations( $product_id ) {
		$args =  array(
			'post_parent' => $product_id,
			'post_type'   => 'product_variation',
			'orderby'     => 'menu_order',
			'order'       => 'ASC',
			'fields'      => 'ids',
			'post_status' => array( 'publish', 'private' ),
			'numberposts' => -1,
		) ;
	
		return get_posts( $args );
	}
  
}