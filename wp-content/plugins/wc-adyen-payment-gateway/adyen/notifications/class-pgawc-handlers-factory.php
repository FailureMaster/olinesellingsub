<?php
namespace PGAWC_Adyen\Adyen\Notifications;

class PGAWC_Handlers_Factory{

	/**
	 * @param string $eventCode
	 *
	 * @return PGAWC_Abstract_Handler|null
	 */
	public static function make($eventCode){
		switch ($eventCode){
			case 'REFUND':
				return new PGAWC_Refund_Handler();
			case 'CAPTURE':
				return new PGAWC_Capture_Handler();
		}
		return null;
	}
}
