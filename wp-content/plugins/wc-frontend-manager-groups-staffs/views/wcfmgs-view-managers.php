<?php
/**
 * WCFM plugin view
 *
 * WCFM Shop Managers View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/view
 * @version   1.0.0
 */

global $WCFM;

$wcfm_is_allow_manage_manager = apply_filters( 'wcfm_is_allow_manage_manager', true );
if( !$wcfm_is_allow_manage_manager ) {
	wcfm_restriction_message_show( "Managers" );
	return;
}

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user-secret"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Shop Managers', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
		
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Manage Managers', 'wc-frontend-manager-groups-staffs' ); ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('users.php?role=shop_manager'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-groups-staffs' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			if( $wcfm_is_allow_capability_controller = apply_filters( 'wcfm_is_allow_capability_controller', true ) ) {
				echo '<a id="wcfm_capability_settings" class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_capability_url().'" data-tip="' . __('Capability Controller', 'wc-frontend-manager') . '"><span class="wcfmfa fa-user-times"></span></a>';
			}
			
			if( $has_new = apply_filters( 'wcfm_add_new_manager_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_shop_managers_manage_url().'" data-tip="' . __('Add New Manager', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
			
		<?php do_action( 'before_wcfm_shop_managers' ); ?>
		
		<div class="wcfm-container">
			<div id="wwcfm_shop_managers_expander" class="wcfm-content">
				<table id="wcfm-shop-managers" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th><?php _e( 'Manager', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Email', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Groups', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th><?php _e( 'Manager', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Name', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Email', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Groups', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_shop_managers' );
		?>
	</div>
</div>