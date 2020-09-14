<?php
/**
 * Chart widget class
 *
 * @package Happy_Addons
 */
namespace Happy_Addons_Pro\Widget;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Scheme_Typography;
use Happy_Addons_Pro\Widget\Radar_Chart\Data_Map;

defined( 'ABSPATH' ) || die();

class Radar_Chart extends Base {

	/**
	 * Get widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Radar Chart', 'happy-elementor-addons' );
	}

//	public function get_custom_help_url() {
//		return '';
//	}

	/**
	 * Get widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'hm hm-graph-pie';
	}

	public function get_keywords() {
		return [ 'chart', 'radar', 'statistic' ];
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'_section_chart',
			[
				'label' => __( 'Radar Chart', 'happy-elementor-addons' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'labels',
			[
				'label'       => __( 'Labels', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'January, February, March, April, May', 'happy-elementor-addons' ),
				'description' => __( 'Write multiple label with comma ( , ) separator. Example: January, February, March etc', 'happy-elementor-addons' ),
			]
		);

		$repeater = new Repeater();

		$repeater->start_controls_tabs( 'bar_tabs' );

		$repeater->start_controls_tab(
			'bar_tab_content',
			[
				'label' => __( 'Content', 'happy-elementor-addons' ),
			]
		);

		$repeater->add_control(
			'label',
			[
				'label'   => __( 'Label', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [ 'active' => true ],
			]
		);

		$repeater->add_control(
			'data',
			[
				'label'       => __( 'Data', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'Write data values with comma ( , ) separator. Example: 20, 30, 40, 50', 'happy-elementor-addons' ),
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'bar_tab_style',
			[
				'label' => __( 'Style', 'happy-elementor-addons' ),
			]
		);

		$repeater->add_control(
			'background_color',
			[
				'label' => __( 'Background Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'border_color',
			[
				'label' => __( 'Border Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->add_control(
			'pointer_color',
			[
				'label' => __( 'Pointer Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$repeater->end_controls_tab();

		$this->add_control(
			'chart_data',
			[
				'type'        => Controls_Manager::REPEATER,
				'fields'      => array_values( $repeater->get_controls() ),
				'title_field' => '{{{ label }}}',
				'default'     => [
					[
						'label'              => 'Happy Addons',
						'data'               => '47, 10, 45, 75, 10',
						'background_color'   => 'rgba(86, 45, 212, 0.17)',
						'border_color'       => '#5A37CF',
						'pointer_color'      => '#3449CD',
					],
					[
						'label'              => 'Happy Addons Pro',
						'data'               => '5, 53, 18, 33, 54',
						'background_color'   => 'rgba(226, 73, 138, 0.22)',
						'border_color'       => '#E2498A',
						'pointer_color'      => '#E22978',
					]
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Settings', 'happy-elementor-addons' ),
			]
		);

		$this->add_responsive_control(
			'chart_height',
			[
				'label'       => __( 'Chart Height', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => [
					'px' => [
						'min' => 50,
						'max' => 1500,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors'   => [
					'{{WRAPPER}} .ha-radar-chart-container' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tooltip_display',
			[
				'label'        => __( 'Show Tooltips', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'title_display',
			[
				'label'        => __( 'Show Title', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'chart_title',
			[
				'label'       => __( 'Title', 'happy-elementor-addons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => __( 'Happy Addons Rocks', 'happy-elementor-addons' ),
				'condition' => [
					'title_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_heading',
			[
				'label'     => __( 'Legend', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'legend_display',
			[
				'label'        => __( 'Show Legend', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'legend_position',
			[
				'label'     => __( 'Position', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'top',
				'options'   => [
					'top'    => __( 'Top', 'happy-elementor-addons' ),
					'left'   => __( 'Left', 'happy-elementor-addons' ),
					'bottom' => __( 'Bottom', 'happy-elementor-addons' ),
					'right'  => __( 'Right', 'happy-elementor-addons' ),
				],
				'condition' => [
					'legend_display' => 'yes',
				],
			]
		);

		$this->add_control(
			'legend_reverse',
			[
				'label'        => __( 'Reverse', 'happy-elementor-addons' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'condition'    => [
					'legend_display'  => 'yes',
				],
			]
		);

		$this->add_control(
			'animation_heading',
			[
				'label'     => __( 'Animation', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'chart_animation_duration',
			[
				'label' => __( 'Duration', 'happy-elementor-addons' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 10000,
				'step' => 1,
				'default' => 1000,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'animation_options',
			[
				'label'     => __( 'Easing', 'happy-elementor-addons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'linear',
				'options'   => [
					'linear'    => __( 'Linear', 'happy-elementor-addons' ),
					'easeInCubic'   => __( 'Ease In Cubic', 'happy-elementor-addons' ),
					'easeInCirc' => __( 'Ease In Circ', 'happy-elementor-addons' ),
					'easeInBounce' => __( 'Ease In Bounce', 'happy-elementor-addons' ),
				]
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_controls() {
		$this->start_controls_section(
			'_section_style_common',
			[
				'label' => __( 'Common', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'layout_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
			]
		);

		$this->add_control(
			'line_tension',
			[
				'label' => __( 'Line Tension', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 15,
				],
			]
		);

		$this->add_control(
			'border_width',
			[
				'label' => __( 'Border Width', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
			]
		);

		$this->add_control(
			'pointer_border_width',
			[
				'label' => __( 'Pointer Border Width', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 5,
				],
			]
		);

		$this->add_control(
			'title_typography_toggle',
			[
				'label' => __( 'Title Typography', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-elementor-addons' ),
				'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'title_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'title_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_family',
			[
				'label' => __( 'Font Family', 'happy-elementor-addons' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-elementor-addons' ),
					'normal' => __( 'Normal', 'happy-elementor-addons' ),
					'bold'   => __( 'Bold', 'happy-elementor-addons' ),
					'300'    => __( '300', 'happy-elementor-addons' ),
					'400'    => __( '400', 'happy-elementor-addons' ),
					'600'    => __( '600', 'happy-elementor-addons' ),
					'700'    => __( '700', 'happy-elementor-addons' )
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-elementor-addons' ),
					'normal'  => __( 'Normal', 'happy-elementor-addons' ),
					'italic'  => __( 'Italic', 'happy-elementor-addons' ),
					'oblique' => __( 'Oblique', 'happy-elementor-addons' ),
				],
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'title_font_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'title_display' => 'yes',
					'title_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_legend',
			[
				'label' => __( 'Legend', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'lagend_note',
			[
				'label' => false,
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'Lagend is Switched off from Content > Settings.', 'happy-elementor-addons' ),
				'condition' => [
					'legend_display!' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_box_width',
			[
				'label' => __( 'Box Width', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 70,
					],
				],
				'condition' => [
					'legend_display' => 'yes'
				]
			]
		);

		$this->add_control(
            'legend_typography_toggle',
            [
                'label' => __( 'Typography', 'happy-elementor-addons' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'happy-elementor-addons' ),
                'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'legend_display' => 'yes'
				]
            ]
		);

		$this->start_popover();

		$this->add_control(
			'legend_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_family',
			[
				'label' => __( 'Font Family', 'happy-elementor-addons' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-elementor-addons' ),
					'normal' => __( 'Normal', 'happy-elementor-addons' ),
					'bold'   => __( 'Bold', 'happy-elementor-addons' ),
					'300'    => __( '300', 'happy-elementor-addons' ),
					'400'    => __( '400', 'happy-elementor-addons' ),
					'600'    => __( '600', 'happy-elementor-addons' ),
					'700'    => __( '700', 'happy-elementor-addons' )
				],
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'legend_font_style',
			array(
				'label'   => esc_html__( 'Font Style', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''        => __( 'Default', 'happy-elementor-addons' ),
					'normal'  => __( 'Normal', 'happy-elementor-addons' ),
					'italic'  => __( 'Italic', 'happy-elementor-addons' ),
					'oblique' => __( 'Oblique', 'happy-elementor-addons' ),
				),
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			)
		);

		$this->add_control(
			'legend_font_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'legend_display' => 'yes',
					'legend_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();

		$this->start_controls_section(
			'_section_style_tooltip',
			[
				'label' => __( 'Tooltip', 'happy-elementor-addons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'tooltip_padding',
			[
				'label' => __( 'Padding', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_border_width',
			[
				'label' => __( 'Border Width', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_border_radius',
			[
				'label' => __( 'Border Radius', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_caret_size',
			[
				'label' => __( 'Caret Size', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_mode',
			[
				'label'   => esc_html__( 'Mode', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Select Mode', 'happy-elementor-addons' ),
					'nearest' => __( 'Nearest', 'happy-elementor-addons' ),
					'index' => __( 'Index', 'happy-elementor-addons' ),
					'x' => __( 'X', 'happy-elementor-addons' ),
					'y' => __( 'Y', 'happy-elementor-addons' ),
				],
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
				]
			]
		);

		$this->add_control(
			'tooltip_background_color',
			[
				'label' => esc_html__( 'Background Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_border_color',
			[
				'label' => esc_html__( 'Border Color', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_typography_toggle',
			[
				'label' => __( 'Title Typography', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-elementor-addons' ),
				'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'tooltip_title_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_family',
			[
				'label' => __( 'Font Family', 'happy-elementor-addons' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-elementor-addons' ),
					'normal' => __( 'Normal', 'happy-elementor-addons' ),
					'bold'   => __( 'Bold', 'happy-elementor-addons' ),
					'300'    => __( '300', 'happy-elementor-addons' ),
					'400'    => __( '400', 'happy-elementor-addons' ),
					'600'    => __( '600', 'happy-elementor-addons' ),
					'700'    => __( '700', 'happy-elementor-addons' )
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-elementor-addons' ),
					'normal'  => __( 'Normal', 'happy-elementor-addons' ),
					'italic'  => __( 'Italic', 'happy-elementor-addons' ),
					'oblique' => __( 'Oblique', 'happy-elementor-addons' ),
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_title_font_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_title_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->add_control(
			'tooltip_body_typography_toggle',
			[
				'label' => __( 'Body Typography', 'happy-elementor-addons' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'label_off' => __( 'None', 'happy-elementor-addons' ),
				'label_on' => __( 'Custom', 'happy-elementor-addons' ),
				'return_value' => 'yes',
				'condition' => [
					'tooltip_display' => 'yes'
				]
			]
		);

		$this->start_popover();

		$this->add_control(
			'tooltip_body_font_size',
			[
				'label' => __( 'Font Size', 'happy-elementor-addons' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_family',
			[
				'label' => __( 'Font Family', 'happy-elementor-addons' ),
				'type' => Controls_Manager::FONT,
				'default' => '',
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_weight',
			[
				'label'   => esc_html__( 'Font Weight', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Default', 'happy-elementor-addons' ),
					'normal' => __( 'Normal', 'happy-elementor-addons' ),
					'bold'   => __( 'Bold', 'happy-elementor-addons' ),
					'300'    => __( '300', 'happy-elementor-addons' ),
					'400'    => __( '400', 'happy-elementor-addons' ),
					'600'    => __( '600', 'happy-elementor-addons' ),
					'700'    => __( '700', 'happy-elementor-addons' )
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_style',
			[
				'label'   => esc_html__( 'Font Style', 'happy-elementor-addons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => __( 'Default', 'happy-elementor-addons' ),
					'normal'  => __( 'Normal', 'happy-elementor-addons' ),
					'italic'  => __( 'Italic', 'happy-elementor-addons' ),
					'oblique' => __( 'Oblique', 'happy-elementor-addons' ),
				],
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->add_control(
			'tooltip_body_font_color',
			[
				'label' => __( 'Color', 'happy-elementor-addons' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'tooltip_display' => 'yes',
					'tooltip_body_typography_toggle' => 'yes'
				]
			]
		);

		$this->end_popover();

		$this->end_controls_section();
	}


	protected function render() {
		$settings = $this->get_settings_for_display();
		include_once HAPPY_ADDONS_PRO_DIR_PATH . "widgets/radar-chart/classes/data-map.php";

		$this->add_render_attribute(
			'container',
			[
				'class'         => 'ha-radar-chart-container',
				'data-settings' => Data_Map::initial($settings)
			]
		);

		$this->add_render_attribute( 'canvas',
			[
				'id' => 'ha-radar-chart',
				'role'  => 'img',
			]
		);
		?>
		<div <?php echo $this->get_render_attribute_string( 'container' ); ?>>

			<canvas <?php echo $this->get_render_attribute_string( 'canvas' ); ?>></canvas>

		</div>

	<?php
	}

}
