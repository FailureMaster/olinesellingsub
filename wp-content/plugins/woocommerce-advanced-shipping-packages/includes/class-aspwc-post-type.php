<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class ASPWC_Post_Type.
 *
 * Initialize the ASPWC custom post type.
 *
 * @class       ASPWC_Post_Type
 * @author     	Jeroen Sormani
 * @package		WooCommerce Shipping Packages
 * @version		1.0.0
 */
class ASPWC_Post_Type {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Add/save meta boxes
		add_action( 'add_meta_boxes', array( $this, 'post_type_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ) );

		// Edit user messages
		add_filter( 'post_updated_messages', array( $this, 'custom_post_type_messages' ) );

		// Redirect after delete
		add_action( 'load-edit.php', array( $this, 'redirect_after_trash' ) );

	}


	/**
	 * Post type.
	 *
	 * Register and setup the custom post type for this plugin.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {

		$labels = array(
			'name'               => __( 'Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'singular_name'      => __( 'Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'add_new'            => __( 'Add New', 'advanced-shipping-packages-for-woocommerce' ),
			'add_new_item'       => __( 'Add New Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'edit_item'          => __( 'Edit Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'new_item'           => __( 'New Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'view_item'          => __( 'View Shipping Package', 'advanced-shipping-packages-for-woocommerce' ),
			'search_items'       => __( 'Search Shipping Packages', 'advanced-shipping-packages-for-woocommerce' ),
			'not_found'          => __( 'No Shipping Packages', 'advanced-shipping-packages-for-woocommerce' ),
			'not_found_in_trash' => __( 'No Shipping Packages found in Trash', 'advanced-shipping-packages-for-woocommerce' ),
		);

		register_post_type( 'shipping_package', array(
			'label'              => 'shipping_package',
			'show_ui'            => true,
			'show_in_menu'       => false,
			'public'             => false,
			'publicly_queryable' => false,
			'capability_type'    => 'post',
			'map_meta_cap'       => true,
			'rewrite'            => false,
			'_builtin'           => false,
			'query_var'          => true,
			'supports'           => array( 'title' ),
			'labels'             => $labels,
		) );

	}


	/**
	 * Messages.
	 *
	 * Modify the notice messages text for the custom post type.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $messages Existing list of messages.
	 * @return array           Modified list of messages.
	 */
	function custom_post_type_messages( $messages ) {

		$post             = get_post();
		$post_type        = get_post_type( $post );
		$post_type_object = get_post_type_object( $post_type );

		$messages['shipping_package'] = array(
			0  => '',
			1  => __( 'Shipping Package updated.', 'advanced-shipping-packages-for-woocommerce' ),
			2  => __( 'Custom field updated.', 'advanced-shipping-packages-for-woocommerce' ),
			3  => __( 'Custom field deleted.', 'advanced-shipping-packages-for-woocommerce' ),
			4  => __( 'Shipping Package updated.', 'advanced-shipping-packages-for-woocommerce' ),
			5  => isset( $_GET['revision'] ) ?
				sprintf( __( 'Shipping Package restored to revision from %s', 'advanced-shipping-packages-for-woocommerce' ), wp_post_revision_title( (int) $_GET['revision'], false ) )
				: false,
			6  => __( 'Shipping Package published.', 'advanced-shipping-packages-for-woocommerce' ),
			7  => __( 'Shipping Package saved.', 'advanced-shipping-packages-for-woocommerce' ),
			8  => __( 'Shipping Package submitted.', 'advanced-shipping-packages-for-woocommerce' ),
			9  => sprintf(
				__( 'Shipping Package scheduled for: <strong>%1$s</strong>.', 'advanced-shipping-packages-for-woocommerce' ),
				date_i18n( __( 'M j, Y @ G:i', 'advanced-shipping-packages-for-woocommerce' ), strtotime( $post->post_date ) )
			),
			10 => __( 'Shipping Package draft updated.', 'advanced-shipping-packages-for-woocommerce' ),
		);

		if ( 'shipping_package' == $post_type ) :
			$overview_link = admin_url( 'admin.php?page=wc-settings&tab=shipping&section=advanced_shipping_packages' );

			$overview                    = sprintf( ' <a href="%s">%s</a>', esc_url( $overview_link ), __( 'Return to overview.', 'advanced-shipping-packages-for-woocommerce' ) );
			$messages[ $post_type ][1]  .= $overview;
			$messages[ $post_type ][6]  .= $overview;
			$messages[ $post_type ][9]  .= $overview;
			$messages[ $post_type ][8]  .= $overview;
			$messages[ $post_type ][10] .= $overview;

		endif;

		return $messages;

	}


	/**
	 * Add meta boxes.
	 *
	 * Add two meta boxes to ASPWC with conditions and settings.
	 *
	 * @since 1.0.0
	 */
	public function post_type_meta_boxes() {

		// Conditions
		add_meta_box( 'advanced_shipping_packages_conditions', __( 'Shipping Package conditions', 'advanced-shipping-packages-for-woocommerce' ), array( $this, 'output_conditions' ), 'shipping_package', 'normal' );

		// Shipping package
		add_meta_box( 'advanced_shipping_packages_settings', __( 'Shipping Package settings & content', 'advanced-shipping-packages-for-woocommerce' ), array( $this, 'output_shipping_package_settings' ), 'shipping_package', 'normal' );

	}


	/**
	 * Render conditions meta box.
	 *
	 * Render and display the condition meta box contents.
	 *
	 * @since 1.0.0
	 */
	public function output_conditions() {

		/**
		 * Load meta box conditions view.
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/meta-box-conditions.php';

	}


	/**
	 * Render meta box.
	 *
	 * Render and display the settings meta box conditions.
	 *
	 * @since 1.0.0
	 */
	public function output_shipping_package_settings() {

		/**
		 * Load meta box settings view
		 */
		require_once plugin_dir_path( __FILE__ ) . 'admin/views/meta-box-shipping-package-settings.php';

	}


	/**
	 * Save conditions meta box.
	 *
	 * Validate + sanitize and then save post meta from meta boxes.
	 *
	 * @since 1.0.0
	 *
	 * @param  int $post_id ID of the post that is saving.
	 * @return int
	 */
	public function save_meta_boxes( $post_id ) {

		if ( ! isset( $_POST['aspwc_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['aspwc_meta_box_nonce'], 'aspwc_meta_box' ) ) :
			return $post_id;
		endif;

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) :
			return $post_id;
		endif;

		if ( ! current_user_can( 'manage_woocommerce' ) ) :
			return $post_id;
		endif;

		// Save sanitized conditions
		update_post_meta( $post_id, '_conditions', wpc_sanitize_conditions( $_POST['conditions'] ) );

		// Sanitize product conditions
		update_post_meta( $post_id, '_product_conditions', wpc_sanitize_conditions( $_POST['_product_conditions'] ) );

		// Save name
		update_post_meta( $post_id, '_name', wp_kses_post( $_POST['_name'] ) );

		// Save excluded rates
		$exclude_shipping = isset( $_POST['_exclude_shipping'] ) ? wc_clean( $_POST['_exclude_shipping'] ) : array();
		update_post_meta( $post_id, '_exclude_shipping', array_filter( $exclude_shipping ) );

		$include_shipping = isset( $_POST['_include_shipping'] ) ? wc_clean( $_POST['_include_shipping'] ) : array();
		update_post_meta( $post_id, '_include_shipping', array_filter( $include_shipping ) );

	}


	/**
	 * Redirect trash.
	 *
	 * Redirect user after trashing a ASPWC post.
	 *
	 * @since 1.0.0
	 */
	public function redirect_after_trash() {

		$screen = get_current_screen();
		if ( 'edit-shipping_package' == $screen->id ) :

			if ( isset( $_GET['trashed'] ) &&  intval( $_GET['trashed'] ) > 0 ) :

				wp_redirect( admin_url( '/admin.php?page=wc-settings&tab=shipping&section=advanced_shipping_packages' ) );
				exit();

			endif;

		endif;

	}


}

/**
 * Load condition object
 */
require_once plugin_dir_path( __FILE__ ) . 'admin/class-aspwc-condition.php';
require_once plugin_dir_path( __FILE__ ) . 'admin/class-aspwc-product-condition.php';
