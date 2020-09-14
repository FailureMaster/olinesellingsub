jQuery(document).ready( function($) {
	// Collapsible
  $('.page_collapsible').collapsible({
		defaultOpen: 'groups_manage_restriction',
		speed: 'slow',
		loadOpen: function (elem) { //replace the standard open state with custom function
				elem.next().show();
		},
		loadClose: function (elem, opts) { //replace the close state with custom function
				elem.next().hide();
		},
		animateOpen: function(elem, opts){
			$('.collapse-open').addClass('collapse-close').removeClass('collapse-open');
			elem.addClass('collapse-open');
			$('#wcfm_groups_manage_form .wcfm-container:not(:first)').stop(true, true).slideUp(opts.speed);
			elem.next().stop(true, true).slideDown(opts.speed);
		}
	});
	
	if( $("#group_vendors").length > 0 ) {
		$("#group_vendors").select2({
			placeholder: "Choose Vendors ..."
		});
	}
	
	if( $("#group_managers").length > 0 ) {
		$("#group_managers").select2({
			placeholder: "Choose Managers ..."
		});
	}
	
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
	
	
	function wcfm_groups_manage_form_validate() {
		$is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
		var title = $.trim($('#wcfm_groups_manage_form').find('#title').val());
		if(title.length == 0) {
			$is_valid = false;
			$('#wcfm_groups_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_groups_manage_messages.no_title).addClass('wcfm-error').slideDown();
			audio.play();
		}
		return $is_valid;
	}
	
	// Submit Group
	$('#wcfm_group_manager_submit_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_groups_manage_form_validate();
	  
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
				controller               : 'wcfm-groups-manage',
				wcfm_groups_manage_form : $('#wcfm_groups_manage_form').serialize(),
				status                   : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.status) {
						audio.play();
						$('#wcfm_groups_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_groups_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#group_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});
} );