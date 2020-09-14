<?php

if (!defined('ABSPATH'))
    exit;

/**
 * @class 		WCFMgs Template Class
 *
 * @version		2.0.0
 * @package		WC
 * @author 		WC Lovers
 */
class WCFMgs_Template {

    public $template_url;

    public function __construct() {
        $this->template_url = 'wcfmgs/';
        
        add_filter( 'template_include', array( $this, 'template_loader' ) );
    }
    
    /**
		 * Load a template.
		 *
		 * Handles template usage so that we can use our own templates instead of the themes.
		 *
		 * Templates are in the 'templates' folder. woocommerce looks for theme.
		 * overrides in /theme/wcfmgs/ by default.
		 *
		 * For beginners, it also looks for a woocommerce.php template first. If the user adds.
		 * this to the theme (containing a woocommerce() inside) this will be used for all.
		 * woocommerce templates.
		 *
		 * @param string $template Template to load.
		 * @return string
		 */
		public function template_loader( $template ) {
			global $WCFMgs;
			
			if ( is_embed() ) {
				return $template;
			}
	
			$default_file = self::get_template_loader_default_file();
	
			if ( $default_file ) {
				//$search_files = $this->get_template_loader_files( $default_file );
				$template     = $this->locate_template( $default_file );
	
				if ( ! $template || defined('WCFM_TEMPLATE_DEBUG_MODE') ) {
					$template = $WCFMgs->plugin_path . 'templates/' . $default_file;
				}
			}
	
			return $template;
		}
		
		/**
		 * Get the default filename for a template.
		 *
		 * @since  2.0.0
		 * @return string
		 */
		public function get_template_loader_default_file() {
			if ( is_singular( 'wcfm_vendor_groups' ) ) {
				$default_file = 'single-group.php';
			} elseif ( is_post_type_archive( 'wcfm_vendor_groups' ) ) {
				$default_file = 'archive-groups.php';
			} else {
				$default_file = '';
			}
			return $default_file;
		}

    /**
     * Get other templates (e.g. product attributes) passing attributes and including the file.
     *
     * @access public
     * @param mixed $template_name
     * @param array $args (default: array())
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     * @return void
     */
    public function get_template($template_name, $args = array(), $template_path = '', $default_path = '') {

        if ($args && is_array($args))
            extract($args);
          
        $located = $this->locate_template($search_files, $template_path, $default_path);

        include ($located);
    }
    
    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     * 		yourtheme		/	$template_path	/	$template_name
     * 		yourtheme		/	$template_name
     * 		$default_path	/	$template_name
     *
     * @access public
     * @param mixed $template_name
     * @param string $template_path (default: '')
     * @param string $default_path (default: '')
     * @return string
     */
    public function locate_template($template_name, $template_path = '', $default_path = '') {
        global $woocommerce, $WCFMgs;
        $default_path = apply_filters('wcfm_template_path', $default_path);
        if (!$template_path) {
            $template_path = $this->template_url;
        }
        if (!$default_path) {
            $default_path = $WCFMgs->plugin_path . 'templates/';
        }
        // Look within passed path within the theme - this is priority
        $template = locate_template(array(trailingslashit($template_path) . $template_name, $template_name));
        // Add support of third perty plugin
        $template = apply_filters('wcfm_locate_template', $template, $template_name, $template_path, $default_path);
        // Get default template
        if (!$template) {
            $template = $default_path . $template_name;
        }

        
        return $template;
    }

    /**
     * Get template part (for templates like the shop-loop).
     *
     * @access public
     * @param mixed $slug
     * @param string $name (default: '')
     * @return void
     */
    public function get_template_part($slug, $name = '') {
        global $WCFMgs;
        $template = '';

        // Look in yourtheme/slug-name.php and yourtheme/wcfmgs/slug-name.php
        if ($name)
            $template = $this->locate_template("{$slug}-{$name}.php");

        // Get default slug-name.php
        if (!$template && $name && file_exists($WCFMgs->plugin_path . "templates/{$slug}-{$name}.php"))
            $template = $WCFMgs->plugin_path . "templates/{$slug}-{$name}.php";

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
        if (!$template)
            $template = $this->locate_template(array("{$slug}.php", "{$this->template_url}{$slug}.php"));

        if ($template)
            load_template($template, false);
    }

}
