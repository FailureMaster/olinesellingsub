<?php
namespace PGAWC_Adyen\Adyen\Notifications;

use WC_Order;

class PGAWC_Capture_Handler extends PGAWC_Abstract_Handler {

	/**
	 * @param DataObjects\PGAWC_Adyen_Notification_Request $notification_request
	 *
	 * @return mixed|void
	 */
	public function handle( $notification_request ) {
		if ( !$notification_request->success ) {
			return;
		}
		$order = new WC_Order(intval($notification_request->merchant_reference));
		$order->update_status('completed', __('Payment was captured via Adyen', 'payment-gateway-for-adyen-and-woocommerce'));
	}
}
