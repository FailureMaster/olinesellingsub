<?php
/**
 * Auxin Pro Tools.
 *
 * @echo HEADER
 */

if ( ! class_exists( 'AUXPRO' ) ) :



class AUXPRO {

  /**
   * Unique identifier for your plugin.
   *
   * The variable name is used as the text domain when internationalizing strings of text.
   *
   * @since    1.0.0
   *
   * @var      string
   */
    protected $plugin_slug = 'auxin-pro-tools';

  /**
    * Instance of this class.
    *
    * @since    1.0.0
    *
    * @var      object
    */
    protected static $instance = null;


   /**
    * Instance of Admin class.
    *
    * @since    1.0.0
    *
    * @var      object
    */
    public $admin = null;



   /**
    * Initialize the plugin
    *
    * @since     1.0.0
    */
    private function __construct() {

        // Auto-load classes on demand
        // if ( function_exists( "__autoload" ) ) {
        //     spl_autoload_register( "__autoload" );
        // }
        // spl_autoload_register( array( $this, 'autoload' ) );

        $this->includes();

        add_action( 'init', array( $this, 'init' ) );

        // Activate plugin when new blog is added
        add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

        // Loaded action
        do_action( 'auxpro_loaded' );
    }

    /**
     *
     * @return [type] [description]
    */
    private function includes() {
        
        // Auto-load classes on demand
        if ( function_exists( "__autoload" ) ) {
            spl_autoload_register( "__autoload" );
        }
        spl_autoload_register( array( $this, 'autoload' ) );

        // load common functionalities
        include_once( AUXPRO_INC_DIR . '/index.php' );

        // Dashboard and Administrative Functionality
        if ( is_admin() ) {

          // Load AJAX spesific codes on demand
          if ( defined('DOING_AJAX') && DOING_AJAX ){
            include( AUXPRO_ADMIN_DIR . '/includes/admin-ajax.php' );


          // Load admin spesific codes
          } else {
            $this->admin = include( AUXPRO_ADMIN_DIR . '/class-auxpro-admin.php' );
          }

        }

        // include the template parts only on frontend or during saving the page by siteorigin page builder
        if( ! is_admin() || ! empty( $_POST['_sopanels_nonce'] ) ){
            include 'includes/index.php';
        }

    }

    /**
     * Auto-load auxin classes on demand to reduce memory consumption
     *
     * @param mixed $class
     * @return void
     */
    function autoload( $class ) {
        $path  = null;
        $class = strtolower( $class );
        $file = 'class-' . str_replace( '_', '-', $class ) . '.php';

        // the possible pathes containing classes
        $possible_pathes = array(
            AUXPRO_INC_DIR   . '/classes/',
            AUXPRO_PUB_DIR   . '/includes/',
            AUXPRO_ADMIN_DIR . '/includes/classes/'
        );

        foreach ( $possible_pathes as $path ) {
            if( is_readable( $path . $file ) ){
                include_once( $path . $file );
                return;
            }

        }
    }





   /**
    * Init the plugin when WordPress Initialises.
    *
    * @return void
    */
    public function init(){
      // Load plugin text domain
      $this->load_plugin_textdomain();
    }


   /**
    * Return an instance of this class.
    *
    * @since     1.0.0
    *
    * @return    object    A single instance of this class.
    */
    public static function get_instance() {

        // If the single instance hasn't been set, set it now.
        if ( null == self::$instance ) {
          self::$instance = new self;
        }

        return self::$instance;
    }


   /**
    * Fired when the plugin is activated.
    *
    * @since    1.0.0
    *
    * @param    boolean    $network_wide    True if WPMU superadmin uses
    *                                       "Network Activate" action, false if
    *                                       WPMU is disabled or plugin is
    *                                       activated on an individual blog.
    */
    public static function activate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

          if ( $network_wide  ) {

            // Get all blog ids
            $blog_ids = self::get_blog_ids();

            foreach ( $blog_ids as $blog_id ) {

              switch_to_blog( $blog_id );
              self::single_activate();
            }

            restore_current_blog();

          } else {
            self::single_activate();
          }

        } else {
          self::single_activate();
        }
    }


  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
    public static function deactivate( $network_wide ) {

        if ( function_exists( 'is_multisite' ) && is_multisite() ) {

            if ( $network_wide ) {

                // Get all blog ids
                $blog_ids = self::get_blog_ids();

                foreach ( $blog_ids as $blog_id ) {
                    switch_to_blog( $blog_id );
                    self::single_deactivate();
                }

                restore_current_blog();

            } else {
                self::single_deactivate();
            }

        } else {
            self::single_deactivate();
        }

    }


    /**
     * Fired for each blog when the plugin is activated.
     *
     * @since    1.0.0
     */
    private static function single_activate() {
        do_action( 'auxpro_activated', get_current_blog_id() );
    }


    /**
     * Fired for each blog when the plugin is deactivated.
     *
     * @since    1.0.0
     */
    private static function single_deactivate() {
        do_action( 'auxpro_deactivated' );
    }


   /**
     * Fired when a new site is activated with a WPMU environment.
     *
     * @since    1.0.0
     *
     * @param    int    $blog_id    ID of the new blog.
    */
    public function activate_new_site( $blog_id ) {
        if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
          return;
        }

        switch_to_blog( $blog_id );
        self::single_activate();
        restore_current_blog();
    }

    /**
     * Get all blog ids of blogs in the current network that are:
     * - not archived
     * - not spam
     * - not deleted
     *
     * @since    1.0.0
     *
     * @return   array|false    The blog ids, false if no matches.
     */
    private static function get_blog_ids() {
        global $wpdb;

        // get an array of blog ids
        $sql = "SELECT blog_id FROM $wpdb->blogs
        WHERE archived = '0' AND spam = '0'
        AND deleted = '0'";

        return $wpdb->get_col( $sql );
    }

    /**
     * Get the template path.
     * @return string
     */
    public function template_path() {
        return apply_filters( 'auxin_pro_template_path', AUXPRO_PUB_DIR . '/templates/' );
    }


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() {
        $locale = apply_filters( 'plugin_locale', get_locale(), PLUGIN_DOMAIN );
        load_textdomain( PLUGIN_DOMAIN, trailingslashit( WP_LANG_DIR ) . PLUGIN_DOMAIN . '/' . PLUGIN_DOMAIN . '-' . $locale . '.mo' );
        load_plugin_textdomain( PLUGIN_DOMAIN, FALSE, basename( AUXPRO_DIR ) . '/languages/' );
    }

}

endif;

function AUXPRO(){ return AUXPRO::get_instance(); }
AUXPRO();
