<?php
namespace PGAWC_Adyen\Adyen\Notifications;

use Exception;
use WC_Order;
use PGAWC_Adyen\Adyen\PGAWC_Adyen_Logger;

class PGAWC_Refund_Handler extends PGAWC_Abstract_Handler {

	public function handle( $notification_request ) {
		if ( !$notification_request->success) {
			return;
		}

		$order = new WC_Order(intval($notification_request->merchant_reference));

		try {
			wc_create_refund([
				'amount' => $notification_request->amount->value / 100,
				'reason' => $notification_request->reason,
				'order_id' => $order->get_id()
			]);

			$order->update_status('refunded', sprintf(__('Payment was refunded via WooCommerce Adyen Integration (PSP Reference: %s)', 'payment-gateway-for-adyen-and-woocommerce'), $notification_request->psp_reference));
		} catch ( Exception $e ) {
			PGAWC_Adyen_Logger::log($e->getMessage());
		}
	}
}
