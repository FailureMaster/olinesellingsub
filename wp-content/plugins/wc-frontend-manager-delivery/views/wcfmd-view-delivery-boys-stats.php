<?php
/**
 * WCFM plugin view
 *
 * WCFM Delivery Boy Stats View
 *
 * @author 		WC Lovers
 * @package 	wcfmd/views/
 * @version   1.0.0
 */
 
global $WCFM, $WCFMd, $wp;

$wcfm_is_allow_delivery = apply_filters( 'wcfm_is_allow_delivery_stats', true );
if( !$wcfm_is_allow_delivery ) {
	wcfm_restriction_message_show( "Delivery Boys" );
	return;
}

$delivery_boy_id = 0; 
if( wcfm_is_delivery_boy() ) {
	$delivery_boy_id = get_current_user_id();
} elseif( isset( $wp->query_vars['wcfm-delivery-boys-stats'] ) && !empty( $wp->query_vars['wcfm-delivery-boys-stats'] ) ) {
	$delivery_boy_id = $wp->query_vars['wcfm-delivery-boys-stats'];
}

if( !$delivery_boy_id ) {
	wcfm_restriction_message_show( "Restricted Access" );
	return;
}

if( wcfm_is_vendor() ) {
	$is_ticket_for_vendor = $WCFM->wcfm_vendor_support->wcfm_is_component_for_vendor( $delivery_boy_id, 'delivery' );
	if( !$is_ticket_for_vendor ) {
		if( apply_filters( 'wcfm_is_show_delivery_restrict_message', true, $delivery_boy_id ) ) {
			wcfm_restriction_message_show( "Restricted Delivery Boy" );
		} else {
			echo apply_filters( 'wcfm_show_custom_delivery_restrict_message', '', $delivery_boy_id );
		}
		return;
	}
}

$delivery_boy_id = absint($delivery_boy_id);

$delivery_boy_label     = '';
$wcfm_delivery_boy_user = get_userdata( absint( $delivery_boy_id ) );
if( $wcfm_delivery_boy_user ) {
	if ( !in_array( 'wcfm_delivery_boy', (array) $wcfm_delivery_boy_user->roles ) ) {
		wcfm_restriction_message_show( "Invalid Delivery Person" );
		return;
	}
	
	$delivery_boy_label     = apply_filters( 'wcfm_delivery_boy_display', $wcfm_delivery_boy_user->first_name . ' ' . $wcfm_delivery_boy_user->last_name, $delivery_boy_id );
} else {
	wcfm_restriction_message_show( "Invalid Delivery Person" );
	return;
}

?>
<div class="collapse wcfm-collapse" id="wcfm_delivery_boy_stats_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-shipping-fast"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Delivery Stats', 'wc-frontend-manager-delivery' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <?php if( !wcfm_is_delivery_boy() ) { ?>
			<div class="wcfm-container wcfm-top-element-container">
				<h2>
					<?php echo __( 'Delivery Stats', 'wc-frontend-manager-delivery' ) . ' - ' . $delivery_boy_label; ?>
				</h2>
				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div><br />
		<?php } ?>
		
		<div class="wcfm_delivery_stats_filter_wrap wcfm_filters_wrap">
			<select name="status_type" id="dropdown_status_type" style="width: 160px; display: inline-block;">
				<option value=""><?php  _e( 'Show all ..', 'wc-frontend-manager' ); ?></option>
				<option value="delivered"><?php  _e( 'Delivered', 'wc-frontend-manager-delivery' ); ?></option>
				<option value="pending" selected><?php  _e( 'Pending', 'wc-frontend-manager' ); ?></option>
			</select>
		</div>
	  
	  <?php do_action( 'before_wcfm_delivery_boy_stats' ); ?>
	  
	  <div class="wcfm_dashboard_stats">
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-ambulance"></span>
					<div>
						<strong><?php echo wcfm_get_delivery_boy_delivery_stat( $delivery_boy_id, 'delivered' ); ?></strong><br />
						<?php _e( 'delivered', 'wc-frontend-manager-delivery' ); ?>
					</div>
				</a>
			</div>
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-shipping-fast"></span>
					<div>
						<strong><?php echo wcfm_get_delivery_boy_delivery_stat( $delivery_boy_id, 'pending' ); ?></strong><br />
						<?php _e( 'pending', 'wc-frontend-manager-delivery' ); ?>
					</div>
				</a>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
	  
		<div class="wcfm-container">
			<div id="wcfm_delivery_boy_stats_listing_expander" class="wcfm-content">
				<table id="wcfm_delivery_boy_stats" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
						  <th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Status', 'wc-frontend-manager-delivery' ); ?>"></span></th>
						  <th><?php _e( 'Order', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Item', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Store', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Customer', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Delivery Address', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-delivery' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
						  <th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Status', 'wc-frontend-manager-delivery' ); ?>"></span></th>
						  <th><?php _e( 'Order', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Item', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Store', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Customer', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Delivery Address', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-delivery' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_delivery_boy_stats' );
		?>
		<input type="hidden" name="wcfm_delivery_boy_id" id="wcfm_delivery_boy_id" value="<?php echo $delivery_boy_id; ?>" />
	</div>
</div>