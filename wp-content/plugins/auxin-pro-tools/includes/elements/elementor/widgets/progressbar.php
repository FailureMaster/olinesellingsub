<?php
namespace Auxin\Plugin\Pro\Elementor\Elements;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'Progressbar' widget.
 *
 * Elementor widget that displays an 'Progressbar' with lightbox.
 *
 * @since 1.0.0
 */
class ProgressBar extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Progressbar' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_progressbar';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Progressbar' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Progressbar', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Progressbar' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'auxin-badge eicon-skill-bar';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Progressbar' widget icon.
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
     * Register 'Progressbar' widget controls.
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
            'progbar_item_section',
            array(
                'label'      => __('Content', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'prog_text',
            array(
                'label'       => __('Text',PLUGIN_DOMAIN ),
                'description' => __('Progressbar text, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Development'
            )
        );

        $this->add_control(
            'display_icon',
            array(
                'label'        => __('Display icon',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'off'
            )
        );

       $this->add_control(
            'aux_progressbar_text_icon',
            array(
                'label'        => __('Icon for text',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::ICONS,
                'condition'    => array(
                    'display_icon' => 'yes',
                )
            )

        );

        $this->add_control(
            'prog_value',
            array(
                'label'       => __( 'Value',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SLIDER,
                'size_units'  => array('%'),
                'range'       => array(
                    '%' => array(
                        'min'  => 1,
                        'max'  => 100,
                        'step' => 1
                    )
                )
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*  header_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'title_colors' );

        $this->start_controls_tab(
            'title_color_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-title' => 'color: {{VALUE}} !important;',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'title_color_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'title_hover_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-title:hover' => 'color: {{VALUE}} !important;',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-progressbar-title',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'title_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-progressbar-title',
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label' => __( 'Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'icon_style_section',
            array(
                'label'     => __( 'Icon', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'icon_color',
            array(
                'label'       => __('Icon color', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::COLOR,
                'default'     => '#303030',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-progressbar-container .aux-progressbar-icon ' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __( 'Icon Size', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array( 'px', '%' ),
                'range'      => array(
                    'px' => array(
                        'min' => 16,
                        'max' => 512,
                        'step' => 2,
                    ),
                    '%' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-progressbar-container  .aux-progressbar-icon ' => 'font-size: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_margin',
            array(
                'label' => __( 'Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-container  .aux-progressbar-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'bg_prog_style_section',
            array(
                'label' => __('Background Progressbar', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'prog_bg_height',
            array(
                'label'       => __( 'Background Height', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '4',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'bg_prog_color',
                'label' => __( 'Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => null,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'in_prog_style_section',
            array(
                'label' => __('Inner Progressbar', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'prog_in_height',
            array(
                'label'       => __( 'Inner Height', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '4',
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'in_prog_color',
                'label' => __( 'Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => null,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'value_style_section',
            array(
                'label'     => __( 'Value', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs( 'value_colors' );

        $this->start_controls_tab(
            'value_color_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'value_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-value' => 'color: {{VALUE}} !important;',
                ),
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'value_color_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'value_hover_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-value:hover' => 'color: {{VALUE}} !important;',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'value_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-progressbar-value',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'value_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-progressbar-value',
            )
        );


        $this->add_responsive_control(
            'value_top_position',
            array(
                'label' => __( 'Top space', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-progressbar-value' => 'top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();



    }




    // t
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

    $icon_value = ! empty( $settings['aux_progressbar_text_icon']['value'] ) ? $settings['aux_progressbar_text_icon']['value'] : ( ! empty( $settings['text_icon'] ) ? $settings['text_icon'] : '' ) ;

    $args = array(
        'prog_text'              => $settings['prog_text'],
        'display_icon'           => $settings['display_icon'],
        'prog_value'             => $settings['prog_value'],
        'prog_bg_color_type'     => $settings['bg_prog_color_background'],
        'prog_bg_color_value'    => $settings['bg_prog_color_color'],
        'prog_bg_color_stop1'    => $settings['bg_prog_color_color_stop'],
        'prog_bg_sec_color_grad' => $settings['bg_prog_color_color_b'],
        'prog_bg_color_stop2'    => $settings['bg_prog_color_color_b_stop'],
        'prog_bg_grad_angle'     => $settings['bg_prog_color_gradient_angle'],
        'prog_in_color_type'     => $settings['in_prog_color_background'],
        'prog_in_color_value'    => $settings['in_prog_color_color'],
        'prog_in_color_stop1'    => $settings['in_prog_color_color_stop'],
        'prog_in_sec_color_grad' => $settings['in_prog_color_color_b'],
        'prog_in_color_stop2'    => $settings['in_prog_color_color_b_stop'],
        'prog_in_grad_angle'     => $settings['in_prog_color_gradient_angle'],
        'prog_bg_height'         => $settings['prog_bg_height'],
        'prog_in_height'         => $settings['prog_in_height'],
        'text_icon'              => $icon_value,
    );

    // get the shortcode base blog page
    echo auxin_widget_progressbar_callback( $args );

  }

}
