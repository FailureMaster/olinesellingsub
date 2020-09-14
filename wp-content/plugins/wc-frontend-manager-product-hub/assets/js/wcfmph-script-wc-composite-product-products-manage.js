jQuery(document).ready(function($) {
  $('#bto_data').find('.assigned_ids').each(function() { $(this).select2( { placeholder: 'Search for a product...' } ); } );
	$('#bto_data').find('.add_multi_input_block').click(function() {
		$('#bto_data').find('.multi_input_block:last').find('input[type=number]').val('1');
	  $('#bto_data').find('.multi_input_block:last').find('.assigned_ids').each(function() { 
	  	$(this).val('');
	    $(this).select2( { placeholder: 'Search for a product...' } ); 
	  } );
	});
	
	// Creating Bundle Collapser
	$('#bto_data').children('.multi_input_block').each(function() {
		$multi_input_block = $(this);
		$multi_input_block.prepend('<div class="wcfm_clearfix"></div>');
		$multi_input_block.prepend('<span class="fields_collapser variations_collapser fa fa-arrow-circle-o-down"></span>');
	});
	
	// Bundle Collapser
	$('#bto_data').children('.multi_input_block').children('.add_multi_input_block').click(function() {
	  $('#bto_data').children('.multi_input_block').children('.variations_collapser').each(function() {
			$(this).off('click').on('click', function() {
				$(this).parent().find('.wcfm_ele:not(.composite_item_title), .wcfm_title:not(.composite_item_title)').toggleClass('variation_ele_hide');
				$(this).toggleClass('fa-arrow-circle-o-up');
				resetCollapsHeight($('#bto_data'));
			} );
			$(this).parent().find('.wcfm_ele:not(.composite_item_title), .wcfm_title:not(.composite_item_title)').addClass('variation_ele_hide');
			$(this).removeClass('fa-arrow-circle-o-up');
			resetCollapsHeight($('#bto_data'));
		} );
		$('#bto_data').children('.multi_input_block:last').children('.variations_collapser').click();
	});
	$('#bto_data').children('.multi_input_block').children('.variations_collapser').each(function() {
		$(this).addClass('fa-arrow-circle-o-up');
		$(this).off('click').on('click', function() {
			$(this).parent().find('.wcfm_ele:not(.composite_item_title), .wcfm_title:not(.composite_item_title)').toggleClass('variation_ele_hide');
			$(this).toggleClass('fa-arrow-circle-o-up');
			resetCollapsHeight($('#bto_data'));
		} ).click();
	} );
});