jQuery(document).ready(function($) {
	$('#vendoraf_commission_rule').change(function() {
		$vendor_commission_rule = $(this).val();
		$(this).parent().parent().find('.affiliate_commission_rule_personal').addClass('wcfm_ele_hide');
		if( $vendor_commission_rule == 'personal' ) {
			$(this).parent().parent().find('.affiliate_commission_rule_personal').removeClass('wcfm_ele_hide');
		}
		resetCollapsHeight($('#vendoraf_commission_rule').parent());
	}).change();
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