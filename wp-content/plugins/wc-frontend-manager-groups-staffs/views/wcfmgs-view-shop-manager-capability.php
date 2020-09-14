<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Shop Manager Capability Settings View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/view
 * @version   1.0.0
 */

global $WCFM;

if( !current_user_can( 'manage_options' ) ) {
	wcfm_restriction_message_show( "Capability" );
	return;
}

$wcfm_shop_manager_capability_options = (array) get_option( 'wcfm_shop_manager_capability_options' );

// Product Capabilities
$submit_products = ( isset( $wcfm_shop_manager_capability_options['submit_products'] ) ) ? $wcfm_shop_manager_capability_options['submit_products'] : 'no';
$add_products = ( isset( $wcfm_shop_manager_capability_options['add_products'] ) ) ? $wcfm_shop_manager_capability_options['add_products'] : 'no';
$publish_products = ( isset( $wcfm_shop_manager_capability_options['publish_products'] ) ) ? $wcfm_shop_manager_capability_options['publish_products'] : 'no';
$edit_live_products = ( isset( $wcfm_shop_manager_capability_options['edit_live_products'] ) ) ? $wcfm_shop_manager_capability_options['edit_live_products'] : 'no';
$publish_live_products = ( isset( $wcfm_shop_manager_capability_options['publish_live_products'] ) ) ? $wcfm_shop_manager_capability_options['publish_live_products'] : 'no';
$delete_products = ( isset( $wcfm_shop_manager_capability_options['delete_products'] ) ) ? $wcfm_shop_manager_capability_options['delete_products'] : 'no';

$simple = ( isset( $wcfm_shop_manager_capability_options['simple'] ) ) ? $wcfm_shop_manager_capability_options['simple'] : 'no';
$variable = ( isset( $wcfm_shop_manager_capability_options['variable'] ) ) ? $wcfm_shop_manager_capability_options['variable'] : 'no';
$grouped = ( isset( $wcfm_shop_manager_capability_options['grouped'] ) ) ? $wcfm_shop_manager_capability_options['grouped'] : 'no';
$external = ( isset( $wcfm_shop_manager_capability_options['external'] ) ) ? $wcfm_shop_manager_capability_options['external'] : 'no';

$inventory = ( isset( $wcfm_shop_manager_capability_options['inventory'] ) ) ? $wcfm_shop_manager_capability_options['inventory'] : 'no';
$shipping = ( isset( $wcfm_shop_manager_capability_options['shipping'] ) ) ? $wcfm_shop_manager_capability_options['shipping'] : 'no';
$taxes = ( isset( $wcfm_shop_manager_capability_options['taxes'] ) ) ? $wcfm_shop_manager_capability_options['taxes'] : 'no';
$linked = ( isset( $wcfm_shop_manager_capability_options['linked'] ) ) ? $wcfm_shop_manager_capability_options['linked'] : 'no';
$attributes = ( isset( $wcfm_shop_manager_capability_options['attributes'] ) ) ? $wcfm_shop_manager_capability_options['attributes'] : 'no';
$advanced = ( isset( $wcfm_shop_manager_capability_options['advanced'] ) ) ? $wcfm_shop_manager_capability_options['advanced'] : 'no';
$catalog = ( isset( $wcfm_shop_manager_capability_options['catalog'] ) ) ? $wcfm_shop_manager_capability_options['catalog'] : 'no';

$featured_img = ( isset( $wcfm_shop_manager_capability_options['featured_img'] ) ) ? $wcfm_shop_manager_capability_options['featured_img'] : 'no';
$gallery_img = ( isset( $wcfm_shop_manager_capability_options['gallery_img'] ) ) ? $wcfm_shop_manager_capability_options['gallery_img'] : 'no';
$category = ( isset( $wcfm_shop_manager_capability_options['category'] ) ) ? $wcfm_shop_manager_capability_options['category'] : 'no';
$add_category = ( isset( $wcfm_shop_manager_capability_options['add_category'] ) ) ? $wcfm_shop_manager_capability_options['add_category'] : 'no';
$tags = ( isset( $wcfm_shop_manager_capability_options['tags'] ) ) ? $wcfm_shop_manager_capability_options['tags'] : 'no';
$addons = ( isset( $wcfm_shop_manager_capability_options['addons'] ) ) ? $wcfm_shop_manager_capability_options['addons'] : 'no';
$toolset_types = ( isset( $wcfm_shop_manager_capability_options['toolset_types'] ) ) ? $wcfm_shop_manager_capability_options['toolset_types'] : 'no';
$acf_fields = ( isset( $wcfm_shop_manager_capability_options['acf_fields'] ) ) ? $wcfm_shop_manager_capability_options['acf_fields'] : 'no';
$mappress = ( isset( $wcfm_shop_manager_capability_options['mappress'] ) ) ? $wcfm_shop_manager_capability_options['mappress'] : 'no';

$add_attribute = ( isset( $wcfm_shop_manager_capability_options['add_attribute'] ) ) ? $wcfm_shop_manager_capability_options['add_attribute'] : 'no';
$add_attribute_term = ( isset( $wcfm_shop_manager_capability_options['add_attribute_term'] ) ) ? $wcfm_shop_manager_capability_options['add_attribute_term'] : 'no';
$delete_media = ( isset( $wcfm_shop_manager_capability_options['delete_media'] ) ) ? $wcfm_shop_manager_capability_options['delete_media'] : 'no';
$rich_editor = ( isset( $wcfm_shop_manager_capability_options['rich_editor'] ) ) ? $wcfm_shop_manager_capability_options['rich_editor'] : 'no';
$featured_product = ( isset( $wcfm_shop_manager_capability_options['featured_product'] ) ) ? $wcfm_shop_manager_capability_options['featured_product'] : 'no';
$duplicate_product = ( isset( $wcfm_shop_manager_capability_options['duplicate_product'] ) ) ? $wcfm_shop_manager_capability_options['duplicate_product'] : 'no';
$product_import = ( isset( $wcfm_shop_manager_capability_options['product_import'] ) ) ? $wcfm_shop_manager_capability_options['product_import'] : 'no';
$product_export = ( isset( $wcfm_shop_manager_capability_options['product_export'] ) ) ? $wcfm_shop_manager_capability_options['product_export'] : 'no';
$product_quick_edit = ( isset( $wcfm_shop_manager_capability_options['product_quick_edit'] ) ) ? $wcfm_shop_manager_capability_options['product_quick_edit'] : 'no';
$product_bulk_edit = ( isset( $wcfm_shop_manager_capability_options['product_bulk_edit'] ) ) ? $wcfm_shop_manager_capability_options['product_bulk_edit'] : 'no';
$stock_manager = ( isset( $wcfm_shop_manager_capability_options['stock_manager'] ) ) ? $wcfm_shop_manager_capability_options['stock_manager'] : 'no';

$manage_sku = ( isset( $wcfm_shop_manager_capability_options['manage_sku'] ) ) ? $wcfm_shop_manager_capability_options['manage_sku'] : 'no';
$manage_price = ( isset( $wcfm_shop_manager_capability_options['manage_price'] ) ) ? $wcfm_shop_manager_capability_options['manage_price'] : 'no';
$manage_sales_price = ( isset( $wcfm_shop_manager_capability_options['manage_sales_price'] ) ) ? $wcfm_shop_manager_capability_options['manage_sales_price'] : 'no';
$manage_sales_scheduling = ( isset( $wcfm_shop_manager_capability_options['manage_sales_scheduling'] ) ) ? $wcfm_shop_manager_capability_options['manage_sales_scheduling'] : 'no';
$manage_excerpt = ( isset( $wcfm_shop_manager_capability_options['manage_excerpt'] ) ) ? $wcfm_shop_manager_capability_options['manage_excerpt'] : 'no';
$manage_description = ( isset( $wcfm_shop_manager_capability_options['manage_description'] ) ) ? $wcfm_shop_manager_capability_options['manage_description'] : 'no';

$knowledgebase = ( isset( $wcfm_shop_manager_capability_options['knowledgebase'] ) ) ? $wcfm_shop_manager_capability_options['knowledgebase'] : 'no';
$notice = ( isset( $wcfm_shop_manager_capability_options['notice'] ) ) ? $wcfm_shop_manager_capability_options['notice'] : 'no';
$notice_reply = ( isset( $wcfm_shop_manager_capability_options['notice_reply'] ) ) ? $wcfm_shop_manager_capability_options['notice_reply'] : 'no';
$notification = ( isset( $wcfm_shop_manager_capability_options['notification'] ) ) ? $wcfm_shop_manager_capability_options['notification'] : 'no';
$direct_message = ( isset( $wcfm_shop_manager_capability_options['direct_message'] ) ) ? $wcfm_shop_manager_capability_options['direct_message'] : 'no';
$enquiry = ( isset( $wcfm_shop_manager_capability_options['enquiry'] ) ) ? $wcfm_shop_manager_capability_options['enquiry'] : 'no';
$profile = ( isset( $wcfm_shop_manager_capability_options['profile'] ) ) ? $wcfm_shop_manager_capability_options['profile'] : 'no';

// Miscellaneous Capabilities
$associate_listings = ( isset( $wcfm_shop_manager_capability_options['associate_listings'] ) ) ? $wcfm_shop_manager_capability_options['associate_listings'] : 'no';

// Article Capabilities
$submit_articles = ( isset( $wcfm_shop_manager_capability_options['submit_articles'] ) ) ? $wcfm_shop_manager_capability_options['submit_articles'] : 'no';
$add_articles = ( isset( $wcfm_shop_manager_capability_options['add_articles'] ) ) ? $wcfm_shop_manager_capability_options['add_articles'] : 'no';
$publish_articles = ( isset( $wcfm_shop_manager_capability_options['publish_articles'] ) ) ? $wcfm_shop_manager_capability_options['publish_articles'] : 'no';
$edit_live_articles = ( isset( $wcfm_shop_manager_capability_options['edit_live_articles'] ) ) ? $wcfm_shop_manager_capability_options['edit_live_articles'] : 'no';
$publish_live_articles = ( isset( $wcfm_shop_manager_capability_options['publish_live_articles'] ) ) ? $wcfm_shop_manager_capability_options['publish_live_articles'] : 'no';
$delete_articles = ( isset( $wcfm_shop_manager_capability_options['delete_articles'] ) ) ? $wcfm_shop_manager_capability_options['delete_articles'] : 'no';

// Coupons Capabilities
$submit_coupons = ( isset( $wcfm_shop_manager_capability_options['submit_coupons'] ) ) ? $wcfm_shop_manager_capability_options['submit_coupons'] : 'no';
$add_coupons = ( isset( $wcfm_shop_manager_capability_options['add_coupons'] ) ) ? $wcfm_shop_manager_capability_options['add_coupons'] : 'no';
$publish_coupons = ( isset( $wcfm_shop_manager_capability_options['publish_coupons'] ) ) ? $wcfm_shop_manager_capability_options['publish_coupons'] : 'no';
$edit_live_coupons = ( isset( $wcfm_shop_manager_capability_options['edit_live_coupons'] ) ) ? $wcfm_shop_manager_capability_options['edit_live_coupons'] : 'no';
$publish_live_coupons = ( isset( $wcfm_shop_manager_capability_options['publish_live_coupons'] ) ) ? $wcfm_shop_manager_capability_options['publish_live_coupons'] : 'no';
$delete_coupons = ( isset( $wcfm_shop_manager_capability_options['delete_coupons'] ) ) ? $wcfm_shop_manager_capability_options['delete_coupons'] : 'no';
$free_shipping_coupons = ( isset( $wcfm_shop_manager_capability_options['free_shipping_coupons'] ) ) ? $wcfm_shop_manager_capability_options['free_shipping_coupons'] : 'no';

// Bookings Capabiliries
$manage_booking = ( isset( $wcfm_shop_manager_capability_options['manage_booking'] ) ) ? $wcfm_shop_manager_capability_options['manage_booking'] : 'no';
$manual_booking = ( isset( $wcfm_shop_manager_capability_options['manual_booking'] ) ) ? $wcfm_shop_manager_capability_options['manual_booking'] : 'no';
$manage_resource = ( isset( $wcfm_shop_manager_capability_options['manage_resource'] ) ) ? $wcfm_shop_manager_capability_options['manage_resource'] : 'no';
$booking_list = ( isset( $wcfm_shop_manager_capability_options['booking_list'] ) ) ? $wcfm_shop_manager_capability_options['booking_list'] : 'no';
$booking_calendar = ( isset( $wcfm_shop_manager_capability_options['booking_calendar'] ) ) ? $wcfm_shop_manager_capability_options['booking_calendar'] : 'no';

// Appointment Capabiliries
$manage_appointment = ( isset( $wcfm_shop_manager_capability_options['manage_appointment'] ) ) ? $wcfm_shop_manager_capability_options['manage_appointment'] : 'no';
$manual_appointment = ( isset( $wcfm_shop_manager_capability_options['manual_appointment'] ) ) ? $wcfm_shop_manager_capability_options['manual_appointment'] : 'no';
$manage_appointment_staff = ( isset( $wcfm_shop_manager_capability_options['manage_appointment_staff'] ) ) ? $wcfm_shop_manager_capability_options['manage_appointment_staff'] : 'no';
$appointment_list = ( isset( $wcfm_shop_manager_capability_options['appointment_list'] ) ) ? $wcfm_shop_manager_capability_options['appointment_list'] : 'no';
$appointment_calendar = ( isset( $wcfm_shop_manager_capability_options['appointment_calendar'] ) ) ? $wcfm_shop_manager_capability_options['appointment_calendar'] : 'no';

// Subscription Capabiliries
$manage_subscription = ( isset( $wcfm_shop_manager_capability_options['manage_subscription'] ) ) ? $wcfm_shop_manager_capability_options['manage_subscription'] : 'no';
$subscription_list = ( isset( $wcfm_shop_manager_capability_options['subscription_list'] ) ) ? $wcfm_shop_manager_capability_options['subscription_list'] : 'no';
$subscription_details = ( isset( $wcfm_shop_manager_capability_options['subscription_details'] ) ) ? $wcfm_shop_manager_capability_options['subscription_details'] : 'no';
$subscription_status_update = ( isset( $wcfm_shop_manager_capability_options['subscription_status_update'] ) ) ? $wcfm_shop_manager_capability_options['subscription_status_update'] : 'no';
$subscription_schedule_update = ( isset( $wcfm_shop_manager_capability_options['subscription_schedule_update'] ) ) ? $wcfm_shop_manager_capability_options['subscription_schedule_update'] : 'no';

// Orders Capabilities
$view_orders  = ( isset( $wcfm_shop_manager_capability_options['view_orders'] ) ) ? $wcfm_shop_manager_capability_options['view_orders'] : 'no';
$order_status_update  = ( isset( $wcfm_shop_manager_capability_options['order_status_update'] ) ) ? $wcfm_shop_manager_capability_options['order_status_update'] : 'no';
$view_order_details = ( isset( $wcfm_shop_manager_capability_options['view_order_details'] ) ) ? $wcfm_shop_manager_capability_options['view_order_details'] : 'no';
$manage_order  = ( isset( $wcfm_shop_manager_capability_options['manage_order'] ) ) ? $wcfm_shop_manager_capability_options['manage_order'] : 'no';
$delete_order  = ( isset( $wcfm_shop_manager_capability_options['delete_order'] ) ) ? $wcfm_shop_manager_capability_options['delete_order'] : 'no';
$view_comments  = ( isset( $wcfm_shop_manager_capability_options['view_comments'] ) ) ? $wcfm_shop_manager_capability_options['view_comments'] : 'no';
$submit_comments  = ( isset( $wcfm_shop_manager_capability_options['submit_comments'] ) ) ? $wcfm_shop_manager_capability_options['submit_comments'] : 'no';
$export_csv  = ( isset( $wcfm_shop_manager_capability_options['export_csv'] ) ) ? $wcfm_shop_manager_capability_options['export_csv'] : 'no';
$view_commission  = ( isset( $wcfm_shop_manager_capability_options['view_commission'] ) ) ? $wcfm_shop_manager_capability_options['view_commission'] : 'no';

$pdf_invoice = ( isset( $wcfm_shop_manager_capability_options['pdf_invoice'] ) ) ? $wcfm_shop_manager_capability_options['pdf_invoice'] : 'no';
$pdf_packing_slip = ( isset( $wcfm_shop_manager_capability_options['pdf_packing_slip'] ) ) ? $wcfm_shop_manager_capability_options['pdf_packing_slip'] : 'no';

// Customers Capabilities
$manage_customers      = ( isset( $wcfm_shop_manager_capability_options['manage_customers'] ) ) ? $wcfm_shop_manager_capability_options['manage_customers'] : 'no';
$add_customers         = ( isset( $wcfm_shop_manager_capability_options['add_customers'] ) ) ? $wcfm_shop_manager_capability_options['add_customers'] : 'no';
$view_customers        = ( isset( $wcfm_shop_manager_capability_options['view_customers'] ) ) ? $wcfm_shop_manager_capability_options['view_customers'] : 'no';
$edit_customers        = ( isset( $wcfm_shop_manager_capability_options['edit_customers'] ) ) ? $wcfm_shop_manager_capability_options['edit_customers'] : 'no';
$delete_customers      = ( isset( $wcfm_shop_manager_capability_options['delete_customers'] ) ) ? $wcfm_shop_manager_capability_options['delete_customers'] : 'no';
$view_customers_orders = ( isset( $wcfm_shop_manager_capability_options['view_customers_orders'] ) ) ? $wcfm_shop_manager_capability_options['view_customers_orders'] : 'no';
$view_customers_name   = ( isset( $wcfm_shop_manager_capability_options['view_name'] ) ) ? $wcfm_shop_manager_capability_options['view_name'] : 'no';
$view_customers_email  = ( isset( $wcfm_shop_manager_capability_options['view_email'] ) ) ? $wcfm_shop_manager_capability_options['view_email'] : 'no';
$view_billing_details  = ( isset( $wcfm_shop_manager_capability_options['view_billing_details'] ) ) ? $wcfm_shop_manager_capability_options['view_billing_details'] : 'no';
$view_shipping_details =  ( isset( $wcfm_shop_manager_capability_options['view_shipping_details'] ) ) ? $wcfm_shop_manager_capability_options['view_shipping_details'] : 'no';

$view_reports  = ( isset( $wcfm_shop_manager_capability_options['view_reports'] ) ) ? $wcfm_shop_manager_capability_options['view_reports'] : 'no';

$delivery      = ( isset( $wcfm_shop_manager_capability_options['delivery'] ) ) ? $wcfm_shop_manager_capability_options['delivery'] : 'no';
$delivery_time = ( isset( $wcfm_shop_manager_capability_options['delivery_time'] ) ) ? $wcfm_shop_manager_capability_options['delivery_time'] : 'no';

$shipping_tracking = ( isset( $wcfm_shop_manager_capability_options['shipping_tracking'] ) ) ? $wcfm_shop_manager_capability_options['shipping_tracking'] : 'no';

$support_ticket = ( isset( $wcfm_shop_manager_capability_options['support_ticket'] ) ) ? $wcfm_shop_manager_capability_options['support_ticket'] : 'no';
$support_ticket_manage = ( isset( $wcfm_shop_manager_capability_options['support_ticket_manage'] ) ) ? $wcfm_shop_manager_capability_options['support_ticket_manage'] : 'no';

$sm_wpadmin = ( isset( $wcfm_shop_manager_capability_options['sm_wpadmin'] ) ) ? $wcfm_shop_manager_capability_options['sm_wpadmin'] : 'no';

$address = ( isset( $wcfm_shop_manager_capability_options['address'] ) ) ? $wcfm_shop_manager_capability_options['address'] : 'no';
$social = ( isset( $wcfm_shop_manager_capability_options['social'] ) ) ? $wcfm_shop_manager_capability_options['social'] : 'no';

$manage_settings = ( isset( $wcfm_shop_manager_capability_options['manage_settings'] ) ) ? $wcfm_shop_manager_capability_options['manage_settings'] : 'no';
$capability_controller = ( isset( $wcfm_shop_manager_capability_options['capability_controller'] ) ) ? $wcfm_shop_manager_capability_options['capability_controller'] : 'no';

$manage_groups = ( isset( $wcfm_shop_manager_capability_options['manage_groups'] ) ) ? $wcfm_shop_manager_capability_options['manage_groups'] : 'no';
$manage_managers = ( isset( $wcfm_shop_manager_capability_options['manage_managers'] ) ) ? $wcfm_shop_manager_capability_options['manage_managers'] : 'no';
$manage_staffs = ( isset( $wcfm_shop_manager_capability_options['manage_staffs'] ) ) ? $wcfm_shop_manager_capability_options['manage_staffs'] : 'no';

$analytics = ( isset( $wcfm_shop_manager_capability_options['analytics'] ) ) ? $wcfm_shop_manager_capability_options['analytics'] : 'no';

?>

<div class="capability_head_message"><?php _e( "Configure what to hide from all Shop Managers", 'wc-frontend-manager-groups-staffs' ); ?></div>
<div class="vendor_capability">

	<div class="vendor_product_capability">
		<div class="vendor_capability_heading"><h3><?php _e( 'Products', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_products', array(
																																																										 "submit_products" => array('label' => __('Manage Products', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[submit_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_products),
																																																										 "add_products" => array('label' => __('Add Products', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[add_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_products),
																																																										 "publish_products" => array('label' => __('Publish Products', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_products),
																																																										 "edit_live_products" => array('label' => __('Edit Live Products', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[edit_live_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_live_products),
																																																										 "publish_live_products" => array('label' => __('Auto Publish Live Products', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_live_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_products),
																																																										 "delete_products" => array('label' => __('Delete Products', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[delete_products]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_products)
																							) ) );
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Types', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_types', array(
																																																									  "simple" => array('label' => __('Simple', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[simple]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $simple),
																																																										"variable" => array('label' => __('Variable', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[variable]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $variable),
																																																										"grouped" => array('label' => __('Grouped', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[grouped]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $grouped),
																																																										"external" => array('label' => __('External / Affiliate', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[external]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $external),
																							), 'wcfm_shop_manager_capability_options', $wcfm_shop_manager_capability_options ) );
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Panels', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_product_panels', array(
																																																										 "inventory" => array('label' => __('Inventory', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[inventory]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $inventory),
																																																										 "shipping" => array('label' => __('Shipping', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[shipping]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $shipping),
																																																										 "taxes" => array('label' => __('Taxes', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[taxes]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $taxes),
																																																										 "linked" => array('label' => __('Linked', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[linked]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $linked),
																																																										 "attributes" => array('label' => __('Attributes', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[attributes]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $attributes),
																																																										 "advanced" => array('label' => __('Advanced', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[advanced]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $advanced),
																																																										 "catalog" => array('label' => __('Catalog', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[catalog]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $catalog),
																							) ) );
		
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Sections', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_product_sections', array(
																																																										 "featured_img" => array('label' => __('Featured Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[featured_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_img),
																																																										 "gallery_img" => array('label' => __('Gallery Image', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[gallery_img]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $gallery_img),
																																																										 "category" => array('label' => __('Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $category),
																																																										 "add_category" => array('label' => __('Add Category', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[add_category]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_category),
																							) ) );
		
		$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
		if( !empty( $product_taxonomies ) ) {
			foreach( $product_taxonomies as $product_taxonomy ) {
				if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) ) {
					if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
						// Fetching Saved Values
						$allow_custom_taxonomie    = ( !empty( $wcfm_shop_manager_capability_options[$product_taxonomy->name] ) ) ? $wcfm_shop_manager_capability_options[$product_taxonomy->name] : 'no';
						$allow_add_taxonomie     = ( !empty( $wcfm_shop_manager_capability_options['add_'.$product_taxonomy->name] ) ) ? $wcfm_shop_manager_capability_options['add_'.$product_taxonomy->name] : 'no';
						
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_product_sections', array(
																																																										 $product_taxonomy->name => array('label' => __($product_taxonomy->label, 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options['.$product_taxonomy->name.']','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $allow_custom_taxonomie ),
																																																										 "add_".$product_taxonomy->name => array('label' => __('Add', 'wc-frontend-manager-ultimate') . ' ' . __($product_taxonomy->label, 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[add_'.$product_taxonomy->name.']','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $allow_add_taxonomie ),
																							) ) );
					}
				}
			}
		}
		
		
		
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_product_sections', array(
																																																										 "tags" => array('label' => __('Tags', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[tags]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $tags),
																																																										 "addons" => array('label' => __('Add-ons', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[addons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $addons),
																																																										 "toolset_types" => array('label' => __('Toolset Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[toolset_types]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $toolset_types),
																																																										 "acf_fields" => array('label' => __('ACF Fields', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[acf_fields]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $acf_fields),
																																																										 "mappress" => array('label' => __('Location', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[mappress]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $mappress),
																							) ) );
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Insights', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_vendor_product_insights', array(
																																																										 "add_attribute" => array('label' => __('Add Attribute', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[add_attribute]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute),
																																																										 "add_attribute_term" => array('label' => __('Add Attribute Term', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[add_attribute_term]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_attribute_term),
																																																									   "delete_media" => array('label' => __('Delete Media', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[delete_media]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_media),
																																																										 "rich_editor" => array('label' => __('Rich Editor', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[rich_editor]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $rich_editor),
																																																										 "featured_product" => array('label' => __('Featured Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[featured_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $featured_product),
																																																										 "duplicate_product" => array('label' => __('Duplicate Product', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[duplicate_product]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $duplicate_product),
																																																										 "product_import" => array('label' => __('Import', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[product_import]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_import),
																																																										 "product_export" => array('label' => __('Export', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[product_export]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_export),
																																																										 "product_quick_edit" => array('label' => __('Quick Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[product_quick_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_quick_edit),
																																																										 "product_bulk_edit" => array('label' => __('Bulk Edit', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[product_bulk_edit]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $product_bulk_edit),
																																																										 "stock_manager" => array('label' => __('Stock Manager', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[stock_manager]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $stock_manager),
																							) ) );
		?>
		
		<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
			<div class="wcfm_clearfix"></div>
			<div class="vendor_capability_sub_heading"><h3><?php _e( 'Fields', 'wc-frontend-manager-ultimate' ); ?></h3></div>
			
			<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_vendor_product_fields', array(
																																																											 "manage_sku" => array('label' => __('SKU', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_sku]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sku),
																																																											 "manage_price" => array('label' => __('Price', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_price]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_price),
																																																											 "manage_sales_price" => array('label' => __('Sale Price', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_sales_price]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sales_price),
																																																											 "manage_sales_scheduling" => array('label' => __('Sales Schedule', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[manage_sales_scheduling]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_sales_scheduling),
																																																											 "manage_excerpt" => array('label' => __('Short Description', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_excerpt]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_excerpt),
																																																											 "manage_description" => array('label' => __('Description', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_description]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_description),
																								) ) );
			?>
		<?php } ?>
	</div>
	
	<div class="vendor_other_capability">
	
		<div class="vendor_capability_heading"><h3><?php _e( 'Access', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_access', array(
																															 "sm_wpadmin" => array('label' => __('Backend Access', 'wc-frontend-manager') . ' (wp-admin)', 'name' => 'wcfm_shop_manager_capability_options[sm_wpadmin]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $sm_wpadmin),
																								) ) );
		?>
	
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Miscellaneous', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		if( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_listings', array(  "associate_listings" => array('label' => __('Associate Listings', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[associate_listings]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'desc' => __( 'by WP Job Manager.', 'wc-frontend-manager' ), 'dfvalue' => $associate_listings),
																									) ) );
		}
		?>
		
		<?php if( apply_filters( 'wcfm_is_pref_article', true ) ) { ?>
			<div class="wcfm_clearfix"></div>
			<div class="vendor_capability_sub_heading"><h3><?php _e( 'Articles', 'wc-frontend-manager' ); ?></h3></div>
			<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_vendor_articles', array(
																																																								 "submit_articles" => array('label' => __('Manage Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[submit_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_articles),
																																																								 "add_articles" => array('label' => __('Add Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[add_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_articles),
																																																								 "publish_articles" => array('label' => __('Publish Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_articles),
																																																								 "edit_live_articles" => array('label' => __('Edit Live Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[edit_live_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_live_articles),
																																																								 "publish_live_articles" => array('label' => __('Auto Publish Live Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_live_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_articles),
																																																								 "delete_articles" => array('label' => __('Delete Articles', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[delete_articles]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_articles)
																								) ) );
			?>
		<?php } ?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Coupons', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_coupons', array(
																																																							 "submit_coupons" => array('label' => __('Manage Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[submit_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_coupons),
																																																							 "add_coupons" => array('label' => __('Add Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[add_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_coupons),
																																																							 "publish_coupons" => array('label' => __('Publish Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_coupons),
																																																							 "edit_live_coupons" => array('label' => __('Edit Live Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[edit_live_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_live_coupons),
																																																							 "publish_live_coupons" => array('label' => __('Auto Publish Live Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[publish_live_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $publish_live_coupons),
																																																							 "delete_coupons" => array('label' => __('Delete Coupons', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[delete_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_coupons),
																																																							 "free_shipping_coupons" => array('label' => __('Allow Free Shipping', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[free_shipping_coupons]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $free_shipping_coupons)
																							) ) );
		?>
		
		<?php
		if( ( wcfm_is_booking() || WCFM_Dependencies::wcfm_tych_booking_active_check() ) && WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			?>
			<div class="wcfm_clearfix"></div>
			<div class="vendor_capability_sub_heading"><h3><?php _e( 'Bookings', 'wc-frontend-manager' ); ?></h3></div>
			<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_bookings', array(
																																																			 "manage_booking" => array('label' => __('Manage Bookings', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_booking]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_booking),
																																																			 "manual_booking" => array('label' => __('Manual Booking', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[manual_booking]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manual_booking),
																																																			 "manage_resource" => array('label' => __('Manage Resource', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[manage_resource]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_resource),
																																																			 "booking_list" => array('label' => __('Bookings List', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[booking_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $booking_list),
																																																			 "booking_calendar" => array('label' => __('Bookings Calendar', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[booking_calendar]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $booking_calendar),
																						) ) );
		}
		?>
		
		<?php
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			if( WCFMu_Dependencies::wcfm_wc_appointments_active_check() ) {
				?>
				<div class="wcfm_clearfix"></div>
				<div class="vendor_capability_sub_heading"><h3><?php _e( 'Appointments', 'wc-frontend-manager' ); ?></h3></div>
				<?php
				$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_appointment', array(
																																																			 "manage_appointment" => array('label' => __('Manage Appointments', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_appointment]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_appointment),
																																																			 "manual_appointment" => array('label' => __('Manual Appointment', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[manual_appointment]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manual_appointment),
																																																			 "manage_appointment_staff" => array('label' => __('Manage Staff', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[manage_appointment_staff]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_appointment_staff),
																																																			 "appointment_list" => array('label' => __('Appointments List', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[appointment_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $appointment_list),
																																																			 "appointment_calendar" => array('label' => __('Appointments Calendar', 'wc-frontend-manager-groups-staffs') , 'name' => 'wcfm_shop_manager_capability_options[appointment_calendar]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $appointment_calendar),
																						) ) );
			}
		}
		?>
		
		<?php
		if( wcfm_is_subscription() && WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			?>
			<div class="wcfm_clearfix"></div>
			<div class="vendor_capability_sub_heading"><h3><?php _e( 'Subscriptions', 'wc-frontend-manager' ); ?></h3></div>
			<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_subscriptions', array(
																																																			 "manage_subscription" => array('label' => __('Manage Subscriptions', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_subscription]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_subscription),
																																																			 "subscription_list" => array('label' => __('Subscriptions List', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[subscription_list]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_list),
																																																			 "subscription_details" => array('label' => __('Subscription Details', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[subscription_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_details),
																																																			 "subscription_status_update" => array('label' => __('Subscription Status Update', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[subscription_status_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_status_update),
																																																			 "subscription_schedule_update" => array('label' => __('Subscription Schedule Update', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[subscription_schedule_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $subscription_schedule_update),
																						) ) );
		}
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Orders', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_orders', array(  
																																																										"view_orders" => array('label' => __('View Orders', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_orders]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_orders),
																																																										 "order_status_update" => array('label' => __('Status Update', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[order_status_update]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $order_status_update),
																																																										 "view_order_details" => array('label' => __('View Details', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_order_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_order_details),
																																																										 "manage_order" => array('label' => __('Add/Edit Order', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_order]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_order),
																																																										 "delete_order" => array('label' => __('Delete Order', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[delete_order]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_order),
																																																										 "view_comments" => array('label' => __('View Comments', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_comments]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_comments),
																																																										 "submit_comments" => array('label' => __('Submit Comments', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[submit_comments]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $submit_comments),
																																																										 "export_csv" => array('label' => __('Export CSV', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[export_csv]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $export_csv),
																																																										 "view_commission" => array('label' => __('View Commission', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_commission]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_commission),
																								 ) ) );
		if( WCFM_Dependencies::wcfmu_plugin_active_check() ) {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_invoice', array(  
																																	 "pdf_invoice" => array('label' => __('PDF Invoice', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[pdf_invoice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $pdf_invoice),
																																	 "pdf_packing_slip" => array('label' => __('PDF Packing Slip', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[pdf_packing_slip]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $pdf_packing_slip),
																										) ) );
		} else {
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_invoice', array(
																																 "pdf_invoice" => array('label' => __('PDF Invoice', 'wc-frontend-manager'), 'name' => 'wcfm_shop_manager_capability_options[pdf_invoice]', 'type' => 'checkboxoffon', 'custom_tags' => array( 'disabled' => 'disabled' ), 'desc' => __( 'Install WCFM Ultimate to enable this feature.', 'wc-frontend-manager' ), 'class' => 'wcfm-checkbox wcfm-checkbox-disabled wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $pdf_invoice),
																									) ) );
		}
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Customers', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_customers', array("manage_customers" => array('label' => __('Manage Customers', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[manage_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_customers),
																																																				 "add_customers" => array('label' => __('Add Customer', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[add_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $add_customers),
																																																				 "view_customers" => array('label' => __('View Customer', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers),
																																																				 "edit_customers" => array('label' => __('Edit Customer', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[edit_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $edit_customers),
																																																				 "delete_customers" => array('label' => __('Delete Customer', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[delete_customers]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delete_customers),
																																																				 "view_customers_orders" => array('label' => __('View Customer Orders', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_customers_orders]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_orders),
																																																				 "view_name" => array('label' => __('View Customer Name', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_name]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_name),
																																																				 "view_email" => array('label' => __('View Customer Email', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_email]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_customers_email),
																																																				 "view_billing_details" => array('label' => __('Billing Address', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_billing_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_billing_details),
																																																				 "view_shipping_details" => array('label' => __('Shipping Address', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_shipping_details]','type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_shipping_details),
																							) ) );
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Reports', 'wc-frontend-manager' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_reports', array("view_reports" => array('label' => __('View Reports', 'wc-frontend-manager') , 'name' => 'wcfm_shop_manager_capability_options[view_reports]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $view_reports),
																								 ) ) );
		?>
		
		<?php if( WCFM_Dependencies::wcfmd_plugin_active_check() ) { ?>
			<?php if( apply_filters( 'wcfm_is_pref_delivery', true ) || apply_filters( 'wcfm_is_pref_delivery_time', true ) ) { ?>
				<div class="wcfm_clearfix"></div>
				<div class="vendor_capability_sub_heading"><h3><?php _e( 'Delivery', 'wc-frontend-manager-delivery' ); ?></h3></div>
				
				<?php
				  if( apply_filters( 'wcfm_is_pref_delivery', true ) ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_delivery_person', array(  
																																				 "delivery" => array('label' => __('Delivery Person', 'wc-frontend-manager-delivery') , 'name' => 'wcfm_shop_manager_capability_options[delivery]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delivery),
																													) ) );
					}
					
					if( apply_filters( 'wcfm_is_pref_delivery_time', true ) ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_delivery_time', array(  
																																				 "delivery_time" => array('label' => __('Delivery Time', 'wc-frontend-manager-delivery') , 'name' => 'wcfm_shop_manager_capability_options[delivery_time]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $delivery_time),
																													) ) );
					}
				?>
			<?php } ?>
		<?php } ?>
		
		<?php if( WCFM_Dependencies::wcfmu_plugin_active_check() ) { ?>
			<?php if( apply_filters( 'wcfm_is_pref_shipment_tracking', true ) ) { ?>
				<div class="wcfm_clearfix"></div>
				<div class="vendor_capability_sub_heading"><h3><?php _e( 'Shipping Tracking', 'wc-frontend-manager-ultimate' ); ?></h3></div>
				
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_shipping_tracking', array(  
																																			 "shipping_tracking" => array('label' => __('Allow', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[shipping_tracking]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $shipping_tracking),
																												) ) );
				?>
			<?php } ?>
		
			<?php if( apply_filters( 'wcfm_is_pref_support', true ) ) { ?>
				<div class="wcfm_clearfix"></div>
				<div class="vendor_capability_sub_heading"><h3><?php _e( 'Support Ticket', 'wc-frontend-manager-ultimate' ); ?></h3></div>
				
				<?php
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_support_ticket', array(  
																																			 "support_ticket" => array('label' => __('View / Manage', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[support_ticket]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $support_ticket),
																																			 "support_ticket_manage" => array('label' => __('Allow Reply', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[support_ticket_manage]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $support_ticket_manage),
																												) ) );
				?>
			<?php } ?>
		<?php } ?>
			
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Header Panels', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_header_panel', array(  
																																 "knowledgebase" => array('label' => __('Knowledgebase', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[knowledgebase]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $knowledgebase),
																																 "notice" => array('label' => __('Notice', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[notice]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice),
																																 "notice_reply" => array('label' => __('Topic Reply', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[notice_reply]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notice_reply),
																																 "notification" => array('label' => __('Notification', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[notification]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $notification),
																																 "direct_message" => array('label' => __('Direct Message', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[direct_message]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $direct_message),
																																 "enquiry" => array('label' => __('Enquiry', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[enquiry]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $enquiry),
																																 "profile" => array('label' => __('Profile', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[profile]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $profile),
																									) ) );
		
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Profile', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
			$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_profile', array(  
																																	 "address" => array('label' => __('Address', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[address]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $address),
																																	 "social" => array('label' => __('Social', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[social]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $social),
																										) ) );
			
		?>
			
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Settings', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_settings', array(  
																																 "manage_settings" => array('label' => __('Store Settings', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[manage_settings]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_settings),
																																 "capability_controller" => array('label' => __('Capability Controller', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[capability_controller]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $capability_controller)
																								) ) );
		
		?>
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Groups & Staffs', 'wc-frontend-manager-ultimate' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_groups', array(  
																																 "manage_groups" => array('label' => __('Manage Group', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[manage_groups]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_groups),
																																 "manage_managers" => array('label' => __('Manage Managers', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[manage_managers]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_managers),
																																 "manage_staffs" => array('label' => __('Manage Staff', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_shop_manager_capability_options[manage_staffs]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_staffs),
																									) ) );
		?>
		
		<div class="wcfm_clearfix"></div>
		<div class="vendor_capability_sub_heading"><h3><?php _e( 'Analytics', 'wc-frontend-manager-analytics' ); ?></h3></div>
		
		<?php
		$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_capability_settings_fields_shop_manager_analytics', array(
																															 "analytics" => array('label' => __('Analytics', 'wc-frontend-manager-analytics') , 'name' => 'wcfm_shop_manager_capability_options[analytics]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $analytics),
																								) ) );
		?>
	</div>
</div>