<?php
global $wp, $WCFM, $WCFMu, $WCFMd, $wp_query;

$order_id = 0;
if( isset( $wp->query_vars['wcfm-orders-details'] ) && !empty( $wp->query_vars['wcfm-orders-details'] ) ) {
	$order_id = absint($wp->query_vars['wcfm-orders-details']);
} else {
	return;
}

$order = wc_get_order( $order_id );

$order_status = sanitize_title( $order->get_status() );
if( $order->get_formatted_shipping_address() && ( !function_exists( 'wcs_order_contains_subscription' ) || ( !wcs_order_contains_subscription( $order_id, 'renewal' ) && !wcs_order_contains_subscription( $order_id, 'renewal' ) ) ) && !in_array( $order_status, apply_filters( 'wcfm_shipment_disable_order_status', array( 'failed', 'cancelled', 'refunded', 'pending' ) ) ) ) {
	$needs_shipping_tracking = false; 
	?>
	<div class="wcfm-clearfix"></div>
	<br />
	<!-- collapsible -->
	<div class="page_collapsible orders_details_shipment" id="sm_order_delivery_options"><?php _e('Delivery', 'wc-frontend-manager-delivery'); ?><span></span></div>
	<div class="wcfm-container orders_details_shipment_expander_container">
		<div id="orders_details_shipment_expander" class="wcfm-content">
		  <h2><?php _e( 'Assign delivery doy to order item(s)', 'wc-frontend-manager-delivery' ); ?></h2>
		  <div class="wcfm-clearfix"></div>
		  <table cellpadding="0" cellspacing="0" class="woocommerce_order_items">
				<tbody id="order_line_items">
				<?php
				  $line_items = $order->get_items( apply_filters( 'woocommerce_admin_order_item_types', 'line_item' ) );
					$line_items = apply_filters( 'wcfm_valid_line_items', $line_items, $order->get_id() );
					
					$shipped_action = 'wcfmd_delivery_boy_assign';
					
					foreach ( $line_items as $item_id => $item ) {
						$_product  = $item->get_product();
						
						$needs_shipping = $WCFM->frontend->is_wcfm_needs_shipping( $_product );
						$shipped = true;
						$tracking_url  = '';
						$tracking_code = '';
						$delivery_boy  = '';
						if( $needs_shipping ) {
							$shipped = false;
							foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
								if( $meta->key == 'wcfm_tracking_url' ) {
									$tracking_url  = $meta->value;
									$shipped = true;
								} elseif( $meta->key == 'wcfm_delivery_boy' ) {
									$delivery_boy  = $meta->value;
								}
							}
						}
						
						//if( $shipped ) continue;
						$needs_shipping_tracking = true;
		
						if( current_user_can( 'edit_published_products' ) && apply_filters( 'wcfm_is_allow_edit_products', true ) && apply_filters( 'wcfm_is_allow_edit_specific_products', true, $item->get_product_id() ) ) {
							$product_link  = $_product ? get_wcfm_edit_product_url( $item->get_product_id(), $_product ) : '';
						} else {
							$product_link  = $_product ? get_permalink( $item->get_product_id() ) : '';
						}
						?>
						<tr class="item <?php echo apply_filters( 'woocommerce_admin_html_order_item_class', ( ! empty( $class ) ? $class : '' ), $item, $order ); ?>" data-order_item_id="<?php echo $item_id; ?>">
							<td class="name" data-sort-value="<?php echo esc_attr( $item->get_name() ); ?>">
								<?php
									echo $product_link ? '<a href="' . esc_url( $product_link ) . '" class="wc-order-item-name">' .  esc_html( $item->get_name() ) . '</a>' : '<div "class="wc-order-item-name"">' . esc_html( $item->get_name() ) . '</div>';
						
									if ( $_product && $_product->get_sku() ) {
										echo '<div class="wc-order-item-sku"><strong>' . __( 'SKU:', 'wc-frontend-manager' ) . '</strong> ' . esc_html( $_product->get_sku() ) . '</div>';
									}
									
									if ( $delivery_boy && function_exists( 'wcfm_get_delivery_boy_label' ) ) {
										$delivery_boy_label = '';
										$is_order_delivered = wcfm_is_order_delivered( $order_id, $item_id );
										if( $is_order_delivered ) {
											$delivery_boy_label = wcfm_get_delivery_boy_label( $delivery_boy, 'completed' );
										} else {
											$delivery_boy_label = wcfm_get_delivery_boy_label( $delivery_boy, 'pending' );
										}
										
										if( $delivery_boy_label ) {
											echo '<div class="wc-order-item-sku"><strong>' . __( 'Delivery Boy', 'wc-frontend-manager-ultimate' ) . ':</strong> ' . $delivery_boy_label . '</div>';
										}
									}
						
									if ( ! empty( $item->get_variation_id() ) ) {
										echo '<div class="wc-order-item-variation"><strong>' . __( 'Variation ID:', 'wc-frontend-manager' ) . '</strong> ';
										if ( ! empty( $item->get_variation_id() ) && 'product_variation' === get_post_type( $item->get_variation_id() ) ) {
											echo esc_html( $item->get_variation_id() );
										} elseif ( ! empty( $item->get_variation_id() ) ) {
											echo esc_html( $item->get_variation_id() ) . ' (' . __( 'No longer exists', 'wc-frontend-manager' ) . ')';
										}
										echo '</div>';
									}
								?>
							</td>
							<td>
							  <a class="wcfm_order_delivery_boy_assign" href="#" data-shipped_action="<?php echo $shipped_action; ?>" data-productid="<?php echo $_product->get_id(); ?>" data-orderitemid="<?php echo $item->get_id(); ?>" data-orderid="<?php echo $order_id; ?>"><span class="wcfmfa fa-shipping-fast text_tip" data-tip="<?php echo esc_attr__( 'Assign Delivery Boy', 'wc-frontend-manager-delivery' ); ?>"></span></a>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
		  </table>
		</div>
	</div>
	<?php
	if( !$needs_shipping_tracking ) {
		?>
		<style>
		#sm_order_delivery_options, .orders_details_shipment_expander_container, #orders_details_shipment_expander { display: none; }
		</style>
		<?php
	}
}
?>