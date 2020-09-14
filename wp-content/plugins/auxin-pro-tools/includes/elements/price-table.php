<?php
/**
 * Price Table element
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2020 
 */

/**
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_price_table_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'             => '',
        'description'       => '',
        'price'             => '',
        'original_price'    => '',
        'period'            => '',
        'features'          => array(),
        'button_text'       => '',
        'button_url'        => '',
        'show_external'     => true,
        'additional_info'   => '',
        'ribbon_title'      => '',
        'ribbon_position'   => '',
        'extra_classes'     => '', // custom css class names for this element
        'base_class'        => 'aux-widget-price-table'  // base class name for container
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );
    
    /* ----------------------------- Header Section ----------------------------- */
    $table_header = '<div class="aux-price-table aux-table-header-section">';
        $table_header .=  '<div class="aux-table-header-title">';
            $table_header .= '<span>'.esc_html( $title ).'</span>';
        $table_header .= '</div>';
        $table_header .=  '<div class="aux-table-header-description">';
            $table_header .= '<span>'.esc_html( $description ).'</span>';
        $table_header .= '</div>';
        if ( ! empty($ribbon_title) ) {
            $table_header .= '<div class="aux-table-header-ribbon '.esc_attr( $ribbon_position ).'">';
                $table_header .= '<div>'.esc_html( $ribbon_title ).'</div>';
            $table_header .= '</div>';
        }
    $table_header .= '</div>';
    
    /* ------------------------------ Price Section ----------------------------- */
    $table_price = '<div class="aux-price-table aux-table-price-section">';
        $table_price .= '<div class="aux-table-price-amount">';
            if ( ! empty($original_price) ) {
                $table_price .= '<span class="aux-sale-amount">'.esc_html( $original_price ).'</span>';
            }
            $table_price .= '<span class="aux-price-amount">'.esc_html( $price ).'</span>';
        $table_price .= '</div>';
        $table_price .= '<div class="aux-table-price-period">';
            $table_price .= '<span>'.esc_html( $period ).'</span>';
        $table_price .= '</div>';
    $table_price .= '</div>';

    /* ---------------------------- Features Section ---------------------------- */
    if ( ! empty( $features ) ) {
        $table_features = '<div class="aux-price-table aux-table-features-section">';
        foreach ( $features as $key => $feature ) {
            $table_features .= '<div class="aux-table-feature">';
                $table_features .= '<span class="aux-table-feature-icon '.esc_attr( $feature['feature_icon'] ).'" style="color:'.$feature['feature_icon_color'].';"></span>';
                $table_features .= '<span class="aux-table-feature-text">'.esc_html( $feature['feature_text'] ).'</span>';
            $table_features .= '</div>';
        }
        $table_features .= '</div>';
    }

    /* ----------------------------- Footer Section ----------------------------- */
    $table_footer = '<div class="aux-price-table aux-table-footer-section">';
        if ( ! empty($button_text) ) {
            $target = $show_external ? 'target="_blank"' : '';
            $table_footer .= '<div class="aux-table-footer-button">';
                $table_footer .= '<a href="'.esc_url( $button_url ).'" '.esc_attr( $target ).'>'.esc_html( $button_text ).'</a>';
            $table_footer .= '</div>';
        }
        $table_footer .= '<div class="aux-table-footer-info">';
            $table_footer .= '<span>'.esc_html( $additional_info ).'</span>';
        $table_footer .= '</div>';
    $table_footer .= '</div>';

    $output = '<div class="aux-price-table-container">';
    $output .= $table_header.$table_price.$table_features.$table_footer;
    $output .= '</div>';

    return $output;

}