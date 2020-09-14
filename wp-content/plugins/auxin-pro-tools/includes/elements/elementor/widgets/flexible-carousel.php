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
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Auxin\Plugin\CoreElements\Elementor\Modules\QueryControl\Controls\Group_Control_Related;
use Elementor\Repeater;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'FlexibleCarousel' widget.
 *
 * Elementor widget that displays an 'FlexibleCarousel' with lightbox.
 *
 * @since 1.0.0
 */
class FlexibleCarousel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'FlexibleCarousel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_flexible_carousel';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'FlexibleCarousel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Flexible Carousel', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'FlexibleCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-carousel auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'FlexibleCarousel' widget icon.
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
     * Register 'FlexibleCarousel' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  layout_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'general_section',
            array(
                'label' => __('General', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'template',
            array(
                'label'          => __( 'Select a template', PLUGIN_DOMAIN ),
                'type'           => Controls_Manager::SELECT,
                'default'        => ' ',
                'options'        => auxin_get_elementor_templates_list('section')
            )
        );

        $this->add_control(
            'slides',
            array(
                'label'  => __( 'Slides', PLUGIN_DOMAIN ),
                'type'   => Controls_Manager::REPEATER,
                'fields' => array_values( $repeater->get_controls() ),
                'default' => array(),
            )
        );

        $this->add_responsive_control(
            'columns',
            array(
                'label'          => __( 'Columns', PLUGIN_DOMAIN ),
                'type'           => Controls_Manager::SELECT,
                'default'        => '4',
                'tablet_default' => 'inherit',
                'mobile_default' => '1',
                'options'        => array(
                    'inherit' => __( 'Inherited from larger', PLUGIN_DOMAIN ),
                    '1'       => '1',
                    '2'       => '2',
                    '3'       => '3',
                    '4'       => '4',
                    '5'       => '5',
                    '6'       => '6'
                ),
                'frontend_available' => true,
            )
        );

        $this->add_control(
            'carousel_space',
            array(
                'label'       => __( 'Column space', PLUGIN_DOMAIN ),
                'description' => __( 'Specifies horizontal space between items (pixel).', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '25'
            )
        );

        $this->add_control(
            'carousel_navigation',
            array(
                'label'       => __('Navigation type', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'peritem',
                'options'     => array(
                   'peritem' => __('Move per column', PLUGIN_DOMAIN),
                   'perpage' => __('Move per page', PLUGIN_DOMAIN),
                   'scroll'  => __('Smooth scroll', PLUGIN_DOMAIN)
                )
            )
        );

        $this->add_control(
            'carousel_navigation_control',
            array(
                'label'       => __('Navigation control', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'bullets',
                'options'     => array(
                    ''        => __('None', PLUGIN_DOMAIN),
                    'arrows'  => __('Arrows', PLUGIN_DOMAIN),
                    'bullets' => __('Bullets', PLUGIN_DOMAIN)
                )
            )
        );

        $this->add_control(
            'carousel_nav_control_pos',
            array(
                'label'       => __('Control Position', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'center',
                'options'     => array(
                   'center'         => __('Center', PLUGIN_DOMAIN),
                   'side'           => __('Side', PLUGIN_DOMAIN)
                ),
                'condition'   => array(
                    'carousel_navigation_control' => 'arrows',
                )
            )
        );

        $this->add_control(
            'carousel_nav_control_skin',
            array(
                'label'       => __('Control Skin', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'boxed',
                'options'     => array(
                   'boxed' => __('boxed', PLUGIN_DOMAIN),
                   'long'  => __('Long Arrow', PLUGIN_DOMAIN)
                ),
                'condition'   => array(
                    'carousel_navigation_control' => 'arrows',
                )
            )
        );

        $this->add_control(
            'carousel_nav_arrow_size',
            array(
                'label'       => __('Arrow Size', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'small',
                'options'     => array(
                   'small'  => __('Small', PLUGIN_DOMAIN),
                   'medium' => __('Medium', PLUGIN_DOMAIN),
                   'large'  => __('Large', PLUGIN_DOMAIN)
                ),
                'condition'   => array(
                    'carousel_navigation_control' => 'arrows',
                )
            )
        );

        $this->add_control(
            'carousel_loop',
            array(
                'label'        => __('Loop navigation',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'carousel_autoplay',
            array(
                'label'        => __('Autoplay carousel',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no'
            )
        );

        $this->add_control(
            'carousel_autoplay_delay',
            array(
                'label'       => __( 'Autoplay delay', PLUGIN_DOMAIN ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2'
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

        // Defining default attributes
        $default_atts = array(
            'slides'                      => array(),
            'post_column'                 => '',
            'columns'                     => 4,
            'columns_tablet'              => 'inherit',
            'columns_mobile'              => '1',
            'extra_classes'               => '',
            'extra_column_classes'        => '',
            'custom_el_id'                => '',
            'carousel_space'              => '30',
            'carousel_autoplay'           => false,
            'carousel_autoplay_delay'     => '2',
            'carousel_navigation'         => 'peritem',
            'carousel_navigation_control' => 'arrows',
            'carousel_nav_control_pos'    => 'center',
            'carousel_nav_arrow_size'     => 'small',
            'carousel_nav_control_skin'   => 'boxed',
            'carousel_loop'               => 1,
            'universal_id'                => '',
            'base'                        => 'aux_flexible_carousel',
            'base_class'                  => 'aux-widget-flexible-carousel'
        );

        $result = auxin_get_widget_scafold( $this->get_settings_for_display(), $default_atts, null );
        extract( $result['parsed_atts'] );

        // widget header ------------------------------
        echo $result['widget_header'];
        echo $result['widget_title'];

        $phone_break_point     = 767;
        $tablet_break_point    = 1025;

        $columns_tablet  = ('inherit' == $columns_tablet ) ? $columns : $columns_tablet;
        $columns_mobile  = ('inherit' == $columns_mobile  )  ? $columns_tablet : $columns_mobile;

        $this->add_render_attribute(
            'column_attr',
            array(
                'data-element-id'    => esc_attr( $universal_id ),
                'class'              => 'master-carousel aux-no-js aux-mc-before-init' . ' aux-' . esc_attr( $carousel_nav_control_pos ) . '-control',
                'data-columns'       => esc_attr( $columns ),
                'data-autoplay'      => auxin_is_true( $carousel_autoplay ) ? 'true' : '',
                'data-delay'         => auxin_is_true( $carousel_autoplay ) ?  esc_attr( $carousel_autoplay_delay ) : '',
                'data-navigation'    => esc_attr( $carousel_navigation ),
                'data-space'         => isset( $carousel_space ) ? esc_attr( $carousel_space ) : 0 ,
                'data-loop'          => auxin_is_true( $carousel_loop ) ? esc_attr( $carousel_loop ) : '',
                'data-wrap-controls' => 'true',
                'data-bullets'       => 'bullets' == $carousel_navigation_control ? 'true' : 'false',
                'data-bullet-class'  => 'aux-bullets aux-small aux-mask',
                'data-arrows'        => 'arrows' == $carousel_navigation_control ? 'true' : 'false',
                'data-same-height'   => 'true'
            )
        );

        if ( 'inherit' != $columns_tablet || 'inherit' != $columns_mobile ) {
            $this->add_render_attribute( 'column_attr', 'data-responsive', esc_attr( ( 'inherit' != $columns_tablet  ? $tablet_break_point . ':' . $columns_tablet . ',' : '' ).
            ( 'inherit' != $columns_mobile   ? $phone_break_point  . ':' . $columns_mobile : '' ) ) );
        }

        $this->add_render_attribute(
            'item_attr',
            array(
                'class' => 'aux-col aux-mc-item'
            )
        );

		if ( ! empty( $slides ) ) {
            echo '<div '. $this->get_render_attribute_string( 'column_attr' ) .' >';
			foreach (  $slides as $slide ) {
                if ( ! isset( $slide['template'] ) ) {
                    continue;
                }
                $template_ID = is_numeric( $slide['template'] ) ? $slide['template'] : get_page_by_path( $slide['template'], OBJECT, 'elementor_library' )->ID ;
                if( $template_ID !== ' ' && get_post_status( $template_ID ) ){
                    echo sprintf( '<div %s>%s</div>', $this->get_render_attribute_string( 'item_attr' ), Plugin::instance()->frontend->get_builder_content_for_display( $template_ID ) );
                }
            }
            if ( 'arrows' == $carousel_navigation_control ) {
                $arrow_size_class = 'aux-' . $carousel_nav_arrow_size;
                if ( 'boxed' === $carousel_nav_control_skin ) :?>
                    <div class="aux-carousel-controls">
                        <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                            <span class="aux-svg-arrow <?php echo $arrow_size_class; ?>-right"></span>
                            <span class="aux-hover-arrow aux-white aux-svg-arrow <?php echo $arrow_size_class; ?>-right"></span>
                        </div>
                        <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                            <span class="aux-svg-arrow <?php echo $arrow_size_class; ?>-left"></span>
                            <span class="aux-hover-arrow aux-white aux-svg-arrow <?php echo $arrow_size_class; ?>-left"></span>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="aux-carousel-controls">
                        <div class="aux-next-arrow">
                            <span class="aux-svg-arrow aux-l-right"></span>
                        </div>
                        <div class="aux-prev-arrow">
                            <span class="aux-svg-arrow aux-l-left"></span>

                        </div>
                    </div>
                <?php  endif;
            }
			echo '</div>';
        }

        // widget footer ------------------------------
        echo $result['widget_footer'];

    }

}
