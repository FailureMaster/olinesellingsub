jQuery(document).ready(function($) {
  $( document.body ).on( 'wcfm-date-range-refreshed', function() {
		$('input[name="start_date"]').val($filter_date_form);
		$('input[name="end_date"]').val($filter_date_to);
		$('input[name="end_date"]').parent().submit();
	});
	
	$("#wcfma_product_id").select2( $wcfm_product_select_args );
	
	$("#wcfma_product_cats").select2({
		//placeholder: "Choose a category ..."
	});
	
	$(".wcfm_product_taxonomies").each(function() {
		$(this).select2({
				//placeholder: "Choose a category ..."
		});
	});
	
	// Fetching Top Referrers List
	if( $('#wcfm_store_referrer_expander').length > 0 ) {
		$('#wcfm_store_referrer_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var range = '7day';
		var start_date = '';
		var end_date = '';
		range = GetURLParameter('range');
		if( range == 'custom' ) {
			start_date = GetURLParameter('start_date');
			end_date = GetURLParameter('end_date');
		}
		var data = {
			action    : 'wcfm_top_referrers_analytics_data',
			range     : range,
			start_date: start_date,
			end_date  : end_date
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				var top_referrers = $.parseJSON( response );
				var tbodyHTML = '';
				$.each(top_referrers, function( index, top_referrer ) {
				  tbodyHTML += '<div class="top_referrer_content"><span class="dont-break-out"><a target="_blank" href="' + top_referrer.href + '">' + top_referrer.label + '</a></span><span>' + top_referrer.data + '</span></div>';
				} );
				$('.top-referrers-report').find('.top_referrers_body').html( tbodyHTML );
				$('#wcfm_store_referrer_expander').unblock();
			}
		});
	}
	
	// Fetching Most Viewd Products
	if( $('#wcfm_top_products_expander').length > 0 ) {
		//$('#wcfm_top_products_expander').css( 'width', $('#wcfm_top_products_expander').outerWidth() + 'px' );
		$('#wcfm_top_products_expander').block({
			message: null,
			overlayCSS: {
				background: '#fff',
				opacity: 0.6
			}
		});
		var range = '7day';
		var start_date = '';
		var end_date = '';
		range = GetURLParameter('range');
		if( range == 'custom' ) {
			start_date = GetURLParameter('start_date');
			end_date = GetURLParameter('end_date');
		}
		var data = {
			action    : 'wcfm_top_products_analytics_data',
			range     : range,
			start_date: start_date,
			end_date  : end_date
		}	
		$.ajax({
			type:		'POST',
			url: wcfm_params.ajax_url,
			data: data,
			success:	function(response) {
				var top_product_array = $.parseJSON( response );
				var config = {
						type: 'pie',
						data: {
								datasets: [{
										data: top_product_array.datas,
										backgroundColor: [
											  window.chartColors.green,
											  window.chartColors.blue,
												window.chartColors.red,
												window.chartColors.orange,
												window.chartColors.purple,
										],
										label: 'Top Products'
								}],
								labels: top_product_array.labels
						},
						options: {
							legend: {
								position: "bottom",
								//display:  false
							},
							responsive: true
						}
				};
				
				var ctx = document.getElementById("analytics-top-products-report-canvas").getContext("2d");
        window.topProductPie = new Chart(ctx, config);
				
				$('#wcfm_top_products_expander').unblock();
			}
		});
	}
	
	// Fetching Product Stats
	jQuery('#wcfma_analytics_product_expander').css( 'width', jQuery('#wcfma_analytics_product_expander').outerWidth() + 'px' );
	$('#wcfma_product_analytics_go').click(function(e) {
	  e.preventDefault();
	  if( $('#wcfma_product_id').val().length > 0 ) {
			$('#wcfma_analytics_product_expander').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var range = '7day';
			var start_date = '';
			var end_date = '';
			range = GetURLParameter('range');
			if( range == 'custom' ) {
				start_date = GetURLParameter('start_date');
				end_date = GetURLParameter('end_date');
			}
			var data = {
				action     : 'wcfm_product_analytics_data',
				productid  : $('#wcfma_product_id').val(),
				range      : range,
				start_date : start_date,
				end_date   : end_date
			}	
			$.ajax({
				type:		'POST',
				url: wcfm_params.ajax_url,
				data: data,
				success:	function(response) {
					drawAnalyticsProductGraph($.parseJSON(response));
					$('#wcfma_analytics_product_expander').unblock();
				}
			});
		}
	});
	
	var drawAnalyticsProductGraph = function( analytics_product_data ) {
		var ctx = document.getElementById("analytics-product-chart-placeholder-canvas").getContext("2d");
				
	  var wcfmProductChart = new Chart(ctx, {
		type: 'line',
		data: {
			  labels: analytics_product_data.labels,
				datasets: [{
								label: "Daily Views",
								backgroundColor: color(window.chartColors.green).alpha(0.2).rgbString(),
								borderColor: window.chartColors.green,
								fill: true,
								data: analytics_product_data.datas,
							}]
		},
		options: {
				title:{
						text: "Product Analytics"
				},
				legend: {
					position: "bottom"
				},
				scales: {
					xAxes: [{
						type: "time",
						time: {
							format: timeFormat,
							// round: 'day'
							tooltipFormat: 'll'
						},
						scaleLabel: {
							display: false,
							labelString: 'Date'
						}
					}, ],
					yAxes: [{
						scaleLabel: {
							display: false,
							labelString: 'Views'
						}
					}]
				},
			}
		});
	};
	
	// Fetching Taxonomies Stats
	jQuery('.wcfma_analytics_taxonomy_expander').css( 'width', jQuery('.wcfma_analytics_taxonomy_expander').outerWidth() + 'px' );
	$('.wcfma_taxonomy_analytics_go').each(function() {
		$(this).click(function(e) {
			e.preventDefault();
			$canvas = $(this).data('canvas');
			$taxonomy = $(this).data('taxonomy');
			console.log($taxonomy);
			if( $('#wcfma_'+$taxonomy).val().length > 0 ) {
				$('#wcfma_analytics_'+$taxonomy+'_expander').block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});
				var range = '7day';
				var start_date = '';
				var end_date = '';
				range = GetURLParameter('range');
				if( range == 'custom' ) {
					start_date = GetURLParameter('start_date');
					end_date = GetURLParameter('end_date');
				}
				var data = {
					action     : 'wcfm_category_analytics_data',
					categoryid : $('#wcfma_'+$taxonomy).val(),
					range      : range,
					start_date : start_date,
					end_date   : end_date
				}	
				$.ajax({
					type:		'POST',
					url: wcfm_params.ajax_url,
					data: data,
					success:	function(response) {
						drawAnalyticsCategoryGraph( $.parseJSON(response), $canvas );
						$('#wcfma_analytics_'+$taxonomy+'_expander').unblock();
					}
				});
			}
		});
	});
	
	var drawAnalyticsCategoryGraph = function( analytics_category_data, $canvas ) {
		var ctx = document.getElementById($canvas).getContext("2d");
				
	  var wcfmCategoryChart = new Chart(ctx, {
		type: 'line',
		data: {
			  labels: analytics_category_data.labels,
				datasets: [{
								label: "Daily Views",
								backgroundColor: color(window.chartColors.blue).alpha(0.2).rgbString(),
								borderColor: window.chartColors.blue,
								fill: true,
								data: analytics_category_data.datas,
							}]
		},
		options: {
				title:{
						text: "Category Analytics"
				},
				legend: {
					position: "bottom"
				},
				scales: {
					xAxes: [{
						type: "time",
						time: {
							format: timeFormat,
							// round: 'day'
							tooltipFormat: 'll'
						},
						scaleLabel: {
							display: false,
							labelString: 'Date'
						}
					}, ],
					yAxes: [{
						scaleLabel: {
							display: false,
							labelString: 'Views'
						}
					}]
				},
			}
		});
	};
} );