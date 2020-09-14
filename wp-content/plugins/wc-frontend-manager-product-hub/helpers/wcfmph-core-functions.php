<?php
if(!function_exists('wcfmph_woocommerce_inactive_notice')) {
	function wcfmph_woocommerce_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Product Hub is inactive.%s The %sWooCommerce plugin%s must be active for the WCFM - Product Hub to work. Please %sinstall & activate WooCommerce%s', WCFMph_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=woocommerce' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}

if(!function_exists('wcfmph_wcfm_inactive_notice')) {
	function wcfmph_wcfm_inactive_notice() {
		?>
		<div id="message" class="error">
		<p><?php printf( __( '%sWCFM - Product Hub is inactive.%s The %sWooCommerce Frontend Manager%s must be active for the WCFM - Product Hub to work. Please %sinstall & activate WooCommerce Frontend Manager%s', WCFMph_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="https://wordpress.org/plugins/wc-frontend-manager/">', '</a>', '<a href="' . admin_url( 'plugin-install.php?tab=search&s=wc+frontend+manager' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}
?>