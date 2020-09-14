<?php
/**
 * WCFM plugin views
 *
 * Plugin Deivery Boys Manage Views
 *
 * @author 		WC Lovers
 * @package 	wcfmd/views
 * @version   1.0.0
 */
global $wp, $WCFM, $WCFMs;

if( !apply_filters( 'wcfm_is_allow_manage_delivery_boys', true ) || !apply_filters( 'wcfm_is_allow_delivery', true ) ) {
	wcfm_restriction_message_show( "Delivery Boys Manage" );
	return;
}

$delivery_boy_id = 0;
$user_name = '';
$user_phone = '';
$user_email = '';
$first_name = '';
$last_name = '';

$wcfm_vendor = 0;
$wcfm_vendor_class = '';
$vendor_arr = array();
$has_custom_capability = '';
$is_marketplace = wcfm_is_marketplace();
if( !$is_marketplace || wcfm_is_vendor() ) {
	$wcfm_vendor_class = 'wcfm_custom_hide';
}

if( isset( $wp->query_vars['wcfm-delivery-boys-manage'] ) && empty( $wp->query_vars['wcfm-delivery-boys-manage'] ) ) {
	if( !apply_filters( 'wcfm_is_allow_add_delivery_boy', true ) ) {
		wcfm_restriction_message_show( "Add Delivery Boy" );
		return;
	}
	if( !apply_filters( 'wcfm_is_allow_delivery_boys_limit', true ) ) {
		wcfm_restriction_message_show( "Delivery Boys Limit Reached" );
		return;
	}
}

if( isset( $wp->query_vars['wcfm-delivery-boys-manage'] ) && !empty( $wp->query_vars['wcfm-delivery-boys-manage'] ) ) {
	$staff_user = get_userdata( $wp->query_vars['wcfm-delivery-boys-manage'] );
	
	// Fetching Staff Data
	if($staff_user && !empty($staff_user)) {
		
		if ( !in_array( 'wcfm_delivery_boy', (array) $staff_user->roles ) ) {
			wcfm_restriction_message_show( "Invalid Delivery Person" );
			return;
		}
		
		$delivery_boy_id = $wp->query_vars['wcfm-delivery-boys-manage'];
		$user_name = $staff_user->user_login;
		$user_email = $staff_user->user_email;
		$first_name = $staff_user->first_name;
		$last_name = $staff_user->last_name;
		
		if( !wcfm_is_vendor() ) {
			$wcfm_vendor = get_user_meta( $delivery_boy_id, '_wcfm_vendor', true );
		} else {
			$wcfm_vendor_class = 'wcfm_custom_hide';
			$wcfm_vendor = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
		}
		if( $wcfm_vendor ) {
			$vendor_arr[$wcfm_vendor] = get_user_by( 'id', $wcfm_vendor )->display_name;
		}
		
		$user_phone = get_user_meta( $delivery_boy_id, 'billing_phone', true );
		
		$has_custom_capability = get_user_meta( $delivery_boy_id, '_wcfm_user_has_custom_capability', true ) ? get_user_meta( $delivery_boy_id, '_wcfm_user_has_custom_capability', true ) : 'no';
	} else {
		wcfm_restriction_message_show( "Invalid Delivery Person" );
		return;
	}
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

do_action( 'before_wcfm_delivery_boys_manage' );

?>

<div class="collapse wcfm-collapse">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-shipping-fast"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Manage Delivery Boys', 'wc-frontend-manager-delivery' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <div class="wcfm-container wcfm-top-element-container">
	    <h2><?php if( $delivery_boy_id ) { _e('Edit Delivery Boy', 'wc-frontend-manager-delivery' ); } else { _e('Add Delivery Boy', 'wc-frontend-manager-delivery' ); } ?></h2>
			
			<?php
			if( $allow_wp_admin_view = apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
				?>
				<a target="_blank" class="wcfm_wp_admin_view text_tip" href="<?php echo admin_url('user-new.php'); ?>" data-tip="<?php _e( 'WP Admin View', 'wc-frontend-manager-delivery' ); ?>"><span class="fab fa-wordpress"></span></a>
				<?php
			}
			
			echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_delivery_boys_dashboard_url().'" data-tip="' . __('Manage Delivery Boys', 'wc-frontend-manager-delivery') . '"><span class="wcfmfa fa-shipping-fast"></span></a>';
			
			if( $has_new = apply_filters( 'wcfm_add_new_delivery_boy_sub_menu', true ) ) {
				echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_delivery_boys_manage_url().'" data-tip="' . __('Add New Delivery Boy', 'wc-frontend-manager-delivery') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
			}
			?>
			<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	    
	  <?php do_action( 'begin_wcfm_delivery_boys_manage' ); ?>
	  
		<form id="wcfm_delivery_boys_manage_form" class="wcfm">
			
		  <?php do_action( 'begin_wcfm_delivery_boys_manage_form' ); ?>
			
			<!-- collapsible -->
			<div class="wcfm-container">
				<div id="delivery_boys_manage_general_expander" class="wcfm-content">
						<?php
						  if( $delivery_boy_id ) {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'attributes' => array( 'readonly' => true ), 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  } else {
						  	$WCFM->wcfm_fields->wcfm_generate_form_field(  array( "user_name" => array( 'label' => __('Username', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_name ) ) );
						  }
							$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_delivery_manager_fields_general', array(  
																																						"user_email" => array( 'label' => __('Email', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_email),
																																						"user_phone" => array( 'label' => __('Phone', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $user_phone),
																																						"first_name" => array( 'label' => __('First Name', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $first_name),
																																						"last_name" => array( 'label' => __('Last Name', 'wc-frontend-manager-delivery') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $last_name),
																																						"wcfm_vendor" => array( 'label' => apply_filters( 'wcfm_sold_by_label', $wcfm_vendor, __( 'Store', 'wc-frontend-manager' ) ), 'type' => 'select', 'options' => $vendor_arr, 'class' => 'wcfm-select wcfm_ele ' .$wcfm_vendor_class, 'label_class' => 'wcfm_title ' . $wcfm_vendor_class, 'value' => $wcfm_vendor ),
																																						//"has_custom_capability" => array( 'label' => __('Custom Capability', 'wc-frontend-manager-delivery') , 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title checkbox_title', 'value' => 'yes', 'dfvalue' => $has_custom_capability),
																																						"delivery_boy_id" => array('type' => 'hidden', 'value' => $delivery_boy_id )
																																					) ) );
						?>
				</div>
			</div>
			<div class="wcfm_clearfix"></div>
			<!-- end collapsible -->
			
			<?php do_action( 'end_wcfm_delivery_boys_manage_form' ); ?>
			
			<div id="wcfm_delivery_boy_manager_submit" class="wcfm_form_simple_submit_wrapper">
			  <div class="wcfm-message" tabindex="-1"></div>
			  
				<input type="submit" name="submit-data" value="<?php _e( 'Submit', 'wc-frontend-manager' ); ?>" id="wcfm_delivery_boy_manager_submit_button" class="wcfm_submit_button" />
			</div>
			<?php
			do_action( 'after_wcfm_delivery_boys_manage' );
			?>
		</form>
	</div>
</div>