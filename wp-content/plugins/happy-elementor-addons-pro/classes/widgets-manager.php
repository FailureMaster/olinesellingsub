<?php
namespace Happy_Addons_Pro;

use Happy_Addons\Elementor\Widgets_Manager as Free_Widgets_Manager;

defined( 'ABSPATH' ) || die();

class Widgets_Manager {
    /**
     * Initialize
     */
    public static function init() {
        add_filter( 'happyaddons_get_widgets_map', [ __CLASS__, 'add_widgets_map' ] );

        if ( hapro_has_valid_license() ) {
            add_action( 'elementor/widgets/widgets_registered', [ __CLASS__, 'register' ], 20 );
        }
    }

    public static function add_widgets_map( $widgets ) {
        return array_merge( $widgets, self::get_local_widgets_map() );
    }

    public static function get_local_widgets_map() {
        return [
			'advanced-heading' => [
			    'title' => __( 'Advanced Heading', 'happy-addons-pro' ),
                'icon' => 'hm hm-advanced-heading',
                'is_pro' => true,
                'css' => ['advanced-heading'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
			'list-group' => [
			    'title' => __( 'List Group', 'happy-addons-pro' ),
                'icon' => 'hm hm-list-group',
                'is_pro' => true,
                'css' => ['list-group'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'hover-box' => [
                'title' => __( 'Hover Box', 'happy-addons-pro' ),
                'icon' => 'hm hm-finger-point',
                'is_pro' => true,
                'css' => ['hover-box'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
	        'countdown' => [
	            'title' => __( 'Countdown', 'happy-addons-pro' ),
                'icon' => 'hm hm-refresh-time',
                'is_pro' => true,
		        'css' => ['countdown'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => ['jquery-countdown'],
		        ],
	        ],
            'team-carousel' => [
                'title' => __( 'Team Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-team-carousel',
                'is_pro' => true,
                'css' => ['team-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'logo-carousel' => [
                'title' => __( 'Logo Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-logo-carousel',
                'is_pro' => true,
                'css' => ['logo-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
	        'source-code' => [
	            'title' => __( 'Source Code', 'happy-addons-pro' ),
                'icon' => 'hm hm-code-browser',
                'is_pro' => true,
		        'css' => ['source-code'],
		        'js' => [],
		        'vendor' => [
			        'css' => ['prism'],
			        'js' => ['prism'],
		        ],
	        ],
            'feature-list' => [
                'title' => __( 'Feature List', 'happy-addons-pro' ),
                'icon' => 'hm hm-list-2',
                'is_pro' => true,
                'css' => ['feature-list'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'testimonial-carousel' => [
                'title' => __( 'Testimonial Carousel', 'happy-addons-pro' ),
                'icon' => 'hm hm-testimonial-carousel',
                'is_pro' => true,
                'css' => ['testimonial-carousel'],
                'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
            ],
            'advanced-tabs' => [
                'title' => __( 'Advanced Tabs', 'happy-addons-pro' ),
                'icon' => 'hm hm-tab',
                'is_pro' => true,
                'css' => ['advanced-tabs'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
			'flip-box' => [
			    'title' => __( 'Flip Box', 'happy-addons-pro' ),
                'icon' => 'hm hm-flip-card1',
				'is_pro' => true,
				'css' => ['flip-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
	        'animated-text' => [
	            'title' => __( 'Animated Text', 'happy-addons-pro' ),
                'icon' => 'hm hm-text-animation',
		        'is_pro' => true,
		        'css' => ['animated-text'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => ['animated-text'],
		        ],
	        ],
	        'timeline' => [
	            'title' => __( 'Timeline', 'happy-addons-pro' ),
                'icon' => 'hm hm-timeline',
		        'is_pro' => true,
		        'css' => ['timeline'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => ['elementor-waypoints'],
		        ],
	        ],
	        'instagram-feed' => [
	            'title' => __( 'Instagram Feed', 'happy-addons-pro' ),
                'icon' => 'hm hm-instagram',
		        'is_pro' => true,
		        'css' => ['instagram-feed'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => [],
		        ],
	        ],
	        'scrolling-image' => [
                'title' => __( 'Scrolling Image', 'happy-addons-pro' ),
                'icon' => 'hm hm-scrolling-image',
		        'is_pro' => true,
		        'css' => ['scrolling-image'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => ['jquery-keyframes'],
		        ],
	        ],
	        'pricing-table' => [
		        'title' => __( 'Pricing Table', 'happy-addons-pro' ),
		        'icon' => 'hm hm-file-cabinet',
		        'is_pro' => true,
		        'css' => ['pricing-table'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => [],
		        ],
	        ],
	        'business-hour' => [
		        'title' => __( 'Business Hour', 'happy-addons-pro' ),
		        'icon' => 'hm hm-hand-watch',
		        'is_pro' => true,
		        'css' => ['business-hour'],
		        'js' => [],
		        'vendor' => [
			        'css' => [],
			        'js' => [],
		        ],
	        ],
            'accordion' => [
                'title' => __( 'Advanced Accordion', 'happy-addons-pro' ),
                'icon' => 'hm hm-accordion-vertical',
                'is_pro' => true,
                'css' => ['accordion'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
            'toggle' => [
                'title' => __( 'Advanced Toggle', 'happy-addons-pro' ),
                'icon' => 'hm hm-accordion-vertical',
                'is_pro' => true,
                'css' => ['toggle'],
                'js' => [],
                'vendor' => [
                    'css' => [],
                    'js' => [],
                ],
            ],
			'promo-box' => [
				'title' => __( 'Promo Box', 'happy-addons-pro' ),
				'icon' => 'hm hm-promo',
				'is_pro' => true,
				'css' => ['promo-box'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
            'hotspots' => [
                'title' => __( 'Hotspots', 'happy-addons-pro' ),
                'icon' => 'hm hm-accordion-vertical',
                'is_pro' => true,
                'css' => ['hotspots'],
                'js' => [],
                'vendor' => [
                    'css' => ['tipso'],
                    'js' => ['jquery-tipso'],
                ],
            ],
			'price-menu' => [
				'title' => __( 'Price Menu', 'happy-addons-pro' ),
				'icon' => 'hm hm-menu-price',
				'is_pro' => true,
				'css' => ['price-menu'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
            ],
            'facebook-feed' => [
				'title' => __( 'Facebook Feed', 'happy-addons-pro' ),
				'icon' => 'hm hm-facebook',
				'is_pro' => true,
				'css' => ['facebook-feed'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
				],
			],
			'line-chart' => [
				'title' => __( 'Line Chart', 'happy-addons-pro' ),
				'icon' => 'hm hm-line-graph-pointed',
				'is_pro' => true,
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
            ],
            'pie-chart' => [
				'title' => __( 'Pie & Doughnut Chart', 'happy-addons-pro' ),
				'icon' => 'hm hm-graph-pie',
				'is_pro' => true,
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
			],
            'polar-chart' => [
				'title' => __( 'Polar area Chart', 'happy-addons-pro' ),
				'icon' => 'hm hm-graph-pie',
				'is_pro' => true,
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
            ],
            'radar-chart' => [
				'title' => __( 'Radar Chart', 'happy-addons-pro' ),
				'icon' => 'hm hm-graph-pie',
				'is_pro' => true,
				'css' => [],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => ['chart-js'],
				],
            ],
			'breadcrumbs' => [
				'title' => __( 'Breadcrumbs', 'happy-addons-pro' ),
				'icon' => 'hm hm-breadcrumbs',
				'is_pro' => true,
				'css' => ['breadcrumbs'],
				'js' => [],
				'vendor' => [
					'css' => [],
					'js' => [],
                ],
            ],
			'twitter-carousel' => [
				'title' => __( 'Twitter Feed Carousel', 'happy-addons-pro' ),
				'icon' => 'hm hm-twitter',
				'is_pro' => true,
				'css' => ['twitter-carousel'],
				'js' => [],
                'vendor' => [
                    'css' => ['slick', 'slick-theme'],
                    'js' => ['jquery-slick'],
                ],
			],
        ];
    }

    /**
     * Init Widgets
     *
     * Include widgets files and register them
     *
     * @since 1.0.0
     *
     * @access public
     */
    public static function register() {
        include_once( HAPPY_ADDONS_PRO_DIR_PATH . 'base/widget-base.php' );

        $inactive_widgets = Free_Widgets_Manager::get_inactive_widgets();

        foreach ( self::get_local_widgets_map() as $widget_key => $data ) {
            if ( ! in_array( $widget_key, $inactive_widgets ) ) {
                self::register_widget( $widget_key );
            }
        }
    }

    protected static function register_widget( $widget_key ) {
        $widget_file = HAPPY_ADDONS_PRO_DIR_PATH . 'widgets/' . $widget_key . '/widget.php';
        if ( is_readable( $widget_file ) ) {
            include_once( $widget_file );
            $widget_class = '\Happy_Addons_Pro\Widget\\' . str_replace( '-', '_', $widget_key );
            if ( class_exists( $widget_class ) ) {
                ha_elementor()->widgets_manager->register_widget_type( new $widget_class );
            }
        }
    }
}
