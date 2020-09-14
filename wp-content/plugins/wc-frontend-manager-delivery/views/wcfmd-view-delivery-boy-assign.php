<?php
/**
 * WCFM plugin view
 *
 * WCfM Delivery Boy Assign popup View
 *
 * @author 		WC Lovers
 * @package 	wcfmd/views/orders
 * @version   1.0.0
 */
 
global $wp, $WCFM, $WCFMd, $_POST, $wpdb;

$order_id = $_POST['orderid'];
$product_id = $_POST['productid'];
$order_item_id = $_POST['orderitemid'];

$wcfm_delivery_boys_array = wcfm_get_delivery_boys();
$delivery_users = array( '' => __( '-Select Delivery Boy-', 'wc-frontend-manager-delivery' ) );

if(!empty($wcfm_delivery_boys_array)) {
	foreach( $wcfm_delivery_boys_array as $wcfm_delivery_boys_single ) {
		$delivery_users[$wcfm_delivery_boys_single->ID] = $wcfm_delivery_boys_single->first_name . ' ' . $wcfm_delivery_boys_single->last_name . ' (' . $wcfm_delivery_boys_single->user_email . ')';
	}
}

$wcfm_delivery_boy  = wc_get_order_item_meta( $order_item_id, 'wcfm_delivery_boy', true );

?>

<div class="wcfm-collapse-content wcfm_popup_wrapper">
  <form id="wcfm_shipping_tracking_form">
		<div style="margin-bottom: 15px;"><h2 style="float: none;"><?php _e( 'Delivery Boy Assign', 'wc-frontend-manager-ultimate' ); ?></h2></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_delivery_boy_assign_fields', array(
																												"wcfm_delivery_boy"           => array( 'label' => __( 'Delivery Boy', 'wc-frontend-manager-delivery' ), 'type' => 'select', 'options' => $delivery_users, 'class' => 'wcfm-select wcfm_popup_input', 'label_class' => 'shipment_tracking_input wcfm_popup_label', 'value' => $wcfm_delivery_boy ),
																												"wcfm_tracking_order_id"      => array( 'type' => 'hidden', 'value' => $order_id ),
																												"wcfm_tracking_product_id"    => array( 'type' => 'hidden', 'value' => $product_id ),
																												"wcfm_tracking_order_item_id" => array( 'type' => 'hidden', 'value' => $order_item_id ),
																											), $order_id, $order_item_id ) );
		?>
		<div class="wcfm-message"></div>
		<input type="submit" id="wcfm_tracking_button" name="wcfm_tracking_button" class="wcfm_submit_button wcfm_popup_button" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" />
	</form>
</div>