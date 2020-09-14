<?php
/**
 * The Template for displaying product list vendor filter form.
 *
 * @package WCfM Markeplace Views Product List Search Form
 *
 * For edit coping this to yourtheme/wcfm/product-geolocate
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $WCFM, $WCFMmp, $post, $wp;

if ( '' === get_option( 'permalink_structure' ) ) {
	$form_action = remove_query_arg( array( 'page', 'paged', 'product-page' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
} else {
	$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
}

$filter_vendor = isset( $_GET['filter_vendor'] ) ? wc_clean( $_GET['filter_vendor'] ) : '';

$vendor_arr = $WCFM->wcfm_vendor_support->wcfm_get_vendor_list( true );
unset($vendor_arr[0]);

$vendor_arr = apply_filters( 'wcfmmp_products_filter_by_vendor_list', $vendor_arr );

$filter_box_style = apply_filters( 'wcfmmp_products_filter_by_vendor_css', 'width:100%!important;' );
$filter_box_js    = apply_filters( 'wcfmmp_products_filter_by_vendor_js', 'onchange="document.getElementById(\'wcfmmp-product-vendors-search-form\').submit()"');

?>
<form role="search" method="get" id="wcfmmp-product-vendors-search-form" class="wcfmmp-product-vendors-search-form wcfm_popup_wrapper" action="<?php echo esc_url( $form_action ); ?>" style="padding:0px;">
	<?php do_action( 'wcfmmp_before_products_filter_by_vendor' ); ?>
	
	<select id="wcfmmp_filter_vendor" class="wcfmmp_filter_vendor wcfm_popup_input search-field" name="filter_vendor" <?php echo $filter_box_js; ?> style="<?php echo $filter_box_style; ?>">
		<?php
		echo '<option value="">' . __( 'Filter by', 'wc-multivendor-marketplace' ) . ' ' . apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ) . '</option>';
		foreach ( $vendor_arr as $key => $value ) {
			echo '<option ';
			if( $key == $filter_vendor ) echo 'selected="selected" ';
			echo 'value="' . esc_attr( $key ) . '">' . esc_html( wcfm_get_vendor_store_name( $key ) ) . '</option>';
		}
		?>
	</select>
	
	<?php echo wc_query_string_form_fields( null, array( 'filter_vendor', 'paged' ), '', true ); ?>
	
	<?php if( apply_filters( 'wcfm_is_allow_products_filter_by_vendor_choosen', true ) ) { ?>
		<script>
		jQuery(document).ready(function($) {
				$("#wcfmmp_filter_vendor").select2({
					allowClear:  true,
					placeholder: '<?php echo __( 'Filter by', 'wc-multivendor-marketplace' ) . ' ' . apply_filters( 'wcfm_sold_by_label', '', __( 'Store', 'wc-frontend-manager' ) ); ?>',
				});
		});
		</script>
	<?php } ?>
	
	<?php do_action( 'wcfmmp_after_products_filter_by_vendor' ); ?>
</form>