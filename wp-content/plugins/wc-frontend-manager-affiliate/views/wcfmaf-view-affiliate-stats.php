<?php
/**
 * WCFM plugin view
 *
 * WCFM Affiliate Stats View
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/views/
 * @version   1.0.0
 */
 
global $WCFM, $WCFMaf, $wp;

$wcfm_is_allow_affiliate = apply_filters( 'wcfm_is_allow_affiliate_stats', true );
if( !$wcfm_is_allow_affiliate ) {
	wcfm_restriction_message_show( "Affiliate" );
	return;
}

$affiliate_id = 0; 
if( wcfm_is_affiliate() ) {
	$affiliate_id = get_current_user_id();
} elseif( isset( $wp->query_vars['wcfm-affiliate-stats'] ) && !empty( $wp->query_vars['wcfm-affiliate-stats'] ) ) {
	$affiliate_id = $wp->query_vars['wcfm-affiliate-stats'];
}

if( !$affiliate_id ) {
	wcfm_restriction_message_show( "Restricted Access" );
	return;
}

$affiliate_id = absint($affiliate_id);

$affiliate_label     = '';
$wcfm_affiliate_user = get_userdata( absint( $affiliate_id ) );
if( $wcfm_affiliate_user ) {
	if ( !in_array( 'wcfm_affiliate', (array) $wcfm_affiliate_user->roles ) ) {
		wcfm_restriction_message_show( "Invalid Affiliate" );
		return;
	}
	$affiliate_label     = apply_filters( 'wcfm_affiliate_display', $wcfm_affiliate_user->first_name . ' ' . $wcfm_affiliate_user->last_name, $affiliate_id );
} else {
	wcfm_restriction_message_show( "Invalid Affiliate" );
	return;
}

?>
<div class="collapse wcfm-collapse" id="wcfm_affiliate_stats_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-user-friends"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Affiliate Stats', 'wc-frontend-manager-affiliate' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  
	  <?php if( !wcfm_is_affiliate() ) { ?>
			<div class="wcfm-container wcfm-top-element-container">
				<h2>
					<?php echo __( 'Affiliate Stats', 'wc-frontend-manager-affiliate' ) . ' - ' . $affiliate_label; ?>
				</h2>
				
				<?php
				if( apply_filters( 'wcfm_allow_wp_admin_view', true ) ) {
					echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_manage_url( $affiliate_id ).'" data-tip="' . __('Affiliate User Manage', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-edit"></span></a>';
					
					echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_dashboard_url().'" data-tip="' . __('Manage Affiliate', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-user-friends"></span></a>';
			
					if( apply_filters( 'wcfm_add_new_staff_sub_menu', true ) ) {
						echo '<a class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_affiliate_manage_url().'" data-tip="' . __('Add New Affiliate', 'wc-frontend-manager-affiliate') . '"><span class="wcfmfa fa-user-plus"></span><span class="text">' . __( 'Add New', 'wc-frontend-manager' ) . '</span></a>';
					}
				}
				?>
				<div class="wcfm-clearfix"></div>
			</div>
			<div class="wcfm-clearfix"></div><br />
		<?php } ?>
		
		<div class="wcfm_affiliate_stats_filter_wrap wcfm_filters_wrap">
			<select name="status_type" id="dropdown_status_type" style="width: 160px; display: inline-block;">
				<option value=""><?php  _e( 'Show all ..', 'wc-frontend-manager' ); ?></option>
				<option value="paid"><?php  _e( 'Paid', 'wc-frontend-manager-affiliate' ); ?></option>
				<option value="pending" selected><?php  _e( 'Pending', 'wc-frontend-manager' ); ?></option>
			</select>
		</div>
	  
	  <?php do_action( 'before_wcfm_affiliate_stats' ); ?>
	  
	  <div class="wcfm_dashboard_stats">
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-currency"><?php echo get_woocommerce_currency_symbol() ; ?></span>
					<div>
						<strong><?php echo wc_price( wcfm_get_affiliate_commission_stat( $affiliate_id ) ); ?></strong><br />
						<?php _e( 'total commission', 'wc-frontend-manager-affiliate' ); ?>
					</div>
				</a>
			</div>
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-money fa-money-bill-alt"></span>
					<div>
						<strong><?php echo wc_price( wcfm_get_affiliate_commission_stat( $affiliate_id, 'paid' ) ); ?></strong><br />
						<?php _e( 'paid commission', 'wc-frontend-manager-affiliate' ); ?>
					</div>
				</a>
			</div>
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-user-alt"></span>
					<div>
						<strong><?php echo wcfm_get_affiliate_count_stat( $affiliate_id, 'vendor' ); ?></strong><br />
						<?php _e( 'no. of vendors', 'wc-frontend-manager-affiliate' ); ?>
					</div>
				</a>
			</div>
			<div class="wcfm_dashboard_stats_block">
			  <a href="#" onclick="return false;">
					<span class="wcfmfa fa-cart-plus"></span>
					<div>
						<strong><?php echo ( wcfm_get_affiliate_count_stat( $affiliate_id, 'order' ) + wcfm_get_affiliate_count_stat( $affiliate_id, 'vendor_order' ) ); ?></strong><br />
						<?php _e( 'no. of orders', 'wc-frontend-manager-affiliate' ); ?>
					</div>
				</a>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
	  
		<div class="wcfm-container">
			<div id="wcfm_affiliate_stats_listing_expander" class="wcfm-content">
				<table id="wcfm_affiliate_stats" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
						  <th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Status', 'wc-frontend-manager-affiliate' ); ?>"></span></th>
						  <th><?php _e( 'Commission', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Amount', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Type', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Date', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-affiliate' ); ?></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
						  <th><span class="wcicon-status-processing text_tip" data-tip="<?php _e( 'Status', 'wc-frontend-manager-affiliate' ); ?>"></span></th>
						  <th><?php _e( 'Commission', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Amount', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Type', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Date', 'wc-frontend-manager-affiliate' ); ?></th>
							<th><?php _e( 'Actions', 'wc-frontend-manager-affiliate' ); ?></th>
						</tr>
					</tfoot>
				</table>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		
		<!-- collapsible -->
		<?php if( $affiliate_id ) { $affiliate_users = wcfm_get_affiliate(); ?>
			<?php if( !empty( $affiliate_users ) ) { ?>
				<div class="wcfm_clearfix"></div><br />
				<div class="page_collapsible affiliate_manage_code" id="wcfm_affiliate_manage_code_head"><?php _e('Generate Affiliate URL', 'wc-frontend-manager-affiliate'); ?><span></span></div>
				<div class="wcfm-container">
					<div id="affiliate_manage_code_expander" class="wcfm-content">
						<form id="wcfm_affiliate_url_form" class="wcfm">
							<?php
							$affiliate_user_array = array();
							foreach( $affiliate_users as $affiliate_user ) {
								$affiliate_code = get_user_meta( $affiliate_user->ID, 'affiliate_code', true );
								$affiliate_user_array[$affiliate_user->ID] = $affiliate_user->first_name . ' ' . $affiliate_user->last_name . ' (' . $affiliate_user->user_email . ' - ' . $affiliate_code . ')';
							}
								$WCFM->wcfm_fields->wcfm_generate_form_field( apply_filters( 'wcfm_affiliate_url_generator', array(  
																																							"affiliate_user" => array( 'label' => __('Affiliate', 'wc-frontend-manager-affiliate') , 'type' => 'select', 'options' => $affiliate_user_array, 'class' => 'wcfm-select wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => $affiliate_id ),
																																							"normal_url" => array( 'label' => __('URL', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele ', 'label_class' => 'wcfm_ele wcfm_title', 'value' => '', 'desc' => __( 'Generate Affiliate URL for any URL for your site. e.g. Vendor Registration page, product page etc ..', 'wc-frontend-manager-affiliate' ), 'desc_class' => 'instruction' ),
																																							"generated_url" => array( 'label' => __('Affiliate URL', 'wc-frontend-manager-affiliate') , 'type' => 'text', 'class' => 'wcfm-text wcfm_ele wcfm_ele_hide', 'label_class' => 'wcfm_ele wcfm_title wcfm_ele_hide', 'value' => '' ),
																																						), $affiliate_id ) );
							?>
							<div class="wcfm_clearfix"></div><br />
							<input type="submit" name="submit-data" value="<?php _e( 'Generate', 'wc-frontend-manager-affiliate' ); ?>" id="wcfm_affiliate_url_generate_button" class="wcfm_submit_button" />
							<div class="wcfm_clearfix"></div>
						</form>
						<div class="wcfm_clearfix"></div>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<?php
		do_action( 'after_wcfm_affiliate_stats' );
		?>
		<input type="hidden" name="wcfm_affiliate_id" id="wcfm_affiliate_id" value="<?php echo $affiliate_id; ?>" />
	</div>
</div>