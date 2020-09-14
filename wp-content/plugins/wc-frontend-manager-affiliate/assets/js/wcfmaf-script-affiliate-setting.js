jQuery(document).ready(function($) {
	$('#vendoraf_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
	
	$('#vendoraf_order_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
	
	$('#orderaf_commission_mode').change(function() {
		$vendor_commission_mode = $(this).val();
		$(this).parent().find('.commission_mode_field').addClass('wcfm_ele_hide');
		$(this).parent().find('.commission_mode_'+$vendor_commission_mode).removeClass('wcfm_ele_hide');
	}).change();
});