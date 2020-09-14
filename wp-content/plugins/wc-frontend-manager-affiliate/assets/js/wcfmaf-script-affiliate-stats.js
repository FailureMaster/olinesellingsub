$wcfm_affiliate_stats_table = '';

jQuery(document).ready(function($) {
		
	$status_type = 'pending';
	$wcfm_affiliate  = $('#wcfm_affiliate_id').val();
	
	$wcfm_affiliate_stats_table = $('#wcfm_affiliate_stats').DataTable( {
		"processing"     : true,
		"serverSide"     : true,
		"aFilter"        : false,
		"bFilter"        : false,
		"responsive"     : true,
		"language"  : $.parseJSON(dataTables_language),
		"columns"   : [
										{ responsivePriority: 1 },
										{ responsivePriority: 3 },
										{ responsivePriority: 1 },
										{ responsivePriority: 2 },
										{ responsivePriority: 4 },
										{ responsivePriority: 1 },
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
				d.action            = 'wcfm_ajax_controller',
				d.controller        = 'wcfm-affiliate-stats',
				d.status_type       = $status_type,
				d.wcfm_affiliate    = $wcfm_affiliate
			},
			"complete" : function () {
				initiateTip();
				
				$('.show_order_items').click(function(e) {
					e.preventDefault();
					$(this).next('div.order_items').toggleClass( "order_items_visible" );
					return false;
				});
				
				// Fire wcfm-affiliate table refresh complete
				$( document.body ).trigger( 'updated_wcfm_affiliate_stats' );
			}
		}
	} );
	
	if( $('#dropdown_status_type').length > 0 ) {
		$('#dropdown_status_type').on('change', function() {
			$status_type = $('#dropdown_status_type').val();
			$wcfm_affiliate_stats_table.ajax.reload();
		});
	}
	
	// Show Affiliate Commission Details
	$( document.body ).on( 'updated_wcfm_affiliate_stats', function() {
		$('.wcfmaff_commission_show_details').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				showWCFMAffCommDetails($(this));
				return false;
			});
		});
	});
	
	function showWCFMAffCommDetails(item) {
		var data = {
			action        : 'wcfmaff_show_coomission_details',
			affiliate_id  : item.data('affiliate_id')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				// Intialize colorbox
				jQuery.colorbox( { html: response, width: $popup_width, height: 450, 
				  onComplete: function() {
						
						$('#wcfmaf_commission_details_print').click(function( event ) {
							event.preventDefault();
							
							Pagelink = "affiliate_commission_report";
							var pwa = window.open(Pagelink, "_new");
							pwa.document.open();
							
							pwa.document.write( $('#wcfmaff_commission_popup_wrapper').html() + "<br /><br />" + affCommissionDetailsPrint());
							
							pwa.document.close();
						});
				  }
				} );
			}
		});
	}
	
	function affCommissionDetailsPrint() {
		return "<html><head><script>function step1(){\n" +
				"setTimeout('step2()', 10);}\n" +
				"function step2(){window.print();window.close()}\n" +
				"</scri" + "pt></head><body onload='step1()'>\n" +
				"</body></html>";
	}
	
	// Mark Commission Paid
	$( document.body ).on( 'updated_wcfm_affiliate_stats', function() {
		$('.wcfmaff_commission_mark_paid').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm(wcfm_affiliate_stats_messages.mark_confirm);
				if(rconfirm) markWCFMPaid($(this));
				return false;
			});
		});
	});
	
	function markWCFMPaid(item) {
		jQuery('#wcfm_affiliate_stats_listing_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action        : 'mark_wcfm_affiliate_order_paid',
			affiliate_id  : item.data('affiliate_id')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_affiliate_stats_table) $wcfm_affiliate_stats_table.ajax.reload();
				jQuery('#wcfm_affiliate_stats_listing_expander').unblock();
			}
		});
	}
	
	// Mark Commission Reject
	$( document.body ).on( 'updated_wcfm_affiliate_stats', function() {
		$('.wcfmaff_commission_mark_reject').each(function() {
			$(this).click(function(event) {
				event.preventDefault();
				var rconfirm = confirm(wcfm_affiliate_stats_messages.mark_reject);
				if(rconfirm) markWCFMReject($(this));
				return false;
			});
		});
	});
	
	function markWCFMReject(item) {
		jQuery('#wcfm_affiliate_stats_listing_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var data = {
			action        : 'mark_wcfm_affiliate_order_reject',
			affiliate_id  : item.data('affiliate_id')
		}	
		jQuery.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				if($wcfm_affiliate_stats_table) $wcfm_affiliate_stats_table.ajax.reload();
				jQuery('#wcfm_affiliate_stats_listing_expander').unblock();
			}
		});
	}
	
	// Screen Manager
	$( document.body ).on( 'updated_wcfm_affiliate_stats', function() {
		$.each(wcfm_affiliate_stats_screen_manage, function( column, column_val ) {
		  $wcfm_affiliate_stats_table.column(column).visible( false );
		} );
	});
	
	// Dashboard FIlter
	if( $('.wcfm_filters_wrap').length > 0 ) {
		$('.dataTable').before( $('.wcfm_filters_wrap') );
		$('.wcfm_filters_wrap').css( 'display', 'inline-block' );
	}
} );