<?php
namespace Happy_Addons_Pro\Extension\Features;

use Elementor\Controls_Manager;
use Happy_Addons_Pro\Extension\Conditions\Display_Conditions;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Happy Addons Pro Features
 */
class Happy_Pro_Features {

	public static function init_actions() {
		// Activate sections for widgets
		add_action( 'elementor/element/common/_section_style/after_section_end', [ __CLASS__, 'add_controls_sections' ], 1, 2 );
		// Activate column for sections
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ __CLASS__, 'add_controls_sections' ], 1, 2 );
		// Activate sections for sections
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ __CLASS__, 'add_controls_sections' ], 1, 2 );

		Display_Conditions::instance()->conditions_init();
		Display_Conditions::instance()->init_actions();
	}

	public static function add_controls_sections( $element, $args ) {
		$element->start_controls_section(
			'_section_happy_pro_features',
			[
				'label' => __( 'Happy Features', 'happy-addons-pro' ) . ha_get_section_icon(),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		Display_Conditions::instance()->add_controls($element , $args);

		$element->end_controls_section();
	}

}
