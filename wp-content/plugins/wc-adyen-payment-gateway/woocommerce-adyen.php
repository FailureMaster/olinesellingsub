<?php
/* @wordpress-plugin
 * Plugin Name:       Payment Gateway for Adyen and WooCommerce
 * Plugin URI:        https://wpruby.com/plugin/payment-gateway-for-adyen-and-woocommerce
 * Description:       Accept adyen payments on your WooCommerce shop
 * Version:           1.1.0
 * WC requires at least: 3.0
 * WC tested up to: 4.2
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * Text Domain:       payment-gateway-for-adyen-and-woocommerce
 * license:           GPL-2.0+
 * license URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

namespace PGAWC_Adyen;

use PGAWC_Adyen\Core\PGAWC_Adyen_Integration;

define('PGAWC_ADYEN_DIR_URL', plugin_dir_url( __FILE__ ));
define('PGAWC_ADYEN_DIR_PATH', plugin_dir_path( __FILE__ ));
define('PGAWC_VERSION', '1.1.0');
define('PGAWC_NAME', 'Payment Gateway for Adyen and WooCommerce');


require PGAWC_ADYEN_DIR_PATH . '/vendor/autoload.php';
require PGAWC_ADYEN_DIR_PATH . '/includes/autoload.php';

PGAWC_Adyen_Integration::get_instance();