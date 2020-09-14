<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Condition class.
 *
 * Represents a single condition in a condition group.
 *
 * @author  Jeroen Sormani
 * @version 1.0.0
 */
class ASPWC_Product_Condition {


	/**
	 * Condition ID.
	 *
	 * @since 1.0.0
	 * @var string $id Condition ID.
	 */
	public $id;

	/**
	 * Condition.
	 *
	 * @since 1.0.0
	 * @var string $condition Condition slug.
	 */
	public $condition;

	/**
	 * Operator.
	 *
	 * @since 1.0.0
	 * @var string $operator Operator slug.
	 */
	public $operator;

	/**
	 * Value.
	 *
	 * @since 1.0.0
	 * @var string $value Condition value.
	 */
	public $value;

	/**
	 * Group ID.
	 *
	 * @since 1.0.0
	 * @var string $group Condition group ID.
	 */
	public $group;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $id = null, $group = 0, $condition = null, $operator = null, $value = null ) {

		$this->id        = $id;
		$this->group     = $group;
		$this->condition = $condition;
		$this->operator  = $operator;
		$this->value     = $value;

		if ( ! $id ) {
			$this->id = rand();
		}

	}


	/**
	 * Output condition row.
	 *
	 * Output the full condition row which includes: condition, operator, value, add/delete buttons and
	 * the description.
	 *
	 * @since 1.0.0
	 */
	public function output_condition_row() {

		$wp_condition = $this;
		require plugin_dir_path( __FILE__ ) . 'views/html-product-condition-row.php';

	}


	/**
	 * Get conditions.
	 *
	 * Get a list with the available conditions.
	 * NOTE: The keys are specifically like this so the matching functionality
	 * of the package conditions is re-used. DO NOT CHANGE KEYS.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of available conditions for a condition row.
	 */
	public function get_conditions() {

		$conditions = array(
			__( 'Product', 'advanced-shipping-packages-for-woocommerce' ) => array(
				'subtotal'                => __( 'Line total (price * quantity)', 'advanced-shipping-packages-for-woocommerce' ),
				'price'                   => __( 'Product price', 'advanced-shipping-packages-for-woocommerce' ),
				'quantity'                => __( 'Quantity', 'advanced-shipping-packages-for-woocommerce' ),
				'contains_product'        => __( 'Product', 'advanced-shipping-packages-for-woocommerce' ),
				'weight'                  => __( 'Weight', 'advanced-shipping-packages-for-woocommerce' ),
				'contains_shipping_class' => __( 'Shipping class', 'advanced-shipping-packages-for-woocommerce' ),
				'contains_category'       => __( 'Has category', 'advanced-shipping-packages-for-woocommerce' ),
				'width'                   => __( 'Width', 'advanced-shipping-packages-for-woocommerce' ),
				'height'                  => __( 'Height', 'advanced-shipping-packages-for-woocommerce' ),
				'length'                  => __( 'Length', 'advanced-shipping-packages-for-woocommerce' ),
				'stock'                   => __( 'Stock', 'advanced-shipping-packages-for-woocommerce' ),
				'stock_status'            => __( 'Stock status', 'advanced-shipping-packages-for-woocommerce' ),
			),
		);
		$conditions = apply_filters( 'advanced_shipping_packages_for_woocommerce_product_conditions', $conditions );

		return $conditions;

	}


	/**
	 * Get available operators.
	 *
	 * Get a list with the available operators for the conditions.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of available operators.
	 */
	public function get_operators() {
		$wpc_condition = wpc_get_condition( $this->condition );
		return apply_filters( 'advanced_shipping_packages_operators', $wpc_condition->get_available_operators() );
	}


	/**
	 * Get value field args.
	 *
	 * Get the value field args that are condition dependent. This usually includes
	 * type, class and placeholder.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public function get_value_field_args() {

		// Defaults
		$default_field_args = array(
			'name' => '_product_conditions[' . absint( $this->group ) . '][' . absint( $this->id ) . '][value]',
			'placeholder' => '',
			'type' => 'text',
			'class' => array( 'wpc-value' ),
			'value' => $this->value,
		);

		$field_args = $default_field_args;
		if ( $condition = wpc_get_condition( $this->condition ) ) {
			$field_args = wp_parse_args( $condition->get_value_field_args(), $default_field_args );
		}

		if ( $this->condition == 'contains_product' && $product = wc_get_product( $this->value ) ) {
			$field_args['custom_attributes']['data-selected'] = $product->get_formatted_name(); // WC < 2.7
			$field_args['options'][ $this->value ] = $product->get_formatted_name(); // WC >= 2.7
		}

		$field_args = apply_filters( 'advanced_shipping_packages_values', $field_args, $this->condition );

		return $field_args;


	}


	/**
	 * Get description.
	 *
	 * Return the description related to this condition.
	 *
	 * @since 1.0.0
	 */
	public function get_description() {

		$descriptions = array(
			'subtotal'                => __( 'The item line total must match the condition', 'advanced-shipping-packages-for-woocommerce' ),
			'price'                   => __( 'The product must have the given price condition', 'advanced-shipping-packages-for-woocommerce' ),
			'quantity'                => __( 'Add products with the set quantity', 'advanced-shipping-packages-for-woocommerce' ),
			'contains_product'        => __( 'Select a specific product to put in the package', 'advanced-shipping-packages-for-woocommerce' ),
			'weight'                  => __( 'The product must match the setup weight condition', 'advanced-shipping-packages-for-woocommerce' ),
			'contains_shipping_class' => __( 'The product must have the set shipping class', 'advanced-shipping-packages-for-woocommerce' ),
			'contains_category'       => __( 'The product must have the set category', 'advanced-shipping-packages-for-woocommerce' ),

			'width'                   => __( 'The product must match the given width', 'advanced-shipping-packages-for-woocommerce' ),
			'height'                  => __( 'The product must match the given height', 'advanced-shipping-packages-for-woocommerce' ),
			'length'                  => __( 'The product must match the given length', 'advanced-shipping-packages-for-woocommerce' ),
			'stock'                   => __( 'The product must match the given stock level', 'advanced-shipping-packages-for-woocommerce' ),
			'stock_status'            => __( 'The product must match the given stock status', 'advanced-shipping-packages-for-woocommerce' ),
		);
		$descriptions = apply_filters( 'advanced_shipping_packages_descriptions', $descriptions );

		return isset( $descriptions[ $this->condition ] ) ? $descriptions[ $this->condition ] : '';

	}


}
