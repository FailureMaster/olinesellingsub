<?php

function auxpro_vc_template_download() {

    if ( ! isset( $_POST['n'] ) || ! isset( $_POST['demo'] ) ) {
        wp_send_json_error('params_error');    
    }

    if ( ! wp_verify_nonce( $_POST['n'], 'auxin_vc_template_download' ) ) {
        wp_send_json_error('nonce_error');
    }

    $res = wp_remote_post( 'http://api.averta.net/products/themes/template', 
        array(
            'body' => array(
                'key'  => get_option( 'auxin_access_key', 'fhdcfhc' ),
                'demo' => $_POST['demo'],
                'url'  => get_site_url(),
            )
        )
    );

    if ( ! is_wp_error( $res ) && 200 === wp_remote_retrieve_response_code( $res ) ) {
        
        $data = json_decode( wp_remote_retrieve_body( $res ), true );

        if ( isset( $data['data'] ) && isset( $data['success'] ) && true === $data['success'] ) {
            
            $templates = get_option( 'auxin_vc_templates', array() );
            $content = base64_decode( $data['data'][$_POST['demo']]['content'] );
            $templates[$_POST['demo']] = array(
                    'version' => $data['data'][$_POST['demo']]['version'],
                    'content' => $content
            );

            update_option( 'auxin_vc_templates', $templates );
            echo $content;
            die();

        }

    }

    wp_send_json_error( 'request_error' );

}