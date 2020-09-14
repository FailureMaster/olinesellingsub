$wcfm_shop_managers_table = '';

jQuery(document).ready(function($) {
	
	$wcfm_shop_managers_table = $('#wcfm-shop-managers').DataTable( {
		"processing": true,
		"pageLength": parseInt(dataTables_config.pageLength),
		"serverSide": true,
		"responsive": true,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 3 },
										{ responsivePriority: 2 },
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
				d.controller   = 'wcfm-managers'
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-appointments table refresh complete
				$( document.body ).trigger( 'updated_wcfm_shop_managers' );
			}
		}
	} );
	
		// Delete Staff
	$( document.body ).on( 'updated_wcfm_shop_managers', function() {
		$('.wcfm_manager_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Manager'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMManager($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMManager(item) {
		jQuery('#wcfm-shop-managers_wrapper').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action    : 'delete_wcfm_manager',
			managerid : item.data('managerid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_shop_managers_table) $wcfm_shop_managers_table.ajax.reload();
				jQuery('#wcfm-shop-managers_wrapper').unblock();
			}
		});
	}
} );