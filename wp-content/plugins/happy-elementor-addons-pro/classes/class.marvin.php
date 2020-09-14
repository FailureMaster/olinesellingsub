<?php
namespace Happy_Addons_Pro;

use Elementor\Controls_Stack;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class Marvin {

	/**
	 * Ajax action
	 */
	const ACTION = 'ha_process_image_import';

	/**
	 * Initialize actions
	 */
	public static function init() {
		if ( hapro_is_elementor_version( '>=', '2.8.0' ) ) {
			add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
		} else {
			add_action( 'elementor/preview/enqueue_scripts', [ __CLASS__, 'enqueue' ] );
		}

		add_action( 'wp_ajax_' . self::ACTION, [ __CLASS__, 'process_image_import' ] );
	}

	/**
	 * Process image import request
	 *
	 * @return void
	 */
	public static function process_image_import() {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		$content = isset( $_POST['content'] ) ? wp_unslash( $_POST['content'] ) : '';

		if ( ! wp_verify_nonce( $nonce, self::ACTION ) ||
			! current_user_can( 'edit_posts' ) ||
			! hapro_has_valid_license()
		) {
			wp_send_json_error(
				__( 'You are not allowed to complete this task, thank you.', 'happy-addons-pro' ),
				403
			);
		}

		if ( empty( $content ) ) {
			wp_send_json_error( __( 'Sorry, cannot process empty content!', 'happy-addons-pro' ) );
		}

		// Enable svg support
		add_filter( 'upload_mimes', [ __CLASS__, 'add_svg_support' ] );

		// Need to be an array to process through elementor db iterator
		$content = [ json_decode( $content, true ) ];
		$content = self::replace_elements_ids( $content );
		$content = self::process_import_content( $content );

		// Disable svg support
		remove_filter( 'upload_mimes', [ __CLASS__, 'add_svg_support' ] );

		wp_send_json_success( $content );
	}

	public static function add_svg_support( $mimes ) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}

	/**
	 * Replace elements IDs.
	 *
	 * For any given Elementor content/data, replace the IDs with new randomly
	 * generated IDs.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @param array $content Any type of Elementor data.
	 *
	 * @return mixed Iterated data.
	 */
	protected static function replace_elements_ids( $content ) {
		return ha_elementor()->db->iterate_data( $content, function( $element ) {
			$element['id'] = Utils::generate_random_string();
			return $element;
		} );
	}

	/**
	 * Process content for import.
	 *
	 * Process the content and all the inner elements, and prepare all the
	 * elements data for import.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @param array  $content A set of elements.
	 *
	 * @return mixed Processed content data.
	 */
	protected static function process_import_content( $content ) {
		return ha_elementor()->db->iterate_data(
			$content,
			function( $element_data ) {
				$element = ha_elementor()->elements_manager->create_element_instance( $element_data );

				// If the widget/element isn't exist, like a plugin that creates a widget but deactivated
				if ( ! $element ) {
					return null;
				}

				return self::process_element_import_content( $element );
			}
		);
	}

	/**
	 * Process single element content for import.
	 *
	 * Process any given element and prepare the element data for import.
	 *
	 * @since 1.2.0
	 * @access protected
	 *
	 * @param Controls_Stack $element
	 *
	 * @return array Processed element data.
	 */
	protected static function process_element_import_content( Controls_Stack $element ) {
		$element_data = $element->get_data();
		$method = 'on_import';

		if ( method_exists( $element, $method ) ) {
			// TODO: Use the internal element data without parameters.
			$element_data = $element->{$method}( $element_data );
		}

		foreach ( $element->get_controls() as $control ) {
			$control_class = ha_elementor()->controls_manager->get_control( $control['type'] );

			// If the control isn't exist, like a plugin that creates the control but deactivated.
			if ( ! $control_class ) {
				return $element_data;
			}

			if ( method_exists( $control_class, $method ) ) {
				$element_data['settings'][ $control['name'] ] = $control_class->{$method}( $element->get_settings( $control['name'] ), $control );
			}
		}

		return $element_data;
	}

	/**
	 * Enqueue assets.
	 *
	 * @return void
	 */
	public static function enqueue() {
		if ( apply_filters( 'happyaddons_marvin_active', true ) && hapro_has_valid_license() ) {
			wp_enqueue_script(
				'marvin-ls',
				HAPPY_ADDONS_PRO_ASSETS . 'admin/js/marvin-ls.min.js',
				null,
				HAPPY_ADDONS_PRO_VERSION,
				true
			);

			if ( hapro_is_elementor_version( '>=', '2.8.0' ) ) {
				$src = HAPPY_ADDONS_PRO_ASSETS . 'admin/js/marvin-new.min.js';
				$dependencies = [ 'marvin-ls', 'elementor-editor' ];
			} else {
				$src = HAPPY_ADDONS_PRO_ASSETS . 'admin/js/marvin.min.js';
				$dependencies = [ 'marvin-ls' ];
			}

			wp_enqueue_script(
				'marvin',
				$src,
				$dependencies,
				HAPPY_ADDONS_PRO_VERSION,
				true
			);

			wp_localize_script(
				'marvin',
				'marvin',
				[
					'storageKey' => md5( 'LICENSE KEY' ),
					'ajaxURL' => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( self::ACTION ),
				]
			);
		}
	}
}

Marvin::init();
