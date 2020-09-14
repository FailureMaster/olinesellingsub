<?php


namespace PGAWC_Adyen\Adyen;


class PGAWC_Adyen_Logger {

	public static $logger;
	const LOG_FILENAME = 'woocommerce-gateway-adyen';

	public static function log( $message ) {
		if ( !class_exists( 'WC_Logger' )) {
			return;
		}

		$logger = wc_get_logger();

		$log_entry = sprintf('==== WC Adyen Log Start [%s] ====', date('d/m/Y H:i:s'));
		$log_entry .=  $message . "\n";
		$log_entry .= '====WC Adyen Log End====' . "\n\n";

		$logger->debug( $log_entry, [ 'source' => self::LOG_FILENAME ] );

	}
}