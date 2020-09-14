<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Class ASPWC_Match_Conditions
 *
 * The ASPWC Match Conditions class handles the matching rules for the conditions.
 *
 * @class 		ASPWC_Match_Conditions
 * @author		Jeroen Sormani
 * @package		Advanced Shipping Packages for WooCommerce
 * @version		1.0.0
 */
class ASPWC_Match_Conditions {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_subtotal', array( $this, 'match_condition_subtotal' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_price', array( $this, 'match_condition_price' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_quantity', array( $this, 'match_condition_quantity' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_contains_product', array( $this, 'match_condition_contains_product' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_weight', array( $this, 'match_condition_weight' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_contains_shipping_class', array( $this, 'match_condition_contains_shipping_class' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_contains_category', array( $this, 'match_condition_contains_category' ), 10, 4 );

		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_zipcode', array( $this, 'match_condition_zipcode' ), 10, 4 );

		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_width', array( $this, 'match_condition_width' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_height', array( $this, 'match_condition_height' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_length', array( $this, 'match_condition_length' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_stock', array( $this, 'match_condition_stock' ), 10, 4 );
		add_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_stock_status', array( $this, 'match_condition_stock_status' ), 10, 4 );

	}


	/**
	 * Subtotal.
	 *
	 * Match the condition value against the cart subtotal.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_subtotal( $match, $operator, $value, $package ) {

		$subtotal = 0;
		foreach ( $package['contents'] as $k => $item ) {
			$_product    = $item['data'];
			$line_price  = $_product->get_price() * $item['quantity'];
			$subtotal   += $line_price;
		}

		// Make sure its formatted correct
		$value = str_replace( ',', '.', $value );

		if ( '==' == $operator ) :
			$match = ( $subtotal == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $subtotal != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $subtotal >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $subtotal <= $value );
		endif;

		return $match;

	}


	/**
	 * Price.
	 *
	 * Match the condition value against the product price.
	 * PRODUCT CONDITION ONLY.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_price( $match, $operator, $value, $package ) {

		$price = 0;
		foreach ( $package['contents'] as $k => $item ) {
			/** @var $product WC_Product */
			$product = $item['data'];
			$price    = $product->get_price();
		}

		// Make sure its formatted correct
		$value = str_replace( ',', '.', $value );

		if ( '==' == $operator ) :
			$match = ( $price == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $price != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $price >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $price <= $value );
		endif;

		return $match;

	}


	/**
	 * Quantity.
	 *
	 * Match the condition value against the cart quantity.
	 * This also includes product quantities.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_quantity( $match, $operator, $value, $package ) {

		$quantity = 0;
		foreach ( $package['contents'] as $item_key => $item ) :
			$quantity += $item['quantity'];
		endforeach;

		if ( '==' == $operator ) :
			$match = ( $quantity == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $quantity != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $quantity >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $quantity <= $value );
		endif;

		return $match;

	}


	/**
	 * Contains product.
	 *
	 * Matches if the condition value product is in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_contains_product( $match, $operator, $value, $package ) {

		$product_ids = array();
		foreach ( $package['contents'] as $product ) :
			$product_ids[] = $product['product_id'];
			if ( isset( $product['variation_id'] ) ) {
				$product_ids[] = $product['variation_id'];
			}
		endforeach;

		if ( '==' == $operator ) :
			$match = ( in_array( $value, $product_ids ) );
		elseif ( '!=' == $operator ) :
			$match = ( ! in_array( $value, $product_ids ) );
		endif;

		return $match;

	}


	/**
	 * Weight.
	 *
	 * Match the condition value against the cart weight.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_weight( $match, $operator, $value, $package ) {

		$weight = 0;
		foreach ( $package['contents'] as $key => $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];
			$weight += (float) $product->get_weight() * $item['quantity'];

		endforeach;

		// Make sure its formatted correct
		$value = str_replace( ',', '.', $value );

		if ( '==' == $operator ) :
			$match = ( $weight == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $weight != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $weight >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $weight <= $value );
		endif;

		return $match;

	}


	/**
	 * Shipping class.
	 *
	 * Matches if the condition value shipping class is in the cart.
	 *
	 * @since 1.0.1
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_contains_shipping_class( $match, $operator, $value, $package ) {

		// True until proven false
		if ( $operator == '!=' ) :
			$match = true;
		endif;

		foreach ( $package['contents'] as $item_key => $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];

			if ( $operator == '==' ) :
				if ( $product->get_shipping_class() == $value ) :
					return true;
				endif;
			elseif ( $operator == '!=' ) :
				if ( $product->get_shipping_class() == $value ) :
					return false;
				endif;
			endif;

		endforeach;

		return $match;

	}


	/**
	 * Contains category.
	 *
	 * Matches if the condition value category is in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_contains_category( $match, $operator, $value, $package ) {

		if ( '==' == $operator ) :

			foreach ( $package['contents'] as $item ) :

				if ( has_term( $value, 'product_cat', $item['product_id'] ) ) :
					return true;
				endif;

			endforeach;

		elseif ( '!=' == $operator ) :

			$match = true;
			foreach ( $package['contents'] as $item ) :

				if ( has_term( $value, 'product_cat', $item['product_id'] ) ) :
					return false;
				endif;

			endforeach;

		endif;

		return $match;

	}


/******************************************************
 * User conditions
 *****************************************************/


	/**
	 * Zipcode.
	 *
	 * Match the condition value against the users shipping zipcode.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_zipcode( $match, $operator, $value, $package ) {

		$user_zipcode = WC()->customer->get_shipping_postcode();
		$user_zipcode = preg_replace( '/[^0-9a-zA-Z]/', '', $user_zipcode );
		$user_zipcode = strtoupper( $user_zipcode );

		// Prepare allowed values.
		$zipcodes = (array) preg_split( '/,+ */', $value );

		// Remove all non- letters and numbers
		foreach ( $zipcodes as $key => $zipcode ) :
			$zipcodes[ $key ] = preg_replace( '/[^0-9a-zA-Z\-]/', '', $zipcode );
		endforeach;

		if ( '==' == $operator ) :

			foreach ( $zipcodes as $zipcode ) :

				if ( empty( $zipcode ) ) continue;

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) :
					$match = ( $user_zipcode >= min( $parts ) && $user_zipcode <= max( $parts ) );
				else :
					$match = preg_match( '/^' . preg_quote( $zipcode, '/' ) . '/i', $user_zipcode );
				endif;

				if ( $match == true ) {
					return true;
				}

			endforeach;

		elseif ( '!=' == $operator ) :

			// True until proven false
			$match = true;

			foreach ( $zipcodes as $zipcode ) :

				if ( empty( $zipcode ) ) continue;

				$parts = explode( '-', $zipcode );
				if ( count( $parts ) > 1 ) :
					$zipcode_match = ( $user_zipcode >= min( $parts ) && $user_zipcode <= max( $parts ) );
				else :
					$zipcode_match = preg_match( '/^' . preg_quote( $zipcode, '/' ) . '/i', $user_zipcode );
				endif;

				if ( $zipcode_match == true ) :
					return $match = false;
				endif;

			endforeach;

		elseif ( '>=' == $operator ) :
			$user_zipcode = preg_replace( '/[^0-9]/', '', $user_zipcode );
			$match        = ( $user_zipcode >= $value );
		elseif ( '<=' == $operator ) :
			$user_zipcode = preg_replace( '/[^0-9]/', '', $user_zipcode );
			$match        = ( $user_zipcode <= $value );
		endif;

		return $match;

	}


/******************************************************
 * Product conditions
 *****************************************************/


	/**
	 * Width.
	 *
	 * Match the condition value against the widest product in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_width( $match, $operator, $value, $package ) {

		$width = array();
		foreach ( $package['contents'] as $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];
			$width[] = $product->get_width();

		endforeach;

		$max_width = max( (array) $width );

		if ( '==' == $operator ) :
			$match = ( $max_width == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_width != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_width >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_width <= $value );
		endif;

		return $match;

	}


	/**
	 * Height.
	 *
	 * Match the condition value against the highest product in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_height( $match, $operator, $value, $package ) {

		$height = array();
		foreach ( $package['contents'] as $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];
			$height[] = $product->get_height();


		endforeach;

		$max_height = max( $height );

		if ( '==' == $operator ) :
			$match = ( $max_height == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_height != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_height >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_height <= $value );
		endif;

		return $match;

	}


	/**
	 * Length.
	 *
	 * Match the condition value against the lengthiest product in the cart.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_length( $match, $operator, $value, $package ) {

		$length = array();
		foreach ( $package['contents'] as $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];
			$length[] = $product->get_length();

		endforeach;

		$max_length = max( $length );

		if ( '==' == $operator ) :
			$match = ( $max_length == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $max_length != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $max_length >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $max_length <= $value );
		endif;

		return $match;

	}


	/**
	 * Product stock.
	 *
	 * Match the condition value against all cart products stock.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_stock( $match, $operator, $value, $package ) {

		$stock = array();

		// Get all product stocks
		foreach ( $package['contents'] as $item ) :

			/** @var $product WC_Product */
			$product = $item['data'];
			$stock[] = $product->get_stock_quantity();

		endforeach;

		// Get lowest value
		$min_stock = min( $stock );

		if ( '==' == $operator ) :
			$match = ( $min_stock == $value );
		elseif ( '!=' == $operator ) :
			$match = ( $min_stock != $value );
		elseif ( '>=' == $operator ) :
			$match = ( $min_stock >= $value );
		elseif ( '<=' == $operator ) :
			$match = ( $min_stock <= $value );
		endif;

		return $match;

	}


	/**
	 * Stock status.
	 *
	 * Match the condition value against all cart products stock statuses.
	 *
	 * @since 1.0.0
	 *
	 * @param  bool   $match    Current match value.
	 * @param  string $operator Operator selected by the user in the condition row.
	 * @param  mixed  $value    Value given by the user in the condition row.
	 * @param  array  $package  Package of which the condition needs to match against.
	 * @return BOOL             Matching result, TRUE if results match, otherwise FALSE.
	 */
	public function match_condition_stock_status( $match, $operator, $value, $package ) {

		$value = $value === '1' ? 'instock' : $value;
		$value = $value === '0' ? 'outofstock' : $value;

		if ( '==' == $operator ) :

			$match = true;
			foreach ( $package['contents'] as $item ) :

				/** @var $product WC_Product */
				$product = $item['data'];

				if ( method_exists( $product, 'get_stock_status' ) ) { // WC 2.7 compatibility
					$stock_status = $product->get_stock_status();
				} else { // Pre 2.7
					$stock_status = $product->stock_status;
				}

				if ( $stock_status != $value ) {
					return false;
				}

			endforeach;

		elseif ( '!=' == $operator ) :

			$match = true;
			foreach ( $package['contents'] as $item ) :

				/** @var $product WC_Product */
				$product = $item['data'];

				if ( method_exists( $product, 'get_stock_status' ) ) { // WC 2.7 compatibility
					$stock_status = $product->get_stock_status();
				} else { // Pre 2.7
					$stock_status = $product->stock_status;
				}

				if ( $stock_status == $value ) {
					return false;
				}

			endforeach;

		endif;

		return $match;

	}


}
