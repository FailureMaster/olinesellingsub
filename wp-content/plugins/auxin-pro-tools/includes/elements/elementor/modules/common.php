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


class Common {

    /**
     * Instance of this class.
     *
     * @var      object
     */
    protected static $instance = null;


    function __construct(){
        // Add new controls to advanced tab globally
        add_action( "elementor/element/after_section_end", array( $this, 'add_parallax_controls_section'   ), 15, 3 );
        add_action( "elementor/element/after_section_end", array( $this, 'add_sticky_controls_section' ), 17, 3 );
        add_action( "elementor/element/after_section_end", array( $this, 'add_pagecover_controls_section' ), 18, 3 );

        // Renders attributes for all Elementor Elements
        add_action( 'elementor/frontend/widget/before_render' , array( $this, 'render_attributes' ) );
        add_action( 'elementor/frontend/column/before_render' , array( $this, 'render_attributes' ) );
        add_action( 'elementor/frontend/section/before_render', array( $this, 'render_attributes' ) );
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
     * Add extra controls to advanced section
     *
     * @return void
     */
    public function add_parallax_controls_section( $widget, $section_id, $args ){

        if( in_array( $widget->get_name(), array('section') ) ){
            return;
        }

        // Hook element section
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }


        // Adds parallax options to advanced section
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_pro_common_parallax_section',
            array(
                'label'     => __( 'Parallax Pro', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'aux_parallax_enabled',
            array(
                'label'        => __( 'Enable Parallax', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            )
        );

        $widget->add_control(
            'aux_parallax_el_origin',
            array(
                'label'   => __( 'Parallax Origin', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'top'     => __( 'Top', PLUGIN_DOMAIN ),
                    'middle'  => __( 'Middle', PLUGIN_DOMAIN ),
                    'bottom'  => __( 'Bottom', PLUGIN_DOMAIN )
                ),
                'default'   => 'middle',
                'condition' => array(
                    'aux_parallax_enabled' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_el_depth',
            array(
                'label'      => __('Parallax Velocity',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 0.15,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => -1,
                        'max'  => 1,
                        'step' => 0.01
                    )
                ),
                'condition' => array(
                    'aux_parallax_enabled' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_disable_on',
            array(
                'label'   => __( 'Disable Parallax', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'tablet'  => __( 'On Mobile and Tablet', PLUGIN_DOMAIN ),
                    'phone'   => __( 'On Mobile', PLUGIN_DOMAIN ),
                    'custom'  => __( 'Under a screen size', PLUGIN_DOMAIN )
                ),
                'default'   => 'tablet',
                'condition' => array(
                    'aux_parallax_enabled' => 'yes'
                ),
                'label_block' => true
            )
        );

        $widget->add_control(
            'aux_parallax_disable_under',
            array(
                'label'      => __('Disable under size',PLUGIN_DOMAIN ),
                'description'=> __('Specifies a screen width under which the parallax will be disabled automatically. (in pixels)',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 768,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1400,
                        'step' => 1
                    )
                ),
                'condition' => array(
                    'aux_parallax_enabled'    => 'yes',
                    'aux_parallax_disable_on' => 'custom'
                )
            )
        );

        $widget->end_controls_section();

        $widget->start_controls_section(
            'aux_pro_parallax_anims_section',
            array(
                'label'     => __( 'Parallax Animations', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );
        
        $widget->add_control(
            'aux_parallax_anims_enable',
            array(
                'label'        => __( 'Enable Parallax Animations', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            )
        );

        $widget->add_control(
            'aux_parallax_in_anims',
            array(
                'label'   => __( 'Move in Animations', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''               => 'None',
                    'moveVertical'   => 'Vertical Move',
                    'moveHorizontal' => 'Horizontal Move',
                    'fade'           => 'Fade',
                    'fadeTop'        => 'Fade From Top',
                    'fadeBottom'     => 'Fade From Bottom',
                    'fadeRight'      => 'Fade From Right',
                    'fadeLeft'       => 'Fade From Left',
                    'slideRight'     => 'Slide From Right',
                    'slideLeft'      => 'Slide From Left',
                    'slideTop'       => 'Slide From Top',
                    'slideBottom'    => 'Slide From Bottom',
                    'maskTop'        => 'Mask From Top',
                    'maskBottom'     => 'Mask From Bottom',
                    'maskRight'      => 'Mask From Right',
                    'maskLeft'       => 'Mask From Left',
                    'rotateIn'       => 'Rotate In',
                    'rotateOut'      => 'Rotate Out',
                    'scale'          => 'Scale',
                ),
                'default'            => '',
                'label_block'        => false,
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_out_anims',
            array(
                'label'   => __( 'Move out Animations', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''               => 'None',
                    'moveVertical'   => 'Vertical Move',
                    'moveHorizontal' => 'Horizontal Move',
                    'fade'           => 'Fade',
                    'fadeTop'        => 'Fade From Top',
                    'fadeBottom'     => 'Fade From Bottom',
                    'fadeRight'      => 'Fade From Right',
                    'fadeLeft'       => 'Fade From Left',
                    'slideRight'     => 'Slide From Right',
                    'slideLeft'      => 'Slide From Left',
                    'slideTop'       => 'Slide From Top',
                    'slideBottom'    => 'Slide From Bottom',
                    'maskTop'        => 'Mask From Top',
                    'maskBottom'     => 'Mask From Bottom',
                    'maskRight'      => 'Mask From Right',
                    'maskLeft'       => 'Mask From Left',
                    'rotateIn'       => 'Rotate In',
                    'rotateOut'      => 'Rotate Out',
                    'scale'          => 'Scale',
                ),
                'default'            => '',
                'label_block'        => false,
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );
            
        $widget->add_control(
            'aux_parallax_horizontal_transform',
            array(
                'label'           => __( 'Horizontal Transform', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'label_block'        => true,
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 50
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 200,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_vertical_transform',
            array(
                'label'           => __( 'Vertical Transform', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 50
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 200,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_rotate_transform',
            array(
                'label'           => __( 'Rotate', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 360,
                        'step' => 10
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 90,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );


        $widget->add_control(
            'aux_parallax_scale_transform',
            array(
                'label'           => __( 'Scale', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 2.5,
                        'step' => 0.1
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 1,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_animation_easing',
            array(
                'label'   => __( 'Easing', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    ''                       => 'Default',
                    'initial'                => 'Initial',

                    'linear'                 => 'Linear',
                    'ease-out'               => 'Ease Out',
                    '0.19,1,0.22,1'          => 'Ease In Out',

                    '0.47,0,0.745,0.715'     => 'Sine In',
                    '0.39,0.575,0.565,1'     => 'Sine Out',
                    '0.445,0.05,0.55,0.95'   => 'Sine In Out',

                    '0.55,0.085,0.68,0.53'   => 'Quad In',
                    '0.25,0.46,0.45,0.94'    => 'Quad Out',
                    '0.455,0.03,0.515,0.955' => 'Quad In Out',

                    '0.55,0.055,0.675,0.19'  => 'Cubic In',
                    '0.215,0.61,0.355,1'     => 'Cubic Out',
                    '0.645,0.045,0.355,1'    => 'Cubic In Out',

                    '0.895,0.03,0.685,0.22'  => 'Quart In',
                    '0.165,0.84,0.44,1'      => 'Quart Out',
                    '0.77,0,0.175,1'         => 'Quart In Out',

                    '0.895,0.03,0.685,0.22'  => 'Quint In',
                    '0.895,0.03,0.685,0.22'  => 'Quint Out',
                    '0.895,0.03,0.685,0.22'  => 'Quint In Out',

                    '0.95,0.05,0.795,0.035'  => 'Expo In',
                    '0.19,1,0.22,1'          => 'Expo Out',
                    '1,0,0,1'                => 'Expo In Out',

                    '0.6,0.04,0.98,0.335'    => 'Circ In',
                    '0.075,0.82,0.165,1'     => 'Circ Out',
                    '0.785,0.135,0.15,0.86'  => 'Circ In Out',

                    '0.6,-0.28,0.735,0.045'  => 'Back In',
                    '0.175,0.885,0.32,1.275' => 'Back Out',
                    '0.68,-0.55,0.265,1.55'  => 'Back In Out'
                ),
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-widget-container' => 'transition-timing-function:cubic-bezier({{VALUE}});'
                ),
                'default'      => '',
                'return_value' => '',
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_animation_duration',
            array(
                'label'     => __( 'Duration', PLUGIN_DOMAIN ) . ' (ms)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1000,
                'min'       => 0,
                'step'      => 100,
                'selectors'    => array(
                    '{{WRAPPER}} > .elementor-widget-container' => 'transition-duration:{{SIZE}}ms; transition-property: all'
                ),
                'render_type' => 'template',
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_animation_delay',
            array(
                'label'     => __( 'Delay', PLUGIN_DOMAIN ) . ' (ms)',
                'type'      => Controls_Manager::NUMBER,
                'default'   => '',
                'min'       => 0,
                'step'      => 100,
                'selectors' => array(
                    '{{WRAPPER}} > .elementor-widget-container' => 'transition-delay:{{SIZE}}ms;'
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_viewport_top_origin',
            array(
                'label'           => __( 'Viewport Top Origin', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 0.5,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_viewport_bottom_origin',
            array(
                'label'           => __( 'Viewport Bottom Origin', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 0.5,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_parallax_element_origin',
            array(
                'label'           => __( 'Element Top Origin', PLUGIN_DOMAIN ),
                'type'            => Controls_Manager::SLIDER,
                'size_units'      => array('px'),
                'range'      => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1,
                        'step' => 0.1
                    ),
                ),
                'default' => array(
					'unit' => 'px',
					'size' => 0.2,
                ),
                'condition' => array(
                    'aux_parallax_anims_enable' => 'yes'
                )
            )
        );

        $widget->end_controls_section();

    }

    /**
     * Add extra controls to advanced section
     *
     * @return void
     */
    public function add_sticky_controls_section( $widget, $section_id, $args ){

        if( in_array( $widget->get_name(), array('section') ) ){
            return;
        }

        // Hook element section
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }


        // Adds parallax options to advanced section
        // ---------------------------------------------------------------------
        $widget->start_controls_section(
            'aux_pro_common_sticky_section',
            array(
                'label'     => __( 'Sticky Pro', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );

        $widget->add_control(
            'aux_sticky_enabled',
            array(
                'label'        => __( 'Enable Sticky', PLUGIN_DOMAIN ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => __( 'On', PLUGIN_DOMAIN ),
                'label_off'    => __( 'Off', PLUGIN_DOMAIN ),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before'
            )
        );

        $widget->add_control(
            'aux_sticky_margin',
            array(
                'label'      => __('Space between elements',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'default'   => array(
                    'size' => 0,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 100
                    )
                ),
                'condition' => array(
                    'aux_sticky_enabled' => 'yes'
                )
            )
        );

        $widget->add_control(
            'aux_sticky_disable_on',
            array(
                'label'   => __( 'Disable Sticky', PLUGIN_DOMAIN ),
                'type'    => Controls_Manager::SELECT,
                'options' => array(
                    'tablet'  => __( 'On Mobile and Tablet', PLUGIN_DOMAIN ),
                    'phone'   => __( 'On Mobile', PLUGIN_DOMAIN ),
                    'custom'  => __( 'Under a screen size', PLUGIN_DOMAIN )
                ),
                'default'   => 'tablet',
                'condition' => array(
                    'aux_sticky_enabled' => 'yes'
                ),
                'label_block' => true
            )
        );

        $widget->add_control(
            'aux_sticky_disable_under',
            array(
                'label'      => __('Disable under size',PLUGIN_DOMAIN ),
                'description'=> __('Specifies a screen width under which the parallax will be disabled automatically. (in pixels)',PLUGIN_DOMAIN ),
                'type'       => Controls_Manager::SLIDER,
                'default'    => array(
                    'size' => 767,
                ),
                'range' => array(
                    'px' => array(
                        'min'  => 0,
                        'max'  => 1400,
                        'step' => 1
                    )
                ),
                'condition' => array(
                    'aux_sticky_enabled'    => 'yes',
                    'aux_sticky_disable_on' => 'custom'
                )
            )
        );        

        $widget->end_controls_section();
    }

    /**
     * Add extra controls to advanced section
     *
     * @return void
     */
    public function add_pagecover_controls_section( $widget, $section_id, $args ){

        if( ! in_array( $widget->get_name(), array('section') ) ){
            return;
        }

        // Hook element section
        $target_sections = array('section_custom_css');

        if( ! defined('ELEMENTOR_PRO_VERSION') ) {
            $target_sections[] = 'section_custom_css_pro';
        }

        if( ! in_array( $section_id, $target_sections ) ){
            return;
        }

        $widget->start_controls_section(
            'aux_page_cover_section',
            array(
                'label'     => __( 'Page Cover', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_ADVANCED
            )
        );
        
        $widget->add_control(
            'aux_page_cover',
			array(
				'label'        => __( 'Enable Page Cover', PLUGIN_DOMAIN ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'prefix_class' => 'aux-',
				'return_value' => 'page-cover-wrapper',
            )
        );

        $widget->end_controls_section();

    }

    /**
     * Renders attributes
     *
     * @param  Widget_Base $widget Instance of widget
     *
     * @return void
     */
    public function render_attributes( $widget ){
        $settings = $widget->get_settings();

        // Add parallax attributes
        if( $this->setting_value( $settings, 'aux_parallax_enabled', 'yes' ) ){
            $widget->add_render_attribute( '_wrapper', 'class', 'aux-parallax-piece' );

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_el_origin' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-parallax-origin', $value );
            }
            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_el_depth' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-parallax-depth', $value['size'] );
            }
            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_disable_on' ) ){
                $breakpoint = 1024;

                if( 'tablet' == $value ){
                    $breakpoint = 1024;
                } elseif( 'phone' == $value ){
                    $breakpoint = 768;
                } elseif( null !== $value = $this->setting_value( $settings, 'aux_parallax_disable_under' ) ){
                    $breakpoint = $value['size'];
                }
                $widget->add_render_attribute( '_wrapper', 'data-parallax-off', $breakpoint );
            }
        }

        if( $this->setting_value( $settings, 'aux_parallax_anims_enable', 'yes' ) ){
            $widget->add_render_attribute( '_wrapper', 'class', 'aux-scroll-anim' );

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_in_anims' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-move-in', $value );
            }
            
            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_out_anims' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-move-out', $value );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_horizontal_transform' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-axis-x', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_vertical_transform' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-axis-y', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_rotate_transform' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-rotate', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_scale_transform' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-scale', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_viewport_top_origin' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-vp-top', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_viewport_bottom_origin' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-vp-bot', $value['size'] );
            }

            if( null !== $value = $this->setting_value( $settings, 'aux_parallax_element_origin' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-el-top', $value['size'] );
            }

        }       


        // Add parallax attributes
        if( $this->setting_value( $settings, 'aux_sticky_enabled', 'yes' ) ){
            // Set sticky data options
            $widget->add_render_attribute( '_wrapper', 'class', 'aux-sticky-piece' );
            $widget->add_render_attribute( '_wrapper', 'data-boundaries', true );
            $widget->add_render_attribute( '_wrapper', 'data-use-transform', true );
            if( null !== $value = $this->setting_value( $settings, 'aux_sticky_margin' ) ){
                $widget->add_render_attribute( '_wrapper', 'data-sticky-margin', $value['size'] );
            }
            if( null !== $value = $this->setting_value( $settings, 'aux_sticky_disable_on' ) ){
                $breakpoint = 768;
                
                if( 'tablet' == $value ){
                    $breakpoint = 1024;
                } elseif( 'phone' == $value ){
                    $breakpoint = 768;
                } elseif( null !== $value = $this->setting_value( $settings, 'aux_sticky_disable_under' ) ){
                    $breakpoint = $value['size'];
                }
                $widget->add_render_attribute( '_wrapper', 'data-sticky-off', $breakpoint );
            }            
        }

    }


    private function setting_value( $settings, $key, $value = null ){
        if( ! isset( $settings[ $key ] ) ){
            return;
        }
        // Retrieves the setting value
        if( is_null( $value ) ){
            return $settings[ $key ];
        }
        // Validates the setting value
        return ! empty( $settings[ $key ] ) && $value == $settings[ $key ];
    }

}
