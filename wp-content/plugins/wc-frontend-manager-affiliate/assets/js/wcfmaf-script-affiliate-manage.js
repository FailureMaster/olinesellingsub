jQuery(document).ready(function($) {
	$('#vendor_commission_rule').change(function() {
		$vendor_commission_rule = $(this).val();
		$(this).parent().parent().find('.affiliate_commission_rule_personal').addClass('wcfm_ele_hide');
		if( $vendor_commission_rule == 'personal' ) {
			$(this).parent().parent().find('.affiliate_commission_rule_personal').removeClass('wcfm_ele_hide');
		}
	}).change();
	$('#vendor_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
	
	$('#vendor_order_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
	
	$('#order_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
  
	function wcfm_affiliate_manage_form_validate() {
		$is_valid = true;
		$('.wcfm-message').html('').removeClass('wcfm-error').slideUp();
		var user_name = $.trim($('#wcfm_affiliate_manage_form').find('#user_name').val());
		var user_email = $.trim($('#wcfm_affiliate_manage_form').find('#user_email').val());
		if(user_name.length == 0) {
			$is_valid = false;
			$('#wcfm_affiliate_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_affiliate_manage_messages.no_username).addClass('wcfm-error').slideDown();
			audio.play();
		} else if(user_email.length == 0) {
			$is_valid = false;
			$('#wcfm_affiliate_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + wcfm_affiliate_manage_messages.no_email).addClass('wcfm-error').slideDown();
			audio.play();
		}
		return $is_valid;
	}
	
	// Submit Affiliate
	$('#wcfm_affiliate_manager_submit_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = wcfm_affiliate_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm-content').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                         : 'wcfm_ajax_controller',
				controller                     : 'wcfm-affiliate-manage',
				wcfm_affiliate_manage_form     : $('#wcfm_affiliate_manage_form').serialize(),
				status                         : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.redirect) {
						audio.play();
						$('#wcfm_affiliate_manage_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown( "slow", function() {
						  if( $response_json.redirect ) window.location = $response_json.redirect;	
						} );
					} else {
						audio.play();
						$('.wcfm-message').html('').removeClass('wcfm-success').slideUp();
						$('#wcfm_affiliate_manage_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					if($response_json.id) $('#affiliate_id').val($response_json.id);
					$('#wcfm-content').unblock();
				}
			});
		}
	});
} );