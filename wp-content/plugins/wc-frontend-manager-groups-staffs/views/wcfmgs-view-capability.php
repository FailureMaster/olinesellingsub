<?php
/**
 * WCFMgs plugin Views
 *
 * Plugin Capability View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/views
 * @version   1.0.0
 */
?>

<?php

/**
 * WCFM advanced capability
 *
 * @since 1.0.0
 */
 
 
add_action( 'wcfm_capability_settings_miscellaneous', 'wcfmgs_capability_settings_miscellaneous_advanced', 50 );

function wcfmgs_capability_settings_miscellaneous_advanced( $wcfm_capability_options ) {
	global $WCFM, $WCFMu;
	
	$manage_staffs = ( isset( $wcfm_capability_options['manage_staffs'] ) ) ? $wcfm_capability_options['manage_staffs'] : 'no';
	$stafflimit = ( !empty( $wcfm_capability_options['stafflimit'] ) ) ? $wcfm_capability_options['stafflimit'] : '';
	
	?>
	<div class="wcfm_clearfix"></div>
	<div class="vendor_capability_sub_heading"><h3><?php _e( 'Groups & Staffs', 'wc-frontend-manager-ultimate' ); ?></h3></div>
	
	<?php
	$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_settings_fields_capability_groups', array(  
																															 "manage_staffs" => array('label' => __('Manage Staff', 'wc-frontend-manager-ultimate') , 'name' => 'wcfm_capability_options[manage_staffs]', 'type' => 'checkboxoffon', 'class' => 'wcfm-checkbox wcfm_ele', 'value' => 'yes', 'label_class' => 'wcfm_title checkbox_title', 'dfvalue' => $manage_staffs),
																															 "stafflimit" => array( 'label' => __('Staff Limit', 'wc-frontend-manager-groups-staffs'), 'placeholder' => __('Unlimited', 'wc-frontend-manager-ultimate'), 'name' => 'wcfm_capability_options[stafflimit]','type' => 'number', 'class' => 'wcfm-text wcfm_ele gallerylimit_ele', 'label_class' => 'wcfm_title gallerylimit_title', 'value' => $stafflimit, 'hints' => __( 'No. of Staffs allow to add by an user.', 'wc-frontend-manager-groups-staffs' ) . ' ' . __( 'Set `-1` if you want to restrict limit at `0`.', 'wc-frontend-manager-groups-staffs' ) )
																								) ) );
}