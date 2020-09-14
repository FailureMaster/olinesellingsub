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
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly.
}

/**
 * Elementor 'FAQ' widget.
 *
 * Elementor widget that displays an 'FAQ' with lightbox.
 *
 * @since 1.0.0
 */
class FAQ extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve 'FAQ' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_faq';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'FAQ' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'FAQ', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'FAQ' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-accordion auxin-badge-pro';
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
     * Retrieve the terms in a given taxonomy or list of taxonomies.
     *
     * Retrieve 'FAQ' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_terms() {
        // Get terms
        $terms = get_terms(
            array(
                'taxonomy'   => 'faq-category',
                'orderby'    => 'count',
                'hide_empty' => true
            )
        );

        // Then create a list
        $list  = array( ' ' => __('All Categories', PLUGIN_DOMAIN ) ) ;

        if ( ! is_wp_error( $terms ) && is_array( $terms ) ){
            foreach ( $terms as $key => $value ) {
                $list[$value->term_id] = $value->name;
            }
        }

        return $list;
    }

    /**
     * Register 'FAQ' widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls() {

        /*-----------------------------------------------------------------------------------*/
        /*  query_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'query_section',
            array(
                'label'      => __('Query', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'cat',
            array(
                'label'       => __('Categories', PLUGIN_DOMAIN),
                'description' => __('Specifies a category that you want to show faq items from it.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SELECT2,
                'multiple'    => true,
                'options'     => $this->get_terms(),
                'default'     => array( ' ' ),
            )
        );

        $this->add_control(
            'num',
            array(
                'label'       => __('Number of items to show', PLUGIN_DOMAIN),
                'description' => __('Leave it empty to show all items', PLUGIN_DOMAIN),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '8',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'order_by',
            array(
                'label'       => __('Order by', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'date',
                'options'     => array(
                    'date'            => __('Date', PLUGIN_DOMAIN),
                    'menu_order date' => __('Menu Order', PLUGIN_DOMAIN),
                    'title'           => __('Title', PLUGIN_DOMAIN),
                    'ID'              => __('ID', PLUGIN_DOMAIN),
                    'rand'            => __('Random', PLUGIN_DOMAIN),
                    'comment_count'   => __('Comments', PLUGIN_DOMAIN),
                    'modified'        => __('Date Modified', PLUGIN_DOMAIN),
                    'author'          => __('Author', PLUGIN_DOMAIN),
                    'post__in'        => __('Inserted Post IDs', PLUGIN_DOMAIN)
                ),
            )
        );

        $this->add_control(
            'order',
            array(
                'label'       => __('Order', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'DESC',
                'options'     => array(
                    'DESC'          => __('Descending', PLUGIN_DOMAIN),
                    'ASC'           => __('Ascending', PLUGIN_DOMAIN),
                ),
            )
        );

        $this->add_control(
            'only_posts__in',
            array(
                'label'       => __('Only FAQs',PLUGIN_DOMAIN ),
                'description' => __('If you intend to display ONLY specific FAQs, you should specify them here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'include',
            array(
                'label'       => __('Include FAQs',PLUGIN_DOMAIN ),
                'description' => __('If you intend to include additional FAQs, you should specify them here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'exclude',
            array(
                'label'       => __('Exclude FAQs',PLUGIN_DOMAIN ),
                'description'       => __('If you intend to exclude specific FAQs from result, you should specify the FAQs here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT
            )
        );

        $this->add_control(
            'offset',
            array(
                'label'       => __('Start offset',PLUGIN_DOMAIN ),
                'description' => __('Number of post to displace or pass over.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::NUMBER
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  query_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'paginate_section',
            array(
                'label'      => __('Paginate', PLUGIN_DOMAIN ),
            )
        );

        $this->add_control(
            'paginate',
            array(
                'label'       => __('Paginate',PLUGIN_DOMAIN ),
                'description' => __('Paginates the FAQ items', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SWITCHER,
                'label_on'    => __( 'On', PLUGIN_DOMAIN ),
                'label_off'   => __( 'Off', PLUGIN_DOMAIN ),
                'default'     => 'yes'
            )
        );


        $this->add_control(
            'perpage',
            array(
                'label'       => __('Items number perpage', PLUGIN_DOMAIN ),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '10',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  paginate_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'transition_section',
            array(
                'label' => __('Transition', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_SETTINGS
            )
        );

        $this->add_control(
            'reveal_transition_duration',
            array(
                'label'       => __('Transition duration on reveal',PLUGIN_DOMAIN ),
                'description' => __('The transition duration while the element is going to be appeared (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '600',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'reveal_between_delay',
            array(
                'label'       => __('Delay between reveal',PLUGIN_DOMAIN ),
                'description' => __('The delay between each sequence of revealing transitions (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '70',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'hide_transition_duration',
            array(
                'label'       => __('Transition duration on hide',PLUGIN_DOMAIN ),
                'description' => __('The transition duration while the element is going to be hidden (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '600',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->add_control(
            'hide_between_delay',
            array(
                'label'       => __('Delay between hide',PLUGIN_DOMAIN ),
                'description' => __('The delay between each sequence of hiding transitions (milliseconds).'),
                'label_block' => true,
                'type'        => Controls_Manager::NUMBER,
                'default'     => '70',
                'min'         => 1,
                'step'        => 1
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  deeplink_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'deeplink_section',
            array(
                'label' => __('Deeplink', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_SETTINGS
            )
        );

        $this->add_control(
            'deeplink',
            array(
                'label'        => __('Deeplink', PLUGIN_DOMAIN ),
                'description'  => __('Enables the deeplink feature, it updates URL based on page and filter status.', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'default'      => 'no'
            )
        );

        $this->add_control(
            'deeplink_slug',
            array(
                'label'       => __('Deeplink slug', PLUGIN_DOMAIN ),
                'description' => __('Specifies the deeplink slug value in address bar.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => '',
                'condition'   => array(
                    'deeplink' => 'yes'
                )
            )
        );

        $this->end_controls_section();

        /*-----------------------------------------------------------------------------------*/
        /*  filter_section
        /*-----------------------------------------------------------------------------------*/

        $this->start_controls_section(
            'filter_section',
            array(
                'label' => __('Filter', PLUGIN_DOMAIN ),
                'tab'   => Controls_Manager::TAB_SETTINGS
            )
        );

        $this->add_control(
            'show_filters',
            array(
                'label'        => __( 'Show filters', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'yes'
            )
        );

        $this->add_control(
            'filter_by',
            array(
                'label'       => __( 'Filter by', PLUGIN_DOMAIN ),
                'description' => __( 'Filter by categories or tags', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'faq-category',
                'options'     => array(
                    'faq-category' => __( 'Categories', PLUGIN_DOMAIN ),
                    'faq-tag'      => __( 'Tags', PLUGIN_DOMAIN)
                ),
                'condition'   => array(
                    'show_filters' => 'yes',
                )
            )
        );

        $this->add_control(
            'filter_align',
            array(
                'label'       => __('Filter Control Alignment',PLUGIN_DOMAIN ),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'aux-left'      => array(
                        'label'     => __('Left' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-left.svg'
                    ),
                    'aux-center'    => array(
                        'label'     => __('Center' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-mid.svg'
                    ),
                    'aux-right'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    )
                ),
                'default'     => 'aux-center',
                'condition'   => array(
                    'show_filters' => 'yes',
                )
            )
        );

        $this->add_control(
            'accor_layout',
            array(
                'label'       => __('FAQ Accordion Layout', PLUGIN_DOMAIN),
                'type'        => 'aux-visual-select',
                'options'     => array(
                    'simple'      => array(
                        'label'     => __('Left' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-left.svg'
                    ),
                    'plus-indicator'    => array(
                        'label'     => __('Center' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-mid.svg'
                    ),
                    'clean-border'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    ),
                    'clean'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    )
                ),
                'default'     => 'simple',
                'condition'   => array(
                    'show_filters' => 'yes',
                )
            )
        );

        $this->add_control(
            'filter_style',
            array(
                'label'       => __( 'Filter button style', PLUGIN_DOMAIN ),
                'description' => __( 'Style of filter buttons.', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'aux-slideup',
                'options'     => array(
                    'aux-slideup'   => __( 'Slide up', PLUGIN_DOMAIN ),
                    'aux-fill'      => __( 'Fill', PLUGIN_DOMAIN ),
                    'aux-cube'      => __( 'Cube', PLUGIN_DOMAIN ),
                    'aux-underline' => __( 'Underline', PLUGIN_DOMAIN ),
                    'aux-overlay'   => __( 'Float frame', PLUGIN_DOMAIN ),
                    'aux-borderd'   => __( 'Borderd', PLUGIN_DOMAIN ),
                    'aux-overlay aux-underline-anim'    => __( 'Float underline', PLUGIN_DOMAIN )
                ),
                'condition'   => array(
                    'show_filters' => 'yes',
                )
            )
        );

        $this->end_controls_section();

       /*-----------------------------------------------------------------------------------*/
        /*  Style TAB
        /*-----------------------------------------------------------------------------------*/

        /*   Title Bar Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_bar_section',
            array(
                'label' => __( 'Title Bar', PLUGIN_DOMAIN ),
                'tab' => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'title_bar_cursor',
            array(
                'label'   => __( 'Mouse Cursor', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'default' => __( 'Default', PLUGIN_DOMAIN ),
                    'pointer' => __( 'Pointer', PLUGIN_DOMAIN ),
                    'zoom-in' => __( 'Zoom', PLUGIN_DOMAIN ),
                    'help'    => __( 'Help', PLUGIN_DOMAIN )
                ),
                'default'   => 'pointer',
                'selectors'  => array(
                    '{{WRAPPER}} .widget-inner > :not(.active) .aux-faq-item-header' => 'cursor: {{VALUE}};'
                )
            )
        );

        $this->add_responsive_control(
            'title_bar_padding',
            array(
                'label'      => __( 'Padding', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-faq-item-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'title_bar_margin',
            array(
                'label'              => __( 'Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-faq-item-header' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'title_bar_border_radius',
            array(
                'label'      => __( 'Border Radius', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-faq-item-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator'  => 'after'
            )
        );

        $this->start_controls_tabs( 'title_bar_status' );

        $this->start_controls_tab(
            'title_bar_status_normal',
            array(
                'label' => __( 'Normal' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_normal',
                'label'     => __( 'Box Shadow', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_normal',
                'selector'  => '{{WRAPPER}} .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_normal',
                'selector'  => '{{WRAPPER}} .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'title_bar_status_hover',
            array(
                'label' => __( 'Hover' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_hover',
                'label'     => __( 'Box Shadow Normal', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .aux-faq-item-header:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_hover',
                'selector'  => '{{WRAPPER}} .aux-faq-item-header:hover',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_hover',
                'selector'  => '{{WRAPPER}} .aux-faq-item-header:hover',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'title_bar_status_active',
            array(
                'label' => __( 'Selected' , PLUGIN_DOMAIN )
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'title_bar_boxshadow_active',
                'label'     => __( 'Box Shadow Normal', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} .active .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'title_bar_border_active',
                'selector'  => '{{WRAPPER}} .active .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'title_bar_background_active',
                'selector'  => '{{WRAPPER}} .active .aux-faq-item-header',
                'separator' => 'none'
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();


        /*   Title Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'title_style_section',
            array(
                'label'     => __( 'Title', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
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
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-faq-item-header' => 'color: {{VALUE}} !important;'
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
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-faq-item-header:hover' => 'color: {{VALUE}} !important;',
                )
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'title_typography',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_1,
                'selector'  => '{{WRAPPER}} .aux-faq-item-header'
            )
        );

        $this->end_controls_section();

        /*   Content Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'content_style_section',
            array(
                'label'     => __( 'Content', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'content_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-faq-item-content ' => 'color: {{VALUE}}'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'content_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-faq-item-content '
            )
        );

        $this->add_responsive_control(
            'content_padding',
            array(
                'label'      => __( 'Padding', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'separator'  => 'before',
                'selectors'  => array(
                    '{{WRAPPER}} .aux-faq-item-content ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                )
            )
        );

        $this->add_responsive_control(
            'content_margin',
            array(
                'label'              => __( 'Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-faq-item-content ' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->add_control(
            'content_border_radius',
            array(
                'label'      => __( 'Border Radius', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array( 'px', 'em', '%' ),
                'selectors'  => array(
                    '{{WRAPPER}} .aux-faq-item-content ' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow:hidden;'
                ),
                'allowed_dimensions' => 'all',
                'separator' => 'before'
            )
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'content_shadow',
                'selector'  => '{{WRAPPER}} .aux-faq-item-content ',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'content_border',
                'selector'  => '{{WRAPPER}} .aux-faq-item-content ',
                'separator' => 'none'
            )
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'content_background',
                'selector'  => '{{WRAPPER}} .aux-faq-item-content ',
                'separator' => 'none'
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

        // Query Section
        'cat'                        => $settings['cat'],
        'num'                        => $settings['num'],
        'order_by'                   => $settings['order_by'],
        'order'                      => $settings['order'],
        'only_posts__in'             => $settings['only_posts__in'],
        'include'                    => $settings['include'],
        'exclude'                    => $settings['exclude'],
        'offset'                     => $settings['offset'],

        'paginate'                   => $settings['paginate'],
        'perpage'                    => $settings['perpage'],

        'reveal_transition_duration' => $settings['reveal_transition_duration'],
        'reveal_between_delay'       => $settings['reveal_between_delay'],
        'hide_transition_duration'   => $settings['hide_transition_duration'],
        'hide_between_delay'         => $settings['hide_between_delay'],

        'deeplink'                   => $settings['deeplink'],
        'deeplink_slug'              => $settings['deeplink_slug'],

        'show_filters'               => $settings['show_filters'],
        'filter_by'                  => $settings['filter_by'],
        'filter_align'               => $settings['filter_align'],
        'accor_layout'               => $settings['accor_layout'],
        'filter_style'               => $settings['filter_style'],

    );

    echo auxin_widget_faq_callback( $args );

  }

}
