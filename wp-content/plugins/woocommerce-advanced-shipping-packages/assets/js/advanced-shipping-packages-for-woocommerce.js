jQuery( function( $ ) {

	// Quick help
	$( '.aspwc-quickhelp' ).on( 'click', function() {
		$( this ).find( '.dashicons' ).toggleClass( 'dashicons-arrow-right-alt2 dashicons-arrow-down-alt2' );
		$( this ).parents( '#advanced-shipping-packages-table' ).find( '.description' ).slideToggle( 'fast' );
	});

	$('.switch-include-exclude-shipping').on('click', function() {
		$(this).parents('.aspwc-option').toggleClass('aspwc-shipping-option-include');
		$(this).parents('.aspwc-option').toggleClass('aspwc-shipping-option-exclude');

		$('.include-exclude-shipping select').attr('name',
			$('.include-exclude-shipping select').attr('name') == '_include_shipping[]' ? '_exclude_shipping[]': '_include_shipping[]'
		);
	});

	/**************************************************************
	 * Product conditions
	 *************************************************************/


	// Update product condition values
	$( '#advanced_shipping_packages_settings' ).on( 'change', '.wpc-condition', function () {

		var loading_wrap = '<span style="width: calc( 42.5% - 75px ); border: 1px solid transparent; display: inline-block;">&nbsp;</span>';
		var data = {
			action: 	'aspwc_update_product_condition_value',
			id:			$( this ).attr( 'data-id' ),
			group:		$( this ).parents( '.wpc-condition-group' ).attr( 'data-group' ),
			condition: 	$( this ).val(),
			nonce: 		wpc.nonce
		};
		var condition_wrap = $( this ).parents( '.wpc-condition-wrap' ).first();
		var replace = '.wpc-value-field-wrap';

		// Loading icon
		condition_wrap.find( replace ).html( loading_wrap ).block({ message: null, overlayCSS: { background: '', opacity: 0.6 } });

		// Replace value field
		$.post( ajaxurl, data, function( response ) {
			condition_wrap.find( replace ).replaceWith( response );
			$( document.body ).trigger( 'wc-enhanced-select-init' );
		});

		// Update operators
		var operator_value = condition_wrap.find( '.wpc-operator' ).val();
		condition_wrap.find( '.wpc-operator' ).empty().html( function() {
			var operator = $( this );
			var available_operators = wpc.condition_operators[ data.condition] || wpc.condition_operators['default'];

			$.each( available_operators, function( index, value ) {
				operator.append( $('<option/>' ).attr( 'value', index ).text( value ) );
				operator.val( operator_value ).val() || operator.val( operator.find( 'option:first' ).val() );
			});
		});

		return false;

	});


});
