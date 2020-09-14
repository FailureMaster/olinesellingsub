<?php
/**
 * WCFM plugin views
 *
 * Plugin Shop Managers Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/views
 * @version   1.0.0
 */
global $wp, $WCFM, $WCFMu;

$wcfm_is_allow_manage_manager = apply_filters( 'wcfm_is_allow_manage_manager', true );
if( !$wcfm_is_allow_manage_manager ) {
	wcfm_restriction_message_show( "Managers Manage" );
	return;
}

$manager_id = 0;
$user_name = '';
$user_email = '';
$first_name = '';
$last_name = '';
$has_custom_capability = 'no';

if( isset( $wp->query_vars['wcfm-managers-manage'] ) && !empty( $wp->query_vars['wcfm-managers-manage'] ) ) {
	$manager_user = get_userdata( $wp->query_vars['wcfm-managers-manage'] );
	
	// Fetching Manager Data
	if($manager_user && !empty($manager_user)) {
		
		if ( !in_array( 'shop_manager', (array) $manager_user->roles ) ) {
			wcfm_restriction_message_show( "Invalid Manager" );
			return;
		}
		
		$manager_id = $wp->query_vars['wcfm-managers-manage'];
		$user_name = $manager_user->user_login;
		$user_email = $manager_user->user_email;
		$first_name = $manager_user->first_name;
		$last_name = $manager_user->last_name;
		
		$has_custom_capability = get_user_meta( $manager_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $manager_id, '_wcfm_user_has_custom_capability', true ) : 'no';

	} else {
		wcfm_restriction_message_show( "Invalid Manager" );
		return;
	}
}

$manager_capability_options = apply_filters( 'wcfmgs_user_capability', get_option( 'wcfm_shop_manager_capability_options', array() ), $manager_id );

do_action( 'before_wcfm_managers_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user-secret"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Manager', 'wc-frontend-manager-groups-staffs' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	    <h2><?php if( $manager_id ) { _e('Edit Manager', 'wc-frontend-manager-groups-staffs' ); } else { _e('Add Manager', 'wc-frontend-manager-groups-staffs' ); } ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('user-new.php'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-groups-staffs' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_shop_managers_dashboard_url().'" data-tip="' . __('Manage Managers', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-user-secret"></span></a>';
			
			if( $has_new = apply_filters( 'wcfm_add_new_manager_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_shop_managers_manage_url().'" data-tip="' . __('Add New Manager', 'wc-frontend-manager-groups-staffs') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
		<?php do_action( 'begin_wcfm_managers_manage' ); ?>
			
		<form id="wcfm_managers_manage_form" class="wcfm">
			
		  <?php do_action( 'begin_wcfm_managers_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="managers_manage_general_expander" class="wcfm-content">
						<?php
						  if( $manager_id ) {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  } else {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  }
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_manager_manager_fields_general', array(  
																																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email),
																																						"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name),
																																						"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager-groups-staffs') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name),
																																						"manager_id" => array('type' => 'hidden', 'value' => $manager_id )
																																				 ) ) );
							
							if( !wcfm_is_group_manager( $manager_id ) ) {
								$WCFM->wcfm_fields->wcfm_generate_form_field( array(
																																		"has_custom_capability" => array( 'label' => __('Custom Capability', 'wc-frontend-manager-groups-staffs') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $has_custom_capability),
																																	) );
							}
						?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div>
			<!-- end collapsible -->
			
			<?php if( !wcfm_is_group_manager( $manager_id ) ) { ?>
				<div class="user_custom_capability" style="display: none;">
					<?php do_action( 'wcfmgs_capability_manager', $manager_capability_options, 'manager' ); ?>
				</div>
			<?php } ?>
			 
			<?php do_action( 'end_wcfm_managers_manage_form' ); ?>
			
			<div id="wcfm_manager_manager_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="submit-data" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" id="wcfm_manager_manager_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_managers_manage' );
			?>
		</form>
	</div>
</div>