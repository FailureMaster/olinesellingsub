<?php
if( class_exists('WPML_Elementor_Module_With_Items') ) {
    /**
     * Class WPML_Elementor_Icon_List
     */
    class Auxpro_WPML_Elementor_PriceList extends WPML_Elementor_Module_With_Items {
        /**
         * @return string
         */
        public function get_items_field() {
            return 'list';
        }
        /**
         * @return array
         */
        public function get_fields() {
            return array( 'text_primary', 'text_secondary', 'description', 'link' => array( 'url' )   );
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_title( $field ) {
            switch( $field ) {
                case 'text_primary':
                    return esc_html__( 'Price List: Text', PLUGIN_DOMAIN );
                case 'text_secondary':
                    return esc_html__( 'Price List: Price', PLUGIN_DOMAIN );
                case 'description':
                    return esc_html__( 'Price List: Description', PLUGIN_DOMAIN );
                case 'link':
                    return esc_html__( 'Price List: Link', PLUGIN_DOMAIN );
                default:
                    return '';
            }
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_editor_type( $field ) {
            switch( $field ) {
                case 'text_primary':
                case 'text_secondary':
                    return 'LINE';
                case 'description':
                    return 'VISUAL';
                case 'link':
                    return 'LINK';
                default:
                    return '';
            }
        }
    }
}