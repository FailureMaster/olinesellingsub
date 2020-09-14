<?php
/**
 * Admin Scripts Class.
 *
 * @
*/

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

/**
 *  Class to load and print master slider panel scripts
 */
class AUXPRO_Admin_Assets {

    /**
     * __construct
     */
    function __construct() {
        // general assets
        // add_action( "admin_enqueue_styles" , array( $this, "load_styles"  ) );
        // add_action( "admin_enqueue_scripts", array( $this, "load_scripts" ) );
    }

    /**
     * Styles for admin
     *
     * @return void
     */
    public function load_styles() {
        // wp_enqueue_style( AUXPRO_SLUG .'-admin-styles',   AUXPRO_ADMIN_URL . '/assets/css/msp-general.css',  array(), AUXPRO_VERSION );
    }

    /**
     * Scripts for admin
     *
     * @return void
     */
    public function load_scripts() {
        // wp_enqueue_script( AUXPRO_SLUG .'-admin-scripts', AUXPRO_ADMIN_URL . '/assets/js/admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-dialog'), AUXPRO_VERSION, true );
        // wp_enqueue_script( AUXPRO_SLUG . '-vc-templates', AUXPRO_ADMIN_URL . '/assets/js/main.js', array( 'jquery' ), AUXPRO_VERSION, true );
    }

}
