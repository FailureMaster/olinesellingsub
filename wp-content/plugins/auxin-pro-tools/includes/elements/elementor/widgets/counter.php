<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes;

/**
 * Elementor counter widget.
 *
 * Elementor widget that displays stats and numbers in an escalating manner.
 *
 * @since 1.0.0
 */
class Counter extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve counter widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'aux_counter';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve counter widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Counter', PLUGIN_DOMAIN );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve counter widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-counter auxin-badge';
	}

	/**
     * Get widget categories.
     *
     * Retrieve 'FAQ' widget icon.
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
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'counter' ];
	}

	/**
	 * Register counter widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_counter',
			[
				'label' => __( 'Counter', PLUGIN_DOMAIN ),
			]
		);

		$this->add_control(
			'starting_number',
			[
				'label' => __( 'Starting Number', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'ending_number',
			[
				'label' => __( 'Ending Number', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'default' => 100,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'prefix',
			[
				'label' => __( 'Number Prefix', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => 1,
			]
		);

		$this->add_control(
			'suffix',
			[
				'label' => __( 'Number Suffix', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => __( 'Plus', PLUGIN_DOMAIN ),
			]
		);

		$this->add_control(
			'duration',
			[
				'label' => __( 'Animation Duration', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'default' => 2000,
				'min' => 100,
				'step' => 100,
			]
        );
        
        $this->add_control(
			'delay',
			[
				'label' => __( 'Animation Delay', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::NUMBER,
				'default' => 0,
				'min' => 100,
				'step' => 100,
			]
		);

        $this->add_control(
			'easing',
			[
				'label' => __( 'Easing', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'swing' => 'swing',
					'easeInQuad'        => 'easeInQuad',
                    'easeOutQuad'       => 'easeOutQuad',
                    'easeInOutQuad'     => 'easeInOutQuad',
                    'easeInCubic'       => 'easeInCubic',
                    'easeOutCubic'      => 'easeOutCubic',
                    'easeInOutCubic'    => 'easeInOutCubic',
                    'easeInQuart'       => 'easeInQuart',
                    'easeOutQuart'      => 'easeOutQuart',
                    'easeInOutQuart'    => 'easeInOutQuart',
                    'easeInQuint'       => 'easeInQuint',
                    'easeOutQuint'      => 'easeOutQuint',
                    'easeInOutQuint'    => 'easeInOutQuint',
                    'easeInSine'        => 'easeInSine',
                    'easeOutSine'       => 'easeOutSine',
                    'easeInOutSine'     => 'easeInOutSine',
                    'easeInExpo'        => 'easeInExpo',
                    'easeOutExpo'       => 'easeOutExpo',
                    'easeInOutExpo'     => 'easeInOutExpo',
                    'easeInCirc'        => 'easeInCirc',
                    'easeOutCirc'       => 'easeOutCirc',
                    'easeInOutCirc'     => 'easeInOutCirc',
                    'easeInElastic'     => 'easeInElastic',
                    'easeOutElastic'    => 'easeOutElastic',
                    'easeInOutElastic'  => 'easeInOutElastic',
                    'easeInBack'        => 'easeInBack',
                    'easeOutBack'       => 'easeOutBack',
                    'easeInOutBack'     => 'easeInOutBack',
                    'easeInBounce'      => 'easeInBounce',
                    'easeOutBounce'     => 'easeOutBounce',
                    'easeInOutBounce'   => 'easeInOutBounce'
				],
			]
        );
        
		$this->add_control(
			'thousand_separator',
			[
				'label' => __( 'Thousand Separator', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => __( 'Show', PLUGIN_DOMAIN ),
				'label_off' => __( 'Hide', PLUGIN_DOMAIN ),
			]
		);

		$this->add_control(
			'thousand_separator_char',
			[
				'label' => __( 'Separator', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'thousand_separator' => 'yes',
				],
				'options' => [
					'' => 'Default',
					'.' => 'Dot',
					' ' => 'Space',
				],
			]
		);

		$this->add_control(
			'title',
			[
				'label' => __( 'Title', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'default' => __( 'Cool Number', PLUGIN_DOMAIN ),
				'placeholder' => __( 'Cool Number', PLUGIN_DOMAIN ),
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_number',
			[
				'label' => __( 'Number', PLUGIN_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'number_color',
			[
				'label' => __( 'Text Color', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} .aux-counter-number-wrapper' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_number',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .aux-counter-number-wrapper',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => __( 'Title', PLUGIN_DOMAIN ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Text Color', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Schemes\Color::get_type(),
					'value' => Schemes\Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} .aux-counter-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography_title',
				'scheme' => Schemes\Typography::TYPOGRAPHY_2,
				'selector' => '{{WRAPPER}} .aux-counter-title',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render counter widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<# view.addRenderAttribute( 'counter-title', {
			'class': 'aux-counter-title'
		} );

		view.addInlineEditingAttributes( 'counter-title' );
		#>
		<div class="aux-counter">
			<div class="aux-counter-number-wrapper">
				<span class="aux-counter-number-prefix">{{{ settings.prefix }}}</span>
				<span class="aux-counter-number" data-duration="{{ settings.duration }}" data-to-value="{{ settings.ending_number }}" data-delimiter="{{ settings.thousand_separator ? settings.thousand_separator_char || ',' : '' }}" data-delay="{{ settings.delay }}" data-easing="{{ settings.easing }}">{{{ settings.starting_number }}}</span>
				<span class="aux-counter-number-suffix">{{{ settings.suffix }}}</span>
			</div>
			<# if ( settings.title ) {
				#><div {{{ view.getRenderAttributeString( 'counter-title' ) }}}>{{{ settings.title }}}</div><#
			} #>
		</div>
		<?php
	}

	/**
	 * Render counter widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'counter', [
			'class' => 'aux-counter-number',
			'data-duration' => $settings['duration'],
			'data-to-value' => $settings['ending_number'],
            'data-from-value' => $settings['starting_number'],
			'data-delay'    => $settings['delay'],
			'data-easing'	=> $settings['easing']
		] );

		if ( ! empty( $settings['thousand_separator'] ) ) {
			$delimiter = empty( $settings['thousand_separator_char'] ) ? ',' : $settings['thousand_separator_char'];
			$this->add_render_attribute( 'counter', 'data-delimiter', $delimiter );
		}

		$this->add_render_attribute( 'counter-title', 'class', 'aux-counter-title' );

		$this->add_inline_editing_attributes( 'counter-title' );
		?>
		<div class="aux-counter">
			<div class="aux-counter-number-wrapper">
				<span class="aux-counter-number-prefix"><?php echo $settings['prefix']; ?></span>
				<span <?php echo $this->get_render_attribute_string( 'counter' ); ?>><?php echo $settings['starting_number']; ?></span>
				<span class="aux-counter-number-suffix"><?php echo $settings['suffix']; ?></span>
			</div>
			<?php if ( $settings['title'] ) : ?>
				<div <?php echo $this->get_render_attribute_string( 'counter-title' ); ?>><?php echo $settings['title']; ?></div>
			<?php endif; ?>
		</div>
		<?php
	}
}
