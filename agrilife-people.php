<?php
/*
 * Plugin Name: AgriLife People
 * Plugin URI: https://github.com/channeleaton/AgriLife-Staff
 * Description: Creates a people custom post type. NOTICE: This plugin cannot be used with the AgriFlex2012 theme.
 * Version: 2.0
 * Author: J. Aaron Eaton
 * Author URI: http://channeleaton.com
 * License: GPL2
 */

define( 'PEOPLE_PLUGIN_DIRNAME', 'agrilife-people' );
define( 'PEOPLE_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'PEOPLE_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PEOPLE_META_PREFIX', 'ag-people-' );

// Autoload all classes
spl_autoload_register( 'AgriLife_People::autoload' );

class AgriLife_People {

  private static $instance;

  public $version = '2.0';

  private static $file = __FILE__;

  /**
   * Start the engine!
   */
  public function __construct() {

    self::$instance = $this;

    // Run the upgrade script
    register_activation_hook( self::$file, array( $this, 'upgrade' ) );

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) ); 

    // Add image sizes

  }

  /**
   * Run the schema upgrade script
   */
  public function upgrade() {

    $alp_upgrade = new ALP_Upgrade;

  }

  /**
   * Initialize the required classes
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
    $alp_shortcode = new ALP_Shortcode;

    // Direct to the proper templates
    $alp_templates = new ALP_Templates;

    add_filter( 'title_save_pre', array( $this, 'save_people_title' ) );

    $this->add_image_sizes();

  }

  /**
   * Saves the person title as lastname, firstname
   * @param  string $people_title The empty staff title
   * @return string              The correct staff title
   */
  public function save_people_title( $people_title ) {

    if ( ! empty( $_POST['post_type'] ) && $_POST['post_type'] == 'people' ){
      global $post;

      $first = $_POST['fields']['field_52540a7be7804'];
      $last = $_POST['fields']['field_52540aa741c37'];

      $people_title = sprintf( '%2$s, %1$s', $first, $last );
    }

    return $people_title;
    
  }

  /**
   * Add the required image sizes
   */
  public function add_image_sizes() {

    add_image_size( 'people_single', 175, 175, true );
    add_image_size( 'people_archive', 70, 70, true );

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