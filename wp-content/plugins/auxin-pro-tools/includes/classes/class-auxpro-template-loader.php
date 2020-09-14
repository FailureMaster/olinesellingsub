<?php
/**
 * Template Loader
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


class Auxpro_Template_Loader {

    public static function init() {
        add_filter( 'template_include' , array( __CLASS__, 'template_loader' ) );
    }

    /**
     * Load a template.
     *
     * @param mixed $template
     * @return string
     */
    public static function template_loader( $template ) {
        $find = array();
        $file = '';

        if ( is_embed() ) {
            return $template;
        }


        if ( is_single() && get_post_type() == 'faq' ) {

            $find[] = AUXPRO()->template_path() . 'single-faq.php';

        } elseif ( is_tax( get_object_taxonomies( 'faq' ) ) ) {

            $term   = get_queried_object();

            if ( is_tax( 'faq-cat' ) || is_tax( 'faq-tag' ) ) {
                $file = 'taxonomy-' . $term->taxonomy . '.php';
            } else {
                $file = 'archive-faq.php';
            }

            $find[] = AUXPRO()->template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
            $find[] = AUXPRO()->template_path() . 'taxonomy-' . $term->taxonomy . '.php';
            $find[] = AUXPRO()->template_path() . $file;

        } elseif ( is_post_type_archive( 'faq' ) ) {

            $find[] = AUXPRO()->template_path() . 'archive-faq.php';
        }

        $find      = array_unique( $find );

        if ( $find && $templates = locate_template( array_unique( $find ) ) ) {
            return $templates;
        }

        foreach ( $find as $file ) {
            if( file_exists( $file ) ){
                $template = $file;
                break;
            }
        }

        return $template;
    }

}

Auxpro_Template_Loader::init();
