<?php
namespace PGAWC_Adyen\Core;

use Adyen\AdyenException;
use WC_Order;
use WC_Payment_Gateway;
use PGAWC_Adyen\Adyen\PGAWC_Adyen;
use PGAWC_Adyen\Adyen\PGAWC_Adyen_Config;
use PGAWC_Adyen\Adyen\PGAWC_Adyen_Logger;

class PGAWC_Adyen_Gateway extends WC_Payment_Gateway {
	/**
	 * @var string
	 */
	private $api_key;
	/**
	 * @var string
	 */
	private $merchant;
	/**
	 * @var string
	 */
	private $origin_key;
	/** @var string */
	private $live_endpoint_prefix;
	/**
	 * @var string
	 */
	private $test_mode;
	/**
	 * @var string
	 */
	private $test_api_key;
	/**
	 * @var string
	 */
	private $test_merchant;
	/**
	 * @var string
	 */
	private $test_origin_key;
	/**
	 * @var string
	 */
	private $capture_immediately;
	/**
	 * @var string
	 */
	private $refund_in_adyen;
	/**
	 * @var string
	 */
	private $hmac_key;

	/** @var PGAWC_Adyen_Config **/
	public $adyenConfig;

	/**
	 * PGAWC_Adyen_Gateway constructor.
	 */
	public function __construct() {
		$this->id           = 'adyen_payment';
		$this->method_title = __( 'Adyen Payments', 'payment-gateway-for-adyen-and-woocommerce' );
		$this->has_fields   = true;
		$this->init_form_fields();
		$this->init_settings();
		$this->supports = ['refunds'];

		$this->title           = $this->get_option( 'title' );
		$this->enabled         = $this->get_option( 'enabled' );
		$this->test_mode       = $this->get_option( 'test_mode' );
		$this->method_title    = $this->get_option( 'method_title' );
		$this->description     = $this->get_option( 'description' );
		$this->api_key         = $this->get_option( 'api_key' );
		$this->merchant        = $this->get_option( 'merchant' );
		$this->origin_key      = $this->get_option( 'origin_key' );
		$this->live_endpoint_prefix      = $this->get_option( 'live_endpoint_prefix' );
		$this->test_api_key    = $this->get_option( 'test_api_key' );
		$this->test_merchant   = $this->get_option( 'test_merchant' );
		$this->test_origin_key = $this->get_option( 'test_origin_key' );
		$this->capture_immediately = $this->get_option( 'capture_immediately' );
		$this->refund_in_adyen = $this->get_option('refund_in_adyen');
		$this->hmac_key = $this->get_option( 'hmac_key' );
		$this->adyenConfig = new PGAWC_Adyen_Config( $this->settings );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );

	}

	public function admin_options() {
		if (! $this->is_gateway_configured()){
			$url = 'https://wpruby.com/knowledgebase/setup-the-adyen-payment-gateway/';
			$link = "<a href='$url' target='_blank'>" . esc_html( __( 'How to setup the Adyen payment gateway.', 'payment-gateway-for-adyen-and-woocommerce' ) ) . '</a>';
			$message = esc_html( __('The Adyen gateway will not show at the Checkout page until it is configured.', 'payment-gateway-for-adyen-and-woocommerce' ));
			echo "<div class='notice'><p>$message $link</p></div>";
		}

		parent::admin_options();
	}

	public function init_form_fields() {
		$api_key_doc_url = 'https://docs.adyen.com/user-management/how-to-get-the-api-key';
		$origin_key_url = 'https://docs.adyen.com/user-management/how-to-get-an-origin-key';
		$live_endpoint_url = 'https://docs.adyen.com/development-resources/live-endpoints#live-url-prefix';

		$api_key_desc = __( sprintf( 'To get your Adyen API key, please visit <a target="_blank" href="%s">
		(How to get the API key)</a> for instructions.', $api_key_doc_url ), 'payment-gateway-for-adyen-and-woocommerce' );

		$origin_key_desc = __( sprintf( 'To get your Adyen Origin key, please visit <a target="_blank" href="%s">
		(How to get an origin key) for instructions.</a>', $origin_key_url ), 'payment-gateway-for-adyen-and-woocommerce' );

		$live_endpoint_desc = __( sprintf( 'To get your Adyen Live endpoint prefix, please visit <a target="_blank" href="%s">Live endpoints</a>', $live_endpoint_url ), 'payment-gateway-for-adyen-and-woocommerce' );

		$this->form_fields = [
			'webhook'         => [
				'title'       => __( 'Webhook Endpoints', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'title',
				'description' => $this->webhook_description(),
			],
			'enabled'         => [
				'title'   => __( 'Enable Adyen Payments', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Adyen Payments', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default' => 'no'
			],
			'title'    => [
				'title'   => __( 'Title', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'text',
				'label'   => __( 'Gateway Title in Checkout', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default' => 'Adyen'
			],
			'description'    => [
				'title'   => __( 'Description', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'text',
				'default' => __( 'Pay with your credit card via Adyen.', 'payment-gateway-for-adyen-and-woocommerce' )
			],
			'sep'   => $this->separator(),
			'test_mode'       => [
				'title'   => __( 'Test Mode', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Test Mode', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default' => 'yes'
			],
			'test_api_key'    => [
				'title'       => __( 'Test API Key', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => $api_key_desc,
				'default'     => '',
			],
			'test_merchant'   => [
				'title'       => __( 'Test Merchant account name', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Get you merchant code from Adyen website', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default'     => '',
			],
			'test_origin_key' => [
				'title'       => __( 'Test Origin Key', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => $origin_key_desc,
				'default'     => '',
			],
			'sep_test_live'   => $this->separator(),
			'api_key'    => [
				'title'       => __( 'Live API Key', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => $api_key_desc,
				'default'     => '',
			],
			'merchant'   => [
				'title'       => __( 'Live Merchant account name', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => __( 'Get you merchant code from Adyen website', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default'     => '',
			],
			'origin_key' => [
				'title'       => __( 'Live Origin Key', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => $origin_key_desc,
				'default'     => '',
			],
			'live_endpoint_prefix' => [
				'title'       => __( 'Live Endpoint Prefix', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => $live_endpoint_desc,
				'default'     => '',
			],
			'hmac_key' => [
				'title'       => __( 'HMAC Key', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'        => 'text',
				'description' => __('HMAC key is used to verify notifications coming from Adyen server. It is recommended to set it up to <a href="https://docs.adyen.com/development-resources/notifications/verify-hmac-signatures#enable-hmac-signatures" target="_blank">secure the notifications endpoint</a>.', 'payment-gateway-for-adyen-and-woocommerce'),
				'default'     => '',
			],
			'capture_immediately'   => [
				'title'   => __( 'Capturing', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'Capture the payments immediately.', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default' => 'yes'
			],
			'refund_in_adyen'   => [
				'title'   => __( 'Refund', 'payment-gateway-for-adyen-and-woocommerce' ),
				'type'    => 'checkbox',
				'label'   => __( 'When I change an order status to <b>refunded</b> in WooCommerce, the gateway should issue a refund in Adyen automatically.', 'payment-gateway-for-adyen-and-woocommerce' ),
				'default' => 'no'
			],
		];
	}

	public function is_available() {
		return $this->is_gateway_configured();
	}

	public function payment_fields() {
		$amount         = WC()->cart->get_total( '' );
		$paymentMethods = ( new PGAWC_Adyen( $this->adyenConfig ) )->get_payment_methods( $amount );
		if ($this->is_gateway_configured()) {
			include PGAWC_ADYEN_DIR_PATH . 'includes/views/payment-fields.php';
		}
	}

	public function process_payment( $order_id ) {
		$order              = new WC_Order( $order_id );
		$adyen_payment_data = $this->get_adyen_payment_data();
		$adyen              = new PGAWC_Adyen( $this->adyenConfig );
		try {

			$result = $adyen->pay( $order, $adyen_payment_data );

		} catch ( AdyenException $exception ) {
			PGAWC_Adyen_Logger::log($exception->getMessage());
			wc_add_notice(__('We can not process the payment at the moment. Please, try again later.', 'payment-gateway-for-adyen-and-woocommerce'), 'error');
			return false;
		}

		if ( empty($result) ) {
			return false;
		}

		if ( array_key_exists( "action", $result ) ) {
			// extra action is needed
			return [
				'result'   => 'success',
				'redirect' => $result['redirect']
			];
		}

		if ( $result['resultCode'] !== 'Authorised' ) {
			wc_add_notice( __( 'Payment error:', 'payment-gateway-for-adyen-and-woocommerce' ) . ' Card was ' . $result['resultCode'], 'error' );
			return false;
		}

		// payment went through
		update_post_meta( (int) $order_id, '_woocommerce_adyen_payment_data', $result );

		$order->payment_complete();
		$order->add_order_note( sprintf( __( 'Payment was complete via Adyen (PSP Reference: %s)', 'payment-gateway-for-adyen-and-woocommerce' ), $adyen->get_adyen_transaction_url($result['pspReference']) ));

		return [
			'result'   => 'success',
			'redirect' => $this->get_return_url( $order )
		];
	}

	/**
	 * @return array
	 */
	private function get_adyen_payment_data() {
		if ( ! isset( $_REQUEST['adyen_payment_data'] ) ) {
			return [];
		}

		$payment_data = json_decode( stripcslashes( $_REQUEST['adyen_payment_data'] ), true );

		return $this->recursive_sanitize_text_field($payment_data);
	}

	/**
	 * Recursive sanitation for an array
	 *
	 * @param $array
	 *
	 * @return mixed
	 */
	 private function recursive_sanitize_text_field($array) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_sanitize_text_field($value);
			}
			else {
				$value = sanitize_text_field( $value );
			}
		}

		return $array;
	 }
	/**
	 * @return string
	 */
	private function webhook_description() {
		$endpoint = add_query_arg( 'wc-api', 'wc_adyen', trailingslashit( get_home_url() ) );
		return __(sprintf('To receive status updates from Adyen and sync payment statuses with WooCommerce, please add the following Webhook URL to your Adyen account <a href="https://docs.adyen.com/development-resources/notifications" target="_blank">server communication settings</a>. <strong style="background-color:#ddd;">&nbsp;%s&nbsp;</strong>', $endpoint), 'payment-gateway-for-adyen-and-woocommerce');
	}

	private function separator() {
		return [
			'title'       => '',
			'type'        => 'title',
			'description' => '<hr>'
		];
	}

	private function is_gateway_configured() {
		if ($this->test_mode === 'yes') {
			return ($this->test_api_key && $this->test_merchant && $this->test_origin_key);
		}

		return ($this->api_key && $this->merchant && $this->origin_key);
	}


}