<?php
/**
 * Load frontend scripts and styles
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */

/**
* Constructor
*/
class AUXPRO_Frontend_Assets {


	/**
	 * Construct
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts'  ) );
	}

    /**
     * Styles for admin
     *
     * @return void
     */
    public function load_styles() {
        //wp_enqueue_style( AUXPRO_SLUG .'-main',   AUXPRO_PUB_URL . '/assets/css/main.css',  array(), AUXPRO_VERSION );
    }

    /**
     * Scripts for admin
     *
     * @return void
     */
    public function load_scripts() {
        wp_enqueue_script( AUXPRO_SLUG .'-pro', AUXPRO_PUB_URL . '/assets/js/pro-tools.js', array( 'jquery', 'auxin-scripts', 'jquery-numerator' ), AUXPRO_VERSION, true );
    }

}
return new AUXPRO_Frontend_Assets();
