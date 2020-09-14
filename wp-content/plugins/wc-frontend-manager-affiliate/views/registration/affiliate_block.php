<?php
/**
 * WCFM plugin view
 *
 * WCFM Affiliate BLock Template
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/templates
 * @version   1.1.0
 */

global $WCFM, $WCFMaf;
  
?>

<div id="wcfmu-feature-missing-message" class="wcfm-warn-message wcfm-wcfmu" style="display: block;">
	<p><span class="wcfmfa fa-exclamation-triangle"></span>
	<?php printf( __( 'Restricted: You are not allowed to access this page. May be you already registered as %sAffiliate%s or your %sUser Role%s does not allow for this action. %sPlease contact %sStore Admin%s for more details.', 'wc-frontend-manager-affiliate' ), '<strong>', '</strong>', '<strong>', '</strong>', '<br />', '<strong>', '</strong>' ); ?></p>
</div>