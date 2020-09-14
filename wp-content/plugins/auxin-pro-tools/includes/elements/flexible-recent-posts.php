<?php
use Auxin\Plugin\CoreElements\Elementor\Modules\QueryControl\Module as Module_Query;

/**
 * Flexible recent posts Element Markup
 *
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_flexible_recent_posts_callback( $atts, $shortcode_content = null ){


    // Defining default attributes
    $default_atts = array(
        'num'                         => '8',   // max generated entry
        'offset'                      => '',
        'paged'                       => '',
        'display_pagination'          => false,
        'pagination_skin'             => 'aux-round aux-page-no-border',
        'preview_mode'                => 'grid',
        'widget_base'                 => 'grid',

        'post_column'                 => '',
        'columns'                     => 4,
        'columns_tablet'              => 'inherit',
        'columns_mobile'              => '1',
        'extra_classes'               => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'carousel_space'              => '30',
        'carousel_autoplay'           => false,
        'carousel_autoplay_delay'     => '2',
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'arrows',
        'carousel_nav_control_pos'    => 'center',
        'carousel_nav_control_skin'   => 'boxed',
        'carousel_loop'               => 1,
        'request_from'                => '',

        'universal_id'                => '',
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'reset_query'                 => true,
        'wp_query_args'               => array(), // additional wp_query args
        'custom_wp_query'             => '',
        'loadmore_type'               => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_per_page'           => '',
        'base'                        => 'aux_flexible_recent_posts',
        'base_class'                  => 'aux-widget-flexible-recent-posts'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    if( ! $use_wp_query && $widget_base ){
        $query_args = [
            'posts_per_page' => $num,
            'paged' => max( 1, get_query_var('paged'), get_query_var('page') ),
        ];
        $elementor_query = Module_Query::instance();
        $wp_query = $elementor_query->get_query( $widget_base, 'posts', $query_args, [] );
    } else {
        global $wp_query;
    }

    ob_start();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $phone_break_point     = 767;
    $tablet_break_point    = 1025;

    $column_class          = '';
    $item_class            = 'aux-col';
    $carousel_attrs        = '';

    $columns_custom_styles = '';

    if( ! empty( $loadmore_type ) ) {
        $item_class        .= ' aux-ajax-item';
    }

    $columns_tablet  = ('inherit' == $columns_tablet ) ? $columns : $columns_tablet;
    $columns_mobile   = ('inherit' == $columns_mobile  )  ? $columns_tablet : $columns_mobile;

    if ( $preview_mode === 'grid' ) {
        // generate columns class
        $column_class  = 'aux-row aux-de-col' . $columns;

        $column_class .=  ' aux-tb-col'.$columns_tablet . ' aux-mb-col'.$columns_mobile;

    } elseif ( $preview_mode === 'carousel' ) {
        $column_class    = 'master-carousel aux-no-js aux-mc-before-init' . ' aux-' . $carousel_nav_control_pos . '-control';
        $item_class      = 'aux-mc-item';

        // genereate the master carousel attributes
        $carousel_attrs  =  'data-columns="' . esc_attr( $columns ) . '"';
        $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-autoplay="true"' : '';
        $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-delay="' . esc_attr( $carousel_autoplay_delay ) . '"' : '';
        $carousel_attrs .= ' data-navigation="' . esc_attr( $carousel_navigation ) . '"';
        $carousel_attrs .= ' data-space="' . esc_attr( $carousel_space ). '"';
        $carousel_attrs .= auxin_is_true( $carousel_loop ) ? ' data-loop="' . esc_attr( $carousel_loop ) . '"' : '';
        $carousel_attrs .= ' data-wrap-controls="true"';
        $carousel_attrs .= ' data-bullets="' . ('bullets' == $carousel_navigation_control ? 'true' : 'false') . '"';
        $carousel_attrs .= ' data-bullet-class="aux-bullets aux-small aux-mask"';
        $carousel_attrs .= ' data-arrows="' . ('arrows' == $carousel_navigation_control ? 'true' : 'false') . '"';
        $carousel_attrs .= ' data-same-height="true"';

        if ( 'inherit' != $columns_tablet || 'inherit' != $columns_mobile ) {
            $carousel_attrs .= ' data-responsive="'. esc_attr( ( 'inherit' != $columns_tablet  ? $tablet_break_point . ':' . $columns_tablet . ',' : '' ).
                                                               ( 'inherit' != $columns_mobile   ? $phone_break_point  . ':' . $columns_mobile : '' ) ) . '"';
        }

    }

    // Specifies whether the columns have footer meta or not
    $column_class  .= ' aux-ajax-view  ' . $extra_column_classes;

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        echo ! $skip_wrappers ? sprintf( '<div data-element-id="%s" class="%s" %s>', esc_attr( $universal_id ), esc_attr( $column_class ), $carousel_attrs ) : '';

        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();
            $post = get_post();
            // add specific class to current classes for each column
            $post_classes  = 'post column-entry';

            if( ! empty( $post_column ) || $post_column !== ' ' ){
                printf( '<div class="%s post-%s">', esc_attr( $item_class ), esc_attr( $post->ID ) );
                auxpro_get_post_column_template( $post_column, $post_classes );
                echo '</div>';
            }

        }

        if ( $preview_mode === 'carousel' && 'arrows' == $carousel_navigation_control ) {
            if ( 'boxed' === $carousel_nav_control_skin ) :?>
                <div class="aux-carousel-controls">
                    <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                        <span class="aux-svg-arrow aux-small-right"></span>
                        <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                    </div>
                    <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                        <span class="aux-svg-arrow aux-small-left"></span>
                        <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                    </div>
                </div>
            <?php else : ?>
                <div class="aux-carousel-controls">
                    <div class="aux-next-arrow">
                        <span class="aux-svg-arrow aux-l-right"></span>
                    </div>
                    <div class="aux-prev-arrow">
                        <span class="aux-svg-arrow aux-l-left"></span>

                    </div>
                </div>
            <?php  endif;
        }

        echo '</div>';

        if( auxin_is_true( $display_pagination ) ) {
            // generate the pagination
            auxin_the_paginate_nav(
                array(
                    'css_class' => esc_attr( $pagination_skin ),
                    'wp_query'  => isset( $wp_query ) ? $wp_query : null
                )
            );
        }

    }


    if( $reset_query ){
        wp_reset_postdata();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
        return false;
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();

}
