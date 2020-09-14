jQuery(document).ready(function($) {
	$( document.body ).on( 'updated_wcfm-orders', function() {
		// WCfM Marketplace Mark Order Shipped
		$('.wcfm_wcfmmarketplace_order_delivery').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				manageVendorDeliveryBoyAssign( $(this), 'wcfmd_delivery_boy_assign' );
				return false;
			});
		});
	});
	
	function manageVendorDeliveryBoyAssign( item, mark_shipped_action ) {
		
		var data = {
							  action        : 'wcfmd_delivery_boy_assign_html',
							  orderid       : item.data('orderid'),
								productid     : item.data('productid'),
								orderitemid   : item.data('orderitemid'),
							}
		jQuery.ajax({
			type    :		'POST',
			url     : wcfm_params.ajax_url,
			data    : data,
			success :	function(response) {
														 
				// Intialize colorbox
				$.colorbox( { html: response, height: 300, width: $popup_width,
					onComplete:function() {
						$('#wcfm_tracking_button').click(function(e) {
							e.preventDefault();
							
							$('#wcfm_shipping_tracking_form').block({
									message: null,
									overlayCSS: {
										background: '#fff',
										opacity: 0.6
									}
								});
							
							jQuery( document.body ).trigger( 'wcfm_form_validate', jQuery('#wcfm_shipping_tracking_form') );
							if( !$wcfm_is_valid_form ) {
								wcfm_notification_sound.play();
								jQuery('#wcfm_shipping_tracking_form').unblock();
							} else {
					
								$('#wcfm_tracking_button').hide();
								$('#wcfm_shipping_tracking_form .wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
								
								var data = {
									action        : mark_shipped_action,
									orderid       : item.data('orderid'),
									productid     : item.data('productid'),
									orderitemid   : item.data('orderitemid'),
									tracking_data : $('#wcfm_shipping_tracking_form').serialize()
								}	
								$.ajax({
									type:		'POST',
									url: wcfm_params.ajax_url,
									data: data,
									success:	function(response) {
										$response_json = $.parseJSON(response);
										wcfm_notification_sound.play();
										$wcfm_orders_table.ajax.reload();
										$('#wcfm_shipping_tracking_form').unblock();
										$('#wcfm_shipping_tracking_form .wcfm-message').html( '<span class="wcicon-status-completed"></span>' + $response_json.message ).addClass('wcfm-success').slideDown();
										setTimeout(function() {
											$.colorbox.remove();
										}, 2000);
									}
								});
							}
						});
					}
				});
			}
		});
	}
} );