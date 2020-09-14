<?php
/**
 * WCFMgs plugin templates
 *
 * Main content area
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/templates/archive-groups
 * @version   2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $WCFM, $WCFMgs, $post;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );

?>
<header class="woocommerce-products-header">
	<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
		<h1 class="woocommerce-products-header__title page-title"><?php echo apply_filters( 'wcfm_groups_archive_title', __( 'Groups', 'wc-frontend-manager-groups-staffs' ) ); ?></h1>
	<?php endif; ?>

	<?php
	do_action( 'woocommerce_archive_description' );
	?>
</header>
<?php

if ( have_posts() ) {

	do_action( 'wcfmgs_before_groups_loop' );

	?>
	<div class="columns-3">
		<ul class="products columns-<?php echo esc_attr( wc_get_loop_prop( 'columns' ) ); ?>">
			<?php
			while ( have_posts() ) {
				the_post();
	
				do_action( 'wcfmgs_groups_loop' );
	
				$WCFMgs->template->get_template_part( 'content', 'groups' );
			}
			?>
		</ul>
	</div>
	<?php

	do_action( 'wcfmgs_after_groups_loop' );
} else {
	do_action( 'wcfmgs_no_groups_found' );
}

do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
