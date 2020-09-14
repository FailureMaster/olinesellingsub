<?php
/**
 * WCFM plugin view
 *
 * WCFM Delivery Boys View
 *
 * @author 		WC Lovers
 * @package 	wcfmd/view
 * @version   1.0.0
 */

global $WCFM;

$wcfm_is_allow_manage_delivery_boy = apply_filters( 'wcfm_is_allow_delivery', true );
if( !$wcfm_is_allow_manage_delivery_boy ) {
	wcfm_restriction_message_show( "Delivery Boys" );
	return;
}

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-shipping-fast"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Delivery Persons', 'wc-frontend-manager-delivery' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Manage Delivery Boys', 'wc-frontend-manager-delivery' ); ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('users.php?role=wcfm_delivery_boy'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-delivery' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_delivery_boy_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_delivery_boys_manage_url().'" data-tip="' . __('Add New Delivery Boy', 'wc-frontend-manager-delivery') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			
			<?php	echo apply_filters( 'wcfm_delivery_boys_limit_label', '' ); ?>
			
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <div class="wcfm_delivery_boys_filter_wrap wcfm_filters_wrap">
			<?php
			if( apply_filters( 'wcfm_is_delivery_boys_vendor_filter', true ) ) {
				$is_marketplace = wcfm_is_marketplace();
				if( $is_marketplace ) {
					if( !wcfm_is_vendor() ) {
						$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																											"dropdown_vendor" => array( 'type' => 'select', 'options' => array(), 'attributes' => array( 'style' => 'width: 150px;' ) )
																											 ) );
					}
				}
			}
			?>
		</div>
			
		<?php do_action( 'before_wcfm_delivery_boys' ); ?>
		
		<div class="wcfm-container">
			<div id="wcfm_delivery_boys_expander" class="wcfm-content">
				<table id="wcfm-delivery-boys" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php _e( 'Delivery Boy', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Stats', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-delivery' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Delivery Boy', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Stats', 'wc-frontend-manager-delivery' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-delivery' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_delivery_boys' );
		?>
	</div>
</div>