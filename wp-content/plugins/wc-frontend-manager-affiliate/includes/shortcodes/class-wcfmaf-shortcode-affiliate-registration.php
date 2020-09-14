<?php
/**
 * WCFM Affiliate plugin shortcode
 *
 * Plugin Registration Shortcode output
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/includes/shortcode
 * @version   1.1.0
 */
 
class WCFM_Affiliate_Registration_Shortcode {
	
	public function __construct() {
		
	}

	/**
	 * Output the WC Frontend Manager Registration shortcode.
	 *
	 * @access public
	 * @param array $atts
	 * @return void
	 */
	static public function output( $attr ) {
		global $WCFM, $WCFMaf, $wp, $WCFM_Query;
		$WCFM->nocache();
		
		echo '<div id="wcfm-main-contentainer"> <div id="wcfm-content"><div class="wcfm-affiliate-wrapper"> ';
		
		if ( isset( $wp->query_vars['page'] ) || is_wcfm_affiliate_registration_page() ) {
			//echo "<h2>" . __( "Affiliate Registration", "wc-frontend-manager-affiliate" ) . "</h2>";
			
			if( is_user_logged_in() && isset($_GET['afstep'] ) && ( $_GET['afstep'] == 'thankyou' ) ) {
				$WCFMaf->template->get_template( 'registration/affiliate_thankyou.php' );
			} else {
				if( !wcfm_is_affiliate() && ( wcfm_is_allowed_affiliate() || current_user_can( 'administrator' ) || current_user_can( 'shop_manager' ) ) ) {
					$application_status = '';
					if( is_user_logged_in() ) {
						$member_id = apply_filters( 'wcfm_current_vendor_id', get_current_user_id() );
						$application_status = get_user_meta( $member_id, 'wcfm_affiliate_application_status', true );
					}
					
					if( $application_status && ( $application_status == 'pending' ) ) {
						$WCFMaf->template->get_template( 'registration/affiliate_thankyou.php' );
					} else {
						echo "<h2>" . __( "Registration", "wc-frontend-manager-affiliate" ) . "</h2>";
						$WCFMaf->template->get_template( 'registration/affiliate_registration.php' );
					}
				} else {
					$WCFMaf->template->get_template( 'registration/affiliate_block.php' );
				}
			}
		}
		
		echo '</div></div></div>';
	}
}
