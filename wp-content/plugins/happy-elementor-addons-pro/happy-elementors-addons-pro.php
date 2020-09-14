<?php
/**
 * Plugin Name: Happy Elementor Addons Pro
 * Plugin URI: https://happyaddons.com/
 * Description: <a href="https://happyaddons.com/">HappyAddons</a> is a collection of slick, powerful widgets that works seamlessly with Elementor page builder. It’s trendy look with detail customization features allows to create extraordinary designs instantly.
 * Version: 1.9.0
 * Author: weDevs
 * Author URI: https://happyaddons.com/
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: happy-addons-pro
 * Domain Path: /languages/
 *
 * @package Happy_Addons_Pro
 */

defined( 'ABSPATH' ) || die();

define( 'HAPPY_ADDONS_PRO_VERSION', '1.9.0' );
define( 'HAPPY_ADDONS_PRO_REQUIRED_MINIMUM_VERSION', '2.6.0' );
define( 'HAPPY_ADDONS_PRO__FILE__', __FILE__ );
define( 'HAPPY_ADDONS_PRO_DIR_PATH', plugin_dir_path( HAPPY_ADDONS_PRO__FILE__ ) );
define( 'HAPPY_ADDONS_PRO_DIR_URL', plugin_dir_url( HAPPY_ADDONS_PRO__FILE__ ) );
define( 'HAPPY_ADDONS_PRO_ASSETS', trailingslashit( HAPPY_ADDONS_PRO_DIR_URL . 'assets' ) );

/**
 * The journey of a thousand miles starts here.
 *
 * @return void Some voids are not really void, you have to explore to figure out why not!
 */
function hapro_let_the_journey_begin() {

    /**
     * Check for Happy Elementor Addons existence
     * And prevent further execution if doesn't exist.
     */
    if ( ! did_action( 'happyaddons_loaded' ) ) {
        add_action( 'admin_notices', 'hapro_missing_happyaddons_notice' );
        return;
    }

    /**
     * Check for Happy Elementor Addons required version
     * And prevent further execution if doesn't match.
     */
    if ( ! version_compare( HAPPY_ADDONS_VERSION, HAPPY_ADDONS_PRO_REQUIRED_MINIMUM_VERSION, '>=' ) ) {
        add_action( 'admin_notices', 'hapro_required_version_missing_notice' );
        return;
    }

    /**
     * Finally we got approval to load the Happy engine!
     */
    include_once HAPPY_ADDONS_PRO_DIR_PATH . 'base.php';

    \Happy_Addons_Pro\Base::instance();
}

add_action( 'plugins_loaded', 'hapro_let_the_journey_begin', 20 );

/**
 * Happy Elementor Addons missing notice for admin panel.
 *
 * @return void
 */
function hapro_missing_happyaddons_notice() {
    $notice = sprintf(
    /* translators: 1: Plugin name 2: Happy Elementor Addons */
        esc_html__( '%1$s requires %2$s to be installed and activated. Please install %3$s', 'happy-addons-pro' ),
        '<strong>' . esc_html__( 'Happy Elementor Addons Pro', 'happy-addons-pro' ) . '</strong>',
        '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-addons-pro' ) . '</strong>',
        '<a target="_blank" rel="noopener" href="' . esc_url( admin_url( 'plugin-install.php?s=Happy+Elementor+Addons&tab=search&type=term' ) ) . '">' . esc_html__( 'Happy Elementor Addons', 'happy-addons-pro' ) . '</a>'
    );

    printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $notice );
}

/**
 * Happy Elementor Addons version incompatibility notice for admin panel.
 *
 * @return void
 */
function hapro_required_version_missing_notice() {
    $notice = sprintf(
    /* translators: 1: Plugin name 2: Happy Elementor Addons 3: Required Happy Elementor Addons version */
        esc_html__( '%1$s requires %2$s version %3$s or greater. Please update your %2$s', 'happy-addons-pro' ),
        '<strong>' . esc_html__( 'Happy Elementor Addons Pro', 'happy-addons-pro' ) . '</strong>',
        '<strong>' . esc_html__( 'Happy Elementor Addons', 'happy-addons-pro' ) . '</strong>',
        HAPPY_ADDONS_PRO_REQUIRED_MINIMUM_VERSION
    );

    printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $notice );
}
