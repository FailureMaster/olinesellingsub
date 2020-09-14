$wcfm_shop_staffs_table = '';

jQuery(document).ready(function($) {
	
	$wcfm_shop_staffs_table = $('#wcfm-shop-staffs').DataTable( {
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
				d.action       = 'wcfm_ajax_controller',
				d.controller   = 'wcfm-staffs'
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-appointments table refresh complete
				$( document.body ).trigger( 'updated_wcfm_shop_staffs' );
			}
		}
	} );
	
	// Delete Staff
	$( document.body ).on( 'updated_wcfm_shop_staffs', function() {
		$('.wcfm_staff_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Staff'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMStaff($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMStaff(item) {
		jQuery('#wcfm-shop-staffs_wrapper').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action  : 'delete_wcfm_staff',
			staffid : item.data('staffid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_shop_staffs_table) $wcfm_shop_staffs_table.ajax.reload();
				jQuery('#wcfm-shop-staffs_wrapper').unblock();
			}
		});
	}
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm_shop_staffs', function() {
		$.each(wcfm_staffs_screen_manage, function( column, column_val ) {
		  $wcfm_shop_staffs_table.column(column).visible( false );
		} );
	});
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
} );