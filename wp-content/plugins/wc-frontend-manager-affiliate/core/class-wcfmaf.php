<?php

/**
 * WCFM Affiliate plugin
 *
 * WCFM Affiliate Core
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.0
 */

class WCFMaf {

	public $plugin_base_name;
	public $plugin_url;
	public $plugin_path;
	public $version;
	public $token;
	public $text_domain;
	public $library;
	public $template;
	public $shortcode;
	public $admin;
	public $frontend;
	public $ajax;
	private $file;
	public $settings;
	public $license;
	public $WCFMaf_fields;
	public $is_marketplace;
	public $WCFMaf_marketplace;
	public $WCFMaf_capability;
	public $wcfmaf_non_ajax;
	public $wcfmaf_setting_options;

	public function __construct($file) {

		$this->file = $file;
		$this->plugin_base_name = plugin_basename( $file );
		$this->plugin_url = trailingslashit(plugins_url('', $plugin = $file));
		$this->plugin_path = trailingslashit(dirname($file));
		$this->token = WCFMaf_TOKEN;
		$this->text_domain = WCFMaf_TEXT_DOMAIN;
		$this->version = WCFMaf_VERSION;
		
		// Installer Hook
		add_action( 'init', array( &$this, 'run_wcfmaf_installer' ) );
		
		add_action( 'init', array(&$this, 'init') );
		
		add_action( 'wcfm_init', array( &$this, 'init_wcfmaf' ), 14 );
		
		add_action( 'wp', array( &$this, 'wcfmaf_init_after_wp' ), 100 );
		
		add_filter( 'wcfm_modules',  array( &$this, 'get_wcfmaf_modules' ), 22 );
		
		// Vendor Affiliate Commission Update
		add_action( 'wcfmmp_new_store_created', array( &$this, 'wcfmaf_affiliate_vendor_registration_commission' ), 200, 2 );
		
		// Update Marketplace Order Status on WC Order Status changed
		add_action( 'woocommerce_order_status_changed', array(&$this, 'wcfmaf_order_status_changed'), 40, 3 );
		
		// On Order Item Refund
		add_action( 'woocommerce_order_refunded', array(&$this, 'wcfmaf_commission_order_item_refund' ), 40, 2 );
		
		// On Order Item Refund Request Approve
		add_action( 'wcfmmp_refund_status_completed', array(&$this, 'wcfmaf_commission_order_item_refund_approve' ), 40, 4 );
		
		// ON Delete Order Item Delete Commision Order
		add_action( 'woocommerce_before_delete_order_item', array(&$this, 'wcfmaf_commission_order_item_delete' ), 30 );
		add_action( 'woocommerce_delete_order_item', array(&$this, 'wcfmaf_commission_order_item_delete' ), 30 );
		
		// ON Trashed Order Trash Commision Order
		add_action( 'woocommerce_trash_order', array(&$this, 'wcfmaf_commission_order_trash' ), 30 );
		add_action( 'wp_trash_post', array(&$this, 'wcfmaf_commission_order_trash' ), 30 );
		
		// ON Delete Order delete Commision Order
		add_action( 'woocommerce_delete_order', array(&$this, 'wcfmaf_commission_order_delete' ), 30 );
		add_action( 'before_delete_post', array(&$this, 'wcfmaf_commission_order_delete' ), 30 );
	}
	
	/**
	 * initilize plugin on WP init
	 */
	function init() {
		global $WCFM, $WCFMaf;
		
		$this->wcfmaf_setting_options = get_option( 'wcfm_affiliate_options', array() );
		
		// Init Text Domain
		$this->load_plugin_textdomain();
		
		// WCfM License Activation
		if (is_admin()) {
			$this->load_class('license');
			$this->license = WCFMaf_LICENSE();
		}
		
		if( !defined('DOING_AJAX') ) {
			$this->load_class( 'non-ajax' );
			$this->wcfmaf_non_ajax = new WCFMaf_Non_Ajax();
		}
	}
		
	function init_wcfmaf() {
		global $WCFM, $WCFMaf;
		
		// Capability Controller
		if (!is_admin() || defined('DOING_AJAX')) {
			//$this->load_class( 'capability' );
			//$this->wcfmaf_capability = new WCFMaf_Capability();
		}
		
		// Check Marketplace
		$this->is_marketplace = wcfm_is_marketplace();
		
		if ( !is_admin() || defined('DOING_AJAX') ) {
			if( $this->is_marketplace ) {
				if( wcfm_is_vendor()) {
					$this->load_class( $this->is_marketplace );
					if( $this->is_marketplace == 'wcfmmarketplace' ) $this->wcfmaf_marketplace = new WCFMaf_Marketplace();
				}
			}
		}

		// Init library
		$this->load_class('library');
		$this->library = new WCFMaf_Library();

		// Init ajax
		if (defined('DOING_AJAX')) {
			$this->load_class('ajax');
			$this->ajax = new WCFMaf_Ajax();
		}

		if (!is_admin() || defined('DOING_AJAX')) {
			$this->load_class('frontend');
			$this->frontend = new WCFMaf_Frontend();
		}
		
		// Template loader
		$this->load_class( 'template' );
		$this->template = new WCFMaf_Template();
		
		// Init shortcode
		$this->load_class( 'shortcode' );
		$this->shortcode = new WCFMaf_Shortcode();
		
		$this->wcfmaf_fields = $WCFM->wcfm_fields;
	}
	
	/**
	 * Set Referal Code at User Session
	 */
	function wcfmaf_init_after_wp() {
		// Referral Code listener
    $this->wcfmaf_affiliate_listener();
	}
	
	function send_affiliate_approval_reminder_admin( $member_id ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		if( !$member_id ) return;
		
		$member_id       = absint( $member_id );
		$member_user     = new WP_User( absint( $member_id ) );
		
		if( !defined( 'DOING_WCFM_EMAIL' ) ) 
			define( 'DOING_WCFM_EMAIL', true );
		
		// Vendor Approval Admin Email Notification
		$onapproval_admin_notication_subject = wcfm_get_option( 'wcfm_affiliate_onapproval_admin_notication_subject', '{site_name}: A vendor application waiting for approval' );
		$onapproval_admin_notication_content = wcfm_get_option( 'wcfm_affiliate_onapproval_admin_notication_content', '' );
		if( !$onapproval_admin_notication_content ) {
			$onapproval_admin_notication_content = "Dear Admin,
																							<br /><br />
																							A new user <b>{affiliate_name}</b> has just applied to become affiliate for the site. 
																							<br /><br />
																							Kindly follow the below the link to approve/reject the application.
																							<br /><br />
																							Application: {notification_url} 
																							<br /><br />
																							Thank You";
		}
														 
		$subject = str_replace( '{site_name}', get_bloginfo( 'name' ), $onapproval_admin_notication_subject );
		$subject = apply_filters( 'wcfm_email_subject_wrapper', $subject );
		$message = str_replace( '{dashboard_url}', get_wcfm_url(), $onapproval_admin_notication_content );
		$message = str_replace( '{affiliate_name}', $member_user->first_name, $message );
		$message = str_replace( '{notification_url}', '<a href="' . get_wcfm_messages_url() . '">' . __( 'Check Here ...', 'wc-multivendor-membership' ) . '</a>', $message );
		$message = apply_filters( 'wcfm_email_content_wrapper', $message, __( 'Affiliate Approval', 'wc-frontend-manager-affiliate' ) );
		
		wp_mail( apply_filters( 'wcfm_admin_email_notification_receiver', get_bloginfo( 'admin_email' ), 'affiliate_approval' ), $subject, $message ); 
		
		// Vendor Approval Admin Desktop Notification
		$author_id = $member_id;
		$author_is_admin = 0;
		$author_is_vendor = 1;
		$message_to = 0;
		$is_notice = 0;
		$is_direct_message = 1;
		$wcfm_messages_type = 'affiliate_approval';
		$wcfm_messages = sprintf( __( '<b>%s</b> affiliate application waiting for approval.', 'wc-frontend-manager-affiliate' ), $member_user->first_name );
		$wcfm_messages = esc_sql( $wcfm_messages );
		$wcfm_create_message     = "INSERT into {$wpdb->prefix}wcfm_messages 
														(`message`, `author_id`, `author_is_admin`, `author_is_vendor`, `is_notice`, `is_direct_message`, `message_to`, `message_type`)
														VALUES
														('{$wcfm_messages}', {$author_id}, {$author_is_admin}, {$author_is_vendor}, {$is_notice}, {$is_direct_message}, {$message_to}, '{$wcfm_messages_type}')";
											
		$wpdb->query($wcfm_create_message);
		
		update_user_meta( $member_id, 'wcfm_affiliate_application_status', 'pending' );
		
		do_action( 'wcfm_affiliate_approval_reminder_admin_after', $member_id );
	}
	
	/**
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 *
	 * @access public
	 * @return void
	 */
	public function load_plugin_textdomain() {
		$locale = function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
		$locale = apply_filters( 'plugin_locale', $locale, 'wc-frontend-manager-affiliate' );

		//load_textdomain( 'wc-frontend-manager-affiliate', WP_LANG_DIR . "/wc-frontend-manager-affiliate/wc-frontend-manager-affiliate-$locale.mo");
		load_textdomain( 'wc-frontend-manager-affiliate', $this->plugin_path . "lang/wc-frontend-manager-affiliate-$locale.mo");
		load_textdomain( 'wc-frontend-manager-affiliate', ABSPATH . "wp-content/languages/plugins/wc-frontend-manager-affiliate-$locale.mo");
	}
	
	/**
	 * List of WCFM Affiliate modules
	 */
	function get_wcfmaf_modules( $wcfm_modules ) {
		$wcfmaf_modules = array(
			                    'affiliate'             	=> array( 'label' => __( 'Affiliate', 'wc-frontend-manager-affiliate' ) ),
													);
		$wcfm_modules = array_merge( $wcfm_modules, $wcfmaf_modules );
		return $wcfm_modules;
	}
	
	/**
	 * Vendor Registration Affiliate Commission Generate
	 */
	function wcfmaf_affiliate_vendor_registration_commission( $member_id, $wcfmmp_settings ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		$wcfm_register_member = get_user_meta( $member_id, 'wcfm_register_member', true );
		if( $wcfm_register_member ) return;
		
		$wcfm_affiliate = get_user_meta( $member_id, '_wcfm_affiliate', true );
		
		if( !$wcfm_affiliate && !current_user_can( 'administrator' ) ) {
			if( !WC()->session ) return;
		
			$wcfm_affiliate = WC()->session->get( 'wcfm_affiliate' );
			if( $wcfm_affiliate ) {
				$wcfm_affiliate = absint( $wcfm_affiliate );
				
				if( $wcfm_affiliate ) {
					update_user_meta( $member_id, '_wcfm_affiliate', $wcfm_affiliate );
				}
			}
		}
		
		if( $wcfm_affiliate ) {
			$commission_amount = 0;
			
			$wcfm_affiliate = absint( $wcfm_affiliate );
			$commission = get_user_meta( $wcfm_affiliate, 'wcfm_affiliate_commission', true );
			if( !$commission ) {
				$commission = array();
			}
			
			// Commission Rule Check
			$membership_id = '';
			$commission_rule = isset( $commission['rule'] ) ? $commission['rule'] : 'personal';
			if( $commission_rule != 'personal' ) {
				$commission = get_option( 'wcfm_affiliate_commission', array() );
				
				// Membership Affiliate Commission Check
				$wcfm_membership = get_user_meta( $member_id, 'temp_wcfm_membership', true );
				$wcfm_membership = absint($wcfm_membership);
				if( $wcfm_membership ) {
					if( ( $wcfm_membership != -1 ) && ( $wcfm_membership != '-1' ) ) {
						$affiliate_membership_commission = get_post_meta( $wcfm_membership, 'wcfm_affiliate_commission', true );
						if( $affiliate_membership_commission ) {
							$membership_commission_rule = isset( $affiliate_membership_commission['rule'] ) ? $affiliate_membership_commission['rule'] : 'global';
							if( $membership_commission_rule == 'personal' ) {
								$membership_id = $wcfm_membership;
								$commission = $affiliate_membership_commission;
							}
						}
					}
				}
			}
			
			$mode = isset( $commission['vendor']['mode'] ) ? $commission['vendor']['mode'] : 'no-commission';
			if( !$mode ) {
				$commission['vendor']['mode'] = 'no-commission';
				$commission['vendor']['percent'] = 0;
				$commission['vendor']['fixed'] = 0;
				$mode = 'no-commission';
			}
			
			if( $mode ) {
				wcfm_aff_log( "WCFMAF Vendor Commission Generate:: Vendor => " . $member_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
				
				$percent = isset( $commission['vendor']['percent'] ) ? $commission['vendor']['percent'] : '';
				$fixed = isset( $commission['vendor']['fixed'] ) ? $commission['vendor']['fixed'] : '';
				$commission_rule = array( 'mode' => $mode, 'percent' => $percent, 'fixed' => $fixed );
				if( $mode && ( $mode == 'no-commission' ) ) {
					$commission_amount = 0;
				} elseif( $mode && ( $mode == 'fixed' ) ) {
					$commission_amount = $fixed;
				} else {
					if( $membership_id ) {
						$subscription = (array) get_post_meta( $membership_id, 'subscription', true );
						$is_free = isset( $subscription['is_free'] ) ? 'yes' : 'no';
						if( $is_free == 'no' ) {
							$subscription_type = isset( $subscription['subscription_type'] ) ? $subscription['subscription_type'] : 'one_time';
							if( $subscription_type == 'one_time' ) {
								$one_time_amt = isset( $subscription['one_time_amt'] ) ? floatval($subscription['one_time_amt']) : '1';
								$commission_amount = wc_format_decimal( $one_time_amt * ($percent/100) );
							} else {
								$trial_amt = isset( $subscription['trial_amt'] ) ? $subscription['trial_amt'] : '0';
								if( $trial_amt ) {
									$commission_amount = wc_format_decimal( $trial_amt * ($percent/100) );
								} else {
									$billing_amt = isset( $subscription['billing_amt'] ) ? floatval($subscription['billing_amt']) : '1';
									$commission_amount = wc_format_decimal( $billing_amt * ($percent/100) );
								}
							}
						} else { $commission_amount = $fixed; }
					} else { $commission_amount = $fixed; }
				}
				
				//if( !$commission_amount ) return;
				
				$wpdb->query(
							$wpdb->prepare(
								"INSERT INTO `{$wpdb->prefix}wcfm_affiliate_orders` 
										( affiliate_id
										, vendor_id
										, order_id
										, order_commission_id
										, product_id
										, variation_id
										, quantity
										, product_price
										, item_id
										, item_type
										, item_sub_total
										, item_total
										, commission_type
										, commission_amount
										, created
										) VALUES ( %d
										, %d
										, %d
										, %d
										, %d
										, %d 
										, %d
										, %s
										, %d
										, %s
										, %s
										, %s
										, %s
										, %s
										, %s
										)"
								, $wcfm_affiliate
								, $member_id
								, 0
								, 0
								, 0
								, 0
								, 1
								, 0
								, 0
								, 0
								, 0
								, 0
								, 'vendor'
								, $commission_amount
								, date( 'Y-m-d H:i:s', current_time( 'timestamp', 0 ) )
					)
				);
				$affiliate_order_id = $wpdb->insert_id;
				$this->wcfmaf_update_affiliate_order_meta( $affiliate_order_id, 'commission_rule', serialize( $commission_rule ) );
				
				$wcfm_affiliate_user = get_userdata( $wcfm_affiliate );
				$affiliate_user_name = $wcfm_affiliate_user->display_name;
				if( $wcfm_affiliate_user->first_name && $wcfm_affiliate_user->last_name ) {
					$affiliate_user_name = $wcfm_affiliate_user->first_name . ' ' . $wcfm_affiliate_user->last_name;
				}
				
				// Affiliate Notifiction
				$wcfm_messages = sprintf( __( 'You have received commission <b>%s</b> for new vendor registration.', 'wc-frontend-manager-affiliate' ), wc_price( $commission_amount ) );
				$WCFM->wcfm_notification->wcfm_send_direct_message( -1, $wcfm_affiliate, 1, 0, $wcfm_messages, 'affiliate_commission' );
				
				// Admin Notifiction
				$wcfm_messages = sprintf( __( '<b>%s</b> has received affiliate commission <b>%s</b> for new vendor registration.', 'wc-frontend-manager-affiliate' ), $affiliate_user_name, wc_price( $commission_amount ) );
				$WCFM->wcfm_notification->wcfm_send_direct_message( -2, 0, 0, 0, $wcfm_messages, 'affiliate_commission' );
			} else {
				wcfm_aff_log( "NO Affiliate Commission for this registration Vendor => " . $member_id . " Affiliate => "  . $wcfm_affiliate . " Rule => " . json_encode( $commission ) );
			}
		}
	}
	
	function wcfmaf_update_affiliate_order_meta( $affiliate_order_id, $key, $value ) {
		global $WCFM, $WCFMaf, $wpdb;
		
		$wpdb->query(
						$wpdb->prepare(
							"INSERT INTO `{$wpdb->prefix}wcfm_affiliate_orders_meta` 
									( order_affiliate_id
									, `key`
									, `value`
									) VALUES ( %d
									, %s
									, %s
									)"
							, $affiliate_order_id
							, $key
							, $value
			)
		);
		$affiliate_meta_id = $wpdb->insert_id;
		return $affiliate_meta_id;
	}
	
	/**
	 * Get Commission metas
	 */
	public function wcfmaf_get_affiliate_order_meta( $affiliate_order_id, $key ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$commission_meta = $wpdb->get_var( 
						$wpdb->prepare(
							"SELECT `value` FROM `{$wpdb->prefix}wcfm_affiliate_orders_meta` 
							     WHERE 
							     `order_affiliate_id` = %d
									  AND `key` = %s
									"
							, $affiliate_order_id
							, $key
			)
		);
		return $commission_meta;
	}
	
	/**
	 * Affiliate Order Status update on WC Order status change
	 */
	function wcfmaf_order_status_changed( $order_id, $status_from, $status_to ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		$commission_cancel_order_status  = apply_filters( 'wcfmaf_commission_auto_cancel_order_status', array( 'cancelled', 'failed', 'refunded' ) );
		
		if( in_array( $status_to, $commission_cancel_order_status ) ) {
			$this->wcfmaf_commission_order_trash( $order_id );
		} else {
			 $this->wcfmaf_commission_order_untrash( $order_id );
		}
		do_action( 'wcfmaf_order_status_updated', $order_id, $status_from, $status_to );
	}
	
	/**
	 * Commission Order item refresh on Order Item Refund - WC Order Action
	 */
	function wcfmaf_commission_order_item_refund( $order_id, $refund_id ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if ( did_action( 'wp_ajax_woocommerce_refund_line_items' ) ) {
			$refund = new WC_Order_Refund( $refund_id );
			if ( is_a( $refund , 'WC_Order_Refund' ) ) {
				$items = $refund->get_items();
				if( !empty( $items ) ) {
					foreach( $items as $order_item_id => $refunded_item ) {
						$item_data = $refunded_item->get_meta_data( '_refunded_item_id', true );
						if( isset( $item_data[0] ) && !empty( $item_data[0]->value ) ) {
							$this->wcfmaf_commission_order_item_delete( $item_data[0]->value );
						}
					}
				}
			}
		}
	}
	
	/**
	 * Commission Order item refresh on Order Item Refund - WC Order Action
	 */
	function wcfmaf_commission_order_item_refund_approve( $refund_id, $order_id, $vendor_id, $refund ) {
		global $WCFM, $WCFMmp, $wpdb;
		
		if ( is_a( $refund , 'WC_Order_Refund' ) ) {
			$items = $refund->get_items();
			if( !empty( $items ) ) {
				foreach( $items as $order_item_id => $refunded_item ) {
					$item_data = $refunded_item->get_meta_data( '_refunded_item_id', true );
					if( isset( $item_data[0] ) && !empty( $item_data[0]->value ) ) {
						$this->wcfmaf_commission_order_item_delete( $item_data[0]->value );
					}
				}
			}
		}
	}
	
	/**
	 * Commission Order Delete on Order Item Delete - WC Order Action
	 */
	function wcfmaf_commission_order_item_delete( $item_id ) {
		global $wpdb;
		
		$affiliate_orders = $wpdb->get_results(  $wpdb->prepare( "SELECT ID from {$wpdb->prefix}wcfm_affiliate_orders WHERE `item_id` = %d", $item_id ) );
		foreach( $affiliate_orders as $affiliate_order ) {
			$this->wcfmaf_vendor_commission_reset_on_affiliate_order_delete( $affiliate_order->ID );
			$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_affiliate_orders_meta WHERE order_affiliate_id = %d", $affiliate_order->ID ) );
		}
		
		$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_affiliate_orders WHERE `item_id` = %d", $item_id ) );
	}
	
	/**
	 * Commission Order Un Trash on Order Retrive
	 */
	function wcfmaf_commission_order_untrash( $order_id ) {
		global $wpdb;
		$wpdb->update("{$wpdb->prefix}wcfm_affiliate_orders", array('is_trashed' => 0), array('order_id' => $order_id), array('%d'), array('%d'));
	}
	
	/**
	 * Commission Order Trash on Order Trashed
	 */
	function wcfmaf_commission_order_trash( $order_id ) {
		global $wpdb;
		
		if ( in_array( get_post_type( $order_id ), wc_get_order_types(), true ) ) {
			$order = wc_get_order( $order_id );
			if ( is_a( $order , 'WC_Order' ) ) {
				$wpdb->update("{$wpdb->prefix}wcfm_affiliate_orders", array('is_trashed' => 1), array('order_id' => $order_id), array('%d'), array('%d'));
			}
		}
	}
	
	/**
	 * Commission Order Delete on Order Delete
	 */
	function wcfmaf_commission_order_delete( $order_id ) {
		global $wpdb;
		
		if ( in_array( get_post_type( $order_id ), wc_get_order_types(), true ) ) {
			$order = wc_get_order( $order_id );
			if ( is_a( $order , 'WC_Order' ) ) {
				$affiliate_orders = $wpdb->get_results(  $wpdb->prepare( "SELECT ID from {$wpdb->prefix}wcfm_affiliate_orders WHERE `order_id` = %d", $order_id ) );
				foreach( $affiliate_orders as $affiliate_order ) {
					$this->wcfmaf_vendor_commission_reset_on_affiliate_order_delete( $affiliate_order->ID );
					$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_affiliate_orders_meta WHERE order_affiliate_id = %d", $affiliate_order->ID ) );
				}
				
				$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}wcfm_affiliate_orders WHERE `order_id` = %d", $order_id ) );
			}
		}
	}
	
	/**
	 * Vendor Commission Reset on Affiliate Order Delete
	 */
	 function wcfmaf_vendor_commission_reset_on_affiliate_order_delete( $affiliate_id ) {
	   global $WCFM, $WCFMaf, $WCFMmp, $wpdb;
	   
	   if( $affiliate_id ) {
			$sql  = "SELECT * FROM `{$wpdb->prefix}wcfm_affiliate_orders`";
			$sql .= " WHERE 1=1";
			$sql .= " AND ID = {$affiliate_id}";
			$affiliate_details = $wpdb->get_results( $sql );
			
			if( !empty( $affiliate_details ) ) {
				foreach( $affiliate_details as $affiliate_detail ) {
					
					// For commission based affiliate restrore vendor commission
					$commission_id = $WCFMaf->wcfmaf_get_affiliate_order_meta( $affiliate_id, 'vendor_commission' );
					if( $commission_id ) {
						$commission_rule = unserialize( $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, '_wcfm_affiliate_commission_rule' ) );
						if( $commission_rule && is_array( $commission_rule ) && isset( $commission_rule['cal_mode'] ) && ( $commission_rule['cal_mode'] == 'on_commission' ) ) {
							$affiliate_commission = $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, '_wcfm_affiliate_commission' );
							if( $affiliate_commission ) {
								$total_commission = $wpdb->get_var( "SELECT `total_commission` FROM {$wpdb->prefix}wcfm_marketplace_orders WHERE ID = {$commission_id}" );
								if( $total_commission ) {
									$total_commission = (float) $total_commission + (float) $affiliate_commission;
									$order_commission_rule = unserialize( $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, 'commission_rule' ) );
									if( $order_commission_rule && is_array( $order_commission_rule ) && isset( $order_commission_rule['tax_enable'] ) && ( $order_commission_rule['tax_enable'] == 'yes' ) ) {
										$commission_tax    = (float) $WCFMmp->wcfmmp_commission->wcfmmp_get_commission_meta( $commission_id, 'commission_tax' );
										$total_commission  += $commission_tax;
										
										$commission_tax = $total_commission * ( (float)$order_commission_rule['tax_percent'] / 100 );
										$commission_tax = apply_filters( 'wcfmmp_commission_deducted_tax', $commission_tax, $affiliate_detail->vendor_id, $affiliate_detail->product_id, $affiliate_detail->order_id, $total_commission, $order_commission_rule );
										$total_commission -= (float) $commission_tax;
										
										$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, 'commission_tax' );
										$WCFMmp->wcfmmp_commission->wcfmmp_update_commission_meta( $commission_id, 'commission_tax', round($commission_tax, 2) );
									}
									
									$wpdb->update("{$wpdb->prefix}wcfm_marketplace_orders", array( 'total_commission' => round( $total_commission, 2 ) ), array('ID' => $commission_id), array('%s'), array('%d'));
									
									$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, '_wcfm_affiliate_id' );
									$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, '_wcfm_affiliate_order' );
									$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, '_wcfm_affiliate_commission' );
									$WCFMmp->wcfmmp_commission->wcfmmp_delete_commission_meta( $commission_id, '_wcfm_affiliate_commission_rule' );
								}
							}
						}
					}
				}
			}
		}
	}

	public function load_class($class_name = '') {
		if ('' != $class_name && '' != $this->token) {
			require_once ('class-' . esc_attr($this->token) . '-' . esc_attr($class_name) . '.php');
		} // End If Statement
	}

	// End load_class()

	/**
	 * Install upon activation.
	 *
	 * @access public
	 * @return void
	 */
	static function activate_wcfmaf() {
		global $WCFM, $WCFMaf, $wpdb;

		require_once ( $WCFMaf->plugin_path . 'helpers/class-wcfmaf-install.php' );
		$WCFMaf_Install = new WCFMaf_Install();
		
		// License Activation
		$WCFMaf->load_class('license');
		WCFMaf_LICENSE()->activation();
	}
	
	/**
	 * Check Installer upon load.
	 *
	 * @access public
	 * @return void
	 */
	function run_wcfmaf_installer() {
		global $WCFM, $WCFMaf, $wpdb;
		
		$wcfm_affiliate_tables = $wpdb->query( "SHOW tables like '{$wpdb->prefix}wcfm_affiliate_orders'");
		if( !$wcfm_affiliate_tables ) {
			delete_option( 'wcfmaf_table_install' );
		}
		
		if ( !get_option("wcfmaf_table_install") || !get_option("wcfmaf_page_install") ) {
			require_once ( $WCFMaf->plugin_path . 'helpers/class-wcfmaf-install.php' );
			$WCFMaf_Install = new WCFMaf_Install();
			
			update_option('wcfmaf_installed', 1);
		}
	}

	/**
	 * UnInstall upon deactivation.
	 *
	 * @access public
	 * @return void
	 */
	static function deactivate_wcfmaf() {
		global $WCFM, $WCFMaf;
		
		// License Deactivation
		$WCFMaf->load_class('license');
		WCFMaf_LICENSE()->uninstall();
        
		delete_option('wcfmaf_installed');
	}
	
	/**
	 * Affiliate Listener 
	 */
  public function wcfmaf_affiliate_listener() {
  	global $WCFM;
  	
		// Listen and handle PayPal IPN
		$wreferrer = filter_input( INPUT_GET, 'wreferrer' );
		if( $wreferrer ) {
			$affiliate = wcfm_get_affiliate_by_referrer( $wreferrer );
			if( $affiliate && WC()->session ) {
				do_action( 'woocommerce_set_cart_cookies', true );
				WC()->session->set( 'wcfm_affiliate', $affiliate );
			}
		}
	}
	
}