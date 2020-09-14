<?php
/**
 * WCFM Groups & Staffs plugin core
 *
 * Plugin Frontend Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/core
 * @version   1.0.1
 */
 
class WCFMgs_Frontend {
	
	public function __construct() {
		global $WCFM, $WCFMgs;
		
		// WCFM Menu Filter
		if( current_user_can('administrator') ) {
			//add_filter( 'wcfm_menus', array( &$this, 'wcfmgs_capability_menu' ), 250 );
			//add_filter( 'wcfm_menu_dependancy_map', array( &$this, 'wcfmgs_cpability_menu_dependancy_map' ) );
		}
		
		add_action( 'wcfmgs_capability_manager', array( &$this, 'wcfmgs_capability_manager' ), 20, 2 );
		
		if( $WCFMgs->is_marketplace ) {
			// WC Marketplace Registration Group Field
			if( $WCFMgs->is_marketplace == 'wcmarketplace' ) {
				add_action( 'wcmp_vendor_register_form', array( &$this, 'wcfmgs_wcmp_vendor_register_groups_field' ), 50 );
				add_action( 'woocommerce_register_post', array( &$this, 'wcfmgs_wcmp_validate_extra_register_fields' ), 50, 3);
				add_action( 'woocommerce_created_customer', array( &$this, 'wcfmgs_wcmp_save_extra_register_fields' ), 50, 3 );
			}
		
			// WC Product Vendors Registration Group Field
			if( $WCFMgs->is_marketplace == 'wcpvendors' ) {
				add_action( 'wcpv_registration_form', array( &$this, 'wcfmgs_wcpv_vendor_register_groups_field' ), 50 );
				add_filter( 'wcpv_shortcode_registration_form_validation_errors', array( &$this, 'wcfmgs_wcpv_validate_extra_register_fields' ), 50, 2 );
				add_action( 'wcpv_shortcode_registration_form_process', array( &$this, 'wcfmgs_wcpv_save_extra_register_fields' ), 50, 2 );
			}
			
			// Dokan Vendors Registration Group Field
			if( $WCFMgs->is_marketplace == 'dokan' ) {
				add_action( 'register_form', array( &$this, 'wcfmgs_dokan_vendor_register_groups_field' ), 50 );
				add_action( 'dokan_seller_registration_field_after', array( &$this, 'wcfmgs_dokan_vendor_register_groups_field' ), 50 );
				add_filter( 'dokan_seller_registration_required_fields', array( &$this, 'wcfmgs_dokan_validate_extra_register_fields' ), 50 );
				add_action( 'dokan_new_seller_created', array( &$this, 'wcfmgs_dokan_save_extra_register_fields' ), 50, 2 );
			}
		}
		
		add_action( 'end_wcfm_settings', array( &$this, 'wcfmgs_groups_staffs_settings' ), 20 );
		add_action( 'wcfm_settings_update', array( &$this, 'wcfmgs_groups_staffs_settings_update' ), 20 );
	}
	
	/**
   * WCFM Capability Menu
   */
  function wcfmgs_capability_menu( $menus ) {
  	global $WCFM;
  	
		$menus = array_slice($menus, 0, 6, true) +
												array( 'wcfm-capability' => array(   'label'  => __( 'Capability', 'wc-frontend-manager-groups-staffs'),
																										 'url'      => get_wcfm_capability_url(),
																										 'icon'     => 'user-times',
																										 'priority' => 80
																										) )	 +
													array_slice($menus, 6, count($menus) - 6, true) ;
		
  	return $menus;
  }
  
  /**
   * WCFM Capability Menu Dependency
   */
  function wcfmgs_cpability_menu_dependancy_map( $menu_dependency_mapping ) {
  	unset( $menu_dependency_mapping['wcfm-capability'] );
  	return $menu_dependency_mapping;
  }
	
	function wcfmgs_capability_manager( $capability_manager_options, $screen_type = 'vendor' ) {
		global $WCFM, $WCFMgs, $wcfmgs_capability_manager_options, $wcfm_screen_type;
		
		$wcfmgs_capability_manager_options = $capability_manager_options;
		$wcfm_screen_type = $screen_type;
		
		include_once( $WCFMgs->library->views_path . 'wcfmgs-view-capability-manage.php' );
	}
	
	function wcfmgs_wcmp_vendor_register_groups_field() {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'ASC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'wcfm_vendor_groups',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => true 
						);
		
		$args = apply_filters( 'wcfm_vendor_groups_args', $args );
		
		$wcfm_groups_array = get_posts( $args );
		if(!empty($wcfm_groups_array)) {
			?>
			<div class="wcmp-regi-12">
				<label><?php _e( 'Preferred Group', 'wc-frontend-manager-groups-staffs' ); ?> <span class="required">*</span></label>
				<select class="select_box" name="wcfm_vendor_group" required="required">
				  <option value=""><?php _e( 'Choose your preferred group ...', 'wc-frontend-manager-groups-staffs' ); ?></option>
					<?php
					foreach($wcfm_groups_array as $wcfm_groups_single) {
						?>
						<option value="<?php echo $wcfm_groups_single->ID; ?>"><?php echo $wcfm_groups_single->post_title; ?></option>
						<?php
					}
					?>
				</select>
			</div>
			<?php
		}
	}
	
  function wcfmgs_wcmp_validate_extra_register_fields( $username, $email, $validation_errors ) {
  	global $WCFM, $WCFMgs;
  	
  	$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
  	if( $wcfm_group_auto_assign == 'no' ) return;
  	
  	if ( isset( $_POST['wcfm_vendor_group'] ) && empty( $_POST['wcfm_vendor_group'] ) ) {
  		$validation_errors->add('group missing', __( 'Please choose your preferred group', 'wc-frontend-manager-groups-staffs' ) );
  	}
  }
  
  function wcfmgs_wcmp_save_extra_register_fields( $vendor_id ) {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		if ( isset( $_POST['wcfm_vendor_group'] ) && !empty( $_POST['wcfm_vendor_group'] ) ) {
			$group_id = $_POST['wcfm_vendor_group'];
			update_user_meta( $vendor_id, '_wcfm_vendor_group', array($group_id) );
			update_user_meta( $vendor_id, '_wcfm_vendor_group_list', implode( ",", array($group_id) ) );
			$group_vendors = (array) get_post_meta( $group_id, '_group_vendors', true );
			$group_vendors[] = $vendor_id;
			update_post_meta( $group_id, '_group_vendors', $group_vendors );
		}
	}
	
	function wcfmgs_wcpv_vendor_register_groups_field() {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		$args = array(
							'posts_per_page'   => $length,
							'offset'           => $offset,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'ASC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'wcfm_vendor_groups',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => true 
						);
		
		$args = apply_filters( 'wcfm_vendor_groups_args', $args );
		
		$wcfm_groups_array = get_posts( $args );
		if(!empty($wcfm_groups_array)) {
			?>
			<p class="form-row form-row-wide">
			  <label for="wcpv-wcfm-group"><?php _e( 'Preferred Group', 'wc-frontend-manager-groups-staffs' ); ?> <span class="required">*</span></label>
				<select class="input-text select_box" name="wcfm_vendor_group" required="required">
				  <option value=""><?php _e( 'Choose your preferred group ...', 'wc-frontend-manager-groups-staffs' ); ?></option>
					<?php
					foreach($wcfm_groups_array as $wcfm_groups_single) {
						?>
						<option value="<?php echo $wcfm_groups_single->ID; ?>"><?php echo $wcfm_groups_single->post_title; ?></option>
						<?php
					}
					?>
				</select>
			</p>
			<?php
		}
	}
	
  function wcfmgs_wcpv_validate_extra_register_fields( $errors, $form_items ) {
  	global $WCFM, $WCFMgs;
  	
  	$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
  	if( $wcfm_group_auto_assign == 'no' ) return $errors;
  	
  	if ( isset( $form_items['wcfm_vendor_group'] ) && empty( $form_items['wcfm_vendor_group'] ) ) {
  		$errors[] = __( 'Please choose your preferred group', 'wc-frontend-manager-groups-staffs' );
  	}
  	
  	return $errors;
  }
  
  function wcfmgs_wcpv_save_extra_register_fields( $args, $items ) {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		if ( isset( $items['wcfm_vendor_group'] ) && !empty( $items['wcfm_vendor_group'] ) ) {
			$vendor_id = $args['user_id'];
			$group_id = $items['wcfm_vendor_group'];
			update_user_meta( $vendor_id, '_wcfm_vendor_group', array($group_id) );
			update_user_meta( $vendor_id, '_wcfm_vendor_group_list', implode( ",", array($group_id) ) );
			$group_vendors = (array) get_post_meta( $group_id, '_group_vendors', true );
			$group_vendors[] = $vendor_id;
			update_post_meta( $group_id, '_group_vendors', $group_vendors );
		}
	}
	
	function wcfmgs_dokan_vendor_register_groups_field() {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'category'         => '',
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'ASC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'wcfm_vendor_groups',
							'post_mime_type'   => '',
							'post_parent'      => '',
							//'author'	   => get_current_user_id(),
							'post_status'      => array('draft', 'pending', 'publish'),
							'suppress_filters' => true 
						);
		
		$args = apply_filters( 'wcfm_vendor_groups_args', $args );
		
		$wcfm_groups_array = get_posts( $args );
		if(!empty($wcfm_groups_array)) {
			?>
			<p class="form-row form-row-wide">
			  <label for="wcpv-wcfm-group"><?php _e( 'Preferred Group', 'wc-frontend-manager-groups-staffs' ); ?> <span class="required">*</span></label>
				<select class="input-text select_box" name="wcfm_vendor_group" required="required">
				  <option value=""><?php _e( 'Choose your preferred group ...', 'wc-frontend-manager-groups-staffs' ); ?></option>
					<?php
					foreach($wcfm_groups_array as $wcfm_groups_single) {
						?>
						<option value="<?php echo $wcfm_groups_single->ID; ?>"><?php echo $wcfm_groups_single->post_title; ?></option>
						<?php
					}
					?>
				</select>
			</p>
			<?php
		}
	}
	
  function wcfmgs_dokan_validate_extra_register_fields( $required_fields ) {
  	global $WCFM, $WCFMgs;
  	
  	$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
  	if( $wcfm_group_auto_assign == 'no' ) return $errors;
  	
  	$required_fields['wcfm_vendor_group'] = __( 'Please choose your preferred group', 'wc-frontend-manager-groups-staffs' );
  	
  	return $required_fields;
  }
  
  function wcfmgs_dokan_save_extra_register_fields( $vendor_id, $dokan_settings ) {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		if( $wcfm_group_auto_assign == 'no' ) return;
		
		if ( isset( $_POST['wcfm_vendor_group'] ) && !empty( $_POST['wcfm_vendor_group'] ) ) {
			$group_id = absint($_POST['wcfm_vendor_group']);
			update_user_meta( $vendor_id, '_wcfm_vendor_group', array($group_id) );
			update_user_meta( $vendor_id, '_wcfm_vendor_group_list', implode( ",", array($group_id) ) );
			$group_vendors = (array) get_post_meta( $group_id, '_group_vendors', true );
			$group_vendors[] = $vendor_id;
			update_post_meta( $group_id, '_group_vendors', $group_vendors );
		}
	}
	
	function wcfmgs_groups_staffs_settings( $wcfm_options ) {
		global $WCFM, $WCFMgs;
		
		$wcfm_group_auto_assign = get_option( 'wcfm_group_auto_assign' ) ? get_option( 'wcfm_group_auto_assign' ) : 'no';
		
		$new_account_mail_subject = "{site_name}: New Account Created";
		$new_account_mail_body = __( 'Dear', 'wc-frontend-manager-groups-staffs' ) . ' {first_name}' .
														 ',<br/><br/>' . 
														 __( 'Your account has been created as {user_role}. Follow the bellow details to log into the system', 'wc-frontend-manager-groups-staffs' ) .
														 '<br/><br/>' . 
														 __( 'Site', 'wc-frontend-manager-groups-staffs' ) . ': {site_url}' . 
														 '<br/>' .
														 __( 'Login', 'wc-frontend-manager-groups-staffs' ) . ': {username}' .
														 '<br/>' . 
														 __( 'Password', 'wc-frontend-manager-groups-staffs' ) . ': {password}' .
														 '<br /><br/>Thank You';
														 
		$wcfmgs_new_account_mail_subject = wcfm_get_option( 'wcfmgs_new_account_mail_subject' );
		if( $wcfmgs_new_account_mail_subject ) $new_account_mail_subject =  $wcfmgs_new_account_mail_subject;
		$wcfmgs_new_account_mail_body = wcfm_get_option( 'wcfmgs_new_account_mail_body' );
		if( $wcfmgs_new_account_mail_body ) $new_account_mail_body =  $wcfmgs_new_account_mail_body;
		?>
		<!-- collapsible -->
		<div class="page_collapsible" id="wcfm_settings_form_groups_staffs_head">
			<label class="wcfmfa fa-users"></label>
			<?php _e('Groups & Staffs', 'wc-frontend-manager-groups-staffs'); ?><span></span>
		</div>
		<div class="wcfm-container">
			<div id="wcfm_settings_form_groups_staffs_expander" class="wcfm-content">
			  <h2><?php _e('Groups & Staffs Settings', 'wc-frontend-manager-groups-staffs'); ?></h2>
			  <div class="wcfm_clearfix"></div>
				<?php
					$rich_editor = apply_filters( 'wcfm_is_allow_rich_editor', 'rich_editor' );
					$wpeditor = apply_filters( 'wcfm_is_allow_settings_wpeditor', 'wpeditor' );
					if( $wpeditor && $rich_editor ) {
						$rich_editor = 'wcfm_wpeditor';
					} else {
						$wpeditor = 'textarea';
					}
					
					$group_auto_assign = '';
					if( $WCFM->is_marketplace && ( $WCFM->is_marketplace == 'wcfmmarketplace' ) ) $group_auto_assign = 'wcfm_custom_hide';
					$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfmgs_settings_fields_general', array(
																																																"wcfm_group_auto_assign" => array('label' => __('Group auto-assignment on Registration', 'wc-frontend-manager-groups-staffs'), 'name' => 'wcfm_group_auto_assign', 'type' => 'checkbox', 'class' => 'wcfm-checkbox wcfm_ele '. $group_auto_assign, 'label_class' => 'wcfm_title checkbox_title wcfm_ele '. $group_auto_assign, 'value' => 'yes', 'dfvalue' => $wcfm_group_auto_assign ),
																																																"new_account_mail_subject" => array('label' => __('New account mail subject', 'wc-frontend-manager-groups-staffs'), 'name' => 'new_account_mail_subject', 'type' => 'text', 'class' => 'wcfm-text wcfm_ele', 'label_class' => 'wcfm_title wcfm_ele', 'value' => $new_account_mail_subject ),
																																																"shop_description" => array('label' => __('New account mail body', 'wc-frontend-manager-groups-staffs'), 'name' => 'new_account_mail_body', 'type' => $wpeditor, 'class' => 'wcfm-textarea wcfm_ele ' . $rich_editor, 'label_class' => 'wcfm_title', 'desc_class' => 'instructions', 'value' => $new_account_mail_body, 'desc' => __('Allowed dynamic variables are: {site_url}, {user_role}, {username}, {first_name}, {password}', 'wc-frontend-manager-groups-staffs') ),
																																																) ) );
				?>
			</div>
		</div>
		<div class="wcfm_clearfix"></div>
		<!-- end collapsible -->
		
		<?php
	}
	
	function wcfmgs_groups_staffs_settings_update( $wcfm_settings_form ) {
		global $WCFM, $WCFMgs, $_POST;
		
		if( isset( $wcfm_settings_form['wcfm_group_auto_assign']) ) {
			update_option( 'wcfm_group_auto_assign', 'yes' );
		} else {
			update_option( 'wcfm_group_auto_assign', 'no' );
		}
		
		if( isset( $wcfm_settings_form['new_account_mail_subject'] ) ) {
			$new_account_mail_subject = $wcfm_settings_form['new_account_mail_subject'];
			wcfm_update_option( 'wcfmgs_new_account_mail_subject',  $new_account_mail_subject );
		}
		
		if( isset( $_POST['profile'] ) ) {
			$new_account_mail_body = stripslashes( html_entity_decode( $_POST['profile'], ENT_QUOTES, 'UTF-8' ) );
			wcfm_update_option( 'wcfmgs_new_account_mail_body',  $new_account_mail_body );
		}
		
	}
	
}