<?php
/**
 * WCFMa plugin view
 *
 * WCFMa Analytics View
 * This template can be overridden by copying it to yourtheme/wcfm/analytics/
 *
 * @author 		WC Lovers
 * @package 	wcfma/view
 * @version   1.0.0
 */

global $WCFM, $WCFMa;

if( !apply_filters( 'wcfm_is_allow_analytics', true ) ) {
	wcfm_restriction_message_show( "Analytics" );
	return;
}

include_once( $WCFM->plugin_path . 'includes/reports/class-wcfm-report-analytics.php' );
$wcfm_report_analytics = new WCFM_Report_Analytics();

$ranges = array(
	'year'         => __( 'Year', 'wc-frontend-manager' ),
	'last_month'   => __( 'Last Month', 'wc-frontend-manager' ),
	'month'        => __( 'This Month', 'wc-frontend-manager' ),
	'7day'         => __( 'Last 7 Days', 'wc-frontend-manager' )
);

$wcfm_report_analytics->chart_colors = apply_filters( 'wcfm_report_analytics_chart_colors', array(
			'view_count'       => '#C79810',
		) );

$current_range = ! empty( $_GET['range'] ) ? sanitize_text_field( $_GET['range'] ) : '7day';

if ( ! in_array( $current_range, array( 'custom', 'year', 'last_month', 'month', '7day' ) ) ) {
	$current_range = '7day';
}

$wcfm_report_analytics->calculate_current_range( $current_range );

$args = array(
					'posts_per_page'   => -1,
					'offset'           => 0,
					'category'         => '',
					'category_name'    => '',
					'orderby'          => 'date',
					'order'            => 'DESC',
					'include'          => '',
					'exclude'          => '',
					'meta_key'         => '',
					'meta_value'       => '',
					'post_type'        => 'product',
					'post_mime_type'   => '',
					'post_parent'      => '',
					//'author'	   => get_current_user_id(),
					'post_status'      => array('publish'),
					'suppress_filters' => true 
				);
$args = apply_filters( 'wcfm_products_args', $args );

$products_objs = get_posts( $args );
$products_array = array( '' => __( 'Choose a Product', 'wc-frontend-manager-analytics' ) . ' ...' );
if( !empty($products_objs) ) {
	foreach( $products_objs as $products_obj ) {
		$products_array[esc_attr( $products_obj->ID )] = esc_html( $products_obj->post_title );
	}
}

$product_categories   = get_terms( 'product_cat', 'orderby=name&hide_empty=0&parent=0' );
$categories = array();
?>

<div class="collapse wcfm-collapse" id="wcfm_shop_listing">
  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-chart-line"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Analytics', 'wc-frontend-manager-analytics' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>
	<div class="wcfm-collapse-content">
	  <div id="wcfm_page_load"></div>
	  <?php do_action( 'before_wcfm_analytics' ); ?>
		
	  <div class="wcfm-container wcfm-top-element-container">
			<h2>
				<?php if ( 'custom' === $current_range && isset( $_GET['start_date'], $_GET['end_date'] ) ) : ?>
				<?php _e('Store Analytics', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( sprintf( _x( 'From %s to %s', 'start date and end date', 'wc-frontend-manager' ), wc_clean( $_GET['start_date'] ), wc_clean( $_GET['end_date'] ) ) ); ?><span></span>
				<?php else : ?>
					<?php _e('Store Analytics BY', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( $ranges[ $current_range ] ); ?><span></span>
				<?php endif; ?>
			</h2>
			
			<?php
			if ( WCFM_Dependencies::wcfm_wp_job_manager_plugin_active_check() ) {
				if( WCFMa_Dependencies::wpjms_plugin_active_check() ) {
					if( $is_allow_wpjm_stats = apply_filters( 'is_allow_wpjm_stats', true ) ) {
						?>
						<a class="add_new_wcfm_ele_dashboard text_tip" href="<?php echo get_wcfm_listings_stats_url(); ?>" data-tip="<?php _e( 'Listings Stats', 'wc-frontend-manager-analytics' ); ?>"><span class="wcfmfa fa-briefcase"></span><span class="text"><?php _e( 'Stats', 'wc-frontend-manager-analytics' ); ?></span></a>
						<?php
					}
				}
			}
			?>
		<div class="wcfm-clearfix"></div>
		</div>
	  <div class="wcfm-clearfix"></div><br />
		
		<div class="wcfm-container">
			<div id="wcfma_analytics_expander" class="wcfm-content">
			
			  <div id="poststuff" class="woocommerce-reports-wide">
					<div class="postbox">
				
						<div class="stats_range">
							<ul>
								<?php
									foreach ( $ranges as $range => $name ) {
										echo '<li class="' . ( $current_range == $range ? 'active' : '' ) . '"><a href="' . esc_url( remove_query_arg( array( 'start_date', 'end_date' ), add_query_arg( 'range', $range ) ) ) . '">' . $name . '</a></li>';
									}
									do_action( 'wcfm_report_analytics_filters' );
								?>
								<li class="custom <?php echo $current_range == 'custom' ? 'active' : ''; ?>">
								  <form method="GET">
								    <input type="hidden" name="range" value="custom" />
								    <input type="hidden" name="start_date" value="" />
								    <input type="hidden" name="end_date" value="" />
										<?php _e( 'Custom:', 'woocommerce' ); ?>
										<?php $WCFM->library->wcfm_date_range_picker_field(); ?>
									</form>
								</li>
							</ul>
						</div>
					</div>
				</div>
				
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
		<br />
		
		<div class="wcfm-clearfix"></div>
		<div class="page_collapsible" id="wcfm_store_world_map_views"><span class="wcfmfa fa-globe"></span><span class="dashboard_widget_head"><?php _e('Top Regions', 'wc-frontend-manager-analytics'); ?></span></div>
		<div class="wcfm-container">
			<div id="wcfma_analytics_expander" class="wcfm-content">
			
			  <div id="poststuff" class="woocommerce-reports-wide">
					<div class="postbox">
					  <div class="inside">
							<div id="wcfm_world_map_analytics_view"></div>
							<?php
							$region_analytics = $WCFMa->library->world_map_analytics_data();
							if( !empty( $region_analytics ) ) {
							  ?>
							  <div class="wcfminsights-col-1-1 wcfminsights-grid-border wcfm-clearfix">
									<div class="wcfminsights-col-1-1 wcfminsights-reports-box-title wcfm-clearfix">
										<div class="wcfminsights-col-1-2"><?php _e('Region', 'wc-frontend-manager-analytics' ); ?></div>
										<div class="wcfminsights-col-1-4"><?php _e('Views', 'wc-frontend-manager-analytics' ); ?></div>
										<div class="wcfminsights-col-1-4">%</div>
									</div>
							    <div class="wcfminsights-reports-box-datalist">
							      <?php
							      $counter = 0;
							      foreach( $region_analytics as $region => $region_analytic ) {
							      	if( $counter == 10 ) break;
							      	?>
							      	<div class="wcfminsights-data-row">
												<div class="wcfminsights-col-1-2"><?php echo $region_analytic['country']; ?></div>
												<div class="wcfminsights-col-1-4"><?php echo $region_analytic['view_count']; ?></div>
												<div class="wcfminsights-col-1-4"><?php echo $region_analytic['view_percent']; ?></div>
											</div>
							      	<?php
							      	$counter++;
							      }
							      ?>
							    </div>
							  </div>
							  <?php
							}
							?>
						</div>                     
					</div>
				</div>
				
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
		<br />
		
		<div class="wcfm-clearfix"></div>
		<div class="page_collapsible" id="wcfm_store_daily_views"><span class="wcfmfa fa-chart-line"></span><span class="dashboard_widget_head"><?php _e('Daily Views Stats', 'wc-frontend-manager-analytics'); ?></span></div>
		<div class="wcfm-container">
			<div id="wcfma_analytics_expander" class="wcfm-content">
			
			  <div id="poststuff" class="woocommerce-reports-wide">
					<div class="postbox">
					  <div class="inside">
							<?php $wcfm_report_analytics->get_main_chart( true ); ?>
						</div>                   
					</div>
				</div>
				
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
		<br />
		
		<div class="wcfm_store_stats">
			<div class="wcfm_store_referrer">
				<div class="page_collapsible" id="wcfm_store_referrer"><span class="wcfmfa fa-recycle"></span><span class="dashboard_widget_head"><?php _e('Top Referrers', 'wc-frontend-manager-analytics'); ?></span></div>
				<div class="wcfm-container">
					<div id="wcfm_store_referrer_expander" class="wcfm-content">
							<div class="top-referrers-report">
							  <div class="top_referrers_head">
									<span><?php _e( 'Referrer', 'wc-frontend-manager-analytics' ); ?></span>
									<span><?php _e( 'Count', 'wc-frontend-manager-analytics' ); ?></span>
							  </div>
							  <div class="top_referrers_body">
							    
							  </div>
							</div>
					</div>
				</div>
			</div>
		
			<div class="wcfm_top_products">
				<div class="page_collapsible" id="wcfm_top_products"><span class="wcfmfa fa-cubes"></span><span class="dashboard_widget_head"><?php _e('Most Viewed Products', 'wc-frontend-manager-analytics'); ?></span></div>
				<div class="wcfm-container">
					<div id="wcfm_top_products_expander" class="wcfm-content">
						<div id="top-products-report"><canvas id="analytics-top-products-report-canvas"></canvas></div>	
					</div>
				</div>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
		<br />
		
		<div class="wcfm-clearfix"></div>
		<div class="page_collapsible" id="wcfm_store_product_views"><span class="wcfmfa fa-cube"></span><span class="dashboard_widget_head">
		  <?php if ( 'custom' === $current_range && isset( $_GET['start_date'], $_GET['end_date'] ) ) : ?>
			<?php _e('Product Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( sprintf( _x( 'From %s to %s', 'start date and end date', 'wc-frontend-manager' ), wc_clean( $_GET['start_date'] ), wc_clean( $_GET['end_date'] ) ) ); ?><span></span>
			<?php else : ?>
				<?php _e('Product Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( $ranges[ $current_range ] ); ?><span></span>
			<?php endif; ?>
		</span></div>
		<div class="wcfm-container">
			<div id="wcfma_analytics_product_expander" class="wcfm-content">
			  <div id="poststuff" class="woocommerce-reports-wide">
					<div class="postbox">
					  <div class="stats_range">
							<ul>
								<li class="custom <?php echo $current_range == 'custom' ? 'active' : ''; ?>">
									<form method="GET">
										<div>
											<?php
												$WCFM->wcfm_fields->wcfm_generate_form_field( array( "wcfma_product_id" => array( 'type' => 'select', 'attributes' => array( 'style' => 'width: 250px;' ), 'class' => 'wcfm-select wcfm_ele', 'label_class' => 'wcfm_title', 'options' => $products_array ) ) );
											?>
											<input type="hidden" name="range" value="custom" />
											<input type="submit" id="wcfma_product_analytics_go" class="button wcfm_add_attribute" value="<?php esc_attr_e( 'Go', 'wc-frontend-manager-analytics' ); ?>" />
										</div>
									</form>
								</li>
							</ul>
						</div>
					  <div class="inside">
							<div class="chart-container">
								<div class="analytics-product-chart-placeholder main"><canvas id="analytics-product-chart-placeholder-canvas"></canvas></div>
							</div>
						</div>                   
					</div>
				</div>
				<div class="wcfm-clearfix"></div>
			</div>
		</div>
		<div class="wcfm-clearfix"></div>
		<br />
		
		<?php if( apply_filters( 'wcfm_is_allow_category', true ) ) { ?>
			<?php if( apply_filters( 'wcfm_is_allow_product_category', true ) ) { ?>
				<div class="wcfm-clearfix"></div>
				<div class="page_collapsible" id="wcfm_store_category_views"><span class="wcfmfa fa-tags"></span><span class="dashboard_widget_head">
					 <?php if ( 'custom' === $current_range && isset( $_GET['start_date'], $_GET['end_date'] ) ) : ?>
					<?php _e('Category Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( sprintf( _x( 'From %s to %s', 'start date and end date', 'wc-frontend-manager' ), wc_clean( $_GET['start_date'] ), wc_clean( $_GET['end_date'] ) ) ); ?><span></span>
					<?php else : ?>
						<?php _e('Category Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( $ranges[ $current_range ] ); ?><span></span>
					<?php endif; ?>
				</span></div>
				<div class="wcfm-container">
					<div id="wcfma_analytics_product_cats_expander" class="wcfm-content wcfma_analytics_taxonomy_expander">
						<div id="poststuff" class="woocommerce-reports-wide">
							<div class="postbox">
								<div class="stats_range">
									<ul>
										<li class="custom <?php echo $current_range == 'custom' ? 'active' : ''; ?>">
											<form method="GET">
												<div>
													<select id="wcfma_product_cats" name="wcfma_product_cats" class="wcfm-select" style="width: 250px;">
														<option value="" disabled selected><?php _e( 'Choose a Category', 'wc-frontend-manager-analytics' ); ?> ...</option>
														<?php
															if ( $product_categories ) {
																$WCFM->library->generateTaxonomyHTML( 'product_cat', $product_categories, $categories );
															}
														?>
													</select>
													<input type="hidden" name="range" value="custom" />
													<input type="submit" id="wcfma_category_analytics_go" class="button wcfm_add_attribute wcfma_taxonomy_analytics_go" data-taxonomy="product_cats" data-canvas="analytics-category-chart-placeholder-canvas" value="<?php esc_attr_e( 'Go', 'wc-frontend-manager-analytics' ); ?>" />
												</div>
											</form>
										</li>
									</ul>
								</div>
								<div class="inside">
									<div class="chart-container">
										<div class="analytics-category-chart-placeholder main"><canvas id="analytics-category-chart-placeholder-canvas"></canvas></div>
									</div>
								</div>                   
							</div>
						</div>
						<div class="wcfm-clearfix"></div>
					</div>
				</div>
				<div class="wcfm-clearfix"></div>
				<br />
			<?php } ?>
		
			<?php
			if( apply_filters( 'wcfm_is_allow_custom_taxonomy', true ) ) {
				$product_taxonomies = get_object_taxonomies( 'product', 'objects' );
				if( !empty( $product_taxonomies ) ) {
					foreach( $product_taxonomies as $product_taxonomy ) {
						if( !in_array( $product_taxonomy->name, array( 'product_cat', 'product_tag', 'wcpv_product_vendors' ) ) && apply_filters( 'wcfm_is_allow_custom_taxonomy_'.$product_taxonomy->name, true ) && apply_filters( 'wcfm_is_allow_taxonomy_'.$product_taxonomy->name, true ) ) {
							if( $product_taxonomy->public && $product_taxonomy->show_ui && $product_taxonomy->meta_box_cb && $product_taxonomy->hierarchical ) {
								?>
							
								<div class="wcfm-clearfix"></div>
								<div class="page_collapsible" id="wcfm_store_<?php echo $product_taxonomy->name; ?>_views"><span class="wcfmfa fa-tags"></span><span class="dashboard_widget_head">
									 <?php if ( 'custom' === $current_range && isset( $_GET['start_date'], $_GET['end_date'] ) ) : ?>
									<?php echo $product_taxonomy->label; ?> <?php _e('Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( sprintf( _x( 'From %s to %s', 'start date and end date', 'wc-frontend-manager' ), wc_clean( $_GET['start_date'] ), wc_clean( $_GET['end_date'] ) ) ); ?><span></span>
									<?php else : ?>
										<?php echo $product_taxonomy->label; ?> <?php _e('Stats', 'wc-frontend-manager-analytics'); ?> - <?php echo esc_html( $ranges[ $current_range ] ); ?><span></span>
									<?php endif; ?>
								</span></div>
								<div class="wcfm-container">
									<div id="wcfma_analytics_<?php echo $product_taxonomy->name; ?>_expander" class="wcfm-content wcfma_analytics_taxonomy_expander">
										<div id="poststuff" class="woocommerce-reports-wide">
											<div class="postbox">
												<div class="stats_range">
													<ul>
														<li class="custom <?php echo $current_range == 'custom' ? 'active' : ''; ?>">
															<form method="GET">
																<div>
																	<select id="wcfma_<?php echo $product_taxonomy->name; ?>" name="wcfma_<?php echo $product_taxonomy->name; ?>" class="wcfm-select wcfm_product_taxonomies" style="width: 250px;">
																		<option value="" disabled selected><?php _e( 'Choose a', 'wc-frontend-manager-analytics' ); ?> <?php echo $product_taxonomy->label; ?> ...</option>
																		<?php
																			$product_taxonomy_terms   = get_terms( $product_taxonomy->name, 'orderby=name&hide_empty=0&parent=0' );
																			if ( $product_taxonomy_terms ) {
																				$WCFM->library->generateTaxonomyHTML( $product_taxonomy->name, $product_taxonomy_terms, array() );
																			}
																		?>
																	</select>
																	<input type="hidden" name="range" value="custom" />
																	<input type="submit" id="wcfma_<?php echo $product_taxonomy->name; ?>_analytics_go" data-taxonomy="<?php echo $product_taxonomy->name; ?>" data-canvas="analytics-<?php echo $product_taxonomy->name; ?>-chart-placeholder-canvas" class="button wcfm_add_attribute wcfma_taxonomy_analytics_go" value="<?php esc_attr_e( 'Go', 'wc-frontend-manager-analytics' ); ?>" />
																</div>
															</form>
														</li>
													</ul>
												</div>
												<div class="inside">
													<div class="chart-container">
														<div class="analytics-<?php echo $product_taxonomy->name; ?>-chart-placeholder main"><canvas id="analytics-<?php echo $product_taxonomy->name; ?>-chart-placeholder-canvas"></canvas></div>
													</div>
												</div>                   
											</div>
										</div>
										<div class="wcfm-clearfix"></div>
									</div>
								</div>
								<div class="wcfm-clearfix"></div>
								<br />
								
								<?php
							}
						}
					}
				}
			}
		}
		?>
		
		<?php
		do_action( 'after_wcfm_analytics' );
		?>
	</div>
</div>