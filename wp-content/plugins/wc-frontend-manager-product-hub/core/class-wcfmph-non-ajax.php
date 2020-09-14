<?php
/**
 * WCFM Product Hub plugin core
 *
 * Plugin non Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmph/core
 * @version   1.0.0
 */
 
class WCFMph_Non_Ajax {

	public function __construct() {
		global $WCFM, $WCFMu;
		
		// Plugins page help links
		//add_filter( 'plugin_action_links_' . $WCFMu->plugin_base_name, array( &$this, 'wcfm_plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( &$this, 'wcfmph_plugin_row_meta' ), 10, 2 );
	}
	
	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param	mixed $links Plugin Row Meta
	 * @param	mixed $file  Plugin Base file
	 * @return	array
	 */
	public function wcfmph_plugin_row_meta( $links, $file ) {
		global $WCFM, $WCFMph;
		if ( $WCFMph->plugin_base_name == $file ) {
			$row_meta = array(
				'changelog' => '<a href="' . esc_url( apply_filters( 'wcfmph_changelog_url', 'http://wclovers.com/wcfm-product-hub-change-log/' ) ) . '" aria-label="' . esc_attr__( 'View WCFMu Change Log', 'wc-frontend-manager-product-hub' ) . '">' . esc_html__( 'Change Log', 'wc-frontend-manager-product-hub' ) . '</a>',
				'docs'      => '<a href="' . esc_url( apply_filters( 'wcfm_docs_url', 'http://wclovers.com/knowledgebase/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM documentation', 'wc-frontend-manager' ) . '">' . esc_html__( 'Documentation', 'wc-frontend-manager' ) . '</a>',
				//'guide'     => '<a href="' . esc_url( apply_filters( 'wcfm_guide_url', 'http://wclovers.com/documentation/developers-guide/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM Developer Guide', 'wc-frontend-manager' ) . '">' . esc_html__( 'Developer Guide', 'wc-frontend-manager' ) . '</a>',
				'support'   => '<a href="' . esc_url( apply_filters( 'wcfm_support_url', 'http://wclovers.com/forums/forum/wcfm-product-hub/' ) ) . '" aria-label="' . esc_attr__( 'Visit premium customer support', 'woocommerce' ) . '">' . esc_html__( 'Support', 'woocommerce' ) . '</a>',
				//'contactus' => '<a href="' . esc_url( apply_filters( 'wcfm_contactus_url', 'http://wclovers.com/contact-us/' ) ) . '" aria-label="' . esc_attr__( 'Any WC help feel free to contact us', 'wc-frontend-manager' ) . '">' . esc_html__( 'Contact US', 'wc-frontend-manager' ) . '</a>'
			);
			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}
}