<?php
/*
 * Plugin Name: AgriLife People
 * Plugin URI: http://github.com/AgriLife/AgriLife-People
 * Description: Creates a people custom post type.
 * Version: 1.1
 * Author: J. Aaron Eaton
 * Author URI: http://channeleaton.com
 * License: GPL2
 */

define( 'PEOPLE_PLUGIN_DIRNAME', 'agrilife-people' );
define( 'PEOPLE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PEOPLE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );

// Autoload all classes
spl_autoload_register( 'AgriLife_People::autoload' );

class AgriLife_People {

  /**
   * Instance of this class
   * @var object
   */
  private static $instance;

  /**
   * The current plugin version
   * @var string
   */
  public $plugin_version = '1.0';

  /**
   * The name of the plugin option
   * @var string
   */
  private $option_name = 'agrilife_people';

  /**
   * The array of plugin options
   * @var array
   */
  private $options = array();

  /**
   * The current meta schema version
   * @var integer
   */
  private $schema_version = 1;

  /**
   * The current file
   * @var string
   */
  private static $file = __FILE__;

  /**
   * Start the engine!
   */
  public function __construct() {

    self::$instance = $this;

    register_activation_hook( __FILE__, array( $this, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) );

    // Add/update options on admin load
    add_action( 'admin_init', array( $this, 'admin_init' ) );

    // Display admin notifications
    add_action( 'admin_notices', array( $this, 'admin_notices' ) );

    // Get the widgets ready
    add_action( 'widgets_init', array( $this, 'register_widgets' ) );

    // Ensure the Relevanssi plugin doesn't hide People posts
    add_filter('relevanssi_prevent_default_request', array( $this, 'rlv_fix_archive_kill' ), 10, 2);

  }

  /**
   * Ensure the Relevanssi plugin doesn't hide People posts
   * @return boolean
   */
  public function rlv_fix_archive_kill($kill, $query) {
    if (empty($query->query_vars[‘s’])) $kill = false;
    return $kill;
  }

  /**
   * Items to run on plugin activation
   * @return void
   */
  public function activate() {

    // Flush rewrite rules as we're creating new post types and taxonomies
    flush_rewrite_rules();

  }

  /**
   * Items to run on plugin deactivation
   * @return void
   */
  public function deactivate() {

    flush_rewrite_rules();

  }

  /**
   * Initialize the required classes
   * @return void
   */
  public function init() {

    // Load the plugin assets
    $alp_assets = new ALP_Assets;

    // Create the custom post type
    $alp_posttype = new ALP_PostType;

    // Create the Type taxonomy
    $alp_taxonomy = new ALP_Taxonomy;

    // Create the Metaboxes
    $alp_metabox = new ALP_Metabox;

    // Make the shortcode
    $alp_shortcode = new ALP_Shortcode( PEOPLE_PLUGIN_DIR_PATH );

    // Direct to the proper templates
    $alp_templates = new ALP_Templates;

  }

  /**
   * Initialize things for the admin area
   * @return void
   */
  public function admin_init() {

    // Setup/update options
    if ( false === $this->options || ! isset( $this->options['schema_version'] ) || $this->options['schema_version'] < $this->schema_version ) {

      //init options array
      if ( ! is_array( $this->options ) )
              $this->options = array();

      //establish schema version
      $current_schema_version = isset( $this->options['schema_version'] ) ? $this->options['schema_version'] : 0;

      $this->options['schema_version'] = $this->schema_version;
      update_option( $this->option_name, $this->options );

    }

  }

  /**
   * Displays admin notices
   * @return void
   */
  public function admin_notices() {

    if ( ! class_exists( 'Acf' ) )
      ALP_Message::install_plugin( 'Advanced Custom Fields' );

  }

  /**
   * Register widgets
   */
  public function register_widgets() {

    register_widget( 'ALP_Widget_FeaturedPerson' );

  }

 /**
   * Autoloads the requested class. PSR-0 compliant
   *
   * @since 2.0
   * @param  string $classname The name of the class
   */
  public static function autoload( $classname ) {

    $filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

  }

  public static function get_instance() {

    return self::$instance;

  }

}

new AgriLife_People;
