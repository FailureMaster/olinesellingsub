<?php
/**
 * Add FAQ post type and taxonomies
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;




/**
 * Register FAQ post type and taxonomies
 *
 */
class Auxin_Post_Type_FAQ extends Auxin_Post_Type_Base {



    function __construct() {

        $post_type = 'faq';
        add_filter( 'enter_title_here', array( $this, 'editpage_custom_title') );

        parent::__construct( $post_type );
    }


    /**
     * Register post type
     *
     * @return void
     */
    public function register_post_type() {

        if( ! $single_slug = get_option( THEME_ID.'_'.$this->post_type.'_structure', '' ) )
            $single_slug   =  $this->post_type; // validate single slug

        if( ! $archive_slug = get_option( THEME_ID.'_'.$this->post_type.'_archive_structure', '' ) )
            $archive_slug   = $this->post_type.'/all'; // validate archive slug

        $labels = array(
            'name'              => _x( 'FAQ', 'archive ,menu & breadcrumb label for FAQ', PLUGIN_DOMAIN ),
            'singular_name'     => __( 'FAQ'           , PLUGIN_DOMAIN ),
            'add_new'           => _x( 'Add New'       , 'FAQs labels', PLUGIN_DOMAIN ),
            'all_items'         => __( 'All FAQs'      , PLUGIN_DOMAIN ),
            'add_new_item'      => __( 'Add New FAQ'   , PLUGIN_DOMAIN ),
            'edit_item'         => __( 'Edit FAQ'      , PLUGIN_DOMAIN ),
            'new_item'          => __( 'New FAQ'       , PLUGIN_DOMAIN ),
            'view_item'         => __( 'View FAQs'     , PLUGIN_DOMAIN ),
            'search_items'      => __( 'Search FAQs'   , PLUGIN_DOMAIN ),
            'not_found'         => __( 'No FAQs found' , PLUGIN_DOMAIN ),
            'not_found_in_trash'=> __( 'No FAQs found in Trash', PLUGIN_DOMAIN ),
            'parent_item_colon' => ''
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'publicly_queryable'=> true,
            'show_ui'           => true,
            'query_var'         => true,
            'rewrite'           => array (
                'slug'       => $single_slug,
                'with_front' => true,
                'feeds'      => true
            ),
            'capability_type'   => $this->post_type,
            'map_meta_cap'      => true,
            'hierarchical'      => false,
            'menu_position'     => 34,
            'show_in_nav_menus' => true,
            'menu_icon'         => 'dashicons-editor-help',
            'supports'          => array( 'title','editor','thumbnail', 'page-attributes' ),
            'has_archive'       => $archive_slug
        );

        return register_post_type( $this->post_type, apply_filters( "auxin_register_post_type_args_{$this->post_type}", $args ) );
    }

    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {


        //labels for FAQ Category custom post type:
        $faq_category_labels = array(
            'name'              => _x( 'FAQ Categories', 'FAQ Categories general name' , PLUGIN_DOMAIN ),
            'singular_name'     => _x( 'FAQ Category'  , 'FAQ Category singular name'  , PLUGIN_DOMAIN ),
            'search_items'      => __( 'Search in FAQ Categories'   , PLUGIN_DOMAIN ),
            'all_items'         => __( 'All FAQ Categories'         , PLUGIN_DOMAIN ),
            'most_used_items'   => null,
            'parent_item'       => null,
            'parent_item_colon' => null,
            'edit_item'         => __( 'Edit FAQ Category'          , PLUGIN_DOMAIN ),
            'update_item'       => __( 'Update FAQ Category'        , PLUGIN_DOMAIN ),
            'add_new_item'      => __( 'Add new FAQ Category'       , PLUGIN_DOMAIN ),
            'new_item_name'     => __( 'New FAQ Category'           , PLUGIN_DOMAIN ),
            'menu_name'         => __( 'Categories'                 , PLUGIN_DOMAIN ),
        );

        $tax_name = 'faq-category';

        register_taxonomy( $tax_name,
            apply_filters( "auxin_taxonomy_post_types_for_{$tax_name}" , array( $this->post_type ) ),
            apply_filters( "auxin_taxonomy_args_{$tax_name}"       , array(
                'hierarchical'  => true,
                'labels'        => $faq_category_labels,
                'singular_name' => __( 'FAQ Category', PLUGIN_DOMAIN ),
                'show_ui'       => true,
                'query_var'     => true,
                'rewrite'       => array(
                    'slug'          => 'faq-category',
                    'hierarchical'  => true,
                )
            ) )
        );
    }


    /**
     * Customizing post type list Columns
     *
     * @param  array $column  An array of column name ⇒ label
     * @return array          List of columns shown when listing posts of the post type
     */
    public function manage_edit_columns( $columns ){
        unset( $columns['date'] );

        $new_columns = array(
            "cb"            => "<input type=\"checkbox\" />",
            "title"         => _x( 'Question' , 'Question column at FAQ edit columns'   , PLUGIN_DOMAIN ),
            "category"      => _x( 'Category' , 'Category column at FAQ edit columns'   , PLUGIN_DOMAIN )
        );

        return array_merge( $columns, $new_columns );
    }


    /**
     * Applied to the list of columns to print on the manage posts screen for current post type
     *
     * @param  array $column  An array of column name ⇒ label
     * @return array          List of columns shown when listing posts of the post type
     */
    function manage_posttype_custom_columns( $column ){
        global $post;
        switch ( $column ) {
            case 'category' :
                echo get_the_term_list( $post->ID, 'faq-category', '', ', ','' );
                break;
        }
    }


    /**
     * Modifies the placeholder text for title field in edit page of post type
     *
     * @param  string $title The default placeholder text for title field
     * @return string        The filtered placeholder text for title field
     */
    function editpage_custom_title( $title ){

        $screen = get_current_screen();

        if( $this->post_type == $screen->post_type ){
            return __('Enter Question Here' , PLUGIN_DOMAIN );
        }

        return $title;
    }

}
