<?php
/**
 * WCFM Product Hub plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */
 
class WCFMph_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMph;
		
		// WCFMph Product Type
		add_filter( 'wcfm_product_types', array( &$this, 'wcfmph_product_types' ), 100 );
		
		// Hub Product Type Capability
		add_filter( 'wcfm_capability_settings_fields_product_types', array( &$this, 'wcfmcap_product_types' ), 100, 3 );
		
		// WooCommerce Product Bundles
    if( $wcfm_allow_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
			if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
				// Bundle Product options
				add_filter( 'wcfm_product_fields_stock', array( &$this, 'wcfm_product_bundles_product_manage_fields_inventory' ), 200, 3 );
				add_filter( 'wcfm_product_manage_fields_advanced', array( &$this, 'wcfm_product_bundles_product_manage_fields_advanced' ), 200, 2 );
				add_filter( 'wcfm_product_manage_fields_linked', array( &$this, 'wcfm_product_bundles_product_product_manage_fields' ), 200, 3 );
				add_action( 'after_wcfm_products_manage_linked', array( &$this, 'wcfm_product_bundles_product_manage_fields' ), 200, 2 );
			}
		}
		
		// WooCommerce Force Sales
		if( apply_filters( 'wcfm_is_allow_wc_product_force_sales', true ) ) {
			if ( WCFMph_Dependencies::wc_force_sales_plugin_active_check() ) {
				// Force Sales Options 
				add_filter( 'wcfm_product_manage_fields_linked', array( &$this, 'wcfm_force_sales_product_manage_fields' ), 100, 3 );
			}
		}
		
		// WooCommerce Chained Product
		if( $wcfm_is_allow_wc_chained_product = apply_filters( 'wcfm_is_allow_wc_chained_product', true ) ) {
			if ( WCFMph_Dependencies::wc_chained_product_plugin_active_check() ) {
				// Chained Product options
				add_filter( 'wcfm_product_manage_fields_linked', array( &$this, 'wcfm_chained_product_product_manage_fields' ), 200, 3 );
				add_filter( 'wcfm_product_manage_fields_variations', array( &$this, 'wcfm_chained_product_product_manage_fields_variations' ), 200, 7 );
				add_filter( 'wcfm_variation_edit_data', array( &$this, 'wcfm_chained_product_product_manage_data_variations' ), 100, 3 );
			}
		}
		
		// WooCommerce Composite Product
		if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true ) ) {
			if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
				add_filter( 'wcfm_product_fields_stock', array( &$this, 'wcfm_composite_product_product_manage_fields_inventory' ), 200, 3 );
				add_filter( 'wcfm_product_manage_fields_advanced', array( &$this, 'wcfm_composite_product_product_manage_fields_advanced' ), 200, 2 );
				add_action( 'end_wcfm_products_manage', array( &$this, 'wcfm_composite_product_product_manage_fields' ), 200 );
			}
		}
		
		// WooCommerce Group Buy
		if( apply_filters( 'wcfm_is_allow_wc_groupbuy_product', true ) ) {
			if ( WCFMph_Dependencies::wc_groupbuy_product_plugin_active_check() ) {
				// Group Buy Options 
				add_filter( 'after_wcfm_products_manage_stock', array( &$this, 'wcfm_groupbuy_product_manage_fields' ), 100, 2 );
			}
		}
	}
	
	/**
   * WCFMph Product Type
   */
  function wcfmph_product_types( $pro_types ) {
  	global $WCFM;
  	
  	// WooCommerce Product Bundles
  	if( $wcfm_allow_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true ) ) {
			if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
				$pro_types['bundle'] = __( 'Product Bundle', 'wc-frontend-manager-product-hub' );
			}
		}
		
		// WooCommerce Composite Product
		if( $wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true ) ) {
			if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
				$pro_types['composite'] = __( 'Composite product', 'woocommerce-composite-products' );
			}
		}
		
		// WooCommerce Group By Product
		if( apply_filters( 'wcfm_is_allow_wc_groupbuy_product', true ) ) {
			if ( WCFMph_Dependencies::wc_groupbuy_product_plugin_active_check() ) {
				$pro_types['groupbuy'] = __( 'Groupbuy product', 'wc-frontend-manager-product-hub' );
			}
		}
  	
  	return $pro_types;
  }
  
  /**
	 * WCFM Capability Product Types
	 */
	function wcfmcap_product_types( $product_types, $handler = 'wcfm_capability_options', $wcfm_capability_options = array() ) {
		global $WCFM, $WCFMu;
		
		// WooCommerce Product Bundles
		if ( WCFMph_Dependencies::wc_product_bundles_plugin_active_check() ) {
			$product_bundle = ( isset( $wcfm_capability_options['product_bundle'] ) ) ? $wcfm_capability_options['product_bundle'] : 'no';
		
			$product_types["product_bundle"] = array('label' => __('Product Bundle', 'wc-frontend-manager-product-hub') , 'name' => $handler . '[product_bundle]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_bundle);
		}
		
		// WooCommerce Composite Product
		if ( WCFMph_Dependencies::wc_composite_product_plugin_active_check() ) {
			$composite_product = ( isset( $wcfm_capability_options['composite_product'] ) ) ? $wcfm_capability_options['composite_product'] : 'no';
	
			$product_types["composite_product"] = array('label' => __('Composite', 'wc-frontend-manager-product-hub') , 'name' => $handler . '[composite_product]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $composite_product);
		}
		
		// WooCommerce Group by Product
		if ( WCFMph_Dependencies::wc_groupbuy_product_plugin_active_check() ) {
			$groupbuy_product = ( isset( $wcfm_capability_options['groupbuy'] ) ) ? $wcfm_capability_options['groupbuy'] : 'no';
	
			$product_types["groupbuy"] = array('label' => __('Group Buy', 'wc-frontend-manager-product-hub') , 'name' => $handler . '[groupbuy]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $groupbuy_product);
		}
		
		return $product_types;
	}
	
	/**
	 * WC Product Bundle Inventory
	 */
  function wcfm_product_bundles_product_manage_fields_inventory( $inventory_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMph, $product_bundle_object;
		
		$value = 'no';
    if( $product_id ) {
      $bundle_product_object     = wc_get_product( $product_id );
      if ($bundle_product_object->is_type('bundle')){
        $sold_individually         = $bundle_product_object->get_sold_individually( 'edit' );
        $sold_individually_context = $bundle_product_object->get_sold_individually_context( 'edit' );
        if ( $sold_individually ) {
          if ( ! in_array( $sold_individually_context, array( 'configuration', 'product' ) ) ) {
            $value = 'product';
          } else {
            $value = $sold_individually_context;
          }
        }
      }
    }
    
    $inventory_fields['sold_individually']['class'] .= ' non-bundle';
    $inventory_fields['sold_individually']['label_class'] .= ' non-bundle';

    $sold_options = array('no'            => __( 'No', 'woocommerce-product-bundles' ),
    											'product'       => __( 'Yes', 'woocommerce-product-bundles' ),
    											'configuration' => __( 'Matching configurations only', 'woocommerce-product-bundles' )
    											);

		// Provide context to the "Sold Individually" option.
    $inventory_fields = $inventory_fields + array(  "_wc_pb_sold_individually" => array('label' => sprintf( esc_html__( 'Sold individually', 'woocommerce-product-bundles' ) ), 'value' => $value, 'type' => 'select', 'options' => $sold_options, 'class' => 'wcfm-select wcfm_ele bundle', 'label_class' => 'wcfm_title wcfm_ele bundle' ) );
	
		return $inventory_fields;
  }

  /**
	 * WC Product Bundle Advanced
	 */
  function wcfm_product_bundles_product_manage_fields_advanced( $advanced_fields, $product_id ) {
    global $WCFM, $WCFMph, $product_bundle_object;
     
    $wc_pb_edit_in_cart = '';
    $enable_edit_cart = 0;
    if( $product_id ) {
      $bundle_product_object     = wc_get_product( $product_id );
      if ( $bundle_product_object->is_type('bundle') ) {
        $wc_pb_edit_in_cart = get_post_meta( $product_id, '_wc_pb_edit_in_cart', true );
      }
    }

    $advanced_fields = $advanced_fields + array( "_wc_pb_edit_in_cart" => array('label' => __('Editing in cart', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele bundle non-simple non-variable-subscription non-variable', 'value' => 'yes', 'label_class' => 'wcfm_title wcfm_ele checkbox_title bundle non-simple non-variable-subscription non-variable', 'dfvalue' => $enable_edit_cart ) );
    return $advanced_fields;
  }
  
  /**
	 * WC Product Bundle - Product Linked Fields
	 */
	function wcfm_product_bundles_product_product_manage_fields( $fields, $product_id, $products_array ) {
		global $WCFM, $WCFMph;
		
		$bundle_sell_ids = array();
		$wc_pb_bundle_sells_title = '';
		
		if( $product_id ) {
			$bundle_sell_ids           = WC_PB_BS_Product::get_bundle_sell_ids( $product_id, 'edit' );
			$wc_pb_bundle_sells_title  = esc_html( WC_PB_BS_Product::get_bundle_sells_title( $product_id, 'edit' ) );
			
			if( !empty( $bundle_sell_ids ) ) {
				foreach( $bundle_sell_ids as $bundle_sell_id ) {
					$products_array[$bundle_sell_id] = get_post( absint($bundle_sell_id) )->post_title;	
				}
			}
		}
		//$product_array_blank = array( '' => __( 'Search for a product...', 'woocommerce-chained-products' ) );
		//$products_array = array_merge( $product_array_blank, $products_array );
		
		$force_sales_fields = array(  
																"bundle_sell_ids" => array( 'label' => __( 'Bundle-sells', 'woocommerce-product-bundles' ), 'type' => 'select', 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple variable', 'label_class' => 'wcfm_title', 'options' => $products_array, 'value' => $bundle_sell_ids, 'hints' => __( 'Bundle-sells are optional products that can be selected and added to the cart along with this product.', 'woocommerce-product-bundles' ) ),
																"wc_pb_bundle_sells_title" => array('label' => __( 'Bundle-sells title', 'woocommerce-product-bundles' ), 'placeholder'   => __( 'e.g. "Frequently Bought Together"', 'woocommerce-product-bundles' ), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele simple variable', 'label_class' => 'wcfm_title', 'value' => $wc_pb_bundle_sells_title, 'hints' => __( 'Text to display above the Bundle-Sells section.', 'woocommerce-product-bundles' ) )
																);
		
		$fields = array_merge( $fields, $force_sales_fields );
		
		return $fields;
	}
	
	/**
	 * WooCommerce Product Bundles Product manager
	 */
	function wcfm_product_bundles_product_manage_fields( $product_id, $product_type ) {
		global $WCFM, $WCFMph;
		require_once( $WCFMph->library->views_path . 'wcfmph-view-wc-product-bundles-product-manage.php' );
	}
	
	/**
	 * Bundle item default attributes
	 */
	function wcfm_get_bundled_item_attribute_defaults( $bundled_item_data ) {
		global $WCFM, $WCFMph;
		
    $default = array();
		$product = wc_get_product( $bundled_item_data->get_product_id() );

		if ( $product && $product->is_type( 'variable' ) ) {
			foreach ( array_filter( (array) $bundled_item_data->get_meta( 'default_variation_attributes' ), 'strlen' ) as $key => $value ) {
				if ( 0 === strpos( $key, 'pa_' ) ) {
					$default[] = array(
						'id'     => wc_attribute_taxonomy_id_by_name( $key ),
						'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
						'option' => $value,
					);
				} else {
					$default[] = array(
						'id'     => 0,
						'name'   => $this->get_attribute_taxonomy_name( $key, $product ),
						'option' => $value,
					);
				}
			}
		}

		return $default;
  }
  
  /**
	 * Get product attribute taxonomy name - @see 'WC_REST_Products_Controller::get_attribute_taxonomy_name'.
	 *
	 * @since  1.0.0
	 *
	 * @param  string      $slug
	 * @param  WC_Product  $product
	 * @return string
	 */
	private static function get_attribute_taxonomy_name( $slug, $product ) {
		$attributes = $product->get_attributes();

		if ( ! isset( $attributes[ $slug ] ) ) {
			return str_replace( 'pa_', '', $slug );
		}

		$attribute = $attributes[ $slug ];

		// Taxonomy attribute name.
		if ( $attribute->is_taxonomy() ) {
			$taxonomy = $attribute->get_taxonomy_object();
			return $taxonomy->attribute_label;
		}

		// Custom product attribute name.
		return $attribute->get_name();
	}
	
	/**
	 * WC Force Sales Fields
	 */
	function wcfm_force_sales_product_manage_fields( $fields, $product_id, $products_array ) {
		global $WCFM, $WCFMph;
		
		$force_sell_ids = array();
		$force_sell_synced_ids = array();
		
		if( $product_id ) {
			$force_sell_ids        = (array) get_post_meta( $product_id, '_force_sell_ids', true );
			$force_sell_synced_ids = (array) get_post_meta( $product_id, '_force_sell_synced_ids', true );
			
			if( !empty( $force_sell_ids ) ) {
				foreach( $force_sell_ids as $force_sell_id ) {
					$products_array[$force_sell_id] = get_post( absint($force_sell_id) )->post_title;	
				}
			}
			
			if( !empty( $force_sell_synced_ids ) ) {
				foreach( $force_sell_synced_ids as $force_sell_synced_id ) {
					$products_array[$force_sell_synced_id] = get_post( absint($force_sell_synced_id) )->post_title;	
				}
			}
			
			$force_sell_ids = array_filter( $force_sell_ids );
			$force_sell_synced_ids = array_filter( $force_sell_synced_ids );
		}
		
		//$product_array_blank = array( '' => __( 'Search for a product&hellip;', 'woocommerce-force-sells' ) );
		//$products_array = array_merge( $product_array_blank, $products_array );
		
		$force_sales_fields = array(  
																"_force_sell_ids" => array('label' => __( 'Force Sells', 'woocommerce-force-sells' ) , 'type' => 'select', 'custom_attributes' => array( 'placeholder' => __( 'Search for a product...', 'woocommerce-force-sells' ) ), 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple variable', 'label_class' => 'wcfm_title', 'options' => $products_array, 'value' => $force_sell_ids, 'hints' => __( 'These products will be added to the cart when the main product is added. Quantity will not be synced in case the main product quantity changes.', 'woocommerce-force-sells' )),
																"_force_sell_synced_ids" => array('label' => __( 'Synced Force Sells', 'woocommerce-force-sells' ) , 'type' => 'select', 'custom_attributes' => array( 'placeholder' => __( 'Search for a product...', 'woocommerce-force-sells' ) ), 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple variable', 'label_class' => 'wcfm_title', 'options' => $products_array, 'value' => $force_sell_synced_ids, 'hints' => __( 'These products will be added to the cart when the main product is added and quantity will be synced in case the main product quantity changes.', 'woocommerce-force-sells' ))
																);
		
		$fields = array_merge( $fields, $force_sales_fields );
		
		return $fields;
	}
	
	/**
	 * WC Chained Product Fields
	 */
	function wcfm_chained_product_product_manage_fields( $fields, $product_id, $products_array ) {
		global $WCFM, $WCFMph;
		
		$chained_product_detail = array();
		$chained_product_manage_stock = '';
		
		if( $product_id ) {
			$chained_product_detail        = (array) get_post_meta( $product_id, '_chained_product_detail', true );
			$chained_product_manage_stock  = get_post_meta( $product_id, '_chained_product_manage_stock', true );
			
			$chained_product_detail = array_filter( $chained_product_detail );
		}
		//$product_array_blank = array( '' => __( 'Search for a product...', 'woocommerce-chained-products' ) );
		//$products_array = array_merge( $product_array_blank, $products_array );
		
		$chained_product_fields = array(  
																"_chained_product_detail" => array( 'label' => __( 'Chained Products', 'woocommerce-chained-products' ), 'type' => 'multiinput', 'class' => 'wcfm_ele simple', 'label_class' => 'wcfm_title wcfm_ele simple', 'value' => $chained_product_detail, 'options' =>
																                                   array( "product_id" => array( 'label' => __( 'Product', 'wc-frontend-manager' ) , 'type' => 'select', 'custom_attributes' => array( 'placeholder' => __( 'Search for a product...', 'woocommerce-chained-products' ) ), 'attributes' => array( 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele simple', 'label_class' => 'wcfm_title', 'options' => $products_array ),
																                                          "unit" => array( 'label' => __( 'Qty', 'woocommerce-chained-products' ) , 'type' => 'number', 'attributes' => array( 'step' => 1, 'min' => 1 ), 'class' => 'wcfm-text wcfm_ele simple', 'label_class' => 'wcfm_title' )
																                             ) ),
																"_chained_product_manage_stock" => array('label' => __( 'Manage stock?', 'woocommerce-chained-products' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele simple', 'label_class' => 'wcfm_title checkbox_title wcfm_ele simple', 'value' => 'yes', 'dfvalue' => $chained_product_manage_stock, 'hints' => __( 'Check to manage stock for products listed in chained products, uncheck otherwise.', 'woocommerce-chained-products' ))
																);
		
		$fields = array_merge( $fields, $chained_product_fields );
		
		return $fields;
	}
	
	/**
	 * WC Chained Product variation Fields
	 */
	function wcfm_chained_product_product_manage_fields_variations( $variation_fileds, $variations, $variation_shipping_option_array, $variation_tax_classes_options, $products_array, $product_id, $product_type ) {
		global $wp, $WCFM, $WCFMu, $wpdb;
		
		$chained_product_fields = array(  
																"_chained_product_detail" => array( 'label' => __( 'Chained Products', 'woocommerce-chained-products' ), 'type' => 'multiinput', 'class' => 'wcfm_ele chained_product_detail_variation variable', 'label_class' => 'wcfm_title', 'options' =>
																                                   array( "product_id" => array( 'label' => __( 'Product', 'wc-frontend-manager' ) , 'type' => 'select', 'custom_attributes' => array( 'placeholder' => __( 'Search for a product...', 'woocommerce-chained-products' ) ), 'attributes' => array( 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele variable', 'label_class' => 'wcfm_title', 'options' => $products_array ),
																                                          "unit" => array( 'label' => __( 'Qty', 'woocommerce-chained-products' ) , 'type' => 'number', 'attributes' => array( 'step' => 1, 'min' => 1 ), 'class' => 'wcfm-text wcfm_ele variable', 'label_class' => 'wcfm_title' )
																                             ) ),
																"_chained_product_manage_stock" => array('label' => __( 'Manage stock?', 'woocommerce-chained-products' ) , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele chained_product_manage_stock variable', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'hints' => __( 'Check to manage stock for products listed in chained products, uncheck otherwise.', 'woocommerce-chained-products' ))
																);
		
		$variation_fileds = array_merge( $variation_fileds, $chained_product_fields );
		
		return $variation_fileds;
	}
	
	/**
	 * WC Chained Product variations Data
	 */
	function wcfm_chained_product_product_manage_data_variations( $variations, $variation_id, $variation_id_key ) {
		global $wp, $WCFM, $WCFMph, $wpdb;
		
		if( $variation_id  ) {
			$chained_product_detail        = (array) get_post_meta( $variation_id, '_chained_product_detail', true );
			$chained_product_manage_stock  = get_post_meta( $variation_id, '_chained_product_manage_stock', true );
			
			$chained_product_detail = array_filter( $chained_product_detail );
			
			$variations[$variation_id_key]['_chained_product_detail']        = $chained_product_detail; 
			$variations[$variation_id_key]['_chained_product_manage_stock']  = $chained_product_manage_stock;
		}
			
		return $variations;
	}
	
		/**
	 * WC Composite Product Inventory
	 */
  function wcfm_composite_product_product_manage_fields_inventory( $inventory_fields, $product_id, $product_type ) {
		global $WCFM, $WCFMph;
		
		$value = 'no';
    if( $product_id ) {
      $composite_product_object     = wc_get_product( $product_id );
      if ($composite_product_object->is_type('composite')){
        $sold_individually         = $composite_product_object->get_sold_individually( 'edit' );
        $sold_individually_context = $composite_product_object->get_sold_individually_context( 'edit' );
        if ( $sold_individually ) {
          if ( ! in_array( $sold_individually_context, array( 'configuration', 'product' ) ) ) {
            $value = 'product';
          } else {
            $value = $sold_individually_context;
          }
        }
      }
    }
    
    $inventory_fields['sold_individually']['class'] .= ' non-composite';
    $inventory_fields['sold_individually']['label_class'] .= ' non-composite';

    $sold_options = array('no'            => __( 'No', 'woocommerce-composite-products' ),
    											'product'       => __( 'Yes', 'woocommerce-composite-products' ),
    											'configuration' => __( 'Matching configurations only', 'woocommerce-composite-products' )
    											);

		// Provide context to the "Sold Individually" option.
    $inventory_fields = $inventory_fields + array(  "_bto_sold_individually" => array('label' => sprintf( esc_html__( 'Sold individually', 'woocommerce-composite-products' ) ), 'value' => $value, 'type' => 'select', 'options' => $sold_options, 'class' => 'wcfm-select wcfm_ele composite', 'label_class' => 'wcfm_title wcfm_ele composite' ) );
	
		return $inventory_fields;
  }

  /**
	 * WC Composite Product Advanced
	 */
  function wcfm_composite_product_product_manage_fields_advanced( $advanced_fields, $product_id ) {
    global $WCFM, $WCFMph;
     
    $wc_pb_edit_in_cart = '';
    $enable_edit_cart = 0;
    if( $product_id ) {
      $composite_product_object     = wc_get_product( $product_id );
      if ( $composite_product_object->is_type('composite') ) {
        $wc_pb_edit_in_cart = get_post_meta( $product_id, '_bto_edit_in_cart', true );
      }
    }

    $advanced_fields = $advanced_fields + array( "_bto_edit_in_cart" => array('label' => __('Editing in cart', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite non-simple non-variable-subscription non-variable', 'value' => 'yes', 'label_class' => 'wcfm_title wcfm_ele checkbox_title composite non-simple non-variable-subscription non-variable', 'dfvalue' => $enable_edit_cart ) );
    return $advanced_fields;
  }
	
	/**
	 * WooCommerce Composite Product manager
	 */
	function wcfm_composite_product_product_manage_fields() {
		global $WCFM, $WCFMph;
		require_once( $WCFMph->library->views_path . 'wcfmph-view-wc-product-composite-product-manage.php' );
	}
	
	/**
	 * WooCommerce Group By Product manager
	 */
	function wcfm_groupbuy_product_manage_fields( $product_id, $product_type ) {
		global $WCFM, $WCFMph;
		require_once( $WCFMph->library->views_path . 'wcfmph-view-wc-product-groupbuy-product-manage.php' );
	}
	
}