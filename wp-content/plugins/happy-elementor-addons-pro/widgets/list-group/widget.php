<?php
/**
 * List Group widget class
 *
 * @package Happy_Addons_Pro
 */
namespace Happy_Addons_Pro\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class List_Group extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'List Group', 'happy-addons-pro' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'hm hm-list-group';
    }

    public function get_keywords() {
        return [ 'list', 'group', 'item', 'icon' ];
    }

    protected function register_content_controls() {
        $this->start_controls_section(
            '_section_list_group',
            [
                'label' => __( 'List Items', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __( 'Media Type', 'happy-addons-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'none',
				'options' => [
					'none' => [
						'title' => __( 'None', 'happy-addons-pro' ),
						'icon' => ' eicon-editor-close',
					],
					'icon' => [
						'title' => __( 'Icon', 'happy-addons-pro' ),
						'icon' => 'eicon-star',
					],
					'number' => [
						'title' => __( 'Number', 'happy-addons-pro' ),
						'icon' => 'eicon-number-field',
					],
					'image' => [
						'title' => __( 'Image', 'happy-addons-pro' ),
						'icon' => 'eicon-image',
					],
				],
				'toggle' => false,
                'style_transfer' => true,
            ]
        );

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'happy-addons-pro' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-smile',
					'library' => 'regular',
				],
				'condition' => [
					'icon_type' => 'icon'
				],
			]
		);

        $repeater->add_control(
            'number',
            [
                'label' => __( 'Item Number', 'happy-addons-pro' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'List Item Number', 'happy-addons-pro' ),
                'default' => __( '1', 'happy-addons-pro' ),
                'condition' => [
                    'icon_type' => 'number'
                ],
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'happy-addons-pro' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'happy-addons-pro' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'separator' => 'before',
                'placeholder' => __( 'List Item', 'happy-addons-pro' ),
                'default' => __( 'Build beautiful websites', 'happy-addons-pro' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'description',
            [
                'label' => __( 'Description', 'happy-addons-pro' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => __( 'List Item Description', 'happy-addons-pro' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

		$repeater->add_control(
			'direction',
			[
				'label' => __( 'Direction', 'happy-addons-pro' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'hm hm-play-next',
					'library' => 'happy-icons',
				]
			]
		);

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'happy-addons-pro' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'happy-addons-pro' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'custom_look',
            [
                'label' => __( 'Custom Look', 'happy-addons-pro' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'happy-addons-pro' ),
                'label_off' => __( 'No', 'happy-addons-pro' ),
                'return_value' => 'yes',
                'default' => 'no',
                'separator' => 'before',
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'title_color',
            [
                'label' => __( 'Title Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_look' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} .ha-text .ha-list-title' => 'color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $repeater->add_control(
            'background_color',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'custom_look' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}}',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'list_item',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => __( 'Build beautiful websites', 'happy-addons-pro' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'Floating Effect', 'happy-addons-pro' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'CSS Transform', 'happy-addons-pro' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'Fast and Lightweight', 'happy-addons-pro' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'mode',
            [
                'label' => __( 'List Mode', 'happy-addons-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'compact' => [
                        'title' => __( 'Compact', 'happy-addons-pro' ),
                        'icon' => 'fa fa-stop',
                    ],
                    'comfy' => [
                        'title' => __( 'Comfy', 'happy-addons-pro' ),
                        'icon' => 'fa fa-bars',
                    ],
                ],
                'toggle' => false,
                'default' => 'compact',
                'prefix_class' => 'ha-mode--',
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'direction_position',
            [
                'label' => __( 'Direction Position', 'happy-addons-pro' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'left' => [
                        'title' => __( 'Left', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'right' => [
                        'title' => __( 'Right', 'happy-addons-pro' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'prefix_class' => 'ha-direction--',
                'default' => 'right',
                'selectors_dictionary' => [
                    'left' => 'flex-direction: row-reverse',
                    'right' => 'flex-direction: row',
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-item-wrap' => '{{VALUE}};',
                ],
                'style_transfer' => true,
            ]
        );

		$this->add_control(
			'text_alignment',
			[
				'label' => __( 'Text Alignment', 'happy-addons-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'happy-addons-pro' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'happy-addons-pro' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'happy-addons-pro' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .ha-text'  => 'text-align: {{VALUE}};'
				],
			]
		);

        $this->end_controls_section();
    }

    protected function register_style_controls() {
        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-item-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 200,
                    ]
                ],
                'condition' => [
                     'mode' => 'comfy'
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'list_border_type',
            [
                'label' => __( 'Border Type', 'happy-addons-pro' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'happy-addons-pro' ),
                    'solid' => __( 'Solid', 'happy-addons-pro' ),
                    'double' => __( 'Double', 'happy-addons-pro' ),
                    'dotted' => __( 'Dotted', 'happy-addons-pro' ),
                    'dashed' => __( 'Dashed', 'happy-addons-pro' ),
                ],
                'default' => 'solid',
                'selectors' => [
                    '{{WRAPPER}}.ha-mode--compact .ha-list-wrap' => 'border-style: {{VALUE}}',
                    '{{WRAPPER}}.ha-mode--compact .ha-list-item:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
                    '{{WRAPPER}}.ha-mode--comfy .ha-list-item' => 'border-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_border_width',
            [
                'label' => __( 'Width', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'list_border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-mode--compact .ha-list-wrap' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.ha-mode--compact .ha-list-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.ha-mode--comfy .ha-list-item' => 'border-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'list_border_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'list_border_type!' => 'none',
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-mode--compact .ha-list-wrap' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.ha-mode--compact .ha-list-item:not(:last-child)' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}}.ha-mode--comfy .ha-list-item' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}.ha-mode--compact .ha-list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.ha-mode--comfy .ha-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'content_box_shadow',
                'selector' => '{{WRAPPER}}.ha-mode--compact .ha-list-wrap, {{WRAPPER}}.ha-mode--comfy .ha-list-item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Media Type', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __( 'Spacing', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}}.ha-direction--right .ha-list-item .ha-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}}.ha-direction--left .ha-list-item .ha-icon' => 'margin-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 250,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-icon.icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .ha-list-item .ha-icon.number span' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ha-list-item .ha-icon.image img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_background_spacing',
            [
                'label' => __( 'Padding', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .ha-list-item .ha-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .ha-list-item .ha-icon.image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'icon_box_shadow',
                'selector' => '{{WRAPPER}} .ha-list-item .ha-icon',
            ]
        );

        $this->start_controls_tabs( '_tabs_icon' );
        $this->start_controls_tab(
            '_tab_icon_normal',
            [
                'label' => __( 'Normal', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ha-list-item .ha-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_background',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_icon_hover',
            [
                'label' => __( 'Hover', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .ha-list-item a:hover .ha-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_background',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_border',
            [
                'label' => __( 'Border Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                     'icon_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-icon' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_text',
            [
                'label' => __( 'Title & Description', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_heading',
            [
                'label' => __( 'Title', 'happy-addons-pro' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_responsive_control(
            'title_spacing',
            [
                'label' => __( 'Bottom Spacing', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-text .ha-list-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .ha-text .ha-list-title',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->start_controls_tabs( '_tabs_title' );
        $this->start_controls_tab(
            '_tab_title_normal',
            [
                'label' => __( 'Normal', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'title_link_color',
            [
                'label' => __( 'Link Text Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a .ha-item-wrap .ha-text .ha-list-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_title_hover',
            [
                'label' => __( 'Hover', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label' => __( 'Link Text Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-item-wrap .ha-text .ha-list-title' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'description_heading',
            [
                'label' => __( 'Description', 'happy-addons-pro' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-text .ha-list-detail' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'selector' => '{{WRAPPER}} .ha-text .ha-list-detail',
                'scheme' => Scheme_Typography::TYPOGRAPHY_3,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            '_section_direction',
            [
                'label' => __( 'Direction', 'happy-addons-pro' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_responsive_control(
			'direction_spacing',
			[
				'label' => __( 'Spacing', 'happy-addons-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					]
				],
				'selectors' => [
					'{{WRAPPER}}.ha-direction--left .ha-direction' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}}.ha-direction--right .ha-direction' => 'margin-left: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
            'direction_font_size',
            [
                'label' => __( 'Size', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-direction' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'direction_padding',
            [
                'label' => __( 'Padding', 'happy-addons-pro' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-direction' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'direction_border',
                'selector' => '{{WRAPPER}} .ha-list-item  .ha-direction',
            ]
        );

        $this->add_control(
            'direction_border_radius',
            [
                'label' => __( 'Border Radius', 'happy-addons-pro' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item  .ha-direction' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'direction_box_shadow',
                'selector' => '{{WRAPPER}} .ha-list-item  .ha-direction',
            ]
        );

        $this->add_control(
            'direction_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-direction i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_background_color',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item .ha-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->start_controls_tabs( '_tabs_direction' );
        $this->start_controls_tab(
            '_tab_direction_normal',
            [
                'label' => __( 'Normal', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'direction_link_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a .ha-direction i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_link_background_color',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a .ha-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_direction_hover',
            [
                'label' => __( 'Hover', 'happy-addons-pro' ),
            ]
        );

        $this->add_control(
            'direction_hover_color',
            [
                'label' => __( 'Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-direction i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_hover_background_color',
            [
                'label' => __( 'Background Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-direction' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'direction_hover_border_color',
            [
                'label' => __( 'Border Color', 'happy-addons-pro' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'direction_border_border!' => ''
                ],
                'selectors' => [
                    '{{WRAPPER}} .ha-list-item a:hover .ha-direction' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( empty($settings['list_item'] ) ) {
            return;
        }
        ?>

        <ul class="ha-list-wrap">
            <?php foreach ( $settings['list_item'] as $index => $item ) :
                // title
                $title_key = $this->get_repeater_setting_key( 'title', 'list_item', $index );
                $this->add_inline_editing_attributes( $title_key, 'basic' );
                $this->add_render_attribute( $title_key, 'class', 'ha-list-title' );

                // description
                $description_key = $this->get_repeater_setting_key( 'description', 'list_item', $index );
                $this->add_inline_editing_attributes( $description_key, 'basic' );
                $this->add_render_attribute( $description_key, 'class', 'ha-list-detail' );

                // link
                if ( $item['link']['url'] ) {
                    $link_key = $this->get_repeater_setting_key( 'link', 'list_item', $index );
                    $this->add_render_attribute( $link_key, 'class', 'ha-link' );
                    $this->add_link_attributes( $link_key, $item['link'] );
                }
                ?>

                <li class="ha-list-item elementor-repeater-item-<?php echo $item['_id']; ?>">

                    <?php if ( !empty( $item['link']['url'] ) ) : ?>
                    <a <?php $this->print_render_attribute_string( $link_key ); ?>>
                    <?php endif; ?>

                        <div class="ha-item-wrap">

							<?php if ( ! empty( $item['icon']['value'] ) ) : ?>
                                <div class="ha-icon icon">
									<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                </div>

                            <?php elseif( $item['number'] ) : ?>
                                <div class="ha-icon number">
                                    <span><?php echo esc_html( $item['number'] ); ?></span>
                                </div>

                            <?php elseif( $item['image'] ) :

                                $images = wp_get_attachment_image_src( $item['image']['id'], 'thumbnail', false );
                                $image = $images[0];
                                if( !$image ) {
                                    $image = $item['image']['url'];
                                }
                                ?>
                                <div class="ha-icon image">
                                    <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
                                </div>

                            <?php
                            endif;
                            ?>

                            <div class="ha-text">

                                    <h4 <?php $this->print_render_attribute_string( $title_key ); ?>>
                                        <?php echo $item['title']; ?>
                                    </h4>

                                <?php if ( $item['description'] ) : ?>
                                    <p <?php $this->print_render_attribute_string( $description_key ); ?>>
                                        <?php echo esc_html( $item['description'] ); ?>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <?php if ( $item['direction']['value'] ) : ?>
                                <div class="ha-direction">
									<?php Icons_Manager::render_icon( $item['direction'], [ 'aria-hidden' => 'true' ] ); ?>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php if ( !empty( $item['link']['url'] ) ) : ?>
                    </a>
                    <?php endif; ?>

                </li>

            <?php endforeach; ?>
        </ul>

        <?php
    }

//    public function _content_template() {}


}
