<?php
/**
 * WCFMa plugin view
 *
 * WCFMa Analytics View
 *
 * @author 		WC Lovers
 * @package 	wcfma/view
 * @version   1.0.0
 */

global $WCFM, $WCFMa;

$wcfm_is_allow_analytics = apply_filters( 'wcfm_is_allow_analytics', true );
$wcfm_is_allow_listings = apply_filters( 'wcfm_is_allow_listings', true );
if( !$wcfm_is_allow_analytics || !$wcfm_is_allow_listings ) {
	wcfm_restriction_message_show( "Listings Stats" );
	return;
}

$WPJMS_Dashboard = WPJMS_Dashboard::get_instance();

?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-chart-line"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Listings Stats', 'wc-frontend-manager-analytics' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  <?php do_action( 'before_wcfm_listings_stats' ); ?>
		
	  <div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e( 'Listings Stats', 'wc-frontend-manager-analytics' ); ?></h2>
			
			<?php
			if( $wcfm_allow_listings = apply_filters( 'wcfm_is_allow_listings', true ) ) {
				echo '<a id="listings_dashboard" class="add_new_wcfm_ele_dashboard text_tip" href="'.get_wcfm_listings_url().'" data-tip="' . __('Listings', 'wc-frontend-manager') . '"><span class="wcfmfa fa-briefcase"></span></a>';
			}
			?>
		<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
	  
		<div class="wcfm-container">
			<div id="wcfma_listings_stats_expander" class="wcfm-content">
			  <?php
			  	if( isset( $_GET['job_id'] ) && !empty( $_GET['job_id'] ) ) {
						wp_enqueue_script( 'wpjms-dashboard-job-stats' );
						?>
						<div id="wpjms-job-stats" class="wpjms-job-dashboard">
							<?php 
							$post_id = intval( $_GET['job_id'] );

							/* Date Range Picker Field */
							$WPJMS_Dashboard->date_range_picker_field();
					
							/* Chart Job Dashboard */
							echo '<div id="wpjms_job_stats_chart" data-post_id="' . $post_id . '" class="wpjms-chart"></div>';
					
							echo wpautop( '<a class="button back-to-stat-dashboard" href="' . get_wcfm_listings_stats_url() . '">' . __( 'View All Listing Stats', 'wp-job-manager-stats' ) . '</a>' );
							?>
						</div><!-- #wpjms-job-stats -->
						<?php
					} else {
						wp_enqueue_script( 'wpjms-dashboard' );
						?>
						<div id="wpjms-job-dashboard" class="wpjms-job-dashboard">
							<?php $WPJMS_Dashboard->job_dashboard(); ?>
						</div><!-- #wpjms-job-dashboard -->
						<?php
					}
			  ?>
			</div>
		</div>
	</div>
</div>