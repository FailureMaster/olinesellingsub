<?php
/**
 * General WordPress Hooks
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */



/**
 * Outputs new theme options
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */
function auxpro_define_pro_theme_options( $fields_sections_list ){

    $options  = $fields_sections_list['fields'  ];
    $sections = $fields_sections_list['sections'];

    /* ---------------------------------------------------------------------------------------------------
        Pro Section
    --------------------------------------------------------------------------------------------------- */


    return array( 'fields' => $options, 'sections' => $sections );
}

/**
 * Add new active post types
 *
 * @param  array $active_post_types  The list of allowed post types
 * @return array
 */
function auxpro_allow_new_active_post_types( $active_post_types ){
    $active_post_types['faq']           = true;

    return $active_post_types;
}

add_filter( 'auxin_active_post_types', 'auxpro_allow_new_active_post_types' );


/**
 * Init FAQ post type and corresponding metaboxes
 *
 * @return void
 */
function auxpro_add_post_types_metafields(){

    $auxin_is_admin  = is_admin();
    $post_type = 'faq' ;

        if( auxin_is_post_type_allowed( $post_type ) ){

            // define metabox args
            $metabox_args = array( 'post_type' => $post_type );

            // Initiate the post type
            $post_type_instance = new Auxin_Post_Type_FAQ();
            $post_type_instance->register();

            $metabox_args['hub_id']        = 'axi_meta_hub_faq';
            $metabox_args['hub_title']     = __('FAQ Options', PLUGIN_DOMAIN);
            $metabox_args['to_post_types'] = array( $post_type );

            // Load metabox fields on admin
            if( $auxin_is_admin ){
                auxin_maybe_render_metabox_hub_for_post_type( $metabox_args );
            }

        }

}

add_action( 'init', 'auxpro_add_post_types_metafields' );

// add_filter( 'auxin_defined_option_fields_sections', 'auxpro_define_pro_theme_options', 13, 1 );




/**
 * Adds a mian css class indicator to body tag
 *
 * @param  array $classes  List of body css classes
 * @return array           The modified list of body css classes
 */
function auxpro_body_class( $classes ) {
    $classes[] = 'auxin-pro';

    return $classes;
}
add_filter( 'body_class', 'auxpro_body_class' );
