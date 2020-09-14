<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Groups List View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/view
 * @version   1.0.0
 */
 
global $WCFM, $WCFMu, $WCFMgs;

$wcfm_is_allow_manage_groups = apply_filters( 'wcfm_is_allow_manage_groups', true );
if( !$wcfm_is_allow_manage_groups ) {
	wcfm_restriction_message_show( "Manage Groups" );
	return;
}

?>
<div class="collapse wcfm-collapse" id="wcfm_groups_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-users"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Groups', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Manage Groups', 'wc-frontend-manager-groups-staffs' ); ?></h2>
			
			<?php
			if( $has_new = apply_filters( 'wcfm_add_new_group_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_groups_manage_url().'" data-tip="' . __('Add New Group', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-users"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			if( apply_filters( 'wcfm_is_allow_groups_archive', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" target="_blank" href="'.get_post_type_archive_link( 'wcfm_vendor_groups' ).'" data-tip="' . __('Groups Directory', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-eye"></span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
			
		<?php do_action( 'before_wcfm_groups' ); ?>
		
		<div class="wcfm-container">
			<div id="wcfm_groups_listing_expander" class="wcfm-content">
				<table id="wcfm-groups" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
						  <th><span class="wcfmfa fa-image text_tip" data-tip="<?php _e( 'Image', 'wc-frontend-manager' ); ?>"></span></th>
							<th><?php _e( 'Group', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Vendors', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Manager(s)', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
						  <th><span class="wcfmfa fa-image text_tip" data-tip="<?php _e( 'Image', 'wc-frontend-manager' ); ?>"></span></th>
							<th><?php _e( 'Group', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Vendors', 'wc-frontend-manager' ) ); ?></th>
							<th><?php _e( 'Manager(s)', 'wc-frontend-manager-groups-staffs' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-groups-staffs' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<?php
		do_action( 'after_wcfm_groups' );
		?>
	</div>
</div>