<?php
if( class_exists('WPML_Elementor_Module_With_Items') ) {
    /**
     * Class WPML_Elementor_Icon_List
     */
    class Auxpro_WPML_Elementor_CustomLabel extends WPML_Elementor_Module_With_Items {
        /**
         * @return string
         */
        public function get_items_field() {
            return 'child_list';
        }
        /**
         * @return array
         */
        public function get_fields() {
            return array( 'child_text' );
        }
        /**
         * @param string $field
         *
         * @return string
         */
        protected function get_title( $field ) {
            switch( $field ) {
                case 'child_text':
                    return esc_html__( 'Custom Label: Text', PLUGIN_DOMAIN );
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
                case 'child_text':
                    return 'LINE';
                default:
                    return '';
            }
        }
    }
}