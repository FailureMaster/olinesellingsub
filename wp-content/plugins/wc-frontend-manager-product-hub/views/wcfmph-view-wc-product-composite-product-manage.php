<?php
/**
 * WCFMph plugin view
 *
 * WCFMph Product Hub View
 * Composite Product
 *
 * @author 		WC Lovers
 * @package 	wcfmph/view
 * @version   1.0.0
 */

global $wp, $WCFM, $WCFMph;

$wcfm_is_allow_wc_composite_product = apply_filters( 'wcfm_is_allow_wc_composite_product', true );
if( !$wcfm_is_allow_wc_composite_product ) {
	//wcfm_restriction_message_show( "Product composite" );
	return;
}

$choose_layout      = 'default';
$selected_cart_form = 'default';
$shop_price_calc    = 'defaults';
$edit_in_cart       = 'no';
$selected_layout    = '';
$composite_data     = array();

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
	if( $product_id ) {
		$composite_product_object = wc_get_product( $product_id );
		if( $composite_product_object && !empty( $composite_product_object ) ) {
			if ( 'composite' == $composite_product_object->get_type() ) {
				$composite      = new WC_Product_Composite( $product_id );
				$composite_data = $composite->get_composite_data();
				
				$selected_layout    = WC_Product_Composite::get_layout_option( get_post_meta( $product_id, '_bto_style', true ) );
				$selected_cart_form = $composite_product_object->get_add_to_cart_form_location( 'edit' );
				$shop_price_calc    = $composite_product_object->get_shop_price_calc( 'edit' );
				$edit_in_cart       = $composite_product_object->get_editable_in_cart( 'edit' ) ? 'yes' : 'no';
				
				if ( ! $composite_data ) {
					$composite_data = array();
				}
			}
		}
	}
}

//wp_localize_script( 'wcfmph_product_composites_products_manage_js', 'variations_prod', array( 'var' => json_encode($sel_array), 'default' => json_encode($default_var) ) );

$layoutoptions = array( 'default' => __( 'Standard', 'woocommerce-composite-products' ), 'tabular' => __( 'Tabular', 'woocommerce-composite-products' ) );

$cart_form_options  = WC_Product_Composite::get_add_to_cart_form_location_options();

$shop_price_calc_options = WC_Product_Composite::get_shop_price_calc_options();

$args = array(
	'posts_per_page'   => -1,
	'offset'           => 0,
	'category'         => '',
	'category_name'    => '',
	'orderby'          => 'date',
	'order'            => 'DESC',
	'include'          => '',
	'exclude'          => '',
	'meta_key'         => '',
	'meta_value'       => '',
	'post_type'        => 'product',
	'post_mime_type'   => '',
	'post_parent'      => '',
	//'author'	   => get_current_user_id(),
	'post_status'      => array('publish'),
	'suppress_filters' => true,
	'fields'           => array( 'ID', 'title' )
);
$args = apply_filters( 'wcfm_products_args', $args );

$products_objs = get_posts( $args );
$products_array = array( );
if( !empty($products_objs) ) {
	foreach( $products_objs as $products_obj ) {
		$products_array[esc_attr( $products_obj->ID )] = esc_html( $products_obj->post_title );
	}
}

$layouts        = WC_Product_Composite::get_layout_options();
$layout_options = array();
if( !empty( $layouts ) ) {
	foreach ( $layouts as $layout_id => $layout_description ) {
		$layout_options[$layout_id] = $layout_description['title'];
	}
}

$select_by = array(
	'product_ids'  => __( 'Select products', 'woocommerce-composite-products' ),
	'category_ids' => __( 'Select categories', 'woocommerce-composite-products' )
);

$selection_modes = array();
foreach ( WC_CP_Component::get_options_styles() as $style ) {
	$selection_modes[$style[ 'id' ]] = $style[ 'description' ];
}
?>

<!-- collapsible - WC Product Composites Support -->
<div class="page_collapsible products_manage_wc_product_composite composite" id="wcfm_products_manage_form_wc_product_composite_head"><label class="wcfmfa fa-cubes"></label><?php _e('Components', 'woocommerce-composite-products'); ?><span></span></div>
<div class="wcfm-container composite">
	<div id="wcfm_products_manage_form_wc_product_composite_expander" class="wcfm-content">
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( array( 
			"_bto_style" => array( 'label' => __('Layout', 'woocommerce-composite-products'), 'type' => 'select', 'value' => $selected_layout, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => $layout_options, 'hints' => __('Select the Tabular option to have the thumbnails, descriptions and quantities of composited products arranged in a table. Recommended for displaying multiple composited products with configurable quantities.', 'woocommerce-composite-products' ) ),
			"_bto_add_to_cart_form_location" => array( 'label' => __('Form Location', 'woocommerce-composite-products'), 'type' => 'select', 'value' => $selected_cart_form, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => array_combine( array_keys( $cart_form_options ), wp_list_pluck( $cart_form_options, 'title' ) ) ),
			"_bto_shop_price_calc" => array( 'label' => __('Catalog Price', 'woocommerce-composite-products'), 'type' => 'select', 'value' => $shop_price_calc, 'class' => 'wcfm-select', 'label_class' => 'wcfm_title', 'options' => array_combine( array_keys( $shop_price_calc_options ), wp_list_pluck( $shop_price_calc_options, 'title' ) ) ),
			"_bto_edit_in_cart" => array( 'label' => __('Edit in Cart', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite ', 'value' => 'yes', 'dfvalue' => $edit_in_cart, 'label_class' => 'wcfm_title checkbox_title composite', 'hinsts' => __( 'Enable this option to allow changing the configuration of this Composite in the cart.', 'woocommerce-composite-products' ) ),
			) );
		
		$WCFM->wcfm_fields->wcfm_generate_form_field( array(  
        "bto_data" => array('label' => __('Add Products in composite', 'woocommerce-composite-products') , 'type' => 'multiinput', 'value' => $composite_data, 'class' => 'composite', 'label_class' => 'composite to_fix_width full', 'options' => array(
            "title" => array('label' => __('Component Name', 'woocommerce-composite-products'), 'type' => 'text', 'class' => ' wcfm-text wcfm_ele  composite every_input_field composite_item_title composite_item', 'label_class' => 'wcfm_title composite composite_item_title','hints' => __( 'Name or title of this Component.', 'woocommerce-composite-products' ) ),
            "description" => array('label' => __('Component Description', 'woocommerce-composite-products'), 'type' => 'textarea', 'class' => ' wcfm-textarea wcfm_ele  composite every_input_field composite_item', 'label_class' => 'wcfm_title composite','hints' => __( 'Optional short description of this Component.', 'woocommerce-composite-products' ) ),
            "thumbnail" => array('label' => __('Component Image', 'woocommerce-composite-products'), 'type' => 'upload', 'class' => ' wcfm-text wcfm_ele  composite every_input_field composite_item', 'label_class' => 'wcfm_title composite','hints' => __( 'Placeholder image to use in configuration summaries. When a Component Option is chosen, the placeholder image will be replaced by the image associated with the selected product. Note: Configuration summary sections are displayed when using a) the Stepped/Componentized layouts and b) the Composite Product Summary widget.', 'woocommerce-composite-products' ) ),
            
            //"query_type" => array( 'label' => __('Component Options', 'woocommerce-composite-products'), 'type' => 'select', 'options' => $select_by, 'class' => ' wcfm-select wcfm_ele  composite every_input_field composite_item composite_item_option_type', 'label_class' => 'wcfm_title composite_item_option_type composite','hints' => __( 'Every Component includes an assortment of products to choose from - the <strong>Component Options</strong>. You can add products individually, or select a category to add all associated products.', 'woocommerce-composite-products' ) ),
        		"assigned_ids" => array( 'label' => __('Component Options', 'woocommerce-composite-products'), 'type' => 'select', 'options' => $products_array, 'attributes' => array( 'multiple' => 'mutiple', 'style' => 'width: 60%;' ), 'class' => ' wcfm-select wcfm_ele assigned_ids composite composite_item composite_item_option', 'label_class' => 'wcfm_title composite_item_option composite_item_title composite' ),
        		//"assigned_category_ids" => array( 'label' => __('', 'woocommerce-composite-products'), 'type' => 'select', 'options' => $products_array, 'class' => ' wcfm-select wcfm_ele  composite every_input_field composite_item composite_item_option', 'label_class' => 'wcfm_title composite_item_option composite' ),
        		
        		
        		"selection_mode" => array( 'label' => __('Options Style', 'woocommerce-composite-products'), 'type' => 'select', 'options' => $selection_modes, 'class' => ' wcfm-select wcfm_ele  composite every_input_field composite_item', 'label_class' => 'wcfm_title composite', 'hints' => __( '<strong>Thumbnails</strong> &ndash; Component Options are presented as thumbnails, paginated and arranged in columns similar to the main shop loop.</br></br><strong>Dropdown</strong> &ndash; Component Options are listed in a dropdown menu.</br></br><strong>Radio Buttons</strong> &ndash; Component Options are listed as radio buttons.', 'woocommerce-composite-products' ) ),
        		
        		
            "quantity_min" => array('label' => __('Min Quantity', 'woocommerce-composite-products'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 1), 'class' => 'wcfm-text wcfm_ele composite composite_quantity_min', 'label_class' => 'wcfm_ele wcfm_title composite', 'hints' => __( 'Set a minimum quantity for the selected Component Option.', 'woocommerce-composite-products' ) ),
            "quantity_max" => array('label' => __('Max Quantity', 'woocommerce-composite-products'), 'type' => 'number', 'value' => 1, 'attributes' => array('min' => 1),'class' => 'wcfm-text wcfm_ele composite composite_quantity_max', 'label_class' => 'wcfm_ele wcfm_title composite ', 'hints' => __( 'Set a maximum quantity for the selected Component Option. Leave the field empty to allow an unlimited maximum quantity.', 'woocommerce-composite-products' ) ),

            "shipped_individually" => array('label' => __('Shipped Individually', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite ', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite', 'hinsts' => __( 'Check this option if this Component is shipped separately from the Composite.', 'woocommerce-composite-products' ) ),

            "priced_individually" => array('label' => __('Priced Individually', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox priced_individually_checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite','dfvalue' => 0, 'hints' => __( 'Check this option to have the price of this Component added to the base price of the Composite.', 'woocommerce-composite-products' ) ),            

            "discount" => array('label' => __('Discount %', 'woocommerce-composite-products'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele composite discount_field dependscheckbox every_input_field hide_visiblity', 'label_class' => 'wcfm_title checkbox_title composite dependscheckbox hide_visiblity', 'hints' => __( 'Component-level discount applied to any selected Component Option when <strong>Priced Individually</strong> is checked.', 'woocommerce-composite-products' ) ),
            
            "optional" => array('label' => __('Optional', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite ', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite',  'hints' => __( 'Checking this option will allow customers to proceed without making any selection for this Component at all.', 'woocommerce-composite-products' ) ),

            "hide_product_title" => array('label' => __('Hide Title', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Check this option to hide the selected Component Option title.', 'woocommerce-composite-products' ) ),
            "hide_product_description" => array('label' => __('Hide Description', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Check this option to hide the selected Component Option description.', 'woocommerce-composite-products' ) ),
            "hide_product_thumbnail" => array('label' => __('Hide Thumbnail', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Check this option to hide the selected Component Option thumbnail.', 'woocommerce-composite-products' ) ),
            "hide_product_price" => array('label' => __('Hide Price', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Check this option to hide the selected Component Option price.', 'woocommerce-composite-products' ) ),
            
            "hide_subtotal_product" => array('label' => __( 'Component Price Visibility', 'woocommerce-composite-products' ) . ' ' . __('Composite', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'no', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Controls the visibility of the Component subtotal in the single-product template of the Composite.', 'woocommerce-composite-products' ) ),
            "hide_subtotal_cart" => array('label' => __( 'Component Price Visibility', 'woocommerce-composite-products' ) . ' ' . __('Cart/checkout', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'no', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Controls the visibility of the Component price/subtotal in cart/checkout templates.', 'woocommerce-composite-products' ) ),
            "hide_subtotal_orders" => array('label' => __( 'Component Price Visibility', 'woocommerce-composite-products' ) . ' ' . __('Order details', 'woocommerce-composite-products') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'no', 'label_class' => 'wcfm_title checkbox_title composite', 'hints' => __( 'Controls the visibility of the Component price/subtotal in order details &amp; e-mail templates.', 'woocommerce-composite-products' ) ),

            "show_orderby" => array( 'label' => __( 'Options Sorting', 'woocommerce-composite-products' ), 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele composite checkbox', 'value' => 'visible', 'label_class' => 'wcfm_title checkbox_title composite','dfvalue' => 'yes', 'hints' => __( 'Check this option to allow sorting the available Component Options by popularity, rating, newness or price.', 'woocommerce-composite-products' ) ),
            
            //"show_filters" => array('type' => 'hidden', 'value' => 'no', 'class' => "show_filters" ), // Not configured now
            //"attribute_filters" => array('type' => 'hidden', 'class' => "attribute_filters" ), // Not configured now
            
            //"default_id" => array('type' => 'hidden', 'value' => '', 'class' => "default_id" ), // Not configured now
            
            //"query_type" => array('type' => 'hidden', 'value' => 'product_ids', 'class' => "query_type" ), // Not configured now
            //"assigned_category_ids" => array('type' => 'hidden', 'value' => '', 'class' => "assigned_category_ids" ), // Not configured now
            
            "component_id" => array('type' => 'hidden', 'class' => "component_id" ),
            ))
      )); 
		?>
	</div>
</div>
<!-- end collapsible -->
<div class="wcfm_clearfix"></div>