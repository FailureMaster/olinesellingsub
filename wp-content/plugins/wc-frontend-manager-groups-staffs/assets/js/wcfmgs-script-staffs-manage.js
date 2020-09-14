var removed_rules = [];
jQuery(document).ready(function($) {
	$('.wcfm_datepicker').each(function() {
	  $(this).datepicker({
      dateFormat : $(this).data('date_format'),
      changeMonth: true,
      changeYear: true
    });
  });
  
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
	
	if( !$('#wcfm_vendor').hasClass('wcfm_custom_hide') ) {
		$('#wcfm_vendor').select2( $wcfm_vendor_select_args );
	}
	
	$('#has_custom_capability').change(function() {
	  if( $(this).is(':checked') ) {
	  	$('.user_custom_capability').show();
	  } else {
	  	$('.user_custom_capability').hide();
	  }
	}).change();
  
	// Availability rules type
	function availabilityRules() {
		$('#_wc_appointment_availability').find('.multi_input_block').each(function() {
			if ( $(this).find('.avail_range_type').parent().is( "span" ) ) { $(this).find('.avail_range_type').unwrap( "span" ); }
			$(this).find('.avail_range_type').change(function() {
				$avail_range_type = $(this).val();
				$(this).parent().find('.avail_rule_field').addClass('wcfm_ele_hide');
				if( $avail_range_type == 'custom' || $avail_range_type == 'months' || $avail_range_type == 'weeks' || $avail_range_type == 'days' ) {
					$(this).parent().find('.avail_rule_' + $avail_range_type).removeClass('wcfm_ele_hide');
				} else if( ( $avail_range_type == 'time:range' ) || ( $avail_range_type == 'custom:daterange' ) ) {
					$(this).parent().find('.avail_rule_custom').removeClass('wcfm_ele_hide');
					$(this).parent().find('.avail_rule_time').removeClass('wcfm_ele_hide');
				} else {
					$(this).parent().find('.avail_rule_time').removeClass('wcfm_ele_hide');
				}
			}).change();
		});
	}
	if( $('#_wc_appointment_availability').length > 0 ) {
		availabilityRules();
		$('#_wc_appointment_availability').find('.add_multi_input_block').click(function() {
			availabilityRules();
		});
		
		// Track Deleting Rules
		$('#_wc_appointment_availability').children('.multi_input_block').children('.remove_multi_input_block').click(function() {
			removed_rules.push($(this).parent().find('.avail_id').val());
		});
	}
	
	function wcfm_staffs_manage_form_validate() {
		$is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
		var user_name = $.trim($('#wcfm_staffs_manage_form').find('#user_name').val());
		var user_email = $.trim($('#wcfm_staffs_manage_form').find('#user_email').val());
		if(user_name.length == 0) {
			$is_valid = false;
			$('#wcfm_staffs_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_staffs_manage_messages.no_username).addClass('wcfm-error').slideDown();
			audio.play();
		} else if(user_email.length == 0) {
			$is_valid = false;
			$('#wcfm_staffs_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_staffs_manage_messages.no_email).addClass('wcfm-error').slideDown();
			audio.play();
		}
		return $is_valid;
	}
	
	// Submit Staff
	$('#wcfm_staff_manager_submit_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_staffs_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                   : 'wcfm_ajax_controller',
				controller               : 'wcfm-staffs-manage',
				wcfm_staffs_manage_form : $('#wcfm_staffs_manage_form').serialize(),
				removed_rules            : removed_rules,
				status                   : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.redirect) {
						audio.play();
						$('#wcfm_staffs_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_staffs_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#staff_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});
} );