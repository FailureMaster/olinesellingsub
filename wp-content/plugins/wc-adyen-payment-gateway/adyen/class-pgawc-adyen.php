<?php
namespace PGAWC_Adyen\Adyen;

use Adyen\AdyenException;
use Adyen\Client;
use Adyen\Environment;
use Adyen\Service\Checkout;
use Adyen\Service\Modification;
use WC_Order;


class PGAWC_Adyen{
	/**
	 * @var PGAWC_Adyen_Config
	 */
	private $adyenConfig;

	/**
	 * Adyen constructor.
	 *
	 * @param PGAWC_Adyen_Config $adyenConfig
	 */
	public function __construct(PGAWC_Adyen_Config $adyenConfig) {
		$this->adyenConfig = $adyenConfig;
	}


	public function getClient(){
		$client = new Client();
		$client->setXApiKey($this->adyenConfig->getApiKey());
		$live_prefix = ($this->adyenConfig->getLiveEndpointPrefix() !==  '')? $this->adyenConfig->getLiveEndpointPrefix(): null;
		$client->setEnvironment($this->adyenConfig->getEnvironment(), $live_prefix);
		return $client;
	}

	/**
	 * @param WC_Order $order
	 *
	 * @param $adyen_payment_data
	 *
	 * @return mixed
	 * @throws AdyenException
	 */
	public function pay( $order, $adyen_payment_data ) {

		try {
			$service = new Checkout( $this->getClient() );
		} catch ( AdyenException $exception ) {
			PGAWC_Adyen_Logger::log($exception->getMessage());
			return [];
		}

		$params = [
			"amount" => [
				"currency" => get_woocommerce_currency(),
				"value" => $order->get_total() * 100,
			],
			"reference" => strval($order->get_id()),
			"paymentMethod" => $adyen_payment_data['paymentMethod'],
			"returnUrl" => str_replace( 'https:', 'http:', add_query_arg( 'wc-api', 'WC_Adyen', home_url( '/' ) ) ),
			"merchantAccount" => $this->adyenConfig->getMerchantCode(),
			"channel" => "Web",
		];

		if ($this->adyenConfig->isCaptureImmediately()) {
			$params["captureDelayHours"] = 0;
		}

		$params['applicationInfo'] = [
			'merchantApplication' =>    [
				'name'  =>  PGAWC_NAME,
				'version'  =>  PGAWC_VERSION,
			],
			'externalPlatform'  =>      [
				'name'  =>  'WordPress',
				'version'  =>  get_bloginfo('version'),
				'integrator' => 'WPRuby',
			],
		];

		return $service->payments( $params );
	}

	/**
	 * @param $amount
	 *
	 * @return mixed
	 */
	public function get_payment_methods($amount){

		try {
			$service = new Checkout( $this->getClient() );
		} catch ( AdyenException $exception ) {
			PGAWC_Adyen_Logger::log($exception->getMessage());
			return [];
		}

		$params = [
			"merchantAccount" => $this->adyenConfig->getMerchantCode(),
			"countryCode" => wc_get_base_location()['country'],
			"amount" => [
				"currency" => get_woocommerce_currency(),
				"value" => $amount * 100,
			],
			"channel" => "Web"
		];

		try {
			return $service->paymentMethods($params);
		} catch (AdyenException $exception){
			PGAWC_Adyen_Logger::log($exception->getMessage());
			return [];
		}
	}

	/**
	 * @param WC_Order $order
	 *
	 * @param null $amount
	 * @param string $reason
	 *
	 * @return bool
	 */
	public function refund( $order, $amount = null, $reason = '' ){

		$adyen_payment_info = $order->get_meta('_woocommerce_adyen_payment_data');
		$refund_amount = (is_null($amount))? $adyen_payment_info['amount']['value']: $amount;

		if(!isset($adyen_payment_info['pspReference'])){
			return false;
		}

		try {
			$service = new Modification( $this->getClient() );
		} catch ( AdyenException $exception ) {
			PGAWC_Adyen_Logger::log($exception->getMessage());
			return false;
		}

		$params = [
			"merchantAccount" => $this->adyenConfig->getMerchantCode(),
			"modificationAmount" => [
				"value" => $refund_amount,
	            "currency" => $adyen_payment_info['amount']['currency'],
	        ],
			"originalReference" => $adyen_payment_info['pspReference'],
            "reference" => $adyen_payment_info['merchantReference'],
		];

		try {
			$result = $service->refund( $params );
		} catch ( AdyenException $exception ) {
			PGAWC_Adyen_Logger::log($exception->getMessage());
			return false;
		}

		if (isset($result['pspReference'])){
			$order->add_order_note(sprintf(__('Payment was refunded via Adyen (PSP Reference: %s)', 'payment-gateway-for-adyen-and-woocommerce'), $this->get_adyen_transaction_url($result['pspReference'])));
			return true;
		}

		return false;

	}

	/**
	 * @param string $psp_reference
	 *
	 * @return string
	 */
	public function get_adyen_transaction_url( $psp_reference ) {

		if ($this->adyenConfig->getEnvironment() === Environment::TEST) {
			return sprintf('<a href="https://ca-test.adyen.com/ca/ca/accounts/showTx.shtml?pspReference=%s&txType=Payment">%s</a>', $psp_reference, $psp_reference);
		}

		return sprintf('<a href="https://ca-live.adyen.com/ca/ca/accounts/showTx.shtml?pspReference=%s&txType=Payment">%s</a>', $psp_reference, $psp_reference);
	}
}