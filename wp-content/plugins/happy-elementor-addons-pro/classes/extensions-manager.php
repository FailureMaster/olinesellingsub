<?php
namespace Happy_Addons_Pro;

use Happy_Addons_Pro\Extension\Image_Masking;
use Happy_Addons_Pro\Extension\Features\Happy_Pro_Features;


defined( 'ABSPATH' ) || die();

class Extensions_Manager {

    /**
     * Initialize
     */
    public static function init() {
        self::include_extensions();

	    Image_Masking::init();
	    Happy_Pro_Features::init_actions();
    }

    public static function include_extensions() {
	    include_once HAPPY_ADDONS_PRO_DIR_PATH . 'extensions/image-masking.php';
	    include_once HAPPY_ADDONS_PRO_DIR_PATH . 'extensions/happy-features.php';
	    include_once HAPPY_ADDONS_PRO_DIR_PATH . 'extensions/display-conditions.php';
	    include_once HAPPY_ADDONS_PRO_DIR_PATH . 'extensions/conditions/condition.php';
    }
}
