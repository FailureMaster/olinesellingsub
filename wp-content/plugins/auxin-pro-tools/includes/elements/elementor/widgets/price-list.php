<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Custom List' widget.
 *
 * Elementor widget that displays an 'Custom List' with lightbox.
 *
 * @since 1.0.0
 */
class PriceList extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Custom List' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_price_list';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Custom List' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Price List', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Custom List' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-price-list auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Custom List' widget icon.
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
     * Register 'Custom List' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  Content TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'list_items_section',
            array(
                'label'      => __('Content', PLUGIN_DOMAIN ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'text_primary',
            array(
                'label'       => __( 'Text', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'List Item',
                'label_block' => true
            )
        );

        $repeater->add_control(
            'aux_price_list_icon',
            array(
                'label'       => __( 'Icon', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => true
            )
        );

        $repeater->add_control(
            'text_secondary',
            array(
                'label'       => __( 'Price', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '10 $',
                'label_block' => true
            )
        );

        $repeater->add_control(
            'description',
            array(
                'label'       => __( 'Description', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXTAREA,
                'default'     => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit',
                'label_block' => true
            )
        );

        $repeater->add_control(
            'link',
            array(
                'label'         => __('Link',PLUGIN_DOMAIN ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => '',
                'show_external' => true,
                'label_block'   => true,
                'dynamic'       => [
					'active' => true
				]
            )
        );

        $repeater->add_control(
            'display_advanced',
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

        $repeater->add_control(
            'custom_class_name',
            array(
                'label'       => __( 'Custom Class Name', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'label_block' => true,
                'condition'   => array(
                    'display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_responsive_control(
            'icon_color',
            array(
                'label'       => __( 'Icon Color', PLUGIN_DOMAIN ),
                'label_block' => 'true',
                'type'        => Controls_Manager::COLOR,
                'selectors'   => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-icon' => 'color: {{VALUE}};',
                ),
                'condition'    => array(
                    'display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_responsive_control(
            'icon_item_margin',
            array(
                'label'      => __( 'Icon Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'display_advanced' => 'yes'
                ),
                'separator'=> 'after'
            )
        );

        $repeater->add_responsive_control(
            'text_primary_color',
            array(
                'label'     => __( 'Text Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text' => 'color: {{VALUE}};',
                ),
                'condition'    => array(
                    'display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_primary_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text',
                'condition'=> array(
                    'display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_responsive_control(
            'text_primary_margin',
            array(
                'label'      => __( 'Text Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'display_advanced' => 'yes'
                )
            )
        );

        $repeater->add_control(
            'text_tag',
            array(
                'label'   => __( 'Text HTML Tag', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'span'    => 'span',
                    'p'       => 'p',
                    'h1'      => 'H1',
                    'h2'      => 'H2',
                    'h3'      => 'H3',
                    'h4'      => 'H4',
                    'h5'      => 'H5',
                    'h6'      => 'H6'
                ),
                'default'   => 'span',
                'condition' => array(
                    'display_advanced' => 'yes'
                ),
                'separator' => 'after'
            )
        );

        $repeater->add_responsive_control(
            'text_secondary_color',
            array(
                'label'     => __( 'Price Text Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text2' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_advanced' => 'yes',
                    'text_secondary!'  => ''
                )
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'text_secondary_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text2',
                'condition'=> array(
                    'display_advanced' => 'yes',
                    'text_secondary!'  => ''
                )
            )
        );

        $repeater->add_responsive_control(
            'text_secondary_margin',
            array(
                'label'      => __( 'Price Text Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-text2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'display_advanced' => 'yes',
                    'text_secondary!'  => ''
                ),
                'separator'=> 'after'
            )
        );

        $repeater->add_responsive_control(
            'description_color',
            array(
                'label'     => __( 'Description Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-description' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'display_advanced' => 'yes',
                    'description!'  => ''
                )
            )
        );

        $repeater->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'description_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-description',
                'condition'=> array(
                    'display_advanced' => 'yes',
                    'description!'  => ''
                )
            )
        );

        $repeater->add_responsive_control(
            'description_margin',
            array(
                'label'      => __( 'Description Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em' ),
                'selectors'  => array(
                    '{{WRAPPER}} {{CURRENT_ITEM}} .aux-icon-list-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
                'condition'  => array(
                    'display_advanced' => 'yes',
                    'description!'  => ''
                ),
                'separator'=> 'after'
            )
        );

        $this->add_control(
            'list',
            array(
                'label'   => __( 'List Items', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::REPEATER,
                'default' => array(
                    array(
                        'text_primary'   => 'Price Item #1',
                        'text_secondary' => '10 $'
                    ),
                    array(
                        'text_primary'   => 'Price Item #2',
                        'text_secondary' => '7.5 $'
                    ),
                    array(
                        'text_primary'   => 'Price Item #3',
                        'text_secondary' => '11 $'
                    )
                ),
                'fields'      => array_values( $repeater->get_controls() ),
                'title_field' => '{{{ text_primary }}}'
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   List Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'list_layout_section',
            array(
                'label'     => __( 'Layout', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_control(
            'direction',
            array(
                'label'   => __( 'Direction', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'default'     => __( 'Default'   , PLUGIN_DOMAIN ),
                    'vertical'    => __( 'Vertical'  , PLUGIN_DOMAIN ),
                    'horizontal'  => __( 'Horizontal', PLUGIN_DOMAIN )
                ),
                'default'   => 'default'
            )
        );

        $this->add_responsive_control(
            'list_height',
            array(
                'label'      => __( 'Height', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'max' => 1000
                    ),
                    'em' => array(
                        'max' => 30
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-items' => 'max-height: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'direction' => 'vertical'
                )
            )
        );

        $this->add_responsive_control(
            'list_width',
            array(
                'label'      => __( 'Width', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em', '%' ),
                'range'      => array(
                    'px' => array(
                        'max' => 1000
                    ),
                    'em' => array(
                        'max' => 30
                    ),
                    '%' => array(
                        'max' => 100
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item' => 'min-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'direction' => 'horizontal'
                )
            )
        );

        $this->add_responsive_control(
            'list_column_gutter',
            array(
                'label'      => __( 'Space Between Columns', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'max' => 100
                    ),
                    'em' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .aux-direction-horizontal .aux-icon-list-item:after' => 'right: calc(-{{SIZE}}{{UNIT}}/2);',
                ),
                'condition' => array(
                    'direction!' => 'default'
                )
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'    => __('Align',PLUGIN_DOMAIN),
                'type'     => Controls_Manager::CHOOSE,
                'options'  => array(
                    'left' => array(
                        'title' => __( 'Left', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-right',
                    )
                ),
                'default'    => 'left',
                'toggle'     => true,
                'selectors'  => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   List Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'list_style_section',
            array(
                'label'     => __( 'List', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'list_items_space',
            array(
                'label'      => __( 'Space Between Rows', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'max' => 25
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item:not(:last-child)'  => 'padding-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .aux-icon-list-item:not(:first-child)' => 'margin-top: {{SIZE}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'connector',
            array(
                'label'        => __( 'Display Connector', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'connector_style',
            array(
                'label'   => __( 'Style', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'solid'  => __( 'Solid', PLUGIN_DOMAIN ),
                    'double' => __( 'Double', PLUGIN_DOMAIN ),
                    'dotted' => __( 'Dotted', PLUGIN_DOMAIN ),
                    'dashed' => __( 'Dashed', PLUGIN_DOMAIN ),
                ),
                'default'   => 'dashed',
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item .aux-list-connector' => 'border-bottom-style: {{VALUE}};',
                ),
                'condition' => array(
                    'connector' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'connector_weight',
            array(
                'label' => __( 'Weight', PLUGIN_DOMAIN ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item .aux-list-connector' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'connector' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'connector_margin_left',
            array(
                'label' => __( 'Left Space', PLUGIN_DOMAIN ),
                'type'  => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 20
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item .aux-list-connector' => 'margin-left: {{SIZE}}{{UNIT}};',
                ),
                'condition' => array(
                    'connector' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'connector_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item .aux-list-connector' => 'border-bottom-color: {{VALUE}};'
                ),
                'condition' => array(
                    'connector' => 'yes'
                )
            )
        );

        $this->add_control(
            'divider',
            array(
                'label'        => __( 'Display Divider', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            )
        );

        $this->add_control(
            'divider_style',
            array(
                'label'   => __( 'Style', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'solid'  => __( 'Solid', PLUGIN_DOMAIN ),
                    'double' => __( 'Double', PLUGIN_DOMAIN ),
                    'dotted' => __( 'Dotted', PLUGIN_DOMAIN ),
                    'dashed' => __( 'Dashed', PLUGIN_DOMAIN ),
                ),
                'default'   => 'solid',
                'selectors' => array(
                    '{{WRAPPER}} .aux-direction-vertical .aux-icon-list-item:not(:last-child):after' => 'border-bottom-style: {{VALUE}};',
                    '{{WRAPPER}} .aux-direction-horizontal .aux-icon-list-item:after' => 'border-right-style: {{VALUE}};'
                ),
                'condition' => array(
                    'divider' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'divider_weight',
            array(
                'label'      => __( 'Weight', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px' ),
                'range'      => array(
                    'px' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-direction-vertical .aux-icon-list-item:not(:last-child):after'   => 'border-bottom-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .aux-direction-horizontal .aux-icon-list-item:after' => 'border-right-width: {{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'divider' => 'yes'
                )
            )
        );

        $this->add_responsive_control(
            'divider_width',
            array(
                'label'      => __( 'Width', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 1200
                    ),
                    '%' => array(
                        'min' => 1,
                        'max' => 100
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-direction-vertical .aux-icon-list-item:after' => 'width: {{SIZE}}{{UNIT}};'
                ),
                'condition' => array(
                    'divider'    => 'yes',
                    'direction!' => 'horizontal'
                )
            )
        );

        $this->add_responsive_control(
            'divider_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-divider .aux-icon-list-item:not(:last-child):after' => 'border-bottom-color: {{VALUE}};'
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-direction-vertical .aux-icon-list-item:not(:last-child):after'   => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .aux-direction-horizontal .aux-icon-list-item:after' => 'border-right-color: {{VALUE}};'
                ),
                'condition' => array(
                    'divider' => 'yes'
                )
            )
        );

        $this->end_controls_section();


        /*   Text Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'text_style_section',
            array(
                'label'     => __( 'Text', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'text_style_heading',
            array(
                'label'     => __( 'Text', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $this->add_responsive_control(
            'text1_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-text' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'text1_hover_color',
            array(
                'label'     => __( 'Hover Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item:hover .aux-icon-list-text' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'text1_typography',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-icon-list-text'
            )
        );

        $this->add_responsive_control(
            'text1_margin',
            array(
                'label'              => __( 'Text Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-icon-list-text' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        /*   Price Style Section
        /*-------------------------------------*/

        $this->add_control(
            'text2_style_heading',
            array(
                'label'     => __( 'Price Text', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'text2_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#e80532',
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-text2' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'text2_hover_color',
            array(
                'label'     => __( 'Hover Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item:hover .aux-icon-list-text2' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'text2_typography',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-icon-list-text2'
            )
        );

        $this->add_responsive_control(
            'text2_margin',
            array(
                'label'              => __( 'Text Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-icon-list-text2' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        /*   Text2 Style Section
        /*-------------------------------------*/

        $this->add_control(
            'description_style_heading',
            array(
                'label'     => __( 'Description Text', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'description_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#aaaaaa',
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-description' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'description_hover_color',
            array(
                'label'     => __( 'Hover Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item:hover .aux-icon-list-description' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'description_typography',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-icon-list-description'
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label'              => __( 'Text Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-icon-list-description' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Icon Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'icon_style_section',
            array(
                'label'     => __( 'Icon', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'icon_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#24af29',
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-icon' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'icon_hover_color',
            array(
                'label'     => __( 'Hover Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-item:hover aux-icon-list-icon' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __( 'Size', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', 'em' ),
                'range'      => array(
                    'px' => array(
                        'max' => 100
                    ),
                    'em' => array(
                        'max' => 10
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-icon-list-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'icon_margin',
            array(
                'label'              => __( 'Icon Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-icon-list-icon' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   List Item Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'list_item_style_section',
            array(
                'label'     => __( 'List Item', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'list_padding',
            array(
                'label'      => __( 'Padding', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-icon-list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'list_margin',
            array(
                'label'              => __( 'Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-icon-list-item' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ),
                'separator' => 'after'
            )
        );

        $this->start_controls_tabs( 'list_colors' );

        $this->start_controls_tab(
            'list_status_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'list_boxshadow_normal',
                'label'     => __( 'Box Shadow Normal', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .aux-icon-list-item',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'list_border_normal',
                'selector'  => '{{WRAPPER}} .aux-icon-list-item',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'list_background_normal',
                'selector'  => '{{WRAPPER}} .aux-icon-list-item',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'list_status_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'list_boxshadow_hover',
                'label'     => __( 'Box Shadow Hover', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .aux-icon-list-item:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'list_border_hover',
                'selector'  => '{{WRAPPER}} .aux-icon-list-item:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'list_background_hover',
                'selector'  => '{{WRAPPER}} .aux-icon-list-item:hover',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    /**
     * Render 'Custom List' widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        foreach ( $settings['list'] as $key => $list_item ) {

            $settings['list'][$key]['icon'] =  ! empty( $list_item['aux_price_list_icon']['value'] ) ? $list_item['aux_price_list_icon']['value'] : ( ! empty( $list_item['icon'] ) ? $list_item['icon'] : '' ) ;

        }

        $args     = array(
            'list'               => $settings['list'], // repeater items
            'direction'          => $settings['direction'],
            'connector'          => $settings['connector'], // A line that connects primary and secondary text
            'divider'            => $settings['divider'], // A line between list items
            'item_class_prefix'  => '',    // Default class prefix for each repeater item
            'base_class'         => 'aux-widget-icon-list aux-widget-price-list'
        );

        // pass the args through the corresponding shortcode callback
        echo auxin_widget_list_callback( $args );
    }

}
