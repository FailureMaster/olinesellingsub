<?php
/**
 * FAQ Element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       https://phlox.pro
 * @copyright  (c) 2010-2020 
 */

function  auxin_get_faq_master_array( $master_array )  {

     $master_array['aux_faq'] = array(
        'name'                    => __('FAQ ', PLUGIN_DOMAIN),
        'auxin_output_callback'   => 'auxin_widget_faq_callback',
        'base'                    => 'aux_faq',
        'description'             => __('Filterable FAQ Element', PLUGIN_DOMAIN),
        'class'                   => 'aux-widget-faq',
        'show_settings_on_create' => true,
        'weight'                  => 1,
        'is_widget'               => false,
        'is_shortcode'            => true,
        'is_so'                   => true,
        'is_vc'                   => true,
        'category'                => THEME_NAME,
        'group'                   => '',
        'so_api'                  => false,
        'admin_enqueue_js'        => '',
        'admin_enqueue_css'       => '',
        'front_enqueue_js'        => '',
        'front_enqueue_css'       => '',
        'icon'                    => 'auxin-element auxin-faq',
        'custom_markup'           => '',
        'js_view'                 => '',
        'html_template'           => '',
        'deprecated'              => '',
        'content_element'         => '',
        'as_parent'               => '',
        'as_child'                => '',
        'params'                  => array(
            array(
                'heading'           => __('Title', PLUGIN_DOMAIN ),
                'description'       => __('FAQ Element Title, leave it empty if you don`t need title.', PLUGIN_DOMAIN),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Categories', PLUGIN_DOMAIN),
                'description'       => __('Specifies a category that you want to show faq items from it.', PLUGIN_DOMAIN ),
                'param_name'        => 'cat',
                'type'              => 'aux_taxonomy',
                'taxonomy'          => 'faq-category',
                'def_value'         => ' ',
                'holder'            => '',
                'class'             => 'cat',
                'value'             => ' ', // should use the taxonomy name
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'            => __('Order by', PLUGIN_DOMAIN),
                'description'        => '',
                'param_name'         => 'order_by',
                'type'               => 'dropdown',
                'def_value'          => 'date',
                'holder'             => '',
                'class'              => 'order_by',
                'value'              => array (
                    'date'            => __('Date', PLUGIN_DOMAIN),
                    'menu_order date' => __('Menu Order', PLUGIN_DOMAIN),
                    'title'           => __('Title', PLUGIN_DOMAIN),
                    'ID'              => __('ID', PLUGIN_DOMAIN),
                    'rand'            => __('Random', PLUGIN_DOMAIN),
                    'comment_count'   => __('Comments', PLUGIN_DOMAIN),
                    'modified'        => __('Date Modified', PLUGIN_DOMAIN),
                    'author'          => __('Author', PLUGIN_DOMAIN),
                    'post__in'        => __('Inserted Post IDs', PLUGIN_DOMAIN)
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Order', PLUGIN_DOMAIN),
                'description'       => '',
                'param_name'        => 'order',
                'type'              => 'dropdown',
                'def_value'         => 'DESC',
                'holder'            => '',
                'class'             => 'order',
                'value'             =>array (
                    'DESC'          => __('Descending', PLUGIN_DOMAIN),
                    'ASC'           => __('Ascending', PLUGIN_DOMAIN),
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Start offset',PLUGIN_DOMAIN ),
                'description'       => __('Number of post to displace or pass over.', PLUGIN_DOMAIN ),
                'param_name'        => 'offset',
                'type'              => 'textfield',
                'value'             => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Only FAQs',PLUGIN_DOMAIN ),
                'description'       => __('If you intend to display ONLY specific FAQs, you should specify them here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', PLUGIN_DOMAIN ),
                'param_name'        => 'only_posts__in',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Include FAQs',PLUGIN_DOMAIN ),
                'description'       => __('If you intend to include additional FAQs, you should specify them here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', PLUGIN_DOMAIN ),
                'param_name'        => 'include',
                'type'              => 'textfield',
                'value'             => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude posts',PLUGIN_DOMAIN ),
                'description'       => __('If you intend to exclude specific posts from result, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', PLUGIN_DOMAIN ),
                'param_name'        => 'exclude',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Show filters',PLUGIN_DOMAIN ),
                'description'       => '',
                'param_name'        => 'show_filters',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => 'Filter',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Filter by', PLUGIN_DOMAIN),
                'description'       => __('Filter by categories or tags', PLUGIN_DOMAIN ),
                'param_name'        => 'filter_by',
                'type'              => 'dropdown',
                'def_value'         => 'faq-category',
                'holder'            => '',
                'value'             =>array (
                    'faq-category' => __('Categories', PLUGIN_DOMAIN),
                    'faq-tags'     => __('Tags', PLUGIN_DOMAIN)
                ),
                'dependency'        => array(
                    'element'       => 'show_filters',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => 'Filter',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Filter Control Alignment', PLUGIN_DOMAIN),
                'param_name'        => 'filter_align',
                'type'              => 'aux_visual_select',
                'def_value'         => 'aux-center',
                'holder'            => '',
                'choices'           => array(
                    'aux-left'      => array(
                        'label'     => __('Left' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-left.svg'
                    ),
                    'aux-center'    => array(
                        'label'     => __('Center' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-mid.svg'
                    ),
                    'aux-right'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    )
                ),
                'dependency'        => array(
                    'element'       => 'show_filters',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => 'Filter',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('FAQ Accordion Layout', PLUGIN_DOMAIN),
                'param_name'        => 'accor_layout',
                'type'              => 'aux_visual_select',
                'def_value'         => 'simple',
                'holder'            => '',
                'choices'           => array(
                    'simple'      => array(
                        'label'     => __('Left' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-left.svg'
                    ),
                    'plus-indicator'    => array(
                        'label'     => __('Center' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-mid.svg'
                    ),
                    'clean-border'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    ),
                    'clean'     => array(
                        'label'     => __('Right' , PLUGIN_DOMAIN),
                        'image'     => AUXIN_URL . 'images/visual-select/filter-right.svg'
                    )
                ),
                'weight'            => '',
                'group'             => 'Filter',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Filter button style', PLUGIN_DOMAIN),
                'description'       => __('Style of filter buttons.', PLUGIN_DOMAIN ),
                'param_name'        => 'filter_style',
                'type'              => 'dropdown',
                'def_value'         => 'aux-slideup',
                'holder'            => '',
                'dependency'        => array(
                    'element'       => 'show_filters',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => 'Filter',
                'edit_field_class'  => '',
                'value'             => array (
                    'aux-slideup'   => __('Slide up', PLUGIN_DOMAIN),
                    'aux-fill'      => __('Fill', PLUGIN_DOMAIN),
                    'aux-cube'      => __('Cube', PLUGIN_DOMAIN),
                    'aux-underline' => __('Underline', PLUGIN_DOMAIN),
                    'aux-overlay'   => __('Float frame', PLUGIN_DOMAIN),
                    'aux-borderd'   => __('Borderd', PLUGIN_DOMAIN),
                    'aux-overlay aux-underline-anim'    => __('Float underline', PLUGIN_DOMAIN)
                )
            ),
            array(
                'heading'           => __('Number of items to show', PLUGIN_DOMAIN),
                'description'       => __('Leave it empty to show all items', PLUGIN_DOMAIN),
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '8',
                'holder'            => '',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Transition duration on reveal',PLUGIN_DOMAIN ),
                'description'       => __('The transition duration while the element is going to be appeared (milliseconds).'),
                'param_name'        => 'reveal_transition_duration',
                'type'              => 'textfield',
                'value'             => '600',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __('Transitions', PLUGIN_DOMAIN),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Delay between reveal',PLUGIN_DOMAIN ),
                'description'       => __('The delay between each sequence of revealing transitions (milliseconds).'),
                'param_name'        => 'reveal_between_delay',
                'type'              => 'textfield',
                'value'             => '70',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __('Transitions', PLUGIN_DOMAIN),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Transition duration on hide',PLUGIN_DOMAIN ),
                'description'       => __('The transition duration while the element is going to be hidden (milliseconds).'),
                'param_name'        => 'hide_transition_duration',
                'type'              => 'textfield',
                'value'             => '600',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __('Transitions', PLUGIN_DOMAIN),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Delay between hide',PLUGIN_DOMAIN ),
                'description'       => __('The delay between each sequence of hiding transitions (milliseconds).'),
                'param_name'        => 'hide_between_delay',
                'type'              => 'textfield',
                'value'             => '40',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => __('Transitions', PLUGIN_DOMAIN),
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Deeplink',PLUGIN_DOMAIN ),
                'description'       => __('Enables the deeplink feature, it updates URL based on page and filter status.', PLUGIN_DOMAIN ),
                'param_name'        => 'deeplink',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Deeplink slug', PLUGIN_DOMAIN ),
                'description'       => __('Specifies the deeplink slug value in address bar.', PLUGIN_DOMAIN ),
                'param_name'        => 'deeplink_slug',
                'type'              => 'textfield',
                'value'             => uniqid('faq-'),
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'deeplink',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Paginate',PLUGIN_DOMAIN ),
                'description'       => __('Paginates the FAQ items', PLUGIN_DOMAIN ),
                'param_name'        => 'paginate',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Items number perpage', PLUGIN_DOMAIN ),
                'param_name'        => 'perpage',
                'type'              => 'textfield',
                'value'             => '10',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'paginate',
                    'value'         => '1'
                ),
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_faq_master_array', 10, 1 );


/**
 * FAQ Element Markup
 *
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_faq_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
         'template'                   => 'default',
         'cat'                        => '',
         'posts_per_page'             => -1,
         'offset'                     => '',
         'paged'                      => '',
         'title'                      => '',
         'accor_layout'               => 'plus-indicator',
         'num'                        => '8',
         'space'                      => 0,
         'paginate'                   => 1,
         'perpage'                    => 10,
         'only_posts__in'             => '',
         'filter_style'               => 'aux-slideup',
         'include'                    => '', // include these post IDs in result too. array or string comma separated
         'exclude'                    => '', // exclude these post IDs from result. array or string comma separated
         'deeplink'                   => 0,
         'deeplink_slug'              => uniqid('faq-'),
         'filter_align'               => 'aux-left',
         'filter_by'                  => 'faq-category',
         'show_filters'               => 1,
         'reveal_transition_duration' => '600',
         'reveal_between_delay'       => '70',
         'hide_transition_duration'   => '600',
         'hide_between_delay'         => '40',
         'order_by'                   => 'date',
         'order'                      => 'DESC',
         'exclude_without_media'      => 0,
         'extra_classes'              => '', // custom css class names for this element
         'universal_id'                => '',
         'custom_el_id'               => '', // custom id attribute for this element
         'reset_query'                => true,
         'use_wp_query'               => false, // true to use the global wp_query, false to use internal custom query
         'wp_query_args'              => array(), // additional wp_query args
         'base_class'                 => 'aux-widget-faq aux-widget-accordion'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts );
    extract( $result['parsed_atts'] );

    ob_start();

    if( empty( $cat ) || $cat == " " || ( is_array( $cat ) && in_array( " ", $cat ) ) ) {
        $tax_args = array();
    } else {
        $tax_args = array(
            array(
                'taxonomy' => 'faq-category',
                'field'    => 'term_id',
                'terms'    => ! is_array( $cat ) ? explode( ",", $cat ) : $cat
            )
        );
    }

    global $wp_query;

    if( ! $use_wp_query ) {
        // create wp_query to get latest items -----------
        $args = array(
            'post_type'             => 'faq',
            'orderby'               => $order_by,
            'order'                 => $order,
            'offset'                => $offset,
            'paged'                 => $paged,
            'tax_query'             => $tax_args,
            'post_status'           => 'publish',
            'posts_per_page'        => $posts_per_page,
            'ignore_sticky_posts'   => 1,

            'include_posts__in'     => $include, // include posts in this list
            'posts__not_in'         => $exclude, // exclude posts in this list
            'posts__in'             => $only_posts__in, // only posts in this list

            'exclude_without_media' => $exclude_without_media
        );

        // ---------------------------------------------------------------------

        // add the additional query args if available
        if( $wp_query_args ){
            $args = wp_parse_args( $args, $wp_query_args );
        }

        // pass the args through the auxin query parser
        $wp_query = new WP_Query( auxin_parse_query_args( $args ) );
    }

    $template           = 'aux-accordion-' . $accor_layout ;
    $post_counter       = 0;
    $items_classes      = 'aux-isotope-faq aux-isotope-animated widget-toggle ' . $template;
    $isoxin_attrs       = '';
    $isotope_id         = uniqid();

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $isoxin_attrs   = 'data-lazyload="true" data-space="'.esc_attr( $space ).'" data-pagination="'. ( $paginate ? 'true' : 'false' ) . '" data-deeplink="'. ( $deeplink ? 'true' : 'false' ) . '"';
    $isoxin_attrs  .= ' data-slug="'. esc_attr( $deeplink_slug ).'" data-perpage="'.esc_attr( $perpage ).'"';
    $isoxin_attrs  .= ' data-reveal-transition-duration="'. esc_attr( $reveal_transition_duration ).'" data-reveal-between-delay="'.esc_attr( $reveal_between_delay ).'"';
    $isoxin_attrs  .= ' data-hide-transition-duration="'. esc_attr( $hide_transition_duration ).'" data-hide-between-delay="'.esc_attr( $hide_between_delay ).'"';

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        if ( $show_filters ) {

            $terms = get_terms(
                array(
                    'taxonomy'   => $filter_by,
                    'meta_query' => array(
                        'relation' => 'OR',
                        array(
                            'meta_key' => 'tax_position',
                        )
                    ),
                    'orderby' => 'tax_position',
                    'hide_empty' => true
                )
            );


            if ( $terms ) {

                $list_output  = '<ul>';
                $list_output .= '<li class="aux-active-item" data-filter="all"><a href="#"><span data-select="' . __('all', PLUGIN_DOMAIN) . '">' . __('all', PLUGIN_DOMAIN) . '</span></a></li>';

                foreach ( $terms as $term ) {
                    if( $filter_by === "faq-category" ) {

                        if( (! is_array( $cat) ) && !( empty( $cat ) || $cat == " " ) ) {
                            $cat = array( $cat );
                        }

                        if ( ( empty( $cat ) || $cat == " " ) || in_array( $term->term_id, $cat ) ) {
                            $list_output .= '<li data-filter="'.$term->slug.'"><a href="#"><span data-select="'.$term->name.'">'.$term->name.'</span></a></li>';
                        }
                    } else {
                        $list_output .= '<li data-filter="'.$term->slug.'"><a href="#"><span data-select="'.$term->name.'">'.$term->name.'</span></a></li>';
                    }
                }

                $output_classes  = 'aux-isotope-filters aux-filters ' . $filter_style . ' ' . $filter_align . ' ';
                $output_classes .= 'aux-dropdown-filter' != $filter_style ? 'aux-togglable ':                         '';

                $filter_output   = '<div class="' . esc_attr( $output_classes ) . '">' ;
                $filter_output  .= 'aux-dropdown-filter' != $filter_style ? '<div class="aux-select-overlay"></div>': '';
                $filter_output  .= 'aux-dropdown-filter' === $filter_style ? '<span class="aux-filter-by"> ' . __('Category:', PLUGIN_DOMAIN) . ' <span class="aux-filter-name"> ' . __('all', PLUGIN_DOMAIN) . ' </span></span>': '';
                $filter_output  .= $list_output . '</ul>';
                $filter_output  .= '</div>';

                echo $filter_output;
            }

        }

        echo sprintf( '<div id="%s" data-element-id="%s" class="%s" %s> %s', esc_attr( $isotope_id ), esc_attr( $universal_id ), esc_attr( $items_classes ), $isoxin_attrs, '<div class="aux-items-loading"><div class="aux-loading-loop"><svg class="aux-circle" width="100%" height="100%" viewBox="0 0 42 42"><circle class="aux-stroke-bg" r="20" cx="21" cy="21" fill="none"></circle><circle class="aux-progress" r="20" cx="21" cy="21" fill="none" transform="rotate(-90 21 21)"></circle></svg></div></div>' );

        while ( $wp_query->have_posts() ) {

            $item_classes = 'aux-faq-item aux-iso-item aux-loading';

            if ( $num == '' || $post_counter < $num ) {
                $post_counter ++;
            } else {
                break;
            }

            $wp_query->the_post();
            $post = $wp_query->post;

            if ( $show_filters ) {
                $filters = wp_get_post_terms( $post->ID, $filter_by );
                foreach ( $filters as $filter ) {
                    $item_classes .= ' '. $filter->slug;
                }

            }   

            if ( $paginate && $post_counter > $perpage ) {
                $item_classes .= ' aux-iso-hidden';
                echo sprintf('<div class="%s">', $item_classes );
            } else {
                echo sprintf('<div class="%s">', $item_classes );
            }

            echo '<h4 class="aux-faq-item-header toggle-header">';

                $post_title = !empty( $the_name ) ? esc_html( $the_name ) : get_the_title();

                if( ! empty( $the_link ) ){
                    echo '<cite><a href="'.esc_url( $the_link ).'" title="'.esc_attr( $post_title ).'">'.$post_title.'</a></cite>';
                } else {
                    echo $post_title;
                }

                echo '<span class="aux-accordion-indicator"></span>';

            echo '</h4>';
            echo '<div class="aux-faq-item-content toggle-content">';
                echo the_content();
            echo '</div>';
            echo '<div class="clear"></div>';
            echo '</div>';
        }

        echo '</div>';
    }

    if( $reset_query ){
        wp_reset_query();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];
    return ob_get_clean();

}
