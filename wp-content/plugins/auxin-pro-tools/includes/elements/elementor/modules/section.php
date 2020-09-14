<?php
namespace Auxin\Plugin\Pro\Elementor\Modules;

use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Control_Media;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;


class Section {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    function __construct(){
        // Modify section render
        //add_action( 'elementor/frontend/section/before_render', array( $this, 'modify_render' ) );

        // Add new controls
        add_action( "elementor/element/section/section_typo/after_section_end", array( $this, 'add_inner_container_controls' ), 90 );
    }

    /**
     * Return an instance of this class.
     *
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {
        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }


    /**
     * Modify the render of section element
     *
     * @param  Element_Section $section Instance of Section element
     *
     * @return void
     */
    public function modify_render( $section ){

    }

    /**
     * Add option for styling the inner container
     *
     * @return void
     */
    public function add_inner_container_controls( $widget ){

        $widget->start_controls_section(
            'aux_pro_inner_container_section',
            array(
                'label'     => __( 'Box Container', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $widget->add_control(
            'aux_inner_container_description',
            array(
                'raw'             => __( 'Options to style the inner container. Use the following options if the section `content width` is set to `boxed`.', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
                'separator'       => 'none'
            )
        );

        $widget->add_group_control(
            Group_Control_Border::get_type(),
            array(
                'name'      => 'inner_container_border',
                'selector'  => '{{WRAPPER}} > .elementor-container > .elementor-row',
                'separator' => 'before'
            )
        );

        /*$widget->add_control(
            'inner_container_border_radius',
            array(
                'label'      => __( 'Border radius', PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => array('px', 'em', '%'),
                'selectors'  => array(
                    '{{WRAPPER}} > .elementor-container > .elementor-row'
                ),
                'separator' => 'before'
            )
        );*/

        $widget->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            array(
                'name'      => 'inner_container_shadow',
                'selector'  => '{{WRAPPER}} > .elementor-container > .elementor-row',
                'separator' => 'before'
            )
        );

        $widget->add_group_control(
            Group_Control_Background::get_type(),
            array(
                'name'      => 'inner_container_backgound',
                'label'     => __( 'Background', PLUGIN_DOMAIN ),
                'selector'  => '{{WRAPPER}} > .elementor-container > .elementor-row',
                'separator' => 'before'
            )
        );

        $widget->add_responsive_control(
            'aux_inner_container_max_height',
            array(
                'label'      => __('Max Height',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', '%'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1500,
                        'step' => 10
                    ),
                    '%' => array(
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1
                    ),
                    'em' => array(
                        'min'  => 0,
                        'max'  => 200,
                        'step' => 1
                    )
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-container > .elementor-row' => 'max-height:{{SIZE}}{{UNIT}};'
                ),
                'separator' => 'before'
            )
        );

        $widget->add_responsive_control(
            'aux_inner_container_is_masked',
            array(
                'label'    => __( 'Mask Overlap', PLUGIN_DOMAIN ),
                'type'     => Controls_Manager::SELECT,
                'options'  => array(
                    'yes'  => __( 'Yes', PLUGIN_DOMAIN ),
                    ''     => __( 'No', PLUGIN_DOMAIN )
                ),
                'default'  => '',
                'selectors'=> array(
                    '{{WRAPPER}} > .elementor-container' => 'overflow:hidden;'
                ),
                'return_value' => 'yes'
            )
        );

        $widget->end_controls_section();
    }

}
