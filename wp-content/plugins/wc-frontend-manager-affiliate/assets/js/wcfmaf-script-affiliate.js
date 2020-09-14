$wcfm_affiliate_table = '';

jQuery(document).ready(function($) {
	
	$wcfm_affiliate_table = $('#wcfm-affiliate').DataTable( {
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
										{ responsivePriority: 4 },
										{ responsivePriority: 5 },
										{ responsivePriority: 1 }
								],
		"columnDefs": [ { "targets": 0, "orderable" : false }, 
									  { "targets": 1, "orderable" : false }, 
										{ "targets": 2, "orderable" : false },
										{ "targets": 3, "orderable" : false },
										{ "targets": 4, "orderable" : false },
										{ "targets": 5, "orderable" : false },
									],
		'ajax': {
			"type"   : "POST",
			"url"    : wcfm_params.ajax_url,
			"data"   : function( d ) {
				d.action              = 'wcfm_ajax_controller',
				d.controller          = 'wcfm-affiliate'
			},
			"complete" : function () {
				initiateTip();
				
				// Fire wcfm-appointments table refresh complete
				$( document.body ).trigger( 'updated_wcfm_affiliate' );
			}
		}
	} );
	
	// Affiliate Action Manager
	$( document.body ).on( 'updated_wcfm_affiliate', function() {
		// Enable Affliate
		$('.wcfm_affiliate_enable_button').each(function() {
			$(this).click(function( event ) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to enable this 'Affiliate'?");
				if(rconfirm) {
					$('#wcfm_affiliate_expander').block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
					var data = {
						action       : 'wcfm_affiliate_enable',
						memberid     : $(this).data('memberid'),
					}	
					$.post(wcfm_params.ajax_url, data, function(response) {
						if(response) {
							$wcfm_affiliate_table.ajax.reload();
							$('#wcfm_affiliate_expander').unblock();
						}
					});
				}
			});
		});
		
		// Disable Affliate
		$('.wcfm_affiliate_disable_button').each(function() {
			$(this).click(function( event ) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to disable this 'Affiliate'?");
				if(rconfirm) {
					$('#wcfm_affiliate_expander').block({
						message: null,
						overlayCSS: {
							background: '#fff',
							opacity: 0.6
						}
					});
					var data = {
						action       : 'wcfm_affiliate_disable',
						memberid     : $(this).data('memberid'),
					}	
					$.post(wcfm_params.ajax_url, data, function(response) {
						if(response) {
							$wcfm_affiliate_table.ajax.reload();
							$('#wcfm_affiliate_expander').unblock();
						}
					});
				}
			});
		});
		
		// Delete Affliate	
		$('.wcfm_affiliate_delete').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm("Are you sure and want to delete this 'Affiliate'?\nYou can't undo this action ...");
				if(rconfirm) deleteWCFMAffiliate($(this));
				return false;
			});
		});
	});
	
	function deleteWCFMAffiliate(item) {
		jQuery('#wcfm_affiliate_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action        : 'delete_wcfm_affiliate',
			affiliateid   : item.data('affiliateid')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_affiliate_table) $wcfm_affiliate_table.ajax.reload();
				jQuery('#wcfm_affiliate_expander').unblock();
			}
		});
	}
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm_affiliate', function() {
		$.each(wcfm_affiliate_screen_manage, function( column, column_val ) {
		  $wcfm_affiliate_table.column(column).visible( false );
		} );
	});
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
} );