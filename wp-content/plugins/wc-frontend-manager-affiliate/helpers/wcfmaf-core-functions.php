<?php
if(!function_exists('wcfmaf_woocommerce_inactive_notice')) {
	function wcfmaf_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Affiliate is inactive.%s The %sWooCommerce plugin%s must be active for the WCFM - Affiliate to work. Please %sinstall & activate WooCommerce%s', WCFMaf_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmaf_wcfm_inactive_notice')) {
	function wcfmaf_wcfm_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Affiliate is inactive.%s The %sWooCommerce Frontend Manager%s must be active for the WCFM - Affiliate to work. Please %sinstall & activate WooCommerce Frontend Manager%s', WCFMaf_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-frontend-manager/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+frontend+manager' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmaf_wcfmmp_inactive_notice')) {
	function wcfmaf_wcfmmp_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Affiliate is inactive.%s The %sWooCommerce Multivendor Marketplace%s must be active for the WCFM - Affiliate to work. Please %sinstall & activate WooCommerce Multivendor Marketplace%s', WCFMaf_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-multivendor-marketplace/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+multivendor+marketplace' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfm_is_allow_vendor_as_affiliate')) {
	function wcfm_is_allow_vendor_as_affiliate() { 
		global $WCFM, $WCFMaf;
		
		$vendor_as_affiliate = isset( $WCFMaf->wcfmaf_setting_options['vendor_as_affiliate'] ) ? $WCFMaf->wcfmaf_setting_options['vendor_as_affiliate'] : 'no';
		if( $vendor_as_affiliate == 'yes' ) {
			return apply_filters( 'wcfm_is_allow_vendor_as_affiliate', true ); 
		}
		return apply_filters( 'wcfm_is_allow_vendor_as_affiliate', false );
	}
}

if(!function_exists('wcfm_allowed_affiliate_user_roles')) {
	function wcfm_allowed_affiliate_user_roles() { 
		$allowed_affiliate_user_roles = apply_filters( 'wcfm_allowed_affiliate_user_roles',  array( 'customer', 'subscriber', 'editor', 'contributor', 'author' ) );
		if( wcfm_is_allow_vendor_as_affiliate() ) {
		  $allowed_affiliate_user_roles[] = 'wcfm_vendor';
		}
		
		return $allowed_affiliate_user_roles;
	}
}

if(!function_exists('wcfm_is_allowed_affiliate')) {
	function wcfm_is_allowed_affiliate() { 
		global $_SESSION;
		
		if( !is_user_logged_in() ) return true;
		
		$allowed_affiliate_user_roles = wcfm_allowed_affiliate_user_roles();
		$user = wp_get_current_user();
		if ( array_intersect( $allowed_affiliate_user_roles, (array) $user->roles ) )  {
			if( !wcfm_is_affiliate() ) { 
				return true;
			}
		}
		
		return apply_filters( 'wcfm_is_allowed_affiliate', false );
	}
}

if(!function_exists('is_wcfm_affiliate_registration_page')) {
	function is_wcfm_affiliate_registration_page() {  
		//return wc_post_content_has_shortcode( 'wcfm_vendor_registration' );
		$pages = get_option("wcfm_page_options", array());
		if( isset( $pages['wcfm_affiliate_registration_page_id'] ) && $pages['wcfm_affiliate_registration_page_id'] ) {
			return is_page( $pages['wcfm_affiliate_registration_page_id'] ) || wc_post_content_has_shortcode( 'wcfm_affiliate_registration' );
		}
		return false;
	}
}

if(!function_exists('get_wcfm_affiliate_registration_page')) {
	function get_wcfm_affiliate_registration_page() {
		$pages = get_option("wcfm_page_options", array());
		if( isset( $pages['wcfm_affiliate_registration_page_id'] ) && $pages['wcfm_affiliate_registration_page_id'] ) {
			if ( defined( 'ICL_SITEPRESS_VERSION' ) && ! ICL_PLUGIN_INACTIVE && class_exists( 'SitePress' ) ) {
				global $sitepress;
				$language_code = $sitepress->get_current_language();
				
				$registration_page = get_permalink( icl_object_id( $pages['wcfm_affiliate_registration_page_id'], 'page', true, $language_code ) );
				$registration_page = apply_filters( 'wpml_permalink', $registration_page, $language_code );
				
				return $registration_page;
			} else {
				return get_permalink( $pages['wcfm_affiliate_registration_page_id'] );
			}
		}
		return false;
	}
}

if( !function_exists( 'wcfm_get_affiliate' ) ) {
	function wcfm_get_affiliate( $limit = -1, $offset = 0 ) {
		$affiliate_role = apply_filters( 'wcfm_affiliate_user_role', array( 'wcfm_affiliate' ) );
		
		$args = array(
									'role__in'     => $affiliate_role,
									'orderby'      => 'ID',
									'order'        => 'ASC',
									'offset'       => $offset,
									'number'       => $limit,
									'count_total'  => false
								 ); 
		$args = apply_filters( 'wcfmaf_get_affiliate_args', $args );
		$wcfm_affiliate_array = get_users( $args );
		return apply_filters( 'wcfm_affiliate', $wcfm_affiliate_array );
	}
}

if( !function_exists( 'wcfm_is_affiliate' ) ) {
	function wcfm_is_affiliate( $affiliate_id = '' ) {
		if( !$affiliate_id && !is_user_logged_in() ) return false;
		
		if( !$affiliate_id && is_user_logged_in() ) {
			$affiliate_id = get_current_user_id();
		} 
		
		$affiliate_roles = apply_filters( 'wcfm_affiliate_user_role', array( 'wcfm_affiliate' ) );
		
		if( $affiliate_id ) {
			$affiliate_user = get_userdata( $affiliate_id );
			if( $affiliate_user ) {
				if ( array_intersect( $affiliate_roles, (array) $affiliate_user->roles ) ) {
					return apply_filters( 'wcfm_is_affiliate', true, $affiliate_id );
				} else {
					return apply_filters( 'wcfm_is_affiliate', false, $affiliate_id );
				}
			} else {
				return apply_filters( 'wcfm_is_affiliate', false, $affiliate_id );
			}
		}
		
		return apply_filters( 'wcfm_is_affiliate', false, $affiliate_id );
	}
}

if( !function_exists( 'wcfm_affiliate_is_active' ) ) {
	function wcfm_affiliate_is_active( $affiliate_id = '' ) {
		if( !$affiliate_id ) return false;
		
		$disable_affiliate = get_user_meta( $affiliate_id, '_disable_affiliate', true );
		if( $disable_affiliate ) {
			return apply_filters( 'wcfm_affiliate_is_active', false, $affiliate_id );
		}
		
		return apply_filters( 'wcfm_affiliate_is_active', true, $affiliate_id );
	}
}

if( !function_exists( 'wcfm_get_affiliate_by_referrer' ) ) {
	function wcfm_get_affiliate_by_referrer( $referral_code = '' ) {
		
		if( !$referral_code ) return '';
		
		$affiliate_role = apply_filters( 'wcfm_affiliate_user_role', array( 'wcfm_affiliate' ) );
		
		$args = array(
									'role__in'     => $affiliate_role,
									'orderby'      => 'ID',
									'order'        => 'ASC',
								 ); 
		$args['meta_key'] = 'affiliate_code';
		$args['meta_value']  = $referral_code;
		$args = apply_filters( 'wcfmaf_get_affiliate_args', $args );
		$wcfm_affiliate_array = get_users( $args );
		
		if( empty( $wcfm_affiliate_array ) ) {
			return '';
		} else {
			foreach( $wcfm_affiliate_array as $wcfm_affiliate ) {
				return $wcfm_affiliate->ID;
			}
		}
		
		return '';
	}
}

if( !function_exists( 'wcfm_is_valid_affiliate' ) ) {
	function wcfm_is_valid_affiliate( $referral_code = '' ) {
		
		if( !$referral_code ) return apply_filters( 'wcfm_is_valid_affiliate', false );
		
		if( !wcfm_get_affiliate_by_referrer( $referral_code ) ) return apply_filters( 'wcfm_is_valid_affiliate', false );
		
		return apply_filters( 'wcfm_is_valid_affiliate', true );
	}
}

if( !function_exists( 'wcfm_get_affiliate_url' ) ) {
	function wcfm_get_affiliate_url( $affiliate_id = '', $normal_url = '' ) {
		if( !wcfm_is_affiliate( $affiliate_id ) ) return '';
		
		$affiliate_code = get_user_meta( $affiliate_id, 'affiliate_code', true );
		if( !$affiliate_code ) return '';
		
		if( !$normal_url ) $normal_url = get_bloginfo( 'url' );
		
		$valid_url = wp_http_validate_url( $normal_url );
		if( !$valid_url ) return '';
		
		$affiliate_url = add_query_arg( 'wreferrer', $affiliate_code, $normal_url );
		
		return apply_filters( 'wcfm_affiliate_url', $affiliate_url, $affiliate_id );
	}
}

if( !function_exists( 'wcfm_get_affiliate_affiliate_stat' ) ) {
	function wcfm_get_affiliate_affiliate_stat( $affiliate_id = '', $status = '' ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		if( !$affiliate_id && !is_user_logged_in() ) return 0;
		if( !wcfm_is_affiliate( $affiliate_id ) ) return 0;
		
		if( !$affiliate_id ) $affiliate_id = get_current_user_id();
		
		$sql  = "SELECT COUNT(ID) FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
		$sql .= " WHERE 1=1";
		$sql .= " AND affiliate = {$affiliate_id}";
		if( $status ) $sql .= " AND affiliate_status = '{$status}'";
		$sql .= ' AND `is_trashed` = 0';
		$affiliate_count = $wpdb->get_var( $sql );
		
		return $affiliate_count;
			
		return 0;
	}
}

if(!function_exists('get_wcfm_affiliates_url')) {
	function get_wcfm_affiliates_url() {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_affiliates_url = wcfm_get_endpoint_url( 'wcfm-affiliates', '', $wcfm_page );
		return $wcfmgs_affiliates_url;
	}
}

if(!function_exists('get_wcfm_affiliate_dashboard_url')) {
	function get_wcfm_affiliate_dashboard_url( ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_affiliate_url = wcfm_get_endpoint_url( 'wcfm-affiliate', '', $wcfm_page );
		return $wcfmgs_affiliate_url;
	}
}

if(!function_exists('get_wcfm_affiliate_manage_url')) {
	function get_wcfm_affiliate_manage_url( $affiliate_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_affiliate_manage_url = wcfm_get_endpoint_url( 'wcfm-affiliate-manage', $affiliate_id, $wcfm_page );
		return $wcfmgs_affiliate_manage_url;
	}
}

if(!function_exists('get_wcfm_affiliate_stats_url')) {
	function get_wcfm_affiliate_stats_url( $affiliate_id = '' ) {
		global $WCFM;
		$wcfm_page = get_wcfm_page();
		$wcfmgs_affiliate_stats_url = wcfm_get_endpoint_url( 'wcfm-affiliate-stats', $affiliate_id, $wcfm_page );
		return $wcfmgs_affiliate_stats_url;
	}
}

if(!function_exists('get_wcfmaf_affiliate_manage_messages')) {
	function get_wcfmaf_affiliate_manage_messages() {
		global $WCFMaf;
		
		$messages = array(
											'no_username'          => __( 'Please insert Affiliate Username before submit.', 'wc-frontend-manager-affiliate' ),
											'no_email'             => __( 'Please insert Affiliate Email before submit.', 'wc-frontend-manager-affiliate' ),
											'username_exists'      => __( 'This Username already exists.', 'wc-frontend-manager-affiliate' ),
											'email_exists'         => __( 'This Email already exists.', 'wc-frontend-manager-affiliate' ),
											'affiliate_failed'     => __( 'Affiliate Saving Failed.', 'wc-frontend-manager-affiliate' ),
											'affiliate_saved'      => __( 'Affiliate Successfully Saved.', 'wc-frontend-manager-affiliate' ),
											'email_invalid_code'   => __( 'Email verification code invalid.', 'wc-frontend-manager-affiliate' ),
											'sms_invalid_code'     => __( 'Phone verification code (OTP) invalid.', 'wc-frontend-manager-affiliate' ),
											'registration_failed'  => __( 'Registration Failed.', 'wc-frontend-manager-affiliate' ),
											'registration_success' => __( 'Registration Successfully Completed.', 'wc-frontend-manager-affiliate' ),
											);
		
		return $messages;
	}
}

if(!function_exists('wcfm_get_affiliate_commission_stat')) {
	function wcfm_get_affiliate_commission_stat( $affiliate_id, $status = '' ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		$sql = 'SELECT SUM( commission.commission_amount ) AS commission_amount FROM ' . $wpdb->prefix . 'wcfm_affiliate_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND affiliate_id = {$affiliate_id}";
		if( $status ) {
			$sql .= " AND `commission_status` = '{$status}'";
		}
		$sql .= ' AND `is_trashed` = 0';
		$commission_stat = $wpdb->get_var( $sql );
		
		if( !$commission_stat ) return 0;
		
		return $commission_stat;
	}
}

if(!function_exists('wcfm_get_affiliate_count_stat')) {
	function wcfm_get_affiliate_count_stat( $affiliate_id, $commissiion_type = '' ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		$sql = 'SELECT COUNT(commission.ID) AS count FROM ' . $wpdb->prefix . 'wcfm_affiliate_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND affiliate_id = {$affiliate_id}";
		if( $commissiion_type ) {
			$sql .= " AND `commission_type` = '{$commissiion_type}'";
		}
		$sql .= ' AND `is_trashed` = 0';
		$commission_stat = $wpdb->get_var( $sql );
		
		if( !$commission_stat ) return 0;
		
		return $commission_stat;
	}
}

if(!function_exists('wcfm_get_affiliate_vendors')) {
	function wcfm_get_affiliate_vendors( $affiliate_id ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		$affiliate_vendor_array = array();
		
		$sql = 'SELECT commission.vendor_id AS vendor FROM ' . $wpdb->prefix . 'wcfm_affiliate_orders AS commission';
		$sql .= ' WHERE 1=1';
		$sql .= " AND affiliate_id = {$affiliate_id}";
		$sql .= " AND `commission_type` = 'vendor'";
		$sql .= ' AND `vendor_id` != 0';
		$sql .= ' AND `is_trashed` = 0';
		$affiliate_vendors = $wpdb->get_results( $sql );
		
		if( !empty( $affiliate_vendors ) ) {
			foreach( $affiliate_vendors as $affiliate_vendor ) {
				$affiliate_vendor_array[$affiliate_vendor->vendor] = $affiliate_vendor->vendor; 
			}
		}
		
		return $affiliate_vendor_array;
	}
}

if(!function_exists('wcfm_aff_log')) {
	function wcfm_aff_log( $message, $level = 'debug' ) {
		wcfm_create_log( $message, $level, 'wcfm-affiliate' );
	}
}

?>