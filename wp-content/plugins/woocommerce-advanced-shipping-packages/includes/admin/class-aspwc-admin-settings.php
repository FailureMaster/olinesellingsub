<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class ASPWC_Admin_Settings.
 *
 * Admin settings class handles everything related to settings.
 *
 * @class		ASPWC_Admin_Settings
 * @version		1.0.0
 * @author		Jeroen Sormani
 */
class ASPWC_Admin_Settings {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Keep WC menu open while in WAS edit screen
		add_action( 'admin_head', array( $this, 'menu_highlight' ) );

		// Add shipping section
		add_action( 'woocommerce_get_sections_shipping', array( $this, 'add_section' ) );

		// Settings <= 3.5
		add_action( 'woocommerce_settings_shipping', array( $this, 'section_settings_pre_3_6' ) );
		add_action( 'woocommerce_settings_save_shipping', array( $this, 'update_options_pre_3_6' ) );

		// Add settings >= 3.6
		add_action( 'woocommerce_get_settings_shipping', array( $this, 'section_settings' ), 10, 2 );

		// Table field type
		add_action( 'woocommerce_admin_field_advanced_shipping_packages_settings_table', array( $this, 'generate_table_field' ) );
	}


	/**
	 * Settings page array.
	 *
	 * Get settings page fields array.
	 *
	 * @since 1.0.0
	 */
	public function get_settings() {

		$settings = apply_filters( 'advanced_shipping_packages_for_woocommerce_settings', array(

			array(
				'title' => __( 'Advanced Shipping Packages', 'advanced-shipping-packages-for-woocommerce' ),
				'type'  => 'title',
			),

			array(
				'title'    => __( 'Enable/Disable', 'advanced-shipping-packages-for-woocommerce' ),
				'desc'     => __( 'Enable Advanced Shipping Packages', 'advanced-shipping-packages-for-woocommerce' ),
				'id'       => 'enable_woocommerce_advanced_shipping_packages',
				'default'  => 'yes',
				'type'     => 'checkbox',
				'autoload' => false
			),

			array(
				'title'    => __( 'Default package name', 'advanced-shipping-packages-for-woocommerce' ),
				'placeholder' => __( 'Leave empty to use default package name', 'advanced-shipping-packages-for-woocommerce' ),
				'id'       => 'advanced_shipping_packages_default_package_name',
				'default'  => '',
				'class'    => 'regular-input',
				'type'     => 'text',
				'autoload' => false
			),

			array(
				'title' => __( 'Advanced Shipping Packages', 'advanced-shipping-packages-for-woocommerce' ),
				'type'  => 'advanced_shipping_packages_settings_table',
			),

			array(
				'type' => 'sectionend',
			),

		) );

		return $settings;
	}


	/**
	 * Keep menu open.
	 *
	 * Highlights the correct top level admin menu item for post type add screens.
	 *
	 * @since 1.0.0
	 */
	public function menu_highlight() {
		global $parent_file, $submenu_file, $post_type;

		if ( 'shipping_package' == $post_type ) {
			$parent_file  = 'woocommerce';
			$submenu_file = 'wc-settings';
		}
	}


	/**
	 * Add shipping section.
	 *
	 * Add a new 'extra shipping options' section under the shipping tab.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $sections List of existing shipping sections.
	 * @return array           List of modified shipping sections.
	 */
	public function add_section( $sections ) {
		$sections['advanced_shipping_packages'] = __( 'Packages', 'advanced-shipping-packages-for-woocommerce' );

		return $sections;
	}


	/**
	 * Shipping validation settings.
	 *
	 * Add the settings to the shipping validation shipping section.
	 * Only here for WC 3.5 support. @todo remove when WC 4.0 releases
	 *
	 * @since 1.0.0
	 */
	public function section_settings_pre_3_6() {
		global $current_section;

		if ( 'advanced_shipping_packages' === $current_section && version_compare( WC()->version, '3.6', '<' ) ) {
			WC_Admin_Settings::output_fields( $this->get_settings() );
		}
	}


	/**
	 * Save settings.
	 *
	 * Save settings based on WooCommerce save_fields() method.
	 * @todo remove when WC 4.0 releases
	 *
	 * @since 1.0.0
	 */
	public function update_options_pre_3_6() {
		global $current_section;

		if ( $current_section == 'advanced_shipping_packages' && version_compare( WC()->version, '3.6', '<' ) ) {
			WC_Admin_Settings::save_fields( $this->get_settings() );
		}
	}


	/**
	 * ASPWC settings.
	 *
	 * Add the settings to the Extra Shipping Options shipping section.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $settings        Current settings.
	 * @param string $current_section Slug of the current section
	 * @return array                   Modified settings.
	 */
	public function section_settings( $settings, $current_section ) {
		if ( 'advanced_shipping_packages' === $current_section ) {
			$settings = $this->get_settings();
		}

		return $settings;
	}


	/**
	 * Table field type.
	 *
	 * Load and render table as a field type.
	 *
	 * @return string
	 */
	public function generate_table_field() {
		require_once plugin_dir_path( __FILE__ ) . 'views/html-advanced-shipping-packages-table.php';
	}


}
