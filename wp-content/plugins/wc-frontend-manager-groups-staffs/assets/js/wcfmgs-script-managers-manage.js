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
	
	$('#has_custom_capability').change(function() {
	  if( $(this).is(':checked') ) {
	  	$('.user_custom_capability').show();
	  } else {
	  	$('.user_custom_capability').hide();
	  }
	}).change();
	
	function wcfm_managers_manage_form_validate() {
		$is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
		var user_name = $.trim($('#wcfm_managers_manage_form').find('#user_name').val());
		var user_email = $.trim($('#wcfm_managers_manage_form').find('#user_email').val());
		if(user_name.length == 0) {
			$is_valid = false;
			$('#wcfm_managers_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_managers_manage_messages.no_username).addClass('wcfm-error').slideDown();
			audio.play();
		} else if(user_email.length == 0) {
			$is_valid = false;
			$('#wcfm_managers_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_managers_manage_messages.no_email).addClass('wcfm-error').slideDown();
			audio.play();
		}
		return $is_valid;
	}
	
	// Submit Manager
	$('#wcfm_manager_manager_submit_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_managers_manage_form_validate();
	  
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
				controller               : 'wcfm-managers-manage',
				wcfm_managers_manage_form : $('#wcfm_managers_manage_form').serialize(),
				status                   : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.redirect) {
						audio.play();
						$('#wcfm_managers_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_managers_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#manager_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});
} );