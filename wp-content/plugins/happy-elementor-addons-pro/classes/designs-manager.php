<?php
namespace Happy_Addons_Pro;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class Designs_Manager {

    public static function init() {
        if ( ! hapro_has_valid_license() ) {
            return;
        }
        add_action( 'happyaddons_start_register_controls', [ __CLASS__, 'add_surprise_controls' ], 10, 3 );
        add_action( 'elementor/editor/after_enqueue_scripts', [ __CLASS__, 'enqueue_editor_scripts' ] );
        add_action( 'wp_ajax_ha_make_me_surprised', [ __CLASS__, 'surprise_me' ] );
    }

    public static function surprise_me() {
        if ( ! check_ajax_referer( self::get_secret_id(), 'secret' ) ) {
            wp_send_json_error( __( 'Invalid surprise request', 'happy-addons-pro' ), 403 );
        }

        if ( empty( $_GET['widget'] ) ) {
            wp_send_json_error( __( 'Incomplete surprise request', 'happy-addons-pro' ), 404 );
        }

        if ( ! ( $surprises = self::get_surprises( substr( $_GET['widget'], 3 ) ) ) ) {
            wp_send_json_error( __( 'Surprise not found', 'happy-addons-pro' ), 404 );
        }

        // Finally you got the surprise
        wp_send_json_success( $surprises, 200 );
    }

    protected static function get_surprises( $design_name ) {
        $design = HAPPY_ADDONS_PRO_DIR_PATH . 'assets/designs/' . $design_name . '.json';
        if ( ! is_readable( $design ) ) {
            return false;
        }
        return file_get_contents( $design );
    }

    private static function get_secret_id() {
        return 'ha_surprise_secret';
    }

    public static function enqueue_editor_scripts() {
        $data = '
        .ha-reset-design {
            position: absolute;
            left: -25px;
            top: 50%;
            -webkit-transform: translateY(-50%);
            transform: translateY(-50%);
            cursor: pointer;
        }
        
        .ha-reset-design:hover {
            color: #562dd4;
        }
        ';
        wp_add_inline_style( 'happy-elementor-addons-editor', $data );

        if ( hapro_is_elementor_version( '>=', '2.8.0' ) ) {
            $src = HAPPY_ADDONS_PRO_ASSETS . 'admin/js/design-new.min.js';
        } else {
            $src = HAPPY_ADDONS_PRO_ASSETS . 'admin/js/design.min.js';
        }

        wp_enqueue_script(
            'hapro-design',
            $src,
            [ 'elementor-editor' ],
            HAPPY_ADDONS_PRO_VERSION,
            true
        );

        wp_localize_script(
            'hapro-design',
            'hapro',
            [
                'ajaxUrl' => admin_url( 'admin-ajax.php' ),
                'secret' => wp_create_nonce( self::get_secret_id() )
            ]
        );
    }

    /**
     * @param $widget
     */
    public static function add_surprise_controls( $widget ) {
        $widget_key = substr( $widget->get_name(), 3 );
        $designs = self::get_designs_map();

        if ( isset( $designs[ $widget_key ] ) && ! empty( $designs[ $widget_key ] ) ) {
            $widget->start_controls_section(
                '_section_ha_design',
                [
                    'label' => __( 'Presets', 'happy-addons-pro' ),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );

            $widget->add_control(
                '_ha_design',
                [
                    'label' => __( 'Designs', 'happy-addons-pro' ),
                    'description' => __( 'Choose the most suitable preset from the dropdown', 'happy-addons-pro' ),
                    'type' => Controls_Manager::SELECT,
                    'options'=> self::get_design_options( $designs[ $widget_key ] ),
                    'render_type' => 'none',
                ]
            );

            $widget->end_controls_section();
        }
    }

    protected static function get_design_options( $qty = 0 ) {
        $options = [];
        $options[''] = __( 'Select ...', 'happy-addons-pro' );

        for ( $i = 1; $i <= $qty; $i++ ) {
            $options['design-'.$i] = sprintf( __( 'Design %s', 'happy-addons-pro' ), $i );
        }
        return $options;
    }

    public static function get_designs_map() {
        return [
            'icon-box' => 11,
            'number' => 8,
            'dual-button' => 7,
            'card' => 13,
            'gradient-heading' => 4,
            'hover-box' => 7,
            'member' => 10,
            'infobox' => 12,
            'skills' => 4,
            'review' => 9,
            'testimonial' => 7,
            'logo-grid' => 6,
            'flip-box' => 9,
            'step-flow' => 7,
            'pricing-table' => 11,
            'advanced-heading' => 12,
            'carousel' => 6,
            'slider' => 4,
            'feature-list' => 11,
            'animated-text' => 10,
            'business-hour' => 10,
            'countdown' => 12,
            'list-group' => 10,
            'logo-carousel' => 8,
            'scrolling-image' => 8,
            'team-carousel' => 12,
            'testimonial-carousel' => 13,
            'timeline' => 13,
            'instagram-feed' => 9,
            'advanced-tabs' => 13,
            'accordion' => 10
        ];
    }
}

Designs_Manager::init();
