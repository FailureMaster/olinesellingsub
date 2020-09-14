<?php
namespace Auxin\Plugin\Pro\Elementor\Modules\Documents;

use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Core\DocumentTypes\Post;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Module;
use Auxin\Plugin\CoreElements\Elementor\Modules\ThemeBuilder\Theme_Document;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor section library document.
 *
 * Elementor section library document handler class is responsible for
 * handling a document of a section type.
 *
 * @since 2.0.0
 */
class Single extends Theme_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'single';

		return $properties;
	}

	public function get_name() {
		return 'single';
	}

	public static function get_title() {
		return __( 'Single', PLUGIN_DOMAIN );
	}

	public function get_css_wrapper_selector() {
		return '.elementor-' . $this->get_main_id();
	}


	protected static function get_editor_panel_categories() {
		// Move to top as active.
		$categories = [
			'auxin-theme-elements-single' => [
				'title' => __( 'Single', PLUGIN_DOMAIN ),
				'active' => true,
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}

	public function before_get_content() {
		parent::before_get_content();

		// For `loop_start` hook.
		if ( have_posts() ) {
			the_post();
		}
	}

	public function after_get_content() {
		wp_reset_postdata();

		parent::after_get_content();
	}


	public function get_container_attributes() {
		$attributes = parent::get_container_attributes();

		if ( is_singular() /* Not 404 */ ) {
			$post_classes = get_post_class( '', get_the_ID() );
			$attributes['class'] .= ' ' . implode( ' ', $post_classes );
		}

		return $attributes;
	}

	public function print_content() {
		$requested_post_id = get_the_ID();
		if ( $requested_post_id !== $this->post->ID ) {
			$requested_document = Module::instance()->get_document( $requested_post_id );

			/**
			 * if current requested document is theme-document & it's not a content type ( like header/footer/sidebar )
			 * show a placeholder instead of content.
			 */
			// if ( $requested_document && ! $requested_document instanceof Section ) {
			// 	echo '<div class="elementor-theme-builder-content-area">' . __( 'Content Area', 'elementor-pro' ) . '</div>';

			// 	return;
			// }
		}

		parent::print_content();
	}

	// public function before_get_content() {
	// 	$post_type = 'post';
	// 	$latest_posts = get_posts( [
	// 		'posts_per_page' => 1,
	// 		'post_type' => $post_type,
	// 	] );
	// 	Plugin::$instance->db->switch_to_query( array(
	// 		'p' => $latest_posts[0]->ID,
	// 		'post_type' => $post_type
	// 	), true );

	// 	// For `loop_start` hook.
	// 	if ( have_posts() ) {
	// 		the_post();
	// 	}
	// }

	// public function after_get_content() {
	// 	Plugin::$instance->db->restore_current_query();
	// 	wp_reset_postdata();
	// }

	protected function _register_controls() {
		parent::_register_controls();
		Post::register_style_controls( $this );
	}


}