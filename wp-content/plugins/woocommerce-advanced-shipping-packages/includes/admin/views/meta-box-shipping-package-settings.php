<?php
/**
 * ASPWC meta box settings.
 *
 * Display the shipping settings in the meta box.
 *
 * @author		Jeroen Sormani
 * @package		Advanced Shipping Packages for WooCommerce
 * @version		1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_nonce_field( 'aspwc_meta_box', 'aspwc_meta_box_nonce' );

global $post;
$post_id = $post->ID;

$name               = get_post_meta( $post_id, '_name', true );
$condition_groups   = get_post_meta( $post_id, '_product_conditions', true );
$excluded_shipping  = array_filter( (array) get_post_meta( $post_id, '_exclude_shipping', true ) );
$included_shipping  = array_filter( (array) get_post_meta( $post_id, '_include_shipping', true ) );
$shipping_methods   = array();

// Get shipping methods from the 'shipping method' condition
$shipping_method_condition = wpc_get_condition( 'shipping_method' );
$field_args = $shipping_method_condition->get_value_field_args();
$shipping_methods = $field_args['options'];
$shipping_option_type = empty( $included_shipping ) ? 'exclude' : 'include';

?><div class='aspwc-meta-box aspwc-meta-box-settings'>

	<div class='aspwc-shipping-package'>

		<p class="aspwc-option">
			<label>
				<span class="aspwc-option-name"><?php _e( 'Package name', 'advanced-shipping-packages-for-woocommerce' ); ?></span>
				<input type="text" class="aspwc-input" name="_name" value="<?php echo wp_kses_post( $name ); ?>">
			</label><?php
			echo wc_help_tip( __( 'This is the name that will be displayed at the cart/checkout', 'advanced-shipping-packages-for-woocommerce' ) );
		?></p>

		<p class="aspwc-option <?php echo 'aspwc-shipping-option-' . $shipping_option_type; ?>">
			<input type="hidden" name="_exclude_shipping[]">
			<input type="hidden" name="_include_shipping[]">

			<span class="include-exclude-shipping">
				<label>
					<span class="aspwc-option-name show-if-exclude">
						<?php _e( 'Exclude shipping methods', 'advanced-shipping-packages-for-woocommerce' ); ?>
						<i class="dashicons dashicons-update switch-include-exclude-shipping" style="line-height: 30px;"></i>
					</span>
					<span class="aspwc-option-name show-if-include">
						<?php _e( 'Whitelist shipping methods', 'advanced-shipping-packages-for-woocommerce' ); ?>
						<i class="dashicons dashicons-update switch-include-exclude-shipping" style="line-height: 30px;"></i>
					</span>

					<select class="aspwc-input wc-enhanced-select" name="<?php echo $shipping_option_type === 'exclude' ? '_exclude_shipping[]' : '_include_shipping[]'; ?>" multiple="multiple" placeholder="<?php _e( 'Exclude shipping options', 'advanced-shipping-packages-for-woocommerce' ); ?>"><?php
						foreach ( $shipping_methods as $optgroup => $methods ) :
							?><optgroup label='<?php echo esc_attr( $optgroup ); ?>'><?php
							foreach ( $methods as $k => $v ) :
								?><option value="<?php echo esc_attr( $k ); ?>" <?php selected( in_array( $k, ($excluded_shipping + $included_shipping) ) ); ?>><?php echo esc_html( $v ); ?></option><?php
							endforeach;
						endforeach;
					?></select>
				</label><?php
				echo wc_help_tip( __( 'Exclude or whitelist shipping methods for this package. Leave empty to allow all available methods.', 'advanced-shipping-packages-for-woocommerce' ) ); ?>
			</span>

		</p>

	</div>

	<br/>
	<hr/>

	<div class='wpc-conditions wpc-conditions-meta-box'>
		<div class='wpc-condition-groups'>

			<p>
				<strong><?php _e( 'Add the products that match one of the following rule groups to the package', 'advanced-shipping-packages-for-woocommerce' ); ?></strong><?php
			?></p><?php

			if ( ! empty( $condition_groups ) ) :

				foreach ( $condition_groups as $condition_group => $conditions ) :
					include 'html-product-condition-group.php';
				endforeach;

			else :

				$condition_group = '0';
				include 'html-product-condition-group.php';

			endif;

		?></div>

		<div class='wpc-condition-group-template hidden' style='display: none'><?php
			$condition_group = '9999';
			$conditions      = array();
			include 'html-product-condition-group.php';
		?></div>
		<a class='button wpc-condition-group-add' href='javascript:void(0);'><?php _e( 'Add \'Or\' group', 'woocommerce-advanced-messages' ); ?></a>

	</div>
</div>
