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
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor 'Weather' widget.
 *
 * Elementor widget that displays an 'Weather' with lightbox.
 *
 * @since 1.0.0
 */
class Weather extends Widget_Base {

    protected $api = 'http://api.openweathermap.org/data/2.5/weather';

    /**
     * Get widget name.
     *
     * Retrieve 'Weather' widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'aux_weather';
    }

    /**
     * Get widget title.
     *
     * Retrieve 'Weather' widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Weather', PLUGIN_DOMAIN );
    }

    /**
     * Get widget icon.
     *
     * Retrieve 'Weather' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-divider-shape auxin-badge-pro';
    }

    /**
     * Get widget categories.
     *
     * Retrieve 'Weather' widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_categories() {
        return array( 'auxin-pro', 'auxin-theme-elements'  );
    }

    /**
     * Register 'Weather' widget controls.
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
            'api_key',
            array(
                'label'       => __( 'API KEY', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'Search',
                'label_block' => true
            )
        );

        $this->add_control(
            'city_name',
            array(
                'label'       => __( 'City Name', PLUGIN_DOMAIN ),
                'type'        => Controls_Manager::TEXT,
                'default'     => 'New York',
                'label_block' => true
            )
        );

        $this->add_control(
            'unit',
            array(
                'label'       => __('Unit format', PLUGIN_DOMAIN),
                'type'        => Controls_Manager::SELECT,
                'default'     => 'imperial',
                'options'     => array(
                    'imperial' => __('Temperature in Fahrenheit', PLUGIN_DOMAIN),
                    'metric'   => __('Temperature in Celsius', PLUGIN_DOMAIN)
                ),
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'      => __('Align',PLUGIN_DOMAIN),
                'type'       => Controls_Manager::CHOOSE,
                'options'    => array(
                    'left' => array(
                        'title' => __( 'Left', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-center',
                    ),
                    'right' => array(
                        'title' => __( 'Right', PLUGIN_DOMAIN ),
                        'icon' => 'fa fa-align-right',
                    ),
                ),
                'toggle'     => true,
                'selectors'  => array(
                    '{{WRAPPER}}' => 'text-align: {{VALUE}}',
                )
            )
        );

        $this->end_controls_section();

        /*   City Name Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'city_name_style_section',
            array(
                'label'     => __( 'City Name', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'city_name_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-weather-name' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'city_name_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-weather-name'
            )
        );

        $this->add_responsive_control(
            'city_name_margin',
            array(
                'label'              => __( 'Icon Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-weather-name' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

        /*   Temperature Style Section
        /*-------------------------------------*/

        $this->start_controls_section(
            'temperature_style_section',
            array(
                'label'     => __( 'Temperature', PLUGIN_DOMAIN ),
                'tab'       => Controls_Manager::TAB_STYLE
            )
        );

        $this->add_responsive_control(
            'temperature_color',
            array(
                'label'     => __( 'Color', PLUGIN_DOMAIN ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .aux-weather-temp' => 'color: {{VALUE}};'
                )
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'temperature_typography',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .aux-weather-temp'
            )
        );

        $this->add_responsive_control(
            'temperature_margin',
            array(
                'label'              => __( 'Icon Margin', PLUGIN_DOMAIN ),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => array( 'px', 'em' ),
                'allowed_dimensions' => 'all',
                'selectors'          => array(
                    '{{WRAPPER}} .aux-weather-temp' => 'margin:{{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                )
            )
        );

        $this->end_controls_section();

    }


    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        if( empty( $settings['api_key'] ) || empty( $settings['api_key'] ) ){
            return;
        }

        // Reduce API requests by setup transient
        $api_data = auxin_get_transient( 'auxin_weather_info' );
	    if ( false === $api_data || !isset(  $api_data->name ) || ( strtolower( $api_data->name ) != strtolower( $settings['city_name'] ) ) ) {
            $api_data = wp_remote_get( add_query_arg( array(
                'q'     => $settings['city_name'],
                'units' => $settings['unit'],
                'appid' => $settings['api_key']
            ), $this->api ) );

            if ( is_array( $api_data ) && ! is_wp_error( $api_data ) ) {
                $api_data = json_decode( $api_data['body'] );
            } else {
                $api_data = null;
            }

            if( empty( $api_data ) || ( isset( $api_data->cod ) && $api_data->cod == '404' ) ){
                echo __( 'Something went wrong!' );
                echo isset( $api_data->message ) ? sprintf( ' (%s)', $api_data->message ) : '';
                return;
            }
            auxin_set_transient( 'auxin_weather_info', $api_data, 5 * MINUTE_IN_SECONDS );
        }

        // Remove day&night string and keep only digits
        $weather_icon = preg_replace('/\D/', '', $api_data->weather[0]->icon );
        // Set unit symbol
        $unit_format  = $settings['unit'] === 'metric' ? '&deg;C': '&deg;F';
        // Set default icons rules
        $select_icon  = array(
            'cloudy'       => array( '03', '04', '50' ),
            'thunderstorm' => array( '11' ),
            'sunny'        => array( '01' ),
            'partlysunny'  => array( '02' ),
            'rainy'        => array( '10', '09' ),
            'snowy'        => array( '13' )
        );
        foreach ( $select_icon as $icon => $desc ) {
            if( in_array( $weather_icon, $desc ) ){
                $select_icon = $icon;
                break;
            }
        }
?>
        <div class="aux-weather-widget">
            <span class="aux-weather-icon">
                <i class="auxicon-ios-<?php echo ! is_array( $select_icon ) ? esc_attr( $select_icon ) : 'sunny'; ?>"></i>
            </span>
            <span class="aux-weather-temp">
                <?php echo esc_html( $api_data->main->temp ) . $unit_format; ?>
            </span>
            <span class="aux-weather-name">
                <?php echo esc_html( $api_data->name ); ?>
            </span>
        </div>
<?php
    }


    public function render_plain_content() {}

}
