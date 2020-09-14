$wcfm_delivery_boys_table = '';
$delivery_boy_vendor = '';

jQuery(document).ready(function($) {
	
	$wcfm_delivery_boys_table = $('#wcfm-delivery-boys').DataTable( {
		"processing": true,
		"pageLength": parseInt(dataTables_config.pageLength),
		"pageLength": parseInt(dataTables_config.pageLength),
		"serverSide": true,
		"responsive": true,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 2 },
										{ responsivePriority: 3 },
										{ responsivePriority: 2 },
										{ responsivePriority: 1 }
								],
		"columnDefs": [ { "targets": 0, "orderable" : false }, 
									  { "targets": 1, "orderable" : false }, 
										{ "targets": 2, "orderable" : false },
										{ "targets": 3, "orderable" : false },
										{ "targets": 4, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action              = 'wcfm_ajax_controller',
				d.controller          = 'wcfm-delivery-boys',
				d.delivery_boy_vendor = $delivery_boy_vendor
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-appointments table refresh complete
				$( document.body ).trigger( 'updated_wcfm_delivery_boys' );
			}
		}
	} );
	
	// Delete Staff
	$( document.body ).on( 'updated_wcfm_delivery_boys', function() {
		$('.wcfm_staff_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Delivery Boy'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMDeliveryBoy($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMDeliveryBoy(item) {
		jQuery('#wcfm_delivery_boys_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action        : 'delete_wcfm_delivery_boy',
			deliveryboyid : item.data('deliveryboyid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_delivery_boys_table) $wcfm_delivery_boys_table.ajax.reload();
				jQuery('#wcfm_delivery_boys_expander').unblock();
			}
		});
	}
	
	if( $('#dropdown_vendor').length > 0 ) {
		$('#dropdown_vendor').on('change', function() {
			$delivery_boy_vendor = $('#dropdown_vendor').val();
			$wcfm_delivery_boys_table.ajax.reload();
		}).select2( $wcfm_vendor_select_args );
	}
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm_delivery_boys', function() {
		$.each(wcfm_delivery_boys_screen_manage, function( column, column_val ) {
		  $wcfm_delivery_boys_table.column(column).visible( false );
		} );
	});
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
} );