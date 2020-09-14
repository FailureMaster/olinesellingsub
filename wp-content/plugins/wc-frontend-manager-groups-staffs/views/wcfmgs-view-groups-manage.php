<?php
/**
 * WCFM plugin view
 *
 * WCFMgs Groups Manage View
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/view
 * @version   1.0.0
 */
 
global $wp, $WCFM, $WCFMu, $WCFMgs;

$wcfm_is_allow_manage_groups = apply_filters( 'wcfm_is_allow_manage_groups', true );
if( !$wcfm_is_allow_manage_groups ) {
	wcfm_restriction_message_show( "Manage Groups" );
	return;
}

$group_id = 0;
$title = '';
$description = '';
$thumbnail = '';
$group_vendors = array();
$group_managers = array();
$group_capability_options = array();

if( isset( $wp->query_vars['wcfm-groups-manage'] ) && !empty( $wp->query_vars['wcfm-groups-manage'] ) ) {
	$group_post = get_post( $wp->query_vars['wcfm-groups-manage'] );
	// Fetching Group Data
	if($group_post && !empty($group_post)) {
		
		if( $group_post->post_type != 'wcfm_vendor_groups' ) {
			wcfm_restriction_message_show( "Invalid Group" );
			return;
		}
		
		$group_id = $wp->query_vars['wcfm-groups-manage'];
		
		$title = $group_post->post_title;
		$description = $group_post->post_excerpt;
		$thumbnail = get_post_meta( $group_id, 'thumbnail', true );
		
		$group_vendors = (array) get_post_meta( $group_id, '_group_vendors', true );
		$group_managers = (array) get_post_meta( $group_id, '_group_managers', true );
		$group_capability_options = (array) get_post_meta( $group_id, '_group_capability_options', true );
	} else {
		wcfm_restriction_message_show( "Invalid Group" );
		return;
	}
}

$user_arr = array( );
$is_marketplace = wcfm_is_marketplace();
if( $is_marketplace ) {
	$user_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list();
	/*$args = array(
		'role__in'     => array( 'dc_vendor', 'vendor', 'wc_product_vendors_admin_vendor', 'seller', 'wcfm_vendor' ),
		'orderby'      => 'login',
		'order'        => 'ASC',
		'count_total'  => false,
		'fields'       => array( 'ID', 'display_name' )
	 ); 
	$all_users = get_users( $args );
	if( !empty( $all_users ) ) {
		foreach( $all_users as $all_user ) {
			$user_arr[$all_user->ID] = $all_user->display_name;
		}
	}*/
}

$managers = array();
$args = array(
	'role__in'     => array( 'shop_manager' ),
	'orderby'      => 'login',
	'order'        => 'ASC',
	'count_total'  => false,
	'fields'       => array( 'ID', 'display_name' )
 ); 
$all_users = get_users( $args );
if( !empty( $all_users ) ) {
	foreach( $all_users as $all_user ) {
		$managers[$all_user->ID] = $all_user->display_name;
	}
}

do_action( 'before_wcfm_groups_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-users"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Group', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	  
	  	<h2><?php if( $group_id ) { _e('Edit Group', 'wc-frontend-manager-groups-staffs' ); } else { _e('Add Group', 'wc-frontend-manager-groups-staffs' ); } ?></h2>
			
			<?php
			echo '<a id="add_new_group_dashboard" class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_groups_dashboard_url().'" data-tip="' . __('Groups', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-users"></span><span class="text">' . __( 'Groups', 'wc-frontend-manager-groups-staffs') . '</span></a>';
			if( $group_id && apply_filters( 'wcfm_is_allow_groups_archive', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" target="_blank" href="'.get_permalink( $group_id ).'" data-tip="' . __('Groups Directory', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-eye"></span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
		<?php do_action( 'begin_wcfm_groups_manage' ); ?>
		
		<form id="wcfm_groups_manage_form" class="wcfm">
		
			<?php do_action( 'begin_wcfm_groups_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="groups_manage_general_expander" class="wcfm-content">
						<?php
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'group_manager_fields_general', array(  "title" => array('label' => __('Name', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $title),
																																															"excerpt" => array('label' => __('Description', 'wc-frontend-manager-groups-staffs') , 'type' => 'textarea', 'class' => 'wcfm-textarea wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $description),
																																															"thumbnail" => array('label' => __('Thumbnail', 'wc-frontend-manager-groups-staffs') , 'type' => 'upload', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title', 'prwidth' => 125, 'value' => $thumbnail),
																																															"group_id" => array('type' => 'hidden', 'value' => $group_id)
																																					) ) );
						?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div><br />
			<!-- end collapsible -->
			
			<?php if( $is_marketplace ) { ?>
				<!-- collapsible -->
				<div class="page_collapsible" id="groups_vendors_manage_capability_head">
					<label class="wcfmfa fa-user-alt"></label>
					<?php echo apply_filters( 'wcfm_sold_by_label', '', __( 'Vendors', 'wc-frontend-manager' ) ); ?>
				</div> 
				<div class="wcfm-container">
					<div id="groups_vendors_manage_capability_expander" class="wcfm-content">
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'group_manager_fields_vendors', array(  
																																																"group_vendors" => array( 'label' => __( 'Group', 'wc-frontend-manager-groups-staffs' ) . ' ' . apply_filters( 'wcfm_sold_by_label', '', __( 'Vendors', 'wc-frontend-manager' ) ), 'type' => 'select', 'options' => $user_arr, 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $group_vendors ),
																																						) ) );
							?>
					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				<!-- end collapsible -->
			<?php } ?>
			
			<!-- collapsible -->
				<div class="page_collapsible" id="groups_managers_manage_capability_head">
					<label class="wcfmfa fa-user fa-user-secret"></label>
					<?php _e('Managers', 'wc-frontend-manager-groups-staffs'); ?>
				</div> 
				<div class="wcfm-container">
					<div id="groups_managers_manage_capability_expander" class="wcfm-content">
							<?php
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'group_manager_fields_managers', array(  
																																																"group_managers" => array( 'label' => __( 'Group Managers', 'wc-frontend-manager-groups-staffs' ), 'type' => 'select', 'options' => $managers, 'attributes' => array( 'multiple' => 'multiple', 'style' => 'width: 60%;' ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'value' => $group_managers ),
																																						) ) );
							?>
					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				<!-- end collapsible -->
			
			<?php do_action( 'wcfmgs_capability_manager', $group_capability_options, 'group' ); ?>
			 
			<?php do_action( 'end_wcfm_groups_manage_form' ); ?>
			
			<div id="wcfm_group_manager_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="group-manager-data" value="<?php _e( 'Submit', 'wc-frontend-manager-groups-staffs' ); ?>" id="wcfm_group_manager_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_groups_manage' );
			?>
		</form>
	</div>
</div>