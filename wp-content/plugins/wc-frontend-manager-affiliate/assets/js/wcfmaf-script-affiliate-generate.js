jQuery(document).ready(function($) {
  // Affiliate URL Generate
	$('#wcfm_affiliate_url_generate_button').click(function(event) {
	  event.preventDefault();
	  
	  // Validations
	  $is_valid = true;
	  var normal_url = $.trim($('#wcfm_affiliate_url_form').find('#normal_url').val());
	  if( !normal_url ) $is_valid = false;
	  
	  
	  if($is_valid) {
			$('#wcfm_affiliate_url_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action                         : 'wcfm_affiliate_url_generate',
				wcfm_affiliate_url_form        : $('#wcfm_affiliate_url_form').serialize(),
				status                         : 'submit'
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					if($response_json.status) {
						if($response_json.generated_url) {
							$('.generated_url').removeClass('wcfm_ele_hide');
							$('#generated_url').val($response_json.generated_url).removeClass('wcfm_ele_hide');
						}
					} else {
						if($response_json.message) {
							$('#normal_url').val('');
							alert( $response_json.message );
						}
					}
					$('#wcfm_affiliate_url_form').unblock();
				}
			});
		}
	});
});