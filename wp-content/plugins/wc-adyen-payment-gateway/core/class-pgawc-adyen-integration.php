<?php
namespace PGAWC_Adyen\Core;

use WC_Order;
use WC_Payment_Gateways;
use PGAWC_Adyen\Adyen\PGAWC_Adyen;
use PGAWC_Adyen\Adyen\Notifications\PGAWC_Abstract_Handler;
use PGAWC_Adyen\Adyen\Notifications\DataObjects\PGAWC_Adyen_Notification_Request;
use PGAWC_Adyen\Adyen\Notifications\PGAWC_Handlers_Factory;

class PGAWC_Adyen_Integration
{

    /**
     * The single instance of the class.
     *
     * @var   PGAWC_Adyen_Integration
     * @since 2.1.1
     */
    protected static $_instance = null;

    /**
     * @return PGAWC_Adyen_Integration
     */
    public static function get_instance()
    {
        if (is_null(self::$_instance) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * PGAWC_Adyen_Integration constructor.
     */
    public function __construct()
    {

        if (!$this->is_woocommerce_active()) {
            return;
        }

        add_filter('woocommerce_payment_gateways', [ $this, 'add_adyen_to_woocommerce']);
        add_action('plugins_loaded', [ $this, 'init_adyen_payment_gateway']);
        add_action('plugins_loaded', [ $this, 'adyen_payment_load_plugin_textdomain']);
        add_action('wp_enqueue_scripts', [ $this, 'scripts_and_styles']);
        add_action('admin_enqueue_scripts', [ $this, 'admin_scripts_and_styles']);
        add_action('woocommerce_api_wc_adyen', [$this, 'receive_adyen_notifications']);
		add_action('woocommerce_order_edit_status', [ $this, 'refund_through_adyen'], 99, 2);
    }

    public function admin_scripts_and_styles(){
	    wp_enqueue_script('adyen-admin', PGAWC_ADYEN_DIR_URL . 'includes/assets/admin-adyen.js');
    }

    public function scripts_and_styles()
    {
        if (!is_checkout()) {
            return;
        }

        wp_enqueue_script('adyen-sdk', 'https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/3.5.0/adyen.js', [], '3.5.0', true);
        wp_enqueue_style('adyen', 'https://checkoutshopper-live.adyen.com/checkoutshopper/sdk/3.5.0/adyen.css', [], '3.5.0');
    }

    public function refund_through_adyen($order_id, $new_status)
    {
    	if (! in_array($new_status, ['cancelled', 'refunded'])){
    		return;
	    }

        $order = new WC_Order($order_id);
        $gateway_controller = WC_Payment_Gateways::instance();
        $all_gateways       = $gateway_controller->payment_gateways();
        $payment_method     = $order->get_payment_method();
        $gateway            = isset($all_gateways[ $payment_method ]) ? $all_gateways[ $payment_method ] : false;
        if ($gateway instanceof PGAWC_Adyen_Gateway && $gateway->can_refund_order($order)) {

        	if ($gateway->adyenConfig->isRefundInAdyen()) {
		        $adyen = new PGAWC_Adyen($gateway->adyenConfig);
		        $adyen->refund($order);
	        }

        }
    }

    public function add_adyen_to_woocommerce( $gateways )
    {
        $gateways[] = PGAWC_Adyen_Gateway::class;
        return $gateways;
    }

    public function init_adyen_payment_gateway()
    {
        include plugin_dir_path(__FILE__) . '/class-pgawc-adyen-gateway.php';
    }

    public function adyen_payment_load_plugin_textdomain()
    {
        load_plugin_textdomain('payment-gateway-for-adyen-and-woocommerce', false, basename(dirname(__FILE__)) . '/languages/');
    }

    public function receive_adyen_notifications()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data) ) {
            $this->accept_request();
        }

        $notification_request = wp_unslash($data);

        $gateways = WC_Payment_Gateways::instance();
	    $all_gateways       = $gateways->payment_gateways();
        /** @var PGAWC_Adyen_Gateway $adyen_payments  **/
        $adyen_payments = (isset($all_gateways['adyen_payment']))? $all_gateways['adyen_payment']: null;

        if (is_null($adyen_payments)) {
            $this->accept_request();
        }

        foreach ($notification_request['notificationItems'] as $notification_item){
	        $notification_item = new PGAWC_Adyen_Notification_Request($notification_item['NotificationRequestItem']);
            $eventHandler = PGAWC_Handlers_Factory::make($notification_item->event_code);

            if (! $eventHandler instanceof PGAWC_Abstract_Handler) {
                continue;
            }

            if ($eventHandler->verify($notification_item, $adyen_payments->adyenConfig->getHmacKey())) {
                $eventHandler->store($notification_item);
                $eventHandler->handle($notification_item);
            }
        }

        $this->accept_request();
    }

    private function is_woocommerce_active()
    {
        $active_plugins = (array) get_option('active_plugins', array());

        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }

        return in_array('woocommerce/woocommerce.php', $active_plugins) || array_key_exists('woocommerce/woocommerce.php', $active_plugins);
    }

    private function accept_request()
    {
    	// required by Adyen in order to acknowledge the notification.
        echo "[accepted]";
        exit;
    }
}