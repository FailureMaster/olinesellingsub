<?php

namespace PGAWC_Adyen\Adyen\Notifications\DataObjects;

class PGAWC_Adyen_Notification_Request{

	/** @var string */
	public $event_code;
	/** @var string */
	public $event_date;
	/** @var string */
	public $merchant_account_code;
	/** @var string */
	public $merchant_reference;
	/** @var string */
	public $original_reference;
	/** @var string */
	public $psp_reference;
	/** @var string */
	public $reason;
	/** @var boolean */
	public $success;
	/** @var PGAWC_Amount */
	public $amount;
	/** @var PGAWC_Additional_Data */
	public $additional_data;
	/** @var array */
	private $raw_notification_request;

	public function __construct($notification_request) {
		$this->raw_notification_request = $notification_request;
		$this->event_code = (isset($notification_request['eventCode']))? $notification_request['eventCode']: null;
		$this->event_date = (isset($notification_request['eventDate']))? strtotime($notification_request['eventDate']): null;
		$this->merchant_account_code = (isset($notification_request['merchantAccountCode']))? $notification_request['merchantAccountCode']: null;
		$this->merchant_reference = (isset($notification_request['merchantReference']))? $notification_request['merchantReference']: null;
		$this->original_reference = (isset($notification_request['originalReference']))? $notification_request['originalReference']: null;
		$this->psp_reference = (isset($notification_request['pspReference']))? $notification_request['pspReference']: null;
		$this->reason = (isset($notification_request['reason']))? $notification_request['reason']: null;
		$this->success = (isset($notification_request['success']))? $notification_request['success']: null;

		if (isset($notification_request['amount'])) {
			$this->amount = new PGAWC_Amount();
			$this->amount->currency = (isset($notification_request['amount']['currency']))? $notification_request['amount']['currency']: null;
			$this->amount->value = (isset($notification_request['amount']['value']))? $notification_request['amount']['value']: null;
		}

		if (isset($notification_request['additionalData'])) {
			$this->additional_data = new PGAWC_Additional_Data();
			$this->additional_data->hmac_signature = (isset($notification_request['additionalData']['hmacSignature']))? $notification_request['additionalData']['hmacSignature']: null;
		}

	}

	public function to_array(){
		return $this->raw_notification_request;
	}

}

