<?php
/**
 * Front-End AJAX
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */

function auxpro_check_domain(){

	// check security field
    if( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'aux-domain-checker' ) ) {
        wp_send_json_error(  __( 'Security Error.', PLUGIN_DOMAIN ) );
    }

    if( ! isset( $_POST['domain'] ) ||  $_POST['domain'] == "" ){
        wp_send_json_error( __( 'Please enter a domain name.', PLUGIN_DOMAIN ) );
    }

    $domain = str_replace( array( 'www.', 'http://' ), NULL, $_POST['domain'] );
    $split  = explode('.', $domain);
    if( count( $split ) == 1 ) {
        $domain = $domain . ".com";
    }
    $domain = preg_replace("/[^-a-zA-Z0-9.]+/", "", $domain);

    if( strlen( $domain ) > 0 ){

        // Class responsible for checking if a domain is registered
        $domain_check = new Auxpro_Domain_Checker();
        $available    = $domain_check->is_available( $domain );

        switch ( $available ) {
            case '1':
                wp_send_json_success( sprintf( __( 'Congratulations! %s is available!', PLUGIN_DOMAIN ), '<strong>' .  $domain . '</strong>' ) );
                break;

            case '0':
                wp_send_json_error( sprintf( __( 'Sorry! %s is already taken!', PLUGIN_DOMAIN ), '<strong>' .  $domain . '</strong>' ) );
                break;

            default:
                wp_send_json_error( __( 'WHOIS server not found for that TLD.', PLUGIN_DOMAIN ) );
        }

    }

    wp_send_json_error( __( 'Please enter a valid domain name.', PLUGIN_DOMAIN ) );
}

add_action('wp_ajax_aux_domain_checker', 'auxpro_check_domain' );
add_action('wp_ajax_nopriv_aux_domain_checker', 'auxpro_check_domain' );
