jQuery(document).ready(function($) {
  if( $('#vendor_allowed_article_category').length > 0 ) {
		$('#vendor_allowed_article_category').select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $("#group_allowed_categories").length > 0 ) {
		$("#group_allowed_categories").select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $('.group_allowed_custom_taxonomies').length > 0 ) {
		$('.group_allowed_custom_taxonomies').select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $('#vendor_allowed_attributes').length > 0 ) {
		$('#vendor_allowed_attributes').select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	if( $('#vendor_allowed_custom_fields').length > 0 ) {
		$('#vendor_allowed_custom_fields').select2({
			placeholder: wcfm_dashboard_messages.choose_select2 + ' ...'
		});
	}
	
	$('#has_custom_capability').change(function() {
	  if( $(this).is(':checked') ) {
	  	$('.user_custom_capability').show();
	  } else {
	  	$('.user_custom_capability').hide();
	  }
	}).change();
});