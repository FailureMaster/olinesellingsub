<?php
namespace Auxin\Plugin\Pro\Elementor\Modules\Documents;

use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\Core\DocumentTypes\Post;
use Elementor\Controls_Manager;
use Elementor\Plugin;
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
class Archive extends Theme_Document {

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['location'] = 'archive';

		return $properties;
	}

	public function get_name() {
		return 'archive';
	}

	public static function get_title() {
		return __( 'Archive', PLUGIN_DOMAIN );
	}

	public function get_css_wrapper_selector() {
		return '.elementor-' . $this->get_main_id();
	}


	protected static function get_editor_panel_categories() {
		// Move to top as active.
		$categories = [
			'auxin-theme-elements-archive' => [
				'title' => __( 'Archive', PLUGIN_DOMAIN ),
				'active' => true,
			],
		];

		return $categories + parent::get_editor_panel_categories();
	}


	public static function get_preview_as_default() {
		return 'archive/recent_posts';
	}

	public static function get_preview_as_options() {
		$post_type_archives = [];

		$taxonomies = [];

		$post_types = auxin_get_public_post_types();

		unset( $post_types['product'] );

		foreach ( $post_types as $post_type => $label ) {
			$post_type_object = get_post_type_object( $post_type );

			if ( $post_type_object->has_archive ) {
				$post_type_archives[ 'post_type_archive/' . $post_type ] = sprintf( __( '%s Archive', PLUGIN_DOMAIN ), $post_type_object->label );
			}

			$post_type_taxonomies = get_object_taxonomies( $post_type, 'objects' );

			$post_type_taxonomies = wp_filter_object_list( $post_type_taxonomies, [
				'public' => true,
				'show_in_nav_menus' => true,
			] );

			foreach ( $post_type_taxonomies as $slug => $object ) {
				$taxonomies[ 'taxonomy/' . $slug ] = sprintf( __( '%s Archive', PLUGIN_DOMAIN ), $object->label );
			}
		}

		$options = [
			'archive/recent_posts' => __( 'Recent Posts', PLUGIN_DOMAIN ),
			'archive/date' => __( 'Date Archive', PLUGIN_DOMAIN ),
			'archive/author' => __( 'Author Archive', PLUGIN_DOMAIN ),
			'search' => __( 'Search Results', PLUGIN_DOMAIN ),
		];

		$options += $taxonomies + $post_type_archives;

		return [
			'archive' => [
				'label' => __( 'Archive', PLUGIN_DOMAIN ),
				'options' => $options,
			],
		];
	}


}