<?php
/**
 * WCFM plugin view
 *
 * WCFM Affiliate Template
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/templates
 * @version   1.1.0
 */

global $WCFM, $WCFMaf;

$member_id = get_current_user_id();

$wcfm_affiliate_options = get_option( 'wcfm_affiliate_options', array() );
$affiliate_reject_rules = array();
if( isset( $wcfm_affiliate_options['affiliate_reject_rules'] ) ) $affiliate_reject_rules = $wcfm_affiliate_options['affiliate_reject_rules'];
$required_approval = isset( $affiliate_reject_rules['required_approval'] ) ? $affiliate_reject_rules['required_approval'] : 'no';

$application_status = get_user_meta( $member_id, 'wcfm_affiliate_application_status', true );
if( $application_status && ( $application_status == 'approved' ) ) { $required_approval = 'no'; }

$approved_thankyou_content = wcfm_get_option( 'wcfmaf_approved_thankyou_content', '' );
if( !$approved_thankyou_content ) {
	$approved_thankyou_content = "<strong>Welcome,</strong>
														<br /><br />
														You have successfully subscribed to our affiliate program. 
														<br /><br />
														Your account already setup and ready to configure.
														<br /><br />
														Kindly follow the below the link to visit your dashboard.
														<br /><br />
														Thank You";
}

$non_approved_thankyou_content = wcfm_get_option( 'wcfmaf_non_approved_thankyou_content', '' );
if( !$non_approved_thankyou_content ) {
	$non_approved_thankyou_content = "<strong>Welcome,</strong>
																		<br /><br />
																		You have successfully submitted your Affiliate Account request. 
																		<br /><br />
																		Your Affiliate application is still under review.
																		<br /><br />
																		You will receive details about our decision in your email very soon!
																		<br /><br />
																		Thank You";
}
?>

<h2><?php _e( "Thank You", "wc-frontend-manager-affiliate" ); ?></h2>
<div id="wcfm_affiliate_container">
  <div class="wcfm_affiliate_thankyou_content_wrapper">
		<?php if( $required_approval == 'no' ) { ?>
			<div class="wcfm_affiliate_thankyou_content">
				<?php echo $approved_thankyou_content; ?>
			</div>
			<a class="wcfm_submit_button" href="<?php echo apply_filters( 'wcfm_affiliate_thank_you_button_url', get_wcfm_affiliates_url() ); ?>"><?php echo apply_filters( 'wcfm_affiliate_thank_you_button_label', __( 'Goto Dashboard', 'wc-frontend-manager-affiliate' ) ); ?> >></a>
		<?php } else { ?>
			<div class="wcfm_affiliate_thankyou_content">
				<?php echo $non_approved_thankyou_content; ?>
			</div>
		<?php } ?>
	</div>
	<div class="wcfm-clearfix"></div>
</div>