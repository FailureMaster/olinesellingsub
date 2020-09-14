<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'Template' widget.
 *
 * Elementor widget that displays an 'Template' with lightbox.
 *
 * @since 1.0.0
 */
class Template extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Template' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_template';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Template' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Template', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Template' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-document-file auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Template' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-pro' );
    }

    public function is_reload_preview_required() {
        return false;
    }

    /**
     * Register 'Template' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        $this->start_controls_section(
            'section_template',
            [
                'label' => __( 'Template', PLUGIN_DOMAIN ),
            ]
        );

        $templates = Plugin::$instance->templates_manager->get_source( 'local' )->get_items();

        if ( empty( $templates ) ) {

            $this->add_control(
                'no_templates',
                [
                    'label' => false,
                    'type'  => Controls_Manager::RAW_HTML,
                    'raw'   => '<div id="elementor-widget-template-empty-templates">
                            <div class="elementor-widget-template-empty-templates-icon"><i class="eicon-nerd"></i></div>
                            <div class="elementor-widget-template-empty-templates-title">' . __( 'You Haven’t Saved Templates Yet.', PLUGIN_DOMAIN ) . '</div>
                            <div class="elementor-widget-template-empty-templates-footer">' . __( 'Want to learn more about Elementor library?', PLUGIN_DOMAIN ) . ' <a class="elementor-widget-template-empty-templates-footer-url" href="https://go.elementor.com/docs-library/" target="_blank">' . __( 'Click Here', PLUGIN_DOMAIN ) . '</a>
                            </div>
                            </div>',
                ]
            );

            return;
        }

        $options = [
            '0' => '— ' . __( 'Select', PLUGIN_DOMAIN ) . ' —',
        ];

        $types = [];

        foreach ( $templates as $template ) {
            $options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
            $types[ $template['template_id'] ] = $template['type'];
        }

        $this->add_control(
            'template_id',
            [
                'label' => __( 'Choose Template', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::SELECT,
                'default' => '0',
                'options' => $options,
                'types' => $types,
                'label_block'  => 'true',
            ]
        );

        $this->end_controls_section();

    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {
        $template_id = $this->get_settings( 'template_id' );
        ?>
        <div class="elementor-template">
            <?php
            echo Plugin::$instance->frontend->get_builder_content_for_display( $template_id );
            ?>
        </div>
        <?php
    }


    public function render_plain_content() {}

}
