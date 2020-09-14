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


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'FlexibleRecentPostsGridCarousel' widget.
 *
 * Elementor widget that displays an 'FlexibleRecentPostsGridCarousel' with lightbox.
 *
 * @since 1.0.0
 */
class FlexibleRecentPostsGridCarousel extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'FlexibleRecentPostsGridCarousel' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_flexible_recent_posts';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'FlexibleRecentPostsGridCarousel' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Flexible Posts', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'FlexibleRecentPostsGridCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-posts-grid auxin-badge';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'FlexibleRecentPostsGridCarousel' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-dynamic' );
    }

    /**
     * Register 'FlexibleRecentPostsGridCarousel' widget controls.
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
            'layout_section',
            array(
                'label' => __('Layout', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_LAYOUT
            )
        );

        $this->add_control(
            'post_column',
            array(
                'label'          => __( 'Select Post Column', PLUGIN_DOMAIN ),
                'type'           => Controls_Manager::SELECT,
                'default'        => ' ',
                'options'        => auxin_get_elementor_templates_list('single')
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
            'preview_mode',
            array(
                'label'       => __('Display items as', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'grid',
                'options'     => array(
                    'grid'            => __( 'Grid', PLUGIN_DOMAIN ),
                    'carousel'        => __( 'Carousel', PLUGIN_DOMAIN )
                )
            )
        );

        $this->add_control(
            'carousel_space',
            array(
                'label'       => __( 'Column space', PLUGIN_DOMAIN ),
                'description' => __( 'Specifies horizontal space between items (pixel).', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '25',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
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
                ),
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
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
                ),
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
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
            'carousel_loop',
            array(
                'label'        => __('Loop navigation',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'yes',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
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
                'default'      => 'no',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
            )
        );

        $this->add_control(
            'carousel_autoplay_delay',
            array(
                'label'       => __( 'Autoplay delay', PLUGIN_DOMAIN ),
                'description' => __('Specifies the delay between auto-forwarding in seconds.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER,
                'default'     => '2',
                'condition'   => array(
                    'preview_mode' => array( 'carousel', 'carousel-modern' ),
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  query_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'query_section',
            array(
                'label'      => __('Query', PLUGIN_DOMAIN ),
            )
        );

		$this->add_group_control(
			Group_Control_Related::get_type(),
			[
				'name' => 'posts',
				'presets' => [ 'full' ],
				'exclude' => [
					'posts_per_page', //use the one from Layout section
				],
			]
        );

        $this->add_control(
            'num',
            array(
                'label'       => __('Number of posts to show', PLUGIN_DOMAIN),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '8',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->end_controls_section();


        /*-----------------------------------------------------------------------------------*/
        /*  pagination
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'paginate_section',
            array(
                'label'      => __('Pagination', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'display_pagination',
            array(
                'label'        => __('Display Pagination',PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
            )
        );

        $this->add_control(
            'pagination_skin',
            array(
                'label'       => __('Pagination Skin',PLUGIN_DOMAIN ),
                'type'        => 'aux-visual-select',
                'style_items' => 'max-width:45%;',
                'options'     => array(
                    'aux-round aux-page-no-border' => array(
                        'label' => __( 'Round, No Page Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-5.svg'
                    ),
                    'aux-round aux-no-border' => array(
                        'label' => __( 'Round, No Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-6.svg'
                    ),
                    'aux-round' => array(
                        'label' => __( 'Round, With Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-4.svg'
                    ),
                    'aux-square aux-page-no-border' => array(
                        'label' => __( 'Square, No Page Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-2.svg'
                    ),
                    'aux-square aux-no-border' => array(
                        'label' => __( 'Square, No Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-3.svg'
                    ),
                    'aux-square' => array(
                        'label' => __( 'Square, With Border', PLUGIN_DOMAIN ),
                        'image' => AUXIN_URL . 'images/visual-select/paginationstyle-1.svg'
                    )
                ),
                'condition'   => array(
                    'display_pagination' => 'yes',
                ),
                'default'     => 'aux-round aux-page-no-border',
                'separator'   => 'after'
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name' => 'pagination_typography',
                'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .pagination li, {{WRAPPER}} .pagination li a',
                'condition'   => array(
                    'display_pagination' => 'yes',
                ),
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
        $settings['widget_base'] = $this;
        echo auxin_widget_flexible_recent_posts_callback( $settings, null );
    }

}
