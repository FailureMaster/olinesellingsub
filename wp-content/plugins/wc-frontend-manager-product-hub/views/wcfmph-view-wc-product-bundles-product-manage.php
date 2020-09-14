<?php
/**
 * WCFMph plugin view
 *
 * WCFMph Product Hub View
 *
 * @author 		WC Lovers
 * @package 	wcfmph/view
 * @version   1.0.0
 */

global $wp, $WCFM, $WCFMph;

$wcfm_allow_product_bundles = apply_filters( 'wcfm_is_allow_wc_product_bundles', true );
if( !$wcfm_allow_product_bundles ) {
	//wcfm_restriction_message_show( "Product bundle" );
	return;
}

$choose_layout = 'default';
$form_location = '';
$group_mode    = ''; 
$edit_in_cart  = '';

$min_qty_limit = '';
$max_qty_limit = '';

$bundle_value[] = array(
							'bundled_item_id'                       => '',
							'product_id'                            => '',
							'bundled_menu_order'                    => '',
							'quantity_min'                          => 1,
							'quantity_max'                          => 1,
							'shipped_individually'                  => '',
							'priced_individually'                   => '',
							'override_title'                        => '',
							'title'                                 => '',
							'override_description'                  => '',
							'description'                           => '',
							'optional'                              => '',
							'hide_thumbnail'                        => '',
							'discount'                              => '',
							'override_variations'                   => '',
							'allowed_variations'                    => '',
							'override_default_variation_attributes' => '',
							'default_variation_attributes'          => array(),
							'single_product_visibility'             => '',
							'cart_visibility'                       => '',
							'order_visibility'                      => '',
							'single_product_price_visibility'       => '',
							'cart_price_visibility'                 => '',
							'order_price_visibility'                => '',
						);
$single_product_visibility_val = array('checked' => 'checked');

$sel_array = array();
$default_var = array();

$products_array = array();

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
	if( $product_id ) {
		$product = wc_get_product( $product_id );
		if( $product && !empty( $product ) ) {
			if ( 'bundle' == $product->get_type() ) {
				$product_bundle_object = $product_id ? new WC_Product_Bundle( $product_id ) : new WC_Product_Bundle();
				
				$choose_layout = $product_bundle_object->get_layout( 'edit' );
				
				$form_location = $product_bundle_object->get_add_to_cart_form_location( 'edit' );
				
				$group_mode   = $product_bundle_object->get_group_mode( 'edit' );
				
				$edit_in_cart = $product_bundle_object->get_editable_in_cart( 'edit' ) ? 'yes' : 'no';
				
				$min_qty_limit = get_post_meta( $product_id, '_wcpb_min_qty_limit', true );
				$max_qty_limit = get_post_meta( $product_id, '_wcpb_max_qty_limit', true );
	
				$bundleargs = array(
														'bundle_id' => $product_id,
														'return'    => 'objects',
														'order_by'  => array( 'menu_order' => 'ASC' )
													);
	
				$data_items = WC_PB_DB::query_bundled_items( $bundleargs );
				
				if ( ! empty( $data_items ) ) {
					foreach ( $data_items as $data_item_key => $data_item ) {
						$bundle_value[$data_item_key] = array(
							'bundled_item_id'                       => $data_item->get_id(),
							'product_id'                            => $data_item->get_product_id(),
							'bundled_menu_order'                    => $data_item->get_menu_order(),
							'quantity_min'                          => $data_item->get_meta( 'quantity_min' ),
							'quantity_max'                          => $data_item->get_meta( 'quantity_max' ),
							'shipped_individually'                  => $data_item->get_meta( 'shipped_individually' ),
							'priced_individually'                   => $data_item->get_meta( 'priced_individually' ),
							'override_title'                        => $data_item->get_meta( 'override_title' ),
							'title'                                 => $data_item->get_meta( 'title' ),
							'override_description'                  => $data_item->get_meta( 'override_description' ),
							'description'                           => $data_item->get_meta( 'description' ),
							'optional'                              => $data_item->get_meta( 'optional' ),
							'hide_thumbnail'                        => $data_item->get_meta( 'hide_thumbnail' ),
							'discount'                              => $data_item->get_meta( 'discount' ),
							'override_variations'                   => $data_item->get_meta( 'override_variations' ),
							'allowed_variations'                    => (array)$data_item->get_meta( 'allowed_variations' ),
							'override_default_variation_attributes' => $data_item->get_meta( 'override_default_variation_attributes' ),
							'default_variation_attributes'          => $this->wcfm_get_bundled_item_attribute_defaults( $data_item ),
							'single_product_visibility'             => $data_item->get_meta( 'single_product_visibility' ),
							'cart_visibility'                       => $data_item->get_meta( 'cart_visibility' ),
							'order_visibility'                      => $data_item->get_meta( 'order_visibility' ),
							'single_product_price_visibility'       => $data_item->get_meta( 'single_product_price_visibility' ),
							'cart_price_visibility'                 => $data_item->get_meta( 'cart_price_visibility' ),
							'order_price_visibility'                => $data_item->get_meta( 'order_price_visibility' ),
						);
						$prod_id = $data_item->get_product_id();
						$products_array[$prod_id] = get_the_title( $prod_id );
						$variations = $WCFMph->library->wcfm_get_all_variations( $prod_id );
						
						$sel_array[$data_item->get_id()]['selected_var'] = $data_item->get_meta( 'allowed_variations' );
						$default_var[$data_item->get_id()] = $this->wcfm_get_bundled_item_attribute_defaults( $data_item );
								 
						foreach ( $variations as $variation_id ) {
							$product_variation = wc_get_product( $variation_id );
							$var_title = wp_strip_all_tags($product_variation->get_formatted_name());
							$sel_array[$data_item->get_id()]['all_var'][$variation_id] = $var_title;
						}
					}
					$single_product_visibility_val = array();
				}
			}
		}
	}
}

wp_localize_script( 'wcfmph_product_bundles_products_manage_js', 'variations_prod', array( 'var' => json_encode($sel_array), 'default' => json_encode($default_var) ) );

$layoutoptions = WC_Product_Bundle::get_layout_options();

$location_options  = WC_Product_Bundle::get_add_to_cart_form_location_options();
$location_help_tip = '';
$loop     = 0;

foreach ( $location_options as $option_key => $option ) {

	$location_help_tip .= '<strong>' . $option[ 'title' ] . '</strong> &ndash; ' . $option[ 'description' ];

	if ( $loop < sizeof( $location_options ) - 1 ) {
		$location_help_tip .= '<br /><br />';
	}

	$loop++;
}

$group_mode_options = WC_Product_Bundle::get_group_mode_options( true );
?>

<!-- collapsible - WC Product Bundles Support -->
<div class="page_collapsible products_manage_wc_product_bundle bundle" id="wcfm_products_manage_form_wc_product_bundle_head"><label class="wcfmfa fa-cubes"></label><?php _e('Bundled', 'wc-frontend-manager-product-hub'); ?><span></span></div>
<div class="wcfm-container bundle">
	<div id="wcfm_products_manage_form_wc_product_bundle_expander" class="wcfm-content">
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_bundles_general_fields',  array( 
			                                              "_wc_pb_layout_style" => array( 'label' => __('Layout', 'woocommerce-product-bundles'), 'type' => 'select', 'value' => $choose_layout, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => $layoutoptions, 'hints' => __('Select the Tabular option to have the thumbnails, descriptions and quantities of bundled products arranged in a table. Recommended for displaying multiple bundled products with configurable quantities.', 'woocommerce-product-bundles' ) ),
			                                              "_wc_pb_add_to_cart_form_location" => array( 'label' => __('Form Location', 'woocommerce-product-bundles'), 'type' => 'select', 'value' => $form_location, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => array_combine( array_keys( $location_options ), wp_list_pluck( $location_options, 'title' ) ), 'hints' => $location_help_tip ),
			                                              "_wc_pb_group_mode" => array( 'label' => __('Item Grouping', 'woocommerce-product-bundles'), 'type' => 'select', 'options' => $group_mode_options, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'value' => $group_mode, 'hints' => __( 'Controls the grouping of parent/child line items in cart/order templates.', 'woocommerce-product-bundles' ) ),
			                                              "_wc_pb_edit_in_cart" => array( 'label' => __('Edit in Cart', 'woocommerce-product-bundles'), 'type' => 'checkbox', 'class' => 'wcfm-checkbox', 'label_class' => 'wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $edit_in_cart, 'hints' => __( 'Enable this option to allow changing the configuration of this bundle in the cart. Applicable to bundles with configurable attributes and/or quantities.', 'woocommerce-product-bundles' ) ),
			                                              ) ) );
		
		if( class_exists( 'WC_PB_Min_Max_Items' ) ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_bundles_general_minmax_fields',  array( 
			                                              "_wcpb_min_qty_limit" => array( 'label' => __( 'Items Required (&ge;)', 'woocommerce-product-bundles-min-max-items' ), 'type' => 'number', 'value' => $min_qty_limit, 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Minimum required quantity of bundled items.', 'woocommerce-product-bundles' ) ),
			                                              "_wcpb_max_qty_limit" => array( 'label' => __( 'Items Allowed (&le;)', 'woocommerce-product-bundles-min-max-items' ), 'type' => 'number', 'value' => $max_qty_limit, 'class' => 'wcfm-text', 'label_class' => 'wcfm_title', 'hints' => __( 'Maximum allowed quantity of bundled items.', 'woocommerce-product-bundles-min-max-items' ) ),
			                                              ) ) );
		}
		
		$WCFM->wcfm_fields->wcfm_generate_form_field( array(  
        "bundle_data" => array('label' => __('Add Products in bundle', 'wc-frontend-manager-product-hub') , 'type' => 'multiinput', 'value' => $bundle_value, 'class' => 'bundle', 'label_class' => 'bundle to_fix_width full', 'options' => array(
            "product_id" => array('label' => __('Product Name', 'wc-frontend-manager-product-hub'), 'type' => 'select', 'options' => $products_array, 'class' => ' wcfm-select wcfm_ele  bundle every_input_field bundle_item bundle_item_pro pro_name', 'label_class' => 'wcfm_title bundle_item_pro bundle','hints' => __('Select a product and add it to this bundle by clicking its name in the results list.', 'woocommerce-product-bundles')),

            "override_variations" => array('label' => __('Filter Variations', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check to enable only a subset of the available variations.', 'woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox filter_variation ovrride_var_chkbx hide_variation override_variations_checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle ovrride_var_chkbx filter_variation to_fix_width hide_variation','dfvalue' => 0),
            "allowed_variations" => array('label' => __('', 'woocommerce-product-bundles'), 'type' => 'select', 'options' => array(), 'class' => 'wcfm-select wcfm_ele bundle bundle_dynamic_field every_input_field allowed_variations variations_hide_datas hide_variation ', 'label_class' => 'wcfm_title bundle to_fix_width allowed_variations hide_variation variations_hide_datas lbl_select', 'attributes' => array( 'multiple' => 'multiple' ), ),
            "override_default_variation_attributes" => array('label' => __('Override Default Selections', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('In effect for this bundle only. The available options are in sync with the filtering settings above. Always save any changes made above before configuring this section.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox filter_variation dflt_atts hide_variation variation_ele_hide override_default_variation_attribute_checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle dflt_atts filter_variation hide_variation variation_ele_hide to_fix_width ','dfvalue' => 0),
            "default_variation_attributes" => array('label' => __('', 'woocommerce-product-bundles'), 'type' => 'select', 'options' => array(), 'class' => 'wcfm-select wcfm_ele bundle every_input_field hide_variation default_ovrride_attr multiSelectBox-right variation_attr_hide dflt_items_datas variation_ele_hide', 'label_class' => 'wcfm_title bundle hide_variation variation_attr_hide default_ovrride_attr variation_ele_hide' ),

            "optional" => array('label' => __('Optional', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option to mark the bundled product as optional.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle ', 'value' => 'yes','label_class' => 'wcfm_title checkbox_title bundle'),

            "quantity_min" => array('label' => __('Quantity Min', 'woocommerce-product-bundles'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 1), 'class' => 'wcfm-text wcfm_ele bundle bundle_quantity_min', 'label_class' => 'wcfm_ele wcfm_title bundle'),
            "quantity_max" => array('label' => __('Quantity Max', 'woocommerce-product-bundles'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 1),'class' => 'wcfm-text wcfm_ele bundle bundle_quantity_max', 'label_class' => 'wcfm_ele wcfm_title bundle '),

            "shipped_individually" => array('label' => __('Shipped Individually', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option if this bundled item is shipped separately from the bundle.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle ', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle'),

            "priced_individually" => array('label' => __('Priced Individually', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option to have the price of this bundled item added to the base price of the bundle.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox priced_individually_checkbox ', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle','dfvalue' => 0),            

            "discount" => array('label' => __('Discount %', 'woocommerce-product-bundles'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele bundle discount_field dependscheckbox every_input_field hide_visiblity','hints' => __('Discount applied to the regular price of this bundled product when Priced Individually is checked. If a Discount is applied to a bundled product which has a sale price defined, the sale price will be overridden.','woocommerce-product-bundles'),'label_class' => 'wcfm_title checkbox_title bundle dependscheckbox hide_visiblity'),

            "single_product_visibility" => array('label' => __('Visibility with Product details', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>__('Controls the visibility of the bundled item in the single-product template of this bundle.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox visibility_product checked_on_load', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "cart_visibility" => array('label' => __('Visibility at Cart/checkout', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>__('Controls the visibility of the bundled item in cart/checkout templates.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox visibility_cart checked_on_load', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "order_visibility" => array('label' => __('Visibility with Order details', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>'Controls the visibility of the bundled item in order details & e-mail templates.','class' => 'wcfm-checkbox wcfm_ele bundle checkbox visibility_order checked_on_load', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "single_product_price_visibility" => array('label' => __('Price Visibility with Product details', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>__('Controls the visibility of the bundled-item price in the single-product template of this bundle.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle price_visibility_product dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle dependscheckbox hide_visiblity','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "cart_price_visibility" => array('label' => __('Price Visibility at Cart/checkout', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>__('Controls the visibility of the bundled-item price in cart/checkout templates.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle checkbox price_visibility_cart dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle dependscheckbox hide_visiblity','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "order_price_visibility" => array('label' => __('Price Visibility with Order details', 'wc-frontend-manager-product-hub') , 'type' => 'checkbox', 'hints' =>'Controls the visibility of the bundled-item price in order details & e-mail templates.','class' => 'wcfm-checkbox wcfm_ele bundle checkbox price_visibility_order dependscheckbox checked_on_load hide_visiblity', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title bundle dependscheckbox hide_visiblity','dfvalue' => 1,'custom_tags' => $single_product_visibility_val),

            "hide_thumbnail" => array('label' => __('Hide Thumbnail', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option to hide the thumbnail image of this bundled product.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle dependsonproductdetails checked_on_load_product_des', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle dependsonproductdetails checked_on_load_product_des','dfvalue' => 0),

            "override_title" => array('label' => __('Override Title', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option to override the default product title.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle override_title_check dependsonproductdetails checked_on_load_product_des', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle dependsonproductdetails checked_on_load_product_des','dfvalue' => 0),

            "title" => array('label' => __('', 'woocommerce-product-bundles') , 'type' => 'textarea', 'class' => 'wcfm-checkbox wcfm_ele bundle override_title_feild hide_variation to_fix_width multiple_dependency', 'label_class' => 'wcfm_title bundle override_title_feild hide_variation multiple_dependency'),

            "override_description" => array('label' => __('Override Short Description', 'woocommerce-product-bundles') , 'type' => 'checkbox', 'hints' =>__('Check this option to override the default short product description.','woocommerce-product-bundles'),'class' => 'wcfm-checkbox wcfm_ele bundle override_description_check dependsonproductdetails checked_on_load_product_des', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title bundle dependsonproductdetails checked_on_load_product_des','dfvalue' => 0),

            "description" => array('label' => __('', 'woocommerce-product-bundles') , 'type' => 'textarea', 'class' => 'wcfm-checkbox wcfm_ele bundle override_description_feild hide_variation to_fix_width multiple_dependency', 'label_class' => 'wcfm_title bundle override_description_feild hide_variation multiple_dependency'),
            
            "bundled_item_id" => array('type' => 'hidden', 'class' => "bundled_item_id" ),
            "bundled_menu_order" => array('type' => 'hidden', 'class' => "bundled_menu_order" ),
            ))
      )); 
		?>
	</div>
</div>
<!-- end collapsible -->
<div class="wcfm_clearfix"></div>