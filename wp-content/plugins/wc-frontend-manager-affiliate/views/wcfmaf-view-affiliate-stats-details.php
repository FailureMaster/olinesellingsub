<?php
global $WCFM, $WCFMaf, $WCFMmp, $wpdb;

?>

<form id="wcfmaff_commission_popup_wrapper" class="wcfm_popup_wrapper">
	<div style="margin-bottom: 15px;"><h2 style="float: none;" class="wcfmaff_commission_popup_heading"><?php echo __( 'Affiliate Commission Details', 'wc-frontend-manager-affiliate' ) . ' #' . $affiliate_id; ?></h2></div>
			
	<?php
	if( !empty( $affiliate_details ) ) {
		?>
		<table>
		  <tbody>
		    <?php
				foreach( $affiliate_details as $wcfm_affiliate_order_single ) {
					
					do_action( 'wcfmaf_before_commission_details', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
					
					echo "<tr>";
					  echo "<td width='30%'>";
					    echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
								echo '<label for="wcfm_refund_request"><strong>' . __( 'Commission for', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
							echo '</p>';
					  echo "</td>";
					  
					  echo "<td>";
							if( $wcfm_affiliate_order_single->commission_type == 'vendor' ) {
								echo '<span style="float:left;" class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type . '">' . __( 'Vendor Register', 'wc-frontend-manager-affiliate' ) . '</span>';
							} else if( $wcfm_affiliate_order_single->commission_type == 'vendor_order' ) {
								echo '<span style="float:left;" class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type . '">' . __( 'Vendor Order', 'wc-frontend-manager-affiliate' ) . '</span>';
							} else {
								echo '<span style="float:left;" class="commission-type commission-type-' . $wcfm_affiliate_order_single->commission_type. '">' . __( 'Order', 'wc-frontend-manager-affiliate' ) . '</span>';
							}
					  echo "</td>";
					echo "</tr>";
					
					do_action( 'wcfmaf_after_commission_details_for', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
					
					if( !wcfm_is_affiliate() ) {
						$affiliate_user = get_userdata( $wcfm_affiliate_order_single->affiliate_id );
						if( $affiliate_user ) {
							$first_name = $affiliate_user->first_name;
							$last_name = $affiliate_user->last_name;
							$user_phone = get_user_meta( $affiliate_user->ID, 'billing_phone', true );
							$affiliate_code = get_user_meta( $affiliate_user->ID, 'affiliate_code', true );
							echo "<tr>";
								echo "<td width='30%'>";
									echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
										echo '<label for="wcfm_refund_request"><strong>' . __( 'Affiliate', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
									echo '</p>';
								echo "</td>";
								
								echo "<td>";
									
									echo $first_name . ' ' . $last_name;
									echo '<br /><p class="comment-notes description" style="margin-top:2px!important;">(';
									echo __( 'Username', 'wc-frontend-manager-affiliate' ) . ': ' . $affiliate_user->user_login;
									echo ', ' . __( 'Email', 'wc-frontend-manager-affiliate' ) . ': ' . $affiliate_user->user_email;
									if( $user_phone ) {
										echo ', ' . __( 'Phone', 'wc-frontend-manager-affiliate' ) . ': ' . $user_phone;
									}
									echo ', ' . __( 'Affiliate Code', 'wc-frontend-manager-affiliate' ) . ': ' . $affiliate_code;
									')</p>';
								echo "</td>";
							echo "</tr>";
							
							do_action( 'wcfmaf_after_commission_details_affiliate', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
						}
					}
					
					echo "<tr>";
					  echo "<td width='30%'>";
					    echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
								echo '<label for="wcfm_refund_request"><strong>' . __( 'Amount', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
							echo '</p>';
					  echo "</td>";
					  
					  echo "<td>";
							echo wc_price( $wcfm_affiliate_order_single->commission_amount );
							
							$commission_rule = unserialize( $WCFMaf->wcfmaf_get_affiliate_order_meta( $wcfm_affiliate_order_single->ID, 'commission_rule' ) );
							if( $commission_rule && is_array( $commission_rule ) && !empty( $commission_rule ) && isset( $commission_rule['mode'] ) ) {
								echo '<br/><p class="comment-notes description" style="margin-top:2px!important;">(' . __( 'Rule', 'wc-frontend-manager-affiliate' ) . ': ';
								if( $commission_rule['mode'] == 'percent' ) {
								  echo __( 'Percent', 'wc-frontend-manager-affiliate' ) . '&nbsp;' . $commission_rule['percent'] . '%';
								} else {
									echo __( 'Fixed', 'wc-frontend-manager-affiliate' ) . '&nbsp;' . wc_price( $commission_rule['fixed'] );
								}
								if( isset( $commission_rule['cal_mode'] ) ) {
									if( $commission_rule['cal_mode'] == 'on_commission' ) {
										echo ', ' . __( 'on vendor commission amount', 'wc-frontend-manager-affiliate' );
									} else {
										echo ', ' . __( 'on item cost', 'wc-frontend-manager-affiliate' );
									}
								}
								echo ')</p>';
							}
					  echo "</td>";
					echo "</tr>";
					
					do_action( 'wcfmaf_after_commission_details_amount', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
					
					echo "<tr>";
					  echo "<td width='30%'>";
					    echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
								echo '<label for="wcfm_refund_request"><strong>' . __( 'Payment Status', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
							echo '</p>';
					  echo "</td>";
					  
					  echo "<td>";
							if( $wcfm_affiliate_order_single->commission_status == 'pending' ) {
								echo '<span class="order-status tips wcicon-status-pending text_tip" data-tip="' . __( 'Pending', 'wc-frontend-manager-affiliate' ) . '"></span>&nbsp;' . __( 'Pending', 'wc-frontend-manager-affiliate' );
							} else {
								echo '<span class="order-status tips wcicon-status-completed text_tip" data-tip="' . __( 'Paid', 'wc-frontend-manager-affiliate' ) . '"></span>&nbsp;' . __( 'Paid', 'wc-frontend-manager-affiliate' );
								
								echo '<br/><p class="comment-notes description" style="margin-top:2px!important;">( ' . __( 'Paid ON', 'wc-frontend-manager-affiliate' ) . ': ' . date_i18n( wc_date_format() . ' ' . wc_time_format(), strtotime( $wcfm_affiliate_order_single->commission_paid_date ) ) . ' )</p>';
							}
					  echo "</td>";
					echo "</tr>";
					
					do_action( 'wcfmaf_after_commission_details_status', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
					
					if( $wcfm_affiliate_order_single->commission_type != 'vendor' ) {
						echo "<tr>";
							echo "<td width='30%'>";
								echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
									echo '<label for="wcfm_refund_request"><strong>' . __( 'Order', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
								echo '</p>';
							echo "</td>";
							
							echo "<td>";
								echo '#' . $wcfm_affiliate_order_single->order_id;
								echo '<br /><p class="comment-notes description" style="margin-top:2px!important;">(' . __( 'Created', 'wc-frontend-manager-affiliate' ) . ': ' . date_i18n( wc_date_format() . ' ' . wc_time_format(), strtotime($wcfm_affiliate_order_single->created) );
								if( !wcfm_is_affiliate() ) {
								  echo ', ' . __( 'Store Order', 'wc-frontend-manager-affiliate' ) . ' #' . $wcfm_affiliate_order_single->order_commission_id;
								}
								echo ')</p>';
							echo "</td>";
						echo "</tr>";
						
						do_action( 'wcfmaf_after_commission_details_order', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
						
						echo "<tr>";
							echo "<td width='30%'>";
								echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
									echo '<label for="wcfm_refund_request"><strong>' . __( 'Product', 'wc-frontend-manager-affiliate' ) . '</strong></label>'; 
								echo '</p>';
							echo "</td>";
							
							echo "<td>";
								echo '<a target="_blank" href="' . get_permalink( $wcfm_affiliate_order_single->product_id ) . '">' . get_the_title( $wcfm_affiliate_order_single->product_id ) . '</a>';
								echo '<br /><p class="comment-notes description" style="margin-top:2px!important;">(' . __( 'Quantity', 'wc-frontend-manager-affiliate' ) . ': ' . $wcfm_affiliate_order_single->quantity . ', ' . __( 'Total Cost', 'wc-frontend-manager-affiliate' ) . ': ' . wc_price( $wcfm_affiliate_order_single->item_total ) . ')</p>';
							echo "</td>";
						echo "</tr>";
						
						do_action( 'wcfmaf_after_commission_details_product', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
					}
				}
				
				if( $WCFMmp && wcfm_is_vendor( $wcfm_affiliate_order_single->vendor_id ) && function_exists( 'wcfmmp_get_store_url' ) ) {
					echo "<tr>";
						echo "<td width='30%'>";
							echo '<p class="wcfm-refund-form-request wcfm_popup_label" style="width:100%">';
								echo '<label for="wcfm_refund_request"><strong>' . $WCFMmp->wcfmmp_vendor->sold_by_label( absint( $wcfm_affiliate_order_single->vendor_id ) ) . '</strong></label>'; 
							echo '</p>';
						echo "</td>";
						
						echo "<td>";
						  echo '<a target="_blank" class="wcfm_dashboard_item_title" href="' . wcfmmp_get_store_url( $wcfm_affiliate_order_single->vendor_id ) . '">' . wcfm_get_vendor_store_name( $wcfm_affiliate_order_single->vendor_id ) . '</a>';
						echo "</td>";
					echo "</tr>";
					
					do_action( 'wcfmaf_after_commission_details_vendor', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
				}
				
				do_action( 'wcfmaf_after_commission_details', $wcfm_affiliate_order_single->ID, $wcfm_affiliate_order_single );
				?>
			</tbody>
		</table>
		
		<div class="wcfm_clearfix"></div><br/>
		<input type="button" class="wcfmaf_commission_details_print wcfm_popup_button wcfm_submit_button" id="wcfmaf_commission_details_print" value="<?php _e( 'Print', 'wc-frontend-manager-affiliate' ); ?>" />
	  <div class="wcfm_clearfix"></div><br/>
		<?php
	} else {
		_e( 'No details found!', 'wc-frontend-manager-affiliate' );
	}
	?>
	<div class="wcfm_clearfix"></div>
</form>