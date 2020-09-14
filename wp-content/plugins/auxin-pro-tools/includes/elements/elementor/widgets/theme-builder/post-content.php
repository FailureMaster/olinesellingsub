<?php
namespace Auxin\Plugin\Pro\Elementor\Elements\Theme_Builder;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Control_Media;
use Elementor\Widget_Heading;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Post_Content' widget.
 *
 * Elementor widget that displays an 'Post_Content'.
 *
 * @since 1.0.0
 */
class Post_Content extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Post_Content' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_post_content';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Post_Content' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Post Content', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Post_Content' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-post-content auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Post_Content' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-core', 'auxin-theme-elements-single' );
    }

    /**
     * Register 'Post_Content' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => __( 'Style', PLUGIN_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', PLUGIN_DOMAIN ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', PLUGIN_DOMAIN ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', PLUGIN_DOMAIN ),
						'icon' => 'fa fa-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', PLUGIN_DOMAIN ),
						'icon' => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_3,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
			]
		);

		$this->end_controls_section();

	}

	/**
	 * @param $post_id
	 *
	 * @return Theme_Document
	 */
	public function get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::instance()->documents->get( $post_id );
		} catch ( \Exception $e ) {
			// Do nothing.
			unset( $e );
		}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
	}

	protected function render() {
		static $did_posts = [];

		$post = get_post();

		if ( post_password_required( $post->ID ) ) {
			echo get_the_password_form( $post->ID );

			return;
		}

		// Avoid recursion
		if ( isset( $did_posts[ $post->ID ] ) ) {
			return;
		}

		$did_posts[ $post->ID ] = true;
		// End avoid recursion

		if ( Plugin::instance()->preview->is_preview_mode( $post->ID ) ) {
			$content = Plugin::instance()->preview->builder_wrapper( '' ); // XSS ok
		} else {
			$document = $this->get_document( $post->ID );
			// // On view theme document show it's preview content.
			// if ( $document ) {
			// 	$preview_type = $document->get_settings( 'preview_type' );
			// 	$preview_id = $document->get_settings( 'preview_id' );

			// 	if ( 0 === strpos( $preview_type, 'single' ) && ! empty( $preview_id ) ) {
			// 		$post = get_post( $preview_id );

			// 		if ( ! $post ) {
			// 			return;
			// 		}
			// 	}
			// }

			$editor = Plugin::instance()->editor;

			// Set edit mode as false, so don't render settings and etc. use the $is_edit_mode to indicate if we need the CSS inline
			$is_edit_mode = $editor->is_edit_mode();
			$editor->set_edit_mode( false );

			// Print manually (and don't use `the_content()`) because it's within another `the_content` filter, and the Elementor filter has been removed to avoid recursion.
			$content = Plugin::instance()->frontend->get_builder_content( $post->ID, true );

			// Restore edit mode state
			Plugin::instance()->editor->set_edit_mode( $is_edit_mode );

			if ( empty( $content ) ) {
				Plugin::instance()->frontend->remove_content_filter();

				// Split to pages.
				setup_postdata( $post );

				/** This filter is documented in wp-includes/post-template.php */
				echo apply_filters( 'the_content', get_the_content() );

				wp_link_pages( [
					'before' => '<div class="page-links elementor-page-links"><span class="page-links-title elementor-page-links-title">' . __( 'Pages:', PLUGIN_DOMAIN ) . '</span>',
					'after' => '</div>',
					'link_before' => '<span>',
					'link_after' => '</span>',
					'pagelink' => '<span class="screen-reader-text">' . __( 'Page', PLUGIN_DOMAIN ) . ' </span>%',
					'separator' => '<span class="screen-reader-text">, </span>',
				] );

				Plugin::instance()->frontend->add_content_filter();

				return;
			} else {
				$content = apply_filters( 'the_content', $content );
			}
		} // End if().

		echo $content; // XSS ok.
	}

}
