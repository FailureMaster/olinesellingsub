<?php
/**
 * WCFMgs plugin templates
 *
 * Main content area
 *
 * @author 		WC Lovers
 * @package 	wcfmgs/templates/content-groups
 * @version   2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $WCFM, $WCFMgs;

$thumbnail = get_post_meta( get_the_ID(), 'thumbnail', true );
if( !$thumbnail ) {
	$thumbnail =  apply_filters( 'woocommerce_placeholder_img_src', WC()->plugin_url() . '/assets/images/placeholder.png' );
}

$group_ele_class = 'product';
$loop_index = wc_get_loop_prop( 'loop', 0 );
$columns    = 3;

$loop_index ++;
wc_set_loop_prop( 'loop', $loop_index );

if ( 0 === ( $loop_index - 1 ) % $columns || 1 === $columns ) {
	$group_ele_class .= ' first';
} elseif ( 0 === $loop_index % $columns ) {
	$group_ele_class .= ' last';
}
?>

<li <?php post_class( $group_ele_class ); ?>>
  <a href="<?php echo get_the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
    <img src="<?php echo wcfm_get_attachment_url( $thumbnail ); ?>" alt="Placeholder" width="247" class="woocommerce-placeholder wp-post-image" height="300">
    <h2 class="woocommerce-loop-product__title"><?php echo get_the_title(); ?></h2>
  </a>
</li>