<?php
/**
 * WCFM plugin view
 *
 * WCFM Affiliate View
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/view
 * @version   1.0.0
 */

global $WCFM;

$wcfm_is_allow_manage_affiliate = apply_filters( 'wcfm_is_allow_affiliate', true );
if( !$wcfm_is_allow_manage_affiliate || wcfm_is_vendor() ) {
	wcfm_restriction_message_show( "Affiliate" );
	return;
}

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user-friends"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Affiliate', 'wc-frontend-manager-affiliate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Manage Affiliate', 'wc-frontend-manager-affiliate' ); ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('users.php?role=wcfm_affiliate'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-affiliate' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_affiliate_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_manage_url().'" data-tip="' . __('Add New Affiliate', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			
			<?php	echo apply_filters( 'wcfm_affiliate_limit_label', '' ); ?>
			
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
	  <div class="wcfm_affiliate_filter_wrap wcfm_filters_wrap">
			<?php
			?>
		</div>
			
		<?php do_action( 'before_wcfm_affiliate' ); ?>
		
		<div class="wcfm-container">
			<div id="wcfm_affiliate_expander" class="wcfm-content">
				<table id="wcfm-affiliate" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php _e( 'Name', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Commission', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Paid', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Vendors', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Orders', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-affiliate' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Name', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Commission', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Paid', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Vendors', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Orders', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-affiliate' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_affiliate' );
		?>
	</div>
</div>