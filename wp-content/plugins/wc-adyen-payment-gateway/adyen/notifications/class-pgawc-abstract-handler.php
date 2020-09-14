<?php
namespace PGAWC_Adyen\Adyen\Notifications;

use Adyen\AdyenException;
use Adyen\Util\HmacSignature;
use WC_Order;
use PGAWC_Adyen\Adyen\Notifications\DataObjects\PGAWC_Adyen_Notification_Request;

abstract class PGAWC_Abstract_Handler{

	/**
	 * @param PGAWC_Adyen_Notification_Request $notification_item
	 *
	 * @param $hmac_key
	 *
	 * @return bool
	 * @throws AdyenException
	 */
	public function verify($notification_item, $hmac_key){
		$hmac = new HmacSignature();
		return $hmac->isValidNotificationHMAC($hmac_key, $notification_item->to_array());
	}

	/**
	 * @param PGAWC_Adyen_Notification_Request $notification_item
	 */
	public function store($notification_item){

		$order = new WC_Order(intval($notification_item->merchant_reference));

		if ( !$order->get_id() ) {
			return;
		}

		$adyen_notifications = get_post_meta($order->get_id(), '_adyen_notifications', true);

		if(empty($adyen_notifications)){
			update_post_meta($order->get_id(), '_adyen_notifications', $notification_item->to_array());
		}else{
			$adyen_notifications[] = $notification_item->to_array();
			update_post_meta($order->get_id(), '_adyen_notifications', $adyen_notifications);
		}

	}

	/**
	 * @param PGAWC_Adyen_Notification_Request $notificationRequest
	 *
	 * @return mixed
	 */
	abstract public function handle($notificationRequest);
}
