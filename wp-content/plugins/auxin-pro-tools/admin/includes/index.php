<?php // load admin related classes & functions

// load admin related functions
include_once( 'admin-the-functions.php' );

// load admin related classes
include_once( 'classes/class-auxpro-admin-assets.php'  );

if ( function_exists('icl_object_id') ) {
    include_once( 'compatibility/wpml/translate.php' );
}

do_action( 'auxpro_admin_classes_loaded' );

// load metaboxes here

// load admin related functions
include_once( 'admin-hooks.php' );
