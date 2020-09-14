<?php
namespace PGAWC_Adyen\Adyen;

class PGAWC_Adyen_Config{

	/** @var string */
	private $environment;
	/** @var string */
	private $api_key;
	/** @var string */
	private $merchant_code;
	/** @var string */
	private $origin_key;
	/** @var boolean */
	private $capture_immediately;
	/** @var string */
	private $hmac_key;
	/** @var boolean */
	private $refund_in_adyen;
	private $live_endpoint_prefix;

	/**
	 * Adyen_Config constructor.
	 *
	 * @param array $settings
	 */
	public function __construct( $settings ) {
		$this->setEnvironment($settings['test_mode']);
		$environment_prefix = ($settings['test_mode'] === 'yes')? 'test_': '';
		$this->setApiKey($settings[$environment_prefix . 'api_key']);
		$this->setMerchantCode($settings[$environment_prefix . 'merchant']);
		$this->setOriginKey($settings[$environment_prefix . 'origin_key']);
		$this->setCaptureImmediately($settings['capture_immediately'] === 'yes');
		$this->setHmacKey($settings['hmac_key']);
		$this->setRefundInAdyen($settings['refund_in_adyen'] === 'yes');
		$this->setLiveEndpointPrefix($settings['live_endpoint_prefix']);
	}


	/**
	 * @return string
	 */
	public function getEnvironment() {
		return $this->environment;
	}

	/**
	 * @param string $test_mode
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setEnvironment( $test_mode ) {
		$this->environment = ($test_mode === 'yes')?\Adyen\Environment::TEST: \Adyen\Environment::LIVE;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getApiKey() {
		return html_entity_decode($this->api_key);
	}

	/**
	 * @param string $api_key
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setApiKey( $api_key ) {
		$this->api_key = trim($api_key);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getMerchantCode() {
		return $this->merchant_code;
	}

	/**
	 * @param string $merchant_code
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setMerchantCode( $merchant_code ) {
		$this->merchant_code = trim($merchant_code);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getOriginKey() {
		return $this->origin_key;
	}

	/**
	 * @param string $origin_key
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setOriginKey( $origin_key ) {
		$this->origin_key = trim($origin_key);

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isCaptureImmediately() {
		return $this->capture_immediately;
	}

	/**
	 * @param bool $capture_immediately
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setCaptureImmediately( $capture_immediately ) {
		$this->capture_immediately = $capture_immediately;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHmacKey() {
		return $this->hmac_key;
	}

	/**
	 * @param string $hmac_key
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setHmacKey( $hmac_key ) {
		$this->hmac_key = trim($hmac_key);

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isRefundInAdyen() {
		return $this->refund_in_adyen;
	}

	/**
	 * @param bool $refund_in_adyen
	 *
	 * @return PGAWC_Adyen_Config
	 */
	public function setRefundInAdyen( $refund_in_adyen ) {
		$this->refund_in_adyen = $refund_in_adyen;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLiveEndpointPrefix() {
		return $this->live_endpoint_prefix;
	}

	/**
	 * @param mixed $live_endpoint_prefix
	 */
	public function setLiveEndpointPrefix( $live_endpoint_prefix ) {
		$this->live_endpoint_prefix = $live_endpoint_prefix;
	}


}
