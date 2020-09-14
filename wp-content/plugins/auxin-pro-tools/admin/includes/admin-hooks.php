<?php
/**
 * Admin actions and filters
 *
 * @echo HEADER
 */

/*============================================================================*/
add_filter( 'vc_get_all_templates', 'auxpro_vc_templates_tab' );
add_filter( 'vc_templates_render_category', 'auxpro_vc_templates_content' );
add_action( 'wp_ajax_auxin_vc_template_download', 'auxpro_vc_template_download' );

/**
 * Triggers an action after plugin was updated to new version.
 *
 * @return void
 */
function auxpro_after_plugin_update(){
    if( AUXPRO_VERSION !== get_transient( 'auxin_' . AUXPRO_SLUG . '_version' ) ){
        set_transient( 'auxin_' . AUXPRO_SLUG . '_version', AUXPRO_VERSION, MONTH_IN_SECONDS );

        do_action( 'auxin_plugin_updated', true, AUXPRO_SLUG, AUXPRO_VERSION, AUXPRO_BASE_NAME );
    }
}
add_action( "admin_init", "auxpro_after_plugin_update");


/**
 * Triggers after updating auxin plugins
 *
 * @param  $flush  Whether to flush rewrite rules after plugin update or not
 * @return void
 */
function auxpro_on_plugin_updated( $flush ){
    update_option( 'bdthemes-element-pack_license_notice_hidden', intval(time()) + YEAR_IN_SECONDS );
}
add_action( 'auxin_plugin_updated', 'auxpro_on_plugin_updated' );


/**
 * Redirect the page to auxin welcome page when the plugin was activated for first time
 *
 * @return void
 */
function auxpro_redirect_to_welcome_page_on_first_activation(){
    if( ! get_theme_mod( 'redirected_to_welcome_page' ) ){
        set_theme_mod( 'redirected_to_welcome_page', 1 );
        wp_redirect( self_admin_url( 'admin.php?page=auxin-welcome' ) );
        exit;
    }
}
add_action( 'init', 'auxpro_redirect_to_welcome_page_on_first_activation' );

/**
 * Remove plugins notices
 *
 * @return void
 */
function auxpro_remove_pro_plugins_notices(){
    // Ultimate addon notices
    remove_action( 'admin_notices','bsf_notices', 1000 );
    remove_action( 'network_admin_notices','bsf_notices', 1000 );
    // Visual composer notice
    remove_action( 'admin_notices', array( 'Vc_License', 'adminNoticeLicenseActivation' ) );
    // Go pricing notice
    remove_action( 'admin_notices', array( 'GW_GoPricing_AdminNotices', 'print_remote_admin_notices' ) );
    // Wp Ulike notice
    remove_action( 'admin_notices','wp_ulike_admin_notice', 25 );

}
add_action( 'admin_head', 'auxpro_remove_pro_plugins_notices', 10 );
