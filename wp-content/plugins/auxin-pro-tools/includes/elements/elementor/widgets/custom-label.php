<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'CustomLabel' widget.
 *
 * Elementor widget that displays an 'CustomLabel' with lightbox.
 *
 * @since 1.0.0
 */
class CustomLabel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'CustomLabel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_flex_label';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'CustomLabel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Custom Label', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'CustomLabel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-text-area auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'CustomLabel' widget icon.
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
     * Register 'CustomLabel' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*--------------------------------------------------------------------*/
        /*  Content TAB
        /*--------------------------------------------------------------------*/

        $this->start_controls_section(
            'label_section',
            array(
                'label'      => __('Label', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'label',
            array(
                'label'       => __( 'Label', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXTAREA,
                'dynamic'     => [
                    'active'  => true
                ],
                'default'     => __( 'Add your label text here ..', PLUGIN_DOMAIN ),
                'label_block' => true
            )
        );

        $this->add_control(
            'link',
            array(
                'label'         => __('Link',PLUGIN_DOMAIN ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'http://your-link.com',
                'show_external' => true,
                'label_block'   => true,
                'dynamic'       => [
                    'active'    => true
                ]
            )
        );

        $this->add_control(
            'label_tag',
            array(
                'label'   => __( 'HTML Tag', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'p'          => 'P',
                    'span'       => 'Span',
                    'blockquote' => 'Blockquote',
                    'h1'         => 'H1',
                    'h2'         => 'H2',
                    'h3'         => 'H3',
                    'h4'         => 'H4',
                    'h5'         => 'H5',
                    'h6'         => 'H6'
                ),
                'default'   => 'p'
            )
        );

        $this->add_responsive_control(
            'alignment',
            array(
                'label'       => __('Alignment', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::CHOOSE,
                'default'     => '',
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
                    '{{WRAPPER}} .aux-widget-flex-label' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .aux-widget-inner' => 'text-align: {{VALUE}}; display:inline-block;',
                )
            )
        );

        $this->end_controls_section();

        /*--------------------------------------------------------------------*/

        $this->start_controls_section(
            'label_items_section',
            array(
                'label'      => __('Highlighted Text Items', PLUGIN_DOMAIN ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'child_text',
            array(
                'label'       => __( 'Text', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Text Item',
                'label_block' => true
            )
        );

        $repeater->add_control(
            'child_display_advanced',
            array(
                'label'        => __( 'Customize this item', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'after'
            )
        );

        $repeater->add_responsive_control(
            'child_text_color',
            array(
                'label'     => __( 'Text Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
                ),
                'condition'    => array(
                    'child_display_advanced' => 'yes'
                ),
                'separator'    => 'none'
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'child_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
                'condition'=> array(
                    'child_display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_responsive_control(
            'child_margin',
            array(
                'label'      => __( 'Text Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'child_display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_control(
            'child_tag',
            array(
                'label'   => __( 'HTML Tag', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'span'    => 'span',
                    'em'      => 'em',
                    'strong'  => 'strong',
                    'p'       => 'p'
                ),
                'default'   => 'span',
                'condition' => array(
                    'child_display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_responsive_control(
            'child_position_type',
            array(
                'label'       => __( 'Position Type', PLUGIN_DOMAIN ),
                'label_block' => true,
                'type'        => Controls_Manager::SELECT,
                'options'     => array(
                    ''         => __( 'Default', PLUGIN_DOMAIN  ),
                    'static'   => __( 'Static', PLUGIN_DOMAIN   ),
                    'relative' => __( 'Relative', PLUGIN_DOMAIN ),
                    'absolute' => __( 'Absolute', PLUGIN_DOMAIN )
                ),
                'default'      => '',
                'condition' => array(
                    'child_display_advanced' => 'yes'
                ),
                'selectors'    => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'position:{{VALUE}};'
                ),
                'separator' => 'before'
            )
        );

        $repeater->add_responsive_control(
            'child_position_top',
            array(
                'label'      => __('Top',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range' => array(
                    'px' => array(
                        'min'  => -2000,
                        'max'  => 2000,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -150,
                        'max'  => 150,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'child_display_advanced' => 'yes',
                    'child_position_type'    => array('relative', 'absolute')
                )
            )
        );

        $repeater->add_responsive_control(
            'child_position_right',
            array(
                'label'      => __('Right',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range' => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -15,
                        'max'  => 15,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'right:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'child_display_advanced' => 'yes',
                    'child_position_type'    => array('relative', 'absolute')
                )
            )
        );

        $repeater->add_responsive_control(
            'child_position_bottom',
            array(
                'label'      => __('Bottom',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range' => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -15,
                        'max'  => 15,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'bottom:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'child_display_advanced' => 'yes',
                    'child_position_type'    => array('relative', 'absolute')
                )
            )
        );

        $repeater->add_responsive_control(
            'child_position_left',
            array(
                'label'      => __('Left',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range' => array(
                    'px' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    '%' => array(
                        'min'  => -100,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => -15,
                        'max'  => 15,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left:{{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'child_display_advanced' => 'yes',
                    'child_position_type'    => array('relative', 'absolute')
                )
            )
        );

        $this->add_control(
            'child_list',
            array(
                'label'       => __( 'Text Items', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::REPEATER,
                'default'     => array(),
                'fields'      => array_values( $repeater->get_controls() ),
                'title_field' => '{{{ child_text }}}'
            )
        );

        $this->end_controls_section();

        /*--------------------------------------------------------------------*/
        /*  Style TAB
        /*--------------------------------------------------------------------*/

        /*   Label Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'label_style_section',
            array(
                'label'     => __( 'Label', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'label_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-flex-label-wrap' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'label_hover_color',
            array(
                'label'     => __( 'Hover Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-flex-label-wrap:hover' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'link!' => ''
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'label_typography',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-flex-label-wrap'
            )
        );

        $this->add_responsive_control(
            'label_margin',
            array(
                'label'              => __( 'Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-flex-label-wrap' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name'     => 'label_text_shadow',
                'label'    => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-flex-label-wrap'
            )
        );

        $this->add_responsive_control(
            'label_width',
            array(
                'label'      => __('Max Width',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-flex-label-wrap' => 'max-width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Wrapper Section
        /*-------------------------------------*/
        $this->start_controls_section(
            'wrapper_style_section',
            array(
                'label'     => __( 'Wrapper', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'width',
            array(
                'label'      => __('Width',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em','%', 'vw'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner' => 'width:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'height',
            array(
                'label'      => __('Height',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%', 'vh'),
                'range'      => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )

                ),
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner' => 'height:{{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_margin',
            array(
                'label'              => __( 'Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em'),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_responsive_control(
            'wrapper_padding',
            array(
                'label'              => __( 'Padding', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em', '%'),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner' => 'padding:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'     => 'wrapper_background',
                'label'    => __( 'Background', PLUGIN_DOMAIN ),
                'types'    => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'wrapper_boxshadow_normal',
                'label'     => __( 'Box Shadow', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .aux-widget-flex-label .aux-widget-inner',
                'separator' => 'none'
            )
        );

        $this->end_controls_section();
    }

    private function get_children_markup(){

        $settings = $this->get_settings_for_display();

        $children = $settings['child_list'];
        $output   = '';

        // widget custom output -----------------------
        if ( is_array( $children ) || is_object( $children ) ) {
            foreach ( $children as $index => $list_item ) {

                // Class for each repeater item
                $item_classes = array( 'aux-label-piece' );

                if( $item_unique_id = ! empty( $list_item['_id'] ) ? $list_item['_id'] : '' ){
                    $item_classes[] = 'aux-label-piece-'. $item_unique_id;
                    $item_classes[] = 'elementor-repeater-item-'. $item_unique_id;
                }

                $item_text_tag = $list_item['child_tag'];

                if( ! empty( $list_item['child_text'] ) ){
                    $output .= "<$item_text_tag ". auxin_make_html_attributes( array( 'class' => $item_classes ) ) .'>' . $list_item['child_text'] . "</$item_text_tag>";
                }

            }
        }

        return $output;
    }

    /**
     * Render 'CustomLabel' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $alignment_class = ! empty( $settings['alignment'] ) ? 'aux-text-align-' . $settings['alignment'] : '';

        echo '<section class="widget-container aux-widget-container aux-widget-flex-label">
            <div class="aux-widget-inner '. esc_attr( $alignment_class ) .'">';

                // Get the label content
                $content = $settings['label'] . $this->get_children_markup();

                // Print main label wrapper
                if( ! empty( $settings['link']['url'] ) ){

                    // Make Link attributes
                    $this->add_render_attribute( 'link-primary', 'href', $settings['link']['url'] );
                    $this->add_render_attribute( 'link-primary', 'class', 'aux-flex-label-link' );
                    if ( $settings['link']['is_external'] ) {
                        $this->add_render_attribute( 'link-primary', 'target', '_blank' );
                    }
                    if ( $settings['link']['nofollow'] ) {
                        $this->add_render_attribute( 'link-primary', 'rel', 'nofollow' );
                    }

                    printf( '<a %1$s><%2$s class="aux-flex-label-wrap">%3$</%2$s></a>',
                        $this->get_render_attribute_string( 'link-primary' ),
                        esc_attr( $settings['label_tag'] ),
                        $content
                    );

                } else {
                    printf( '<%1$s class="aux-flex-label-wrap">%2$s</%1$s>',
                        esc_attr( $settings['label_tag'] ),
                        $content
                    );
                }

        echo '</div>
        </section>';
    }

}
