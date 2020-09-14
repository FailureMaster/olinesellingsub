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
 * Elementor 'Price Table' widget.
 *
 * Elementor widget that displays an 'Progressbar' with lightbox.
 *
 * @since 1.0.0
 */
class PriceTable extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'Price Table' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_price_table';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Price Table' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Price Table', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Price Table' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'auxin-badge eicon-price-table';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Price Table' widget categories.
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
            'price_table_header',
            array(
                'label'      => __('Header', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'title',
            array(
                'label'       => __('Title',PLUGIN_DOMAIN ),
                'description' => __('Price Table title, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Enter your title',PLUGIN_DOMAIN),
            )
        );

        $this->add_control(
            'description',
            array(
                'label'       => __('Description',PLUGIN_DOMAIN ),
                'description' => __('Price Table title, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Enter your description',PLUGIN_DOMAIN)
            )
        );

        $this->end_controls_section();
        
        $this->start_controls_section(
            'price_table_price',
            array(
                'label'      => __('Pricing', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'price',
            array(
                'label'       => __('Price',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '99.99'
            )
        );

        $this->add_control(
            'sale',
            array(
                'label'       => __('Sale',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', PLUGIN_DOMAIN ),
                'label_off'   => __( 'Off', PLUGIN_DOMAIN ), 
                'return_value'=> 'on',
                'default'     => 'off'
            )
        );

        $this->add_control(
            'original_price',
            array(
                'label'       => __('Original Price',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '100',
                'condition'   => array(
                    'sale'    => 'on'
                )
            )
        );

        $this->add_control(
            'period',
            array(
                'label'       => __('Period',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Monthly',PLUGIN_DOMAIN)
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_table_features',
            array(
                'label'      => __('Features', PLUGIN_DOMAIN ),
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'feature_text',
            array(
                'label'       => __( 'Text', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'List Item',
                'label_block' => true
            )
        );

        $repeater->add_control(
            'aux_price_table_feature_icon',
            array(
                'label'       => __( 'Icon', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::ICONS,
                'label_block' => true
            )
        );

        $repeater->add_control(
			'feature_icon_color',
			[
				'label' => __( 'Icon Color', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::COLOR,
			]
        );

        $this->add_control(
            'features',
            array(
                'label'   => __( 'Feature Item', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::REPEATER,
                'default' => array(
                    array(
                        'feature_text' => __( 'Feature #1', PLUGIN_DOMAIN ),
                    )
                ),
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ feature_text }}}'
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_table_footer',
            array(
                'label'      => __('Footer', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'       => __( 'Button Text', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __('Button Text',PLUGIN_DOMAIN),
                'label_block' => true
            )
        );

        $this->add_control(
            'button_url',
            array(
                'label'         => __('Link',PLUGIN_DOMAIN ),
                'type'          => Controls_Manager::URL,
                'placeholder'   => '',
                'show_external' => true,
                'label_block'   => true
            )
        );

        $this->add_control(
            'additional_info',
            array(
                'label'       => __( 'Additional Information', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXTAREA,
                'label_block' => true
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'price_table_ribbon',
            array(
                'label'      => __('Ribbon', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'show_ribbon',
            array(
                'label'       => __('Show',PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', PLUGIN_DOMAIN ),
                'label_off'   => __( 'Off', PLUGIN_DOMAIN ), 
                'return_value'=> 'on',
                'default'     => 'off'
            )
        );

        $this->add_control(
            'ribbon_title',
            array(
                'label'       => __( 'Title', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => true,
                'condition' => [
					'show_ribbon' => 'on',
				],
            )
        );

        $this->add_control(
			'ribbon_position',
			[
				'label' => __( 'Position', PLUGIN_DOMAIN ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', PLUGIN_DOMAIN ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', PLUGIN_DOMAIN ),
						'icon' => 'eicon-h-align-right',
					],
                ],
                'default' => 'right',
				'condition' => [
					'show_ribbon' => 'on',
				],
			]
        );
        
        $this->end_controls_section();
        /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'header_style_section',
            array(
                'label'     => __( 'Header', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'header_bg_color',
                'label' => __( 'Header Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => "{{WRAPPER}} .aux-price-table.aux-table-header-section",
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'body_bg_color',
                'label' => __( 'Body Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => "{{WRAPPER}} .aux-table-price-section, {{WRAPPER}} .aux-table-features-section, {{WRAPPER}} .aux-table-footer-section",
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
                    '{{WRAPPER}} .aux-table-header-title span' => 'color: {{VALUE}} !important;',
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
                    '{{WRAPPER}} .aux-table-header-title span:hover' => 'color: {{VALUE}} !important;',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'label' => __( 'Title Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-header-title span',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'title_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-table-header-title span',
            )
        );

        $this->add_responsive_control(
            'title_margin',
            array(
                'label' => __( 'Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-table-header-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'description_typography',
                'label' => __( 'Description Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-header-description span',
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'description_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-table-header-description span',
            )
        );

        $this->add_responsive_control(
            'description_margin',
            array(
                'label' => __( 'Margin', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-table-header-description span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'pricing_style_section',
            array(
                'label'     => __( 'Pricing', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'price_typography',
                'label' => __( 'Price Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-price-amount .aux-price-amount',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'original_price_typography',
                'label' => __( 'Original Price Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-price-amount .aux-sale-amount',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'period_typography',
                'label' => __( 'Period Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-price-period span',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'feature_style_section',
            array(
                'label'     => __( 'Features', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE,
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
                    '{{WRAPPER}} .aux-table-feature-icon ' => 'font-size: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .aux-table-feature-icon ' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'feature_typography',
                'label' => __( 'Feature Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-feature-text',
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'feature_border',
                'selector' => '{{WRAPPER}} .aux-table-feature'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'footer_style_section',
            array(
                'label' => __('Footer', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'button_bg_color',
            array(
                'label' => __( 'Button Background Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-table-footer-button a' => 'background-color: {{VALUE}} !important;',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'button_typography',
                'label' => __( 'Button Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-footer-button a',
            )
        );

        $this->add_responsive_control(
            'button_padding',
            array(
                'label' => __( 'Padding', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors' => array(
                    '{{WRAPPER}} .aux-table-footer-button a ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'additional_info_typography',
                'label' => __( 'Additional Info Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-footer-info span',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ribbon_style_section',
            array(
                'label' => __('Ribbon', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'ribbon_typography',
                'label' => __( 'Ribbon Typography', PLUGIN_DOMAIN ),
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-table-header-ribbon div',
            )
        );

        $this->add_control(
            'ribbon_bg_color',
            array(
                'label' => __( 'Ribbon Background Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-table-header-ribbon div' => 'background-color: {{VALUE}} !important;',
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
    
    foreach ( $settings['features'] as $key => $feature ) {
        $settings['features'][$key]['feature_icon'] =  $feature['aux_price_table_feature_icon']['value']  ;
    }

    $args = array(
        'title'             => $settings['title'],
        'description'       => $settings['description'],
        'price'             => $settings['price'],
        'original_price'    => $settings['original_price'],
        'period'            => $settings['period'],
        'features'          => $settings['features'],
        'button_text'       => $settings['button_text'],
        'button_url'        => $settings['button_url']['url'],
        'show_external'     => $settings['button_url']['is_external'],
        'additional_info'   => $settings['additional_info'],
        'ribbon_title'      => $settings['ribbon_title'],
        'ribbon_position'   => $settings['ribbon_position']
    );

    // get the shortcode base blog page
    echo auxin_widget_price_table_callback( $args );

  }

}
