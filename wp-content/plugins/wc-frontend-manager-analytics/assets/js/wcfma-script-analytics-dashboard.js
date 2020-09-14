jQuery(document).ready(function($) {
	jQuery('#wcfm_world_map_analytics_view').vectorMap({
			map: wcfma_map_name,
			backgroundColor: 'transparent',
			regionStyle: {
				initial: {
					fill: '#d7f0ed'
				}
			},
			series: {
				regions: [{
				values: wcfminsights_countries,
				scale: ['#d7f0ed', '#00798B'],
				normalizeFunction: 'polynomial'
				}]
			},
			onRegionTipShow: function(e, el, code){
				el.html(el.html()+' ('+ wcfminsights_countries[code] + ' ' + wcfminsights_viewname +')');
			}
  });
});