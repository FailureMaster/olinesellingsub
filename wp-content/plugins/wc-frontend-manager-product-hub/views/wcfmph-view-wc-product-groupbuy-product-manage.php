<?php
/**
 * WCFM plugin views
 *
 * Plugin WC Group Buy Products Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmph/views/thirdparty
 * @version   1.0.2
 */
global $wp, $WCFM, $WCFMph;

$product_id = 0;
$product    = '';

$_groupbuy_min_deals          = '';
$_groupbuy_max_deals          = '';
$_groupbuy_max_deals_per_user = '';
$_groupbuy_price              = '';
$_groupbuy_regular_price      = '';
$_groupbuy_dates_from         = '';
$_groupbuy_dates_to           = '';

$_relist_groupbuy_dates_from  = '';
$_relist_groupbuy_dates_to    = '';

if( isset( $wp->query_vars['wcfm-products-manage'] ) && !empty( $wp->query_vars['wcfm-products-manage'] ) ) {
	$product_id = $wp->query_vars['wcfm-products-manage'];
	if( $product_id ) {
		$product                        = wc_get_product( $product_id );
		if( 'groupbuy' == $product->get_type() ) {
			$_groupbuy_min_deals            = get_post_meta( $product_id, '_groupbuy_min_deals', true );
			$_groupbuy_max_deals            = get_post_meta( $product_id, '_groupbuy_max_deals', true );
			$_groupbuy_max_deals_per_user   = get_post_meta( $product_id, '_groupbuy_max_deals_per_user', true );
			$_groupbuy_price                = get_post_meta( $product_id, '_groupbuy_price', true );
			$_groupbuy_regular_price        = get_post_meta( $product_id, '_groupbuy_regular_price', true );
			$_groupbuy_dates_from           = get_post_meta( $product_id, '_groupbuy_dates_from', true );
			$_groupbuy_dates_to             = get_post_meta( $product_id, '_groupbuy_dates_to', true );
			
			$_relist_groupbuy_dates_from    = get_post_meta( $product_id, '_relist_groupbuy_dates_from', true );
			$_relist_groupbuy_dates_to      = get_post_meta( $product_id, '_relist_groupbuy_dates_to', true );
		}
	}
}

?>

<!-- collapsible - WC Groupbuy Product Support -->
<div class="page_collapsible products_manage_groupbuy groupbuy non-variable-subscription" id="wcfm_products_manage_form_groupbuy_head"><label class="wcfmfa fa-object-group"></label><?php _e('Group Buy', 'wc-frontend-manager-product-hub'); ?><span></span></div>
<div class="wcfm-container groupbuy non-variable-subscription">
	<div id="wcfm_products_manage_form_groupbuy_expander" class="wcfm-content">
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_groupbuy_fields', array( 
			"_groupbuy_min_deals"            => array( 'label' => __('Min deals', 'wc_groupbuy') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_min_deals, 'hints' => __( 'Minimum deals to be sold', 'wc_groupbuy' ) ),
			"_groupbuy_max_deals"            => array( 'label' => __('Max deals', 'wc_groupbuy') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_max_deals, 'hints' => __( 'Maximum deals to be sold', 'wc_groupbuy' ) ),
			"_groupbuy_max_deals_per_user"   => array( 'label' => __('Max deals per user', 'wc_groupbuy') , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_max_deals_per_user, 'hints' => __( 'Max deals sold per user', 'wc_groupbuy' ) ),
			"_groupbuy_price"                => array( 'label' => __('Group Buy Price', 'wc_groupbuy') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_price, 'hints' => __( 'Group Buy deal price', 'wc_groupbuy' ) ),
			"_groupbuy_regular_price"        => array( 'label' => __('Regular Price', 'wc_groupbuy') . '(' . get_woocommerce_currency_symbol() . ')' , 'type' => 'number', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_regular_price, 'hints' => __( 'Regular product price (for comparison)', 'wc_groupbuy' ) ),
			"_groupbuy_dates_from"           => array( 'label' => __('Group buy available from date', 'wc_groupbuy'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_dates_from, 'placeholder' => 'YYYY-MM-DD HH:MM', 'attributes' => array( 'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])[ ](0[0-9]|1[0-9]|2[0-4]):(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])' ) ),
			"_groupbuy_dates_to"             => array( 'label' => __('Group buy available to date', 'wc_groupbuy'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_groupbuy_dates_to, 'placeholder' => 'YYYY-MM-DD HH:MM', 'attributes' => array( 'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])[ ](0[0-9]|1[0-9]|2[0-4]):(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])' ) ),
			), $product_id ) );
		
		if( $product_id && ( 'groupbuy' == $product->get_type() ) && $product->is_closed() ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_product_manage_groupbuy_relist_fields', array( 
				"_relist_groupbuy_dates_from" => array( 'label' => __('Relist form date', 'wc_groupbuy'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_relist_groupbuy_dates_from, 'placeholder' => 'YYYY-MM-DD HH:MM', 'attributes' => array( 'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])[ ](0[0-9]|1[0-9]|2[0-4]):(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])' ) ),
				"_relist_groupbuy_dates_to"   => array( 'label' => __('Relist to dates', 'wc_groupbuy'), 'type' => 'text', 'class' => 'wcfm-text wcfm_ele groupbuy', 'label_class' => 'wcfm_title groupbuy', 'value' => $_relist_groupbuy_dates_to, 'placeholder' => 'YYYY-MM-DD HH:MM', 'attributes' => array( 'pattern' => '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])[ ](0[0-9]|1[0-9]|2[0-4]):(0[0-9]|1[0-9]|2[0-9]|3[0-9]|4[0-9]|5[0-9])' ) ),
				), $product_id ) );
		}
		?>
	</div>
</div>
<!-- end collapsible -->
<div class="wcfm_clearfix"></div>