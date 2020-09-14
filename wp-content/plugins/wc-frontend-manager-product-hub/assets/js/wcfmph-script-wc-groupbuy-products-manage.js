jQuery(document).ready(function($) {

	var startDateTextBox = $('#_groupbuy_dates_from');
	var endDateTextBox   = $('#_groupbuy_dates_to');

	$.timepicker.datetimeRange(
		startDateTextBox,
		endDateTextBox,
		{
			minInterval: (1000*60), // 1min
			dateFormat: 'yy-mm-dd',
			timeFormat: 'HH:mm',
			start: {}, // start picker options
			end: {} // end picker options
		}
	);
	
	if( $('#_relist_groupbuy_dates_from').length > 0 ) {
		var reliststartDateTextBox = $('#_relist_groupbuy_dates_from');
		var relistendDateTextBox   = $('#_relist_groupbuy_dates_to');

		$.timepicker.datetimeRange(
			reliststartDateTextBox,
			relistendDateTextBox,
			{
				minInterval: (1000*60), // 1min
				dateFormat: 'yy-mm-dd',
				timeFormat: 'HH:mm',
				start: {}, // start picker options
				end: {} // end picker options
			}
		);
	}

});