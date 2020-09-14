<?php
if(!function_exists('wcfmgs_woocommerce_inactive_notice')) {
	function wcfmgs_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Groups & Staffs is inactive.%s The %sWooCommerce plugin%s must be active for the WCFM - Groups & Staffs to work. Please %sinstall & activate WooCommerce%s', WCFMgs_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmgs_wcfm_inactive_notice')) {
	function wcfmgs_wcfm_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Groups & Staffs is inactive.%s The %sWooCommerce Frontend Manager%s must be active for the WCFM - Groups & Staffs to work. Please %sinstall & activate WooCommerce Frontend Manager%s', WCFMgs_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-frontend-manager/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+frontend+manager' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if( !function_exists( 'wcfm_is_manager' ) ) {
	function wcfm_is_manager() {
		if( !is_user_logged_in() ) return false;
		
		if( current_user_can('shop_manager') ) return apply_filters( 'wcfm_is_manager', true );
		
		return apply_filters( 'wcfm_is_manager', false );
	}
}

if( !function_exists( 'wcfm_is_group_manager' ) ) {
	function wcfm_is_group_manager( $manager_id = '' ) {
		if( !is_user_logged_in() ) return false;
		
		if( !$manager_id ) {
			if( !wcfm_is_manager() ) return false;
			$manager_id = get_current_user_id();
		}
		
		$wcfm_vendor_groups = get_user_meta( $manager_id, '_wcfm_vendor_group', true );
		if( $wcfm_vendor_groups && is_array( $wcfm_vendor_groups ) && !empty( array_filter( $wcfm_vendor_groups ) ) ) return apply_filters( 'wcfm_is_group_manager', true );
			
		return apply_filters( 'wcfm_is_group_manager', false );
	}
}

if( !function_exists( 'wcfm_is_staff' ) ) {
	function wcfm_is_staff( $staff_id = '' ) {
		if( !$staff_id ) {
			if( !is_user_logged_in() ) return false;
			$staff_id = get_current_user_id();
		}
		
		$user = get_userdata( $staff_id );
		if( is_a( $user, 'WP_User' ) ) {
			if ( in_array( 'shop_staff', (array) $user->roles ) )  return apply_filters( 'wcfm_is_staff', true );
		}
		
		return apply_filters( 'wcfm_is_staff', false );
	}
}

if( !function_exists( 'wcfm_is_vendor_staff' ) ) {
	function wcfm_is_vendor_staff() {
		if( !is_user_logged_in() ) return false;
		if( !wcfm_is_staff() ) return false;
		
		$staff_id = get_current_user_id();
		$wcfm_vendor = get_user_meta( $staff_id, '_wcfm_vendor', true );
		if( $wcfm_vendor ) return apply_filters( 'wcfm_is_vendor_staff', true );
			
		return apply_filters( 'wcfm_is_vendor_staff', false );
	}
}

if( !function_exists( 'wcfm_get_staff_vendor' ) ) {
	function wcfm_get_staff_vendor() {
		if( !is_user_logged_in() ) return 0;
		if( !wcfm_is_staff() ) return 0;
		if( !wcfm_is_vendor_staff() ) return 0;
		
		$staff_id = get_current_user_id();
		$wcfm_vendor = get_user_meta( $staff_id, '_wcfm_vendor', true );
		if( $wcfm_vendor ) return apply_filters( 'wcfm_staff_vendor_id', $wcfm_vendor );
			
		return 0;
	}
}

if(!function_exists('get_wcfm_groups_dashboard_url')) {
	function get_wcfm_groups_dashboard_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_groups_url = wcfm_get_endpoint_url( 'wcfm-groups', '', $wcfm_page );
		return $wcfmgs_groups_url;
	}
}

if(!function_exists('get_wcfm_groups_manage_url')) {
	function get_wcfm_groups_manage_url( $group_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_manage_groups_url = wcfm_get_endpoint_url( 'wcfm-groups-manage', $group_id, $wcfm_page );
		return $wcfm_manage_groups_url;
	}
}

if(!function_exists('get_wcfm_shop_managers_dashboard_url')) {
	function get_wcfm_shop_managers_dashboard_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_shop_managers_url = wcfm_get_endpoint_url( 'wcfm-managers', '', $wcfm_page );
		return $wcfmgs_shop_managers_url;
	}
}

if(!function_exists('get_wcfm_shop_managers_manage_url')) {
	function get_wcfm_shop_managers_manage_url( $manager_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_manage_shop_managers_url = wcfm_get_endpoint_url( 'wcfm-managers-manage', $manager_id, $wcfm_page );
		return $wcfm_manage_shop_managers_url;
	}
}

if(!function_exists('get_wcfm_shop_staffs_dashboard_url')) {
	function get_wcfm_shop_staffs_dashboard_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_shop_staffs_url = wcfm_get_endpoint_url( 'wcfm-staffs', '', $wcfm_page );
		return $wcfmgs_shop_staffs_url;
	}
}

if(!function_exists('get_wcfm_shop_staffs_manage_url')) {
	function get_wcfm_shop_staffs_manage_url( $staff_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfm_manage_shop_staffs_url = wcfm_get_endpoint_url( 'wcfm-staffs-manage', $staff_id, $wcfm_page );
		return $wcfm_manage_shop_staffs_url;
	}
}

if(!function_exists('get_wcfmgs_groups_manage_messages')) {
	function get_wcfmgs_groups_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_title'     => __( 'Please insert Group Name before submit.', 'wc-frontend-manager-groups-staffs' ),
											'group_failed' => __( 'Group Saving Failed.', 'wc-frontend-manager-groups-staffs' ),
											'group_saved'  => __( 'Group Successfully Saved.', 'wc-frontend-manager-groups-staffs' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfmgs_managers_manage_messages')) {
	function get_wcfmgs_managers_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_username' => __( 'Please insert Manager Username before submit.', 'wc-frontend-manager-groups-staffs' ),
											'no_email' => __( 'Please insert Manager Email before submit.', 'wc-frontend-manager-groups-staffs' ),
											'username_exists' => __( 'This Username already exists.', 'wc-frontend-manager-groups-staffs' ),
											'email_exists' => __( 'This Email already exists.', 'wc-frontend-manager-groups-staffs' ),
											'manager_failed' => __( 'Manager Saving Failed.', 'wc-frontend-manager-groups-staffs' ),
											'manager_saved' => __( 'Manager Successfully Saved.', 'wc-frontend-manager-groups-staffs' ),
											);
		
		return $messages;
	}
}

if(!function_exists('get_wcfmgs_staffs_manage_messages')) {
	function get_wcfmgs_staffs_manage_messages() {
		global $WCFMu;
		
		$messages = array(
											'no_username' => __( 'Please insert Staff Username before submit.', 'wc-frontend-manager-groups-staffs' ),
											'no_email' => __( 'Please insert Staff Email before submit.', 'wc-frontend-manager-groups-staffs' ),
											'username_exists' => __( 'This Username already exists.', 'wc-frontend-manager-groups-staffs' ),
											'email_exists' => __( 'This Email already exists.', 'wc-frontend-manager-groups-staffs' ),
											'staff_failed' => __( 'Staff Saving Failed.', 'wc-frontend-manager-groups-staffs' ),
											'staff_saved' => __( 'Staff Successfully Saved.', 'wc-frontend-manager-groups-staffs' ),
											);
		
		return $messages;
	}
}
?>