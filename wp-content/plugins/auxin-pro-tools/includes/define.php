<?php

// no direct access allowed
if ( ! defined('ABSPATH') ) {
    die();
}

//die( "MJ" );

// theme name
if( ! defined( 'THEME_NAME' ) ){
    $theme_data = wp_get_theme();
    define( 'THEME_NAME', $theme_data->Name );
}

if( ! defined( 'PLUGIN'.'_DOMAIN' ) ){
    define( 'PLUGIN'.'_DOMAIN'      , 'auxin-pro-tools' );
}


define( 'AUXPRO_VERSION'        , '1.5.1' );

define( 'AUXPRO_SLUG'           , 'auxin-pro-tools' );


define( 'AUXPRO_DIR'            , dirname( plugin_dir_path( __FILE__ ) ) );
define( 'AUXPRO_URL'            , plugins_url( '', plugin_dir_path( __FILE__ ) ) );
define( 'AUXPRO_BASE_NAME'      , plugin_basename( AUXPRO_DIR ) . '/auxin-pro-tools.php' ); // auxin-pro-tools/auxin-pro-tools.php


define( 'AUXPRO_ADMIN_DIR'      , AUXPRO_DIR . '/admin' );
define( 'AUXPRO_ADMIN_URL'      , AUXPRO_URL . '/admin' );

define( 'AUXPRO_INC_DIR'        , AUXPRO_DIR . '/includes' );
define( 'AUXPRO_INC_URL'        , AUXPRO_URL . '/includes' );

define( 'AUXPRO_PUB_DIR'        , AUXPRO_DIR . '/public' );
define( 'AUXPRO_PUB_URL'        , AUXPRO_URL . '/public' );
