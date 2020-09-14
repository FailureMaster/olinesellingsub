<?php
/**
 * WCFM Affiliate plugin core
 *
 * Plugin non Ajax Controler
 *
 * @author 		WC Lovers
 * @package 	wcfmaf/core
 * @version   1.0.0
 */
 
class WCFMaf_Non_Ajax {

	public function __construct() {
		global $WCFM, $WCFMaf;
		
		// Plugins page help links
		add_filter( 'plugin_action_links_' . $WCFMaf->plugin_base_name, array( &$this, 'wcfmaf_plugin_action_links' ) );
		add_filter( 'plugin_row_meta', array( &$this, 'wcfmaf_plugin_row_meta' ), 10, 2 );
	}
	
	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function wcfmaf_plugin_action_links( $links ) {
		global $WCFMaf;
		$action_links = array(
			'wcfmaf_license' => '<a href="' . esc_url( admin_url( 'admin.php?page=wcfm-license&tab=' . str_replace('-', '_', esc_attr($WCFMaf->token)) . '_license' ) ) . '" aria-label="' . esc_attr__( 'Set WCFM Affiliate License', 'wc-frontend-manager-affiliate' ) . '">' . esc_html__( 'License', 'wc-frontend-manager-affiliate' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}
	
	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param	mixed $links Plugin Row Meta
	 * @param	mixed $file  Plugin Base file
	 * @return	array
	 */
	public function wcfmaf_plugin_row_meta( $links, $file ) {
		global $WCFM, $WCFMaf;
		if ( $WCFMaf->plugin_base_name == $file ) {
			$row_meta = array(
				'changelog' => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfmaf_changelog_url', 'http://wclovers.com/wcfm-affiliate-change-log/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM Affiliate Change Log', 'wc-frontend-manager-affiliate' ) . '">' . esc_html__( 'Change Log', 'wc-frontend-manager-affiliate' ) . '</a>',
				'docs'      => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfm_docs_url', 'https://docs.wclovers.com/wcfm-affiliate/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM documentation', 'wc-frontend-manager' ) . '">' . esc_html__( 'Documentation', 'wc-frontend-manager' ) . '</a>',
				//'guide'     => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfm_guide_url', 'http://wclovers.com/documentation/developers-guide/' ) ) . '" aria-label="' . esc_attr__( 'View WCFM Developer Guide', 'wc-frontend-manager' ) . '">' . esc_html__( 'Developer Guide', 'wc-frontend-manager' ) . '</a>',
				'support'   => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfm_support_url', 'http://wclovers.com/forums/forum/wcfm-affiliate/' ) ) . '" aria-label="' . esc_attr__( 'Visit premium customer support', 'woocommerce' ) . '">' . esc_html__( 'Support', 'woocommerce' ) . '</a>',
				//'contactus' => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfm_contactus_url', 'http://wclovers.com/contact-us/' ) ) . '" aria-label="' . esc_attr__( 'Any WC help feel free to contact us', 'wc-frontend-manager' ) . '">' . esc_html__( 'Contact US', 'wc-frontend-manager' ) . '</a>'
				'customizationa' => '<a target="_blank" href="' . esc_url( apply_filters( 'wcfm_customization_url', 'https://wclovers.com/woocommerce-multivendor-customization/' ) ) . '" aria-label="' . esc_attr__( 'Any WC help feel free to contact us', 'wc-frontend-manager' ) . '">' . esc_html__( 'Customization Help', 'wc-frontend-manager' ) . '</a>'
			);
			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}
}