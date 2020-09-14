<?php
// Include integration classes for repetition values
include_once( 'integration-classes/custom-label.php' );
include_once( 'integration-classes/price-list.php' );

/**
 * Make our widgets compatible with WPML elementor list
 *
 * @param array $widgets
 * @return array
 */
function auxpro_wpml_widgets_to_translate_list( $widgets ) {

   $widgets[ 'aux_3d_textbox' ] = array(
      'conditions' => array( 'widgetType' => 'aux_3d_textbox' ),
      'fields'     => array(
         array(
            'field'       => 'title',
            'type'        => __( '3D text box: Title', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'subtitle',
            'type'        => __( '3D text box: Subtitle', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         )
      ),
   );

   $widgets[ 'aux_flex_label' ] = array(
      'conditions'        => array( 'widgetType' => 'aux_flex_label' ),
      'fields'            => array(
         array(
            'field'       => 'label',
            'type'        => __( 'Custom Label: Label', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         )
      ),
      'integration-class' => 'Auxpro_WPML_Elementor_CustomLabel',
   );

   $widgets[ 'aux_domain_checker' ] = array(
      'conditions' => array( 'widgetType' => 'aux_domain_checker' ),
      'fields'     => array(
         array(
            'field'       => 'button_text',
            'type'        => __( 'Domain Checker: Text', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         ),
         array(
            'field'       => 'palceholder_text',
            'type'        => __( 'Domain Checker: Placeholder', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         )
      ),
   );

   $widgets[ 'aux_price_list' ] = array(
      'conditions'        => array( 'widgetType' => 'aux_price_list' ),
      'fields'            => array(),
      'integration-class' => 'Auxpro_WPML_Elementor_PriceList',
   );

   $widgets[ 'aux_progressbar' ] = array(
      'conditions' => array( 'widgetType' => 'aux_progressbar' ),
      'fields'     => array(
         array(
            'field'       => 'prog_text',
            'type'        => __( 'Progressbar: Text', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         ),
      ),
   );

   $widgets[ 'aux_weather' ] = array(
      'conditions' => array( 'widgetType' => 'aux_weather' ),
      'fields'     => array(
         array(
            'field'       => 'city_name',
            'type'        => __( 'Weather: City Name', PLUGIN_DOMAIN ),
            'editor_type' => 'LINE'
         )
      ),
   );

   return $widgets;
}

/**
 * Add filter on wpml elementor widgets node when init action.
 *
 * @return void
 */
function auxpro_wpml_widgets_to_translate_filter(){
    add_filter( 'wpml_elementor_widgets_to_translate', 'auxpro_wpml_widgets_to_translate_list' );
}
add_action( 'init', 'auxpro_wpml_widgets_to_translate_filter' );

