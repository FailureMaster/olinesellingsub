jQuery(document).ready(function($) {
	$("#bundle_sell_ids").select2( $wcfm_product_select_args );
  $(".bundle_item").select2( $wcfm_product_select_args );
  $('#bundle_data').on('change','.bundle_item', function(e) {
    var bd = $(this);
    var productid = $(this).val();
    var selected_array = [];
    var selected_default_array = [];
    bd.parents('.multi_input_block').find('.selected_product_id').val(productid);
    bd.parents('.multi_input_block').find('.dflt_items_datas').prev().addClass('hide_variation');
    bd.parents('.multi_input_block').find('.dflt_items_datas,.allowed_variations').addClass('hide_variation');
    var allcls = $('.dflt_items_datas').attr('class'); 
    bd.parents('.multi_input_block').find('.dflt_items_datas').removeClass(allcls).addClass('wcfm-select multiSelectBox-right wcfm_ele bundle every_input_field hide_variation multi_input_block_element dflt_items_datas');
  
    bd.parents('.multi_input_block').find('.apnd').remove();
    bd.parents('.multi_input_block').find('.override_variations_checkbox,.override_default_variation_attribute_checkbox').attr('checked', false);
    if( productid ) {
    	get_product_all_variation_details(bd,productid,selected_array,selected_default_array,'add');
    }
  });
  if(typeof variations_prod != "undefined" && variations_prod !== "null") {
      $('.bundle_item').each(function() {
        prodid = $(this).val();
        var bd = $(this);
        var bundle_id = bd.parents('.multi_input_block').find('.bundled_item_id').val();
        var prod_variation = $.parseJSON(variations_prod.var);
        var selected_array = [];
        if( bundle_id in prod_variation ) {
					$.each( prod_variation[bundle_id]['selected_var'], function(i,v){
						selected_array.push( parseInt(v) );
					});
				}
        var default_variation = $.parseJSON(variations_prod.default);
        var selected_default_array = [];
        if( bundle_id in default_variation ) {
					$.each( default_variation[bundle_id],function(i,v){
						$.each( v,function(k,val){
							selected_default_array.push( val );
						});
					});
				}
        var select_name = $(this).attr('name');
        var select_id = $(this).attr('id');
        var selected_product_name = $(this).find("option:selected").text();

        var select_class = 'wcfm-text wcfm_ele bundle every_input_field bundle_item bundle_item_pro pro_name_txt multi_input_block_element';
        if( bundle_id != '' ) {
          $(this).next(".select2-container").remove();
          $('<input type="text" class="'+select_class+'" id="txt_'+select_id+'" name="bundle_pro_name" readonly value="'+selected_product_name+'"/>').insertAfter($(this));
        }
        
        if( prodid ) {
        	get_product_all_variation_details(bd,prodid,selected_array,selected_default_array,'edit');
        }
      });
  }
  
  function get_product_all_variation_details(bd,productid,selected_array,selected_default_array,mode) {       
    if( selected_array.length > 0 ) {
      bd.parents('.multi_input_block').find('.allowed_variations').removeClass("hide_variation");
    }     
    var data = {
      action : 'wcfmph_check_product_type',
      productid : productid
    };
    $.post(wcfm_params.ajax_url,data,function(response) {        
      response = $.parseJSON(response);
      if(response.variableproduct == 0 ) {
        bd.parents('.multi_input_block').find('.filter_variation,.bundle_dynamic_field,.default_ovrride_attr,.allowed_variations').addClass("hide_variation");
        if(mode == 'add') {
          if( response.subscription == 1 ) {
            bd.parents('.multi_input_block').find('.priced_individually_checkbox').attr('checked', true);
            bd.parents('.multi_input_block').find('.dependscheckbox').removeClass('hide_visiblity hide_variation');
          } else {
            bd.parents('.multi_input_block').find('.priced_individually_checkbox').attr('checked', false);
            bd.parents('.multi_input_block').find('.dependscheckbox').addClass('hide_visiblity hide_variation');
          }
        }
        
      } else {
        var attr_name = bd.parents('.multi_input_block').find('.dflt_items_datas').attr('name');
        bd.parents('.multi_input_block').find('.filter_variation').removeClass("hide_variation");  
        if(mode == 'add') {
          if( response.subscription == 1 ) {
            bd.parents('.multi_input_block').find('.priced_individually_checkbox').attr('checked', true);
            bd.parents('.multi_input_block').find('.dependscheckbox').removeClass('hide_visiblity hide_variation');
          } else {
            bd.parents('.multi_input_block').find('.priced_individually_checkbox').attr('checked', false);
            bd.parents('.multi_input_block').find('.dependscheckbox').addClass('hide_visiblity hide_variation');
          } 
        }     
        var select_filter_options = '';
        $.each( response.title, function( key, value ) {
          if( $.inArray( parseInt(key), selected_array ) > -1 ){
            select_filter_options += '<option selected="true" value="'+key+'" >'+value+'</option>';
          } else {
            select_filter_options += '<option value="'+key+'" >'+value+'</option>';
          }
        });        
        var select_tag = '';
        var override_default_variation_attribute_checkbox = bd.parents('.multi_input_block').find('.override_default_variation_attribute_checkbox');
        var c = override_default_variation_attribute_checkbox.next();
        if((override_default_variation_attribute_checkbox).is(":checked")) {
          bd.parents('.multi_input_block').find('.default_ovrride_attr').removeClass('hide_variation');
          bd.parents('.multi_input_block').find('.dflt_items_datas').removeClass('hide_variation');
        }       

        var count = 0;
        $.each( response.attr_array, function( key, value ) {
          $.each( value, function( k, v ) {
            if(key != select_tag) {
              if( $.inArray( v, selected_default_array ) > -1 ) {
                attr_html = '<option selected="true" value="'+v+'" >'+v+'</option>';  
              } else {
                attr_html = '<option value="'+v+'" >'+v+'</option>'; 
              }       
            } else {
              if( $.inArray( v, selected_default_array ) > -1 ) {
                attr_html += '<option selected="true" value="'+v+'" >'+v+'</option>';  
              } else {
                attr_html += '<option value="'+v+'" >'+v+'</option>'; 
              }
            }
           select_tag = key;
          });
          count++;          
          bd.parents('.multi_input_block').find('.dflt_items_datas').clone().removeClass('dflt_items_datas').addClass('dflt_items_datas'+count).addClass('multiSelectBox-right apnd variation_attr_hide').attr('name', attr_name+'['+key+']').insertAfter(c).html('<option value="">'+default_attribute.default_txt+'</option>'+attr_html);
          attr_html = '';
        });
        bd.parents('.multi_input_block').find('.bundle_dynamic_field').html(select_filter_options);
        bd.parents('.multi_input_block').find('.bundle_dynamic_field').select2({
            placeholder: "Choose ..."
        });
        bd.parents('.multi_input_block').find('.dflt_items_datas').remove();
        bd.parents('.multi_input_block').find('.dflt_items_datas'+count).addClass('dflt_items_datas').removeClass('apnd');
      }
    });
  }

  $("body").on("change", ".override_variations_checkbox",function() {  
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.allowed_variations');
    container.find('.select2').removeClass("hide_variation");
    field_hideShow(current_ele, checkboxs);
  });

  $("body").on("change", ".override_default_variation_attribute_checkbox",function() {  
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.variation_attr_hide');
    field_hideShow(current_ele,checkboxs);
    if(current_ele.is(':checked')) {     
      container.find('.variation_attr_hide').last().removeClass("hide_variation");   
    } else {    
      //container.find('.variation_attr_hide').last().addClass("hide_variation");     
    }
  });

  //change price individually
  $("body").on("change wcfm_bundle_change", ".priced_individually_checkbox",function() {   
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.dependscheckbox');
    field_hideShow(current_ele,checkboxs);
  });
  //override title
  $("body").on("change wcfm_bundle_change", ".override_title_check",function() {  
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.override_title_feild');  
    field_hideShow(current_ele,checkboxs);    
  }); 
  //override description
  $("body").on("change wcfm_bundle_change", ".override_description_check",function() {  
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.override_description_feild');  
    field_hideShow(current_ele,checkboxs);
  });
  //visible product
  $("body").on("change wcfm_bundle_change", ".visibility_product",function() {    
    var current_ele = $(this);
    var container = current_ele.parent('.multi_input_block'); 
    var checkboxs = container.find('.dependsonproductdetails');
    var override_title_checkbox = container.find('.override_title_check');
    var dependent_title = container.find('.override_title_feild');
    var override_des_checkbox = container.find('.override_description_check');
    var dependent_description = container.find('.override_description_feild');   
    if((current_ele).is(":checked")) {
      field_hideShow(override_title_checkbox,dependent_title);
      field_hideShow(override_des_checkbox,dependent_description);
    } else {
      $(dependent_description).addClass("hide_variation");
      $(dependent_title).addClass("hide_variation");
    }
    field_hideShow(current_ele,checkboxs);     
  });

  function field_hideShow(current_ele,checkboxs) {    
    if((current_ele).is(":checked")) {
      $(checkboxs).removeClass("hide_variation hide_visiblity");
      resetCollapsHeight($('#bundle_data'));
    } else {
      $(checkboxs).addClass("hide_variation hide_visiblity");
    }
  }

  $('#bundle_data .add_multi_input_block').on('click', function() {
    $(this).parents('.multi_input_block').find('.bundled_item_id,.pro_name').val("");
    $(this).parents('.multi_input_block').find('.select2-container,.pro_name_txt').remove();
    $(this).parents('.multi_input_block').find('.pro_name').select2( $wcfm_product_select_args ); 
    $(this).parents('.multi_input_block').find('.allowed_variations,.filter_variation,.variation_attr_hide,.dependscheckbox,.override_title_feild,.override_description_feild').addClass("hide_variation");
    $(this).parents('.multi_input_block').find('.checked_on_load').attr('checked', true);
    $(this).parents('.multi_input_block').find('.checked_on_load_product_des').removeClass('hide_visiblity hide_variation');
    $(this).parents('.multi_input_block').find('.bundle_quantity_min,.bundle_quantity_max').val(1);
    var bundle_dynamic_field = $(this).parent('.multi_input_block').find('.bundle_dynamic_field').attr('name');
    $(this).parent('.multi_input_block').find('.bundle_dynamic_field').attr( 'name', bundle_dynamic_field+'[]' );  
    initiateTip();
  });
  
  // Creating Bundle Collapser
	$('#bundle_data').children('.multi_input_block').each(function() {
		$multi_input_block = $(this);
		$multi_input_block.prepend('<div class="wcfm_clearfix"></div>');
		$multi_input_block.prepend('<span class="fields_collapser variations_collapser wcfmfa fa-arrow-circle-down"></span>');
	});
	
	// Bundle Collapser
	$('#bundle_data').children('.multi_input_block').children('.add_multi_input_block').click(function() {
	  $('#bundle_data').children('.multi_input_block').children('.variations_collapser').each(function() {
			$(this).off('click').on('click', function() {
				$(this).parent().find('.wcfm_ele:not(.bundle_item_pro), .wcfm_title:not(.bundle_item_pro)').toggleClass('variation_ele_hide');
				$(this).toggleClass('fa-arrow-circle-up');
				resetCollapsHeight($('#bundle_data'));
			} );
			$(this).parent().find('.wcfm_ele:not(.bundle_item_pro), .wcfm_title:not(.bundle_item_pro)').addClass('variation_ele_hide');
			$(this).removeClass('fa-arrow-circle-up');
			resetCollapsHeight($('#bundle_data'));
		} );
		$('#bundle_data').children('.multi_input_block:last').children('.variations_collapser').click();
	});
	$('#bundle_data').children('.multi_input_block').children('.variations_collapser').each(function() {
		$(this).addClass('fa-arrow-circle-up');
		$(this).off('click').on('click', function() {
			$(this).parent().find('.wcfm_ele:not(.bundle_item_pro), .wcfm_title:not(.bundle_item_pro)').toggleClass('variation_ele_hide');
			$(this).toggleClass('fa-arrow-circle-up');
			resetCollapsHeight($('#bundle_data'));
		} ).click();
	} );
});
jQuery(window).load(function(){
  jQuery('body .override_title_check,.override_description_check,.priced_individually_checkbox,.visibility_product').trigger('wcfm_bundle_change');
});