<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Get Shipping packages group posts.
 *
 * Get a list of the posts (IDs).
 *
 * @since 1.0.0
 *
 * @param  array $args List of WP_Query arguments.
 * @return array List of published 'shipping_package' post IDs.
 */
function aspwc_get_shipping_package_posts( $args = array() ) {

	$query = new WP_Query( wp_parse_args( $args, array(
		'posts_per_page'         => 1000,
		'post_type'              => 'shipping_package',
		'post_status'            => 'publish',
		'orderby'                => 'menu_order',
		'order'                  => 'ASC',
		'no_found_rows'          => true,
		'update_post_term_cache' => false,
	) ) );

	return apply_filters( 'advanced_shipping_packages_get_post_ids', $query->posts );

}


/**
 * Get posts that match.
 *
 * Get a list of a the post IDs that are matching their conditions.
 *
 * @since 1.0.0
 *
 * @return array
 */
function aspwc_get_posts_matching_conditions() {

	$posts        = aspwc_get_shipping_package_posts();
	$matching_ids = array();

	// Get the total shipping package
	remove_filter( 'woocommerce_cart_shipping_packages', 'aspwc_split_cart_shipping_packages' );
	$shipping_packages = WC()->cart->get_shipping_packages();
	$first_package = reset( $shipping_packages );
	add_filter( 'woocommerce_cart_shipping_packages', 'aspwc_split_cart_shipping_packages' );

	// Packages can be empty in WC Subscriptions for example when up-/down-grading
	// This check prevents it from checking conditions which may fail because of packages without contents
	if ( empty( $shipping_packages ) ) {
		return array();
	}

	foreach ( $posts as $post ) {
		$condition_groups = get_post_meta( $post->ID, '_conditions', true );
		if ( wpc_match_conditions( $condition_groups, array( 'context' => 'aspwc', 'package' => $first_package ) ) == true ) {
			$matching_ids[] = $post->ID;
		}
	}

	return $matching_ids;

}


/**
 * Product matches split conditions?
 *
 * Check if the given item matches the conditions to split it from
 * the main package.
 *
 * @since 1.0.0
 *
 * @param  array $item    List of cart item data.
 * @param  int   $post_id ID of the shipping package post ID
 * @return bool           True when the products matches the conditions, false otherwise.
 */
function aspwc_product_matches_package_split_conditions( $item, $post_id ) {

	// Format as package so matching functions are recognising it.
	$package['contents'][] = $item;

	$condition_groups = get_post_meta( $post_id, '_product_conditions', true );
	return wpc_match_conditions( $condition_groups, array( 'context' => 'aspwc', 'package' => $package ) );

}


/**
 * Get the split package.
 *
 * Get the full split package array with all the details, including the
 * products that should be split.
 *
 * @since 1.0.0
 *
 * @param  array         $default_package Default package.
 * @param  int           $post_id         ID of the post to base the package splitting on.
 * @return array|boolean                  The split package with all its data when valid. False when invalid.
 */
function aspwc_get_split_package( $default_package, $post_id ) {

	$split_package                  = $default_package;
	$split_package['contents']      = array();
	$split_package['contents_cost'] = 0;
	$split_package['id']            = $post_id;

	foreach ( $default_package['contents'] as $key => $item ) {
		if ( ! $item['data']->needs_shipping() ) continue;

		if ( aspwc_product_matches_package_split_conditions( $item, $post_id ) == true ) {
			$split_package['contents'][ $key ] = $item;
		}
	}

	if ( empty( $split_package['contents'] ) ) {
		return false;
	}

	// Set package contents cost
	foreach ( $split_package['contents'] as $item ) {
		if ( $item['data']->needs_shipping() ) {
			if ( isset( $item['line_total'] ) ) {
				$split_package['contents_cost'] += $item['line_total'];
			}
		}
	}

	return $split_package;

}


/**
 * THE splitting of packages.
 *
 * This is THE function that splits the order into
 * multiple packages.
 *
 * @since 1.0.0
 *
 * @param  array $packages List of existing packages.
 * @return array           List of modified packages.
 */
function aspwc_split_cart_shipping_packages( $packages ) {

	if ( get_option( 'enable_woocommerce_advanced_shipping_packages', 'yes' ) != 'yes' ) {
		return $packages;
	}

	$origin_packages = $packages;
	$packages        = array();
	$matching_posts  = aspwc_get_posts_matching_conditions();
	$default_package = reset( $origin_packages );

	if ( ! $matching_posts ) {
		return $origin_packages; // Return original package(s) if no rules apply.
	}

	// Get the split packages
	foreach ( $matching_posts as $post_id ) {

		if ( ! $split_package = aspwc_get_split_package( $default_package, $post_id ) ) {
			continue;  // Skip invalid packages
		}

		// Set package
		$packages[ $post_id ] = $split_package;

		// Unset the split items from the default package items
		$unset_keys = array_intersect( array_keys( $default_package['contents'] ), array_keys( $split_package['contents'] ) );
		foreach ( $unset_keys as $k ) {
			if ( isset( $default_package['contents'][ $k ] ) ) {
				unset( $default_package['contents'][ $k ] );
			}
		}

	}

	// Default package calculation
	$default_package['id'] = 'default';
	$default_package['content_cost'] = 0;

	foreach ( $default_package['contents'] as $item ) {
		if ( $item['data']->needs_shipping() ) {
			if ( isset( $item['line_total'] ) ) {
				$default_package['contents_cost'] += $item['line_total'];
			}
		}
	}

	if ( ! empty( $default_package['contents'] ) ) {
		$packages[0] = $default_package;
	}

	return $packages;

}
add_filter( 'woocommerce_cart_shipping_packages', 'aspwc_split_cart_shipping_packages' );


/**
 * Name shipping packages.
 *
 * Set the shipping package name accordingly.
 *
 * @since 1.0.0
 *
 * @param  string $name    original shipping package name.
 * @param  int    $i       Shipping package index.
 * @param  array  $package Package list.
 * @return string          Modified shipping package name.
 */
function aspwc_shipping_package_name( $name, $i, $package ) {

	if ( is_numeric( $i ) && 'shipping_package' == get_post_type( $i ) ) {
		$name = get_post_meta( $i, '_name', true );
	}

	// Default package name
	if ( $i === 0 && $title = get_option( 'advanced_shipping_packages_default_package_name', '' ) ) { // Default package
		$name = $title;
	}

	return $name;

}
add_filter( 'woocommerce_shipping_package_name', 'aspwc_shipping_package_name', 10, 3 );


/**
 * Exclude shipping rates.
 *
 * Exclude any shipping rates that may have been set to be excluded.
 *
 * @since 1.0.0
 *
 * @param  array $rates   List of available shipping options.
 * @param  array $package List of shipping package data.
 * @return array          List of modified shipping options.
 */
function aspwc_exclude_shipping_rates( $rates, $package ) {

	if ( ! isset( $package['id'] ) || $package['id'] == 'default' ) {
		return $rates;
	}
	$excluded_rates = (array) get_post_meta( $package['id'], '_exclude_shipping', true );

	foreach ( $rates as $k => $rate ) {
		// For BC add a ID formatted as we're saving it; the default way of writing a rate ID (method_id:instance_id).
		// Carrier plugins tend to (rightfully) override this rate ID in a different way.
		$id = $rate->method_id . ':' . $rate->instance_id;
		if ( array_intersect( array( $rate->id, $rate->method_id, $rate->instance_id, $id ), $excluded_rates ) ) {
			unset( $rates[ $k ] );
		}
	}

	return $rates;

}
add_filter( 'woocommerce_package_rates', 'aspwc_exclude_shipping_rates', 10, 2 );


/**
 * Whitelist shipping rates.
 *
 * Filter shipping rates based on the set whitelist of rate.
 *
 * @since NEWVERSION
 *
 * @param  array $rates   List of available shipping options.
 * @param  array $package List of shipping package data.
 * @return array          List of modified shipping options.
 */
function aspwc_whitelist_shipping_rates( $rates, $package ) {
	if ( ! isset( $package['id'] ) || $package['id'] == 'default' ) {
		return $rates;
	}
	$whitelisted_rates = array_filter( (array) get_post_meta( $package['id'], '_include_shipping', true ) );

	if ( empty( $whitelisted_rates ) ) {
		return $rates;
	}

	$new_rates = array();
	foreach ( $rates as $k => $rate ) {
		// For BC add a ID formatted as we're saving it; the default way of writing a rate ID (method_id:instance_id).
		// Carrier plugins tend to (rightfully) override this rate ID in a different way.
		$id = $rate->method_id . ':' . $rate->instance_id;
		if ( array_intersect( array( $rate->id, $rate->method_id, $rate->instance_id, $id ), array_filter( $whitelisted_rates ) ) ) {
			$new_rates[ $k ] = $rate;
		}
	}

	return $new_rates;
}
add_filter( 'woocommerce_package_rates', 'aspwc_whitelist_shipping_rates', 20, 2 );


/**************************************************************
 * Backwards compatibility for WP Conditions
 *************************************************************/

/**
 * Add the filters required for backwards-compatibility for the matching functionality.
 *
 * @since 1.1.0
 */
function aspwc_add_bc_filter_condition_match( $match, $condition, $operator, $value, $args = array() ) {

	if ( ! isset( $args['context'] ) || $args['context'] != 'aspwc' ) {
		return $match;
	}

	if ( has_filter( 'advanced_shipping_packages_for_woocommerce_match_condition_' . $condition ) ) {
		$package = isset( $args['package'] ) ? $args['package'] : array();
		$match = apply_filters( 'advanced_shipping_packages_for_woocommerce_match_condition_' . $condition, $match = false, $operator, $value, $package );
	}

	return $match;

}
add_action( 'wp-conditions\condition\match', 'aspwc_add_bc_filter_condition_match', 10, 5 );


/**
 * Add condition descriptions of custom conditions.
 *
 * @since 1.1.0
 */
function aspwc_add_bc_filter_condition_descriptions( $descriptions ) {
	return apply_filters( 'advanced_shipping_packages_descriptions', $descriptions );
}
add_filter( 'wp-conditions\condition_descriptions', 'aspwc_add_bc_filter_condition_descriptions' );


/**
 * Add custom field BC.
 *
 * @since 1.1.0
 */
function aspwc_add_bc_action_custom_fields( $type, $args ) {

	if ( has_action( 'wp_condition_html_field_type_' . $type ) ) {
		do_action( 'wp_condition_html_field_type_' . $args['type'], $args );
	}

}
add_action( 'wp-conditions\html_field_hook', 'aspwc_add_bc_action_custom_fields', 10, 2 );
