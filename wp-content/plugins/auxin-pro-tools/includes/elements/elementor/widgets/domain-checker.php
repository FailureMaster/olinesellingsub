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
use Elementor\Group_Control_Text_Shadow;
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
class DomainChecker extends Widget_Base {

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
        return 'aux_domain_checker';
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
        return __('Domain Checker', PLUGIN_DOMAIN );
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
        return 'eicon-url auxin-badge-pro';
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

        $this->start_controls_section(
            'label_section',
            array(
                'label'      => __('Label', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'button_text',
            array(
                'label'       => __( 'Text', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Search',
                'label_block' => true
            )
        );

        $this->add_control(
            'palceholder_text',
            array(
                'label'       => __( 'Input Placeholder', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Enter Your Domain Here',
                'label_block' => true
            )
        );

        $this->end_controls_section();

        /*  button_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'button_style_section',
            array(
                'label'     => __('Button', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->start_controls_tabs( 'button_background_tab' );

        $this->start_controls_tab(
            'button_bg_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'button_background',
                'label' => __( 'Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'button_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_bg_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name' => 'hover_button_background',
                'label' => __( 'Background', PLUGIN_DOMAIN ),
                'types' => array( 'classic', 'gradient' ),
                'selector' => '{{WRAPPER}} .aux-button .aux-overlay::after',
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'hover_button_box_shadow',
                'selector'  => '{{WRAPPER}} .aux-button:hover'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_text_heading',
            array(
                'label'     => __( 'Button Text', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before'
            )
        );

        $this->start_controls_tabs( 'button_text_style' );

        $this->start_controls_tab(
            'button_text_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_control(
            'btn_text_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button span' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'btn_text_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-button',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'button_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-button span'
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_text_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_control(
            'hover_btn_text_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button:hover .aux-button span' => 'color: {{VALUE}};',
                )
            )
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            array(
                'name' => 'hover_btn_text_shadow',
                'label' => __( 'Text Shadow', PLUGIN_DOMAIN ),
                'selector' => '{{WRAPPER}} .aux-button:hover',
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'hover_button_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-button span'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'button_padding',
            array(
                'label'      => __( 'Padding', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->end_controls_section();


        /*  loader_style_section
        /*-------------------------------------*/

        $this->start_controls_section(
            'loader_style_section',
            array(
                'label'     => __('Loader', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'loader_color',
            array(
                'label' => __( 'Color', PLUGIN_DOMAIN ),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-button path, .aux-button rect' => 'fill: {{VALUE}};',
                )
            )
        );

        $this->add_control(
            'loader_size',
            array(
                'label'       => __( 'Size', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '24',
                'min'         => 16,
                'step'        => 1
            )
        );

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

        ob_start();
        ?>
        <div class="aux-domain-checker">
            <div class="aux-input-group">
                <form method="post">
                    <input type="text" placeholder="<?php echo esc_attr( $settings['palceholder_text'] ); ?>" class="form-control" autocomplete="off">
                    <button type="submit" class="aux-button aux-black aux-btn-loader">
                        <span><?php echo $settings['button_text']; ?></span>
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                             width="<?php echo $settings['loader_size']; ?>px" height="<?php echo $settings['loader_size']; ?>px" viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
                          <path opacity="0.2" fill="#ffffff" d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946
                              s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634
                              c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
                          <path fill="#ffffff" d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0
                              C22.32,8.481,24.301,9.057,26.013,10.047z">
                            <animateTransform attributeType="xml"
                                attributeName="transform"
                                type="rotate"
                                from="0 20 20"
                                to="360 20 20"
                                dur="0.5s"
                                repeatCount="indefinite"/>
                          </path>
                        </svg>
                    </button>
                </form>
            </div>
            <div class="aux-results"></div>
        </div>
        <script>
        ;(function($){
            $(function(){
                $('.aux-domain-checker').on('submit', function(event) {
                    event.preventDefault();
                    var $this  = $(this);
                    var domain = $('.form-control', $this).val();
                    $.ajax({
                        type      :'POST',
                        dataType  : 'json',
                        url       : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                        data      :{
                            action: 'aux_domain_checker',
                            domain: domain,
                            nonce : '<?php echo wp_create_nonce( 'aux-domain-checker' ); ?>'
                        },
                        beforeSend:function(){
                            // Add progress status class to button
                            $('.aux-button' , $this).addClass( 'aux-svg-progress' ).prop('disabled', true);
                        }
                    }).then(function( response ) {
                        $('.aux-button' , $this).removeClass( 'aux-svg-progress' ).prop('disabled', false);
                        if( response.success ) {
                            $( '.aux-results', $this ).addClass( "aux-success" ).removeClass( "aux-error" ).html( response.data );
                        } else {
                            $( '.aux-results', $this ).addClass( "aux-error" ).removeClass( "aux-success" ).html( response.data );
                        }
                    });
                });
            });
        })( jQuery );
        </script>
        <?php
        echo ob_get_clean();
    }

}
