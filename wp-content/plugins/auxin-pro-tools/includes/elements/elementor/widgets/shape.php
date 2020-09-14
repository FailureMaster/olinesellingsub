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
 * Elementor 'Shape' widget.
 *
 * Elementor widget that displays an 'Shape' with lightbox.
 *
 * @since 1.0.0
 */
class Simple_Shape extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Shape' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_simple_shape';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Shape' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Simple Shape', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Shape' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-clone auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Shape' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-pro' );
    }

    /**
     * Register 'Shape' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Style Tab
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'shape_style_section',
            array(
                'label'     => __( 'Shape', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'shape_type',
            array(
                'label'   => __( 'Type', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'rectangle'             => __( 'Rectangle', PLUGIN_DOMAIN ),
                    'circle'                => __( 'Circle', PLUGIN_DOMAIN ),
                    // 'triangle-up'           => __( 'Triangle Up', PLUGIN_DOMAIN ),
                    // 'triangle-down'         => __( 'Triangle Down', PLUGIN_DOMAIN ),
                    // 'triangle-right'        => __( 'Triangle Right', PLUGIN_DOMAIN ),
                    // 'triangle-left'         => __( 'Triangle Left', PLUGIN_DOMAIN ),
                    // 'triangle-top-right'    => __( 'Triangle Top Right', PLUGIN_DOMAIN ),
                    // 'triangle-top-left'     => __( 'Triangle Top Left', PLUGIN_DOMAIN ),
                    // 'triangle-bottom-right' => __( 'Triangle Bottom Right', PLUGIN_DOMAIN ),
                    // 'triangle-bottom-left'  => __( 'Triangle Bottom Left', PLUGIN_DOMAIN ),
                ),
                'default'      => 'circle',
                'prefix_class' => 'aux-shpe-type-',
                'separator'    => 'after'
            )
        );

        $this->add_responsive_control(
            '_width',
            array(
                'label'      => __('Width',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'vw', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'desktop_default' => array(
                    'size' => 500,
                    'unit' => 'px'
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-the-shape' => 'width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_height',
            array(
                'label'      => __( 'Height',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'vh', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'desktop_default' => array(
                    'size' => 500,
                    'unit' => 'px'
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-the-shape' => 'height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_aspect_ratio',
            array(
                'label'     => __( 'Aspect Ratio', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::SLIDER,
                'range'     => array(
                    'px' => array(
                        'min' => 0.1,
                        'max' => 2,
                        'step' => 0.01,
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-the-shape' => 'padding-bottom:calc( {{SIZE}} * 100% )'
                ),
                'separator'  => 'after'
            )
        );

        $this->add_responsive_control(
            '_max_width',
            array(
                'label'      => __('Max Width',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'vw', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1600,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-widget-container-inner' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            '_max_height',
            array(
                'label'      => __('Max Height',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'vh', '%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 10
                    ),
                    'em' => array(
                        'min'  => 0,
                        'step' => 1
                    )
                ),
                'selectors'   => array(
                    '{{WRAPPER}} .aux-widget-container-inner' => 'max-height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'rotation',
            array(
                'label'      => __('Rotation',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 360,
                        'step' => 10
                    )
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-widget-container-inner' => 'transform: rotate({{SIZE}}deg);',
                ),
                'separator'  => 'after'
            )
        );

        $this->add_responsive_control(
            'alignment',
            array(
                'label'       => __('Alignment', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => 'center',
                'options'     => array(
                    'left' => array(
                        'title' => __( 'Left', PLUGIN_DOMAIN ),
                        'icon'  => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', PLUGIN_DOMAIN ),
                        'icon'  => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', PLUGIN_DOMAIN ),
                        'icon'  => 'fa fa-align-right',
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-widget-container' => 'text-align: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'shape_border_radius',
            array(
                'label'      => __( 'Border radius', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-the-shape' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;',
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'shape_style_tabs' );

        $this->start_controls_tab(
            'shape_status_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'shape_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-the-shape',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'shape_border',
                'selector'  => '{{WRAPPER}} .aux-the-shape',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'shape_background',
                'selector'  => '{{WRAPPER}} .aux-the-shape',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'shape_status_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'shape_box_shadow_hover',
                'selector'  => '{{WRAPPER}} .aux-the-shape:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'shape_border_hover',
                'selector'  => '{{WRAPPER}} .aux-the-shape:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'shape_background_hover',
                'selector'  => '{{WRAPPER}} .aux-the-shape:hover',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {
    ?>
    <div class="aux-widget-container aux-simple-shape-container">
        <div class="aux-widget-container-inner">
            <div class="aux-the-shape"></div>
        </div>
    </div>
    <?php
    }


    /**
     * Render the widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     */
    protected function _content_template() {
    ?>
    <div class="aux-widget-container aux-simple-shape-container">
        <div class="aux-widget-container-inner">
            <div class="aux-the-shape"></div>
        </div>
    </div>
    <?php
    }

}
