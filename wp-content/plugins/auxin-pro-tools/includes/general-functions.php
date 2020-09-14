<?php
/**
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */


/**
 * Get template part.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 */
function auxpro_get_template_part( $slug, $name = '' ) {
    auxin_get_template_part( $slug, $name, AUXPRO()->template_path() );
}


/**
 * Whether a plugin is active or not
 *
 * @param  string $plugin_basename  plugin directory name and mail file address
 * @return bool                     True if plugin is active and FALSE otherwise
 */
if( ! function_exists( 'auxin_is_plugin_active' ) ){
    function auxin_is_plugin_active( $plugin_basename ){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active( $plugin_basename );
    }
}

/**
 * Return elementor footer template
 *
 * @return void
 */
function auxpro_get_post_column_template( $post_column, $post_classes = '' ){
    $template_ID = is_numeric( $post_column ) ? $post_column : get_page_by_path( $post_column, OBJECT, 'elementor_library' )->ID ;
    if( $template_ID !== ' ' && get_post_status( $template_ID ) ){
?>
    <article <?php post_class( $post_classes ); ?>>
        <?php echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_ID ); ?>
    </article><!-- end article -->
<?php
    }
}