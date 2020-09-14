<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'aux_3d_textbox' widget.
 *
 * Elementor widget that displays an 'aux_3d_textbox' with lightbox.
 *
 * @since 1.0.0
 */
class Text_Box_3D extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'aux_3d_textbox' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_3d_textbox';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'aux_3d_textbox' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( '3D text box', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'aux_3d_textbox' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-image-hotspot auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'aux_3d_textbox' widget icon.
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
     * Register 'aux_3d_textbox' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  content_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'content_section',
            array(
                'label'      => __('Content', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => __('Title',PLUGIN_DOMAIN ),
                'description' => __('Text title, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'subtitle',
            array(
                'label'       => __('Subtitle',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'link',
            array(
                'label'         => __('Title Link',PLUGIN_DOMAIN ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => 'https://your-link.com',
                'show_external' => false,
                'dynamic' => [
					'active' => true
				]
            )
        );

        $this->add_control(
            'wrapper_bg_image',
            array(
                'label'      => __('Wrapper Background image',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::MEDIA
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  wrapper_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'wrapper_section',
            array(
                'label' => __('Wrapper', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'box_width',
            array(
                'label'       => __( 'Wrapper Background image width', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                )
            )
        );

        $this->add_control(
            'box_height',
            array(
                'label'       => __( 'Wrapper Background Image height', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('px'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 1600,
                        'step' => 1
                    )
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  wrapper_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'tilt_section',
            array(
                'label' => __('Tilt', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'tilt',
            array(
                'label'       => __( 'Maximum Tilt', PLUGIN_DOMAIN ),
                'description' => __( 'Set it to 0 in order to disable tilt.', PLUGIN_DOMAIN ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '20',
                'min'         => 0,
                'step'        => 1
            )
        );

        $this->add_control(
            'time',
            array(
                'label'       => __( 'Tilt Time', PLUGIN_DOMAIN ),
                'description' => __( 'Use a value between 100 (fast) and 2000 (slow tilt)', PLUGIN_DOMAIN ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '1000',
                'min'         => 0,
                'step'        => 1
            )
        );

        $this->add_control(
            'transformz',
            array(
                'label'       => __( '3D Margin', PLUGIN_DOMAIN ),
                'description' => __( 'Margin with background image.', PLUGIN_DOMAIN ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '50',
                'min'         => 0,
                'step'        => 1
            )
        );

        $this->add_control(
			'scale',
			array(
				'label' => __( 'Tilt Scale', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range' => array(
					'px' => array(
						'min' => 1,
						'max' => 10,
						'step' => 0.5,
					)
				),
				'default' => array(
					'unit' => 'px',
					'size' => 1,
				),
            )
        );

        $this->add_control(
            'reverse',
            array(
                'label'        => __('Reverse Direction',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => true,
                'default'      => false
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  text_box_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'text_box_section',
            array(
                'label'     => __( 'Text Box', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );
        $this->add_control(
            'width',
            array(
                'label'       => __( 'Text Box Width (%)', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('%'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    )
                )
            )
        );

        $this->add_control(
            'height',
            array(
                'label'       => __( 'Text Box Height (%)', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('%'),
                'range'       => array(
                    'px' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    )
                )
            )
        );

        $this->add_control(
            'text_position',
            array(
                'label'       => __( 'Text Position', PLUGIN_DOMAIN ),
                'description' => __( 'Filter by categories or tags', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'center-center',
                'options'     => array(
                    'top-left'      => __( 'Top Left', PLUGIN_DOMAIN ),
                    'top-center'    => __( 'Top Center', PLUGIN_DOMAIN ),
                    'top-right'     => __( 'Top Right', PLUGIN_DOMAIN ),
                    'center-left'   => __( 'Center Left', PLUGIN_DOMAIN ),
                    'center-center' => __( 'Center Center', PLUGIN_DOMAIN ),
                    'center-right'  => __( 'Center Right', PLUGIN_DOMAIN ),
                    'bottom-left'   => __( 'Bottom Left', PLUGIN_DOMAIN ),
                    'bottom-center' => __( 'Bottom Center', PLUGIN_DOMAIN ),
                    'bottom-right'  => __( 'Bottom Right', PLUGIN_DOMAIN ),
                )
            )
        );

        $this->add_control(
            'text_box_bg',
            array(
                'label' => __( 'Background', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-widget-3d-textbox .aux-3d-textbox-widget-content' => 'background-color: {{VALUE}};',
                )
            )
        );

        // $this->add_responsive_control(
        //     'text_box_padding',
        //     array(
        //         'label'      => __( 'Padding', PLUGIN_DOMAIN ),
        //         'type'       => Controls_Manager::DIMENSIONS,
        //         'size_units' => array( 'px', '%' ),
        //         'selectors'  => array(
        //             '{{WRAPPER}} .aux-widget-3d-textbox .aux-3d-textbox-widget-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        //         )
        //     )
        // );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  info_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'title!' => '',
                ),
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-title a, .col-title' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-title:hover' => 'color: {{VALUE}};',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .col-title'
            )
        );

        $this->add_responsive_control(
            'title_margin_bottom',
            array(
                'label' => __( 'Bottom space', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-title' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  info_style_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'subtitle_style_section',
            array(
                'label'     => __( 'Subtitle', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'subtitle!' => '',
                ),
            )
        );

        $this->add_control(
            'subtitle_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'subtitle_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .col-subtitle'
            )
        );

        $this->add_responsive_control(
            'subtitle_margin_bottom',
            array(
                'label' => __( 'Bottom space', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .col-subtitle' => 'padding-bottom: {{SIZE}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();

    }

  /**
   * Render image box widget output on the frontend.
   *
   * Written in PHP and used to generate the final HTML.
   *
   * @since 1.0.0
   * @access protected
   */
  protected function render() {

    $settings = $this->get_settings_for_display();

    $args     = array(

        'title'            => $settings['title'],
        'subtitle'         => $settings['subtitle'],
        'link'             => $settings['link']['url'],

        'wrapper_bg_image' => $settings['wrapper_bg_image']['id'],
        'box_width'        => $settings['box_width']['size'],
        'box_height'       => $settings['box_height']['size'],

        'tilt'             => $settings['tilt'],
        'time'             => $settings['time'],
        'transformz'       => $settings['transformz'],
        'scale'            => $settings['scale']['size'],
        'reverse'          => $settings['reverse'],

        'width'            => $settings['width']['size'],
        'height'           => $settings['height']['size'],
        'text_position'    => $settings['text_position'],

    );

    echo auxin_widget_3d_textbox_callback( $args );

  }

}
