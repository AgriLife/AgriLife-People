<?php
/*
 * Plugin Name: AgriLife Staff
 * Plugin URI: https://github.com/channeleaton/AgriLife-Staff
 * Description: Creates a staff custom post type. NOTICE: This plugin cannot be used with the AgriFlex2012 theme.
 * Version: 2.0
 * Author: J. Aaron Eaton
 * Author URI: http://channeleaton.com
 * License: GPL2
 */

define( 'STAFF_PLUGIN_DIRNAME', 'agrilife-staff' );
define( 'STAFF_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'STAFF_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'STAFF_META_PREFIX', 'als_' );

// Autoload all classes
spl_autoload_register( 'AgriLife_Staff::autoload' );

class AgriLife_Staff {

  private static $instance;

  public $version = '2.0';

  private static $file = __FILE__;

  /**
   * Start the engine!
   */
  public function __construct() {

    self::$instance = $this;

    // Make sure the Meta Box plugin is installed
    add_action( 'init', array( $this, 'meta_box_check' ) );

    // Run the upgrade script
    register_activation_hook( self::$file, array( $this, 'upgrade' ) );

    // Load up the plugin
    add_action( 'init', array( $this, 'init' ) ); 

    // Add image sizes

  }

  /**
   * Checks to see if Meta Box plugin is installed. Shows an
   * error if it's not.
   */
  public function meta_box_check() {

    // Make sure that the Meta Box plugin is installed and activated
    if ( ! function_exists( 'rwmb_meta' ) ) {
      ALS_Error::no_metabox_plugin();
    }

  }

  /**
   * Run the schema upgrade script
   */
  public function upgrade() {

    $als_upgrade = new ALS_Upgrade;

  }

  /**
   * Initialize the required classes
   */
  public function init() {

    // Load the plugin assets
    $als_assets = new ALS_Assets;

    // Create the custom post type
    $als_posttype = new ALS_PostType;

    // Create the Type taxonomy
    $als_taxonomy = new ALS_Taxonomy;

    // Create the Metaboxes
    $als_metabox = new ALS_Metabox;

    // Make the shortcode
    $als_shortcode = new ALS_Shortcode;

    // Direct to the proper templates
    $als_templates = new ALS_Templates;

    add_filter( 'title_save_pre', array( $this, 'save_staff_title' ) );

    $this->add_image_sizes();

  }

  /**
   * Saves the staff title as lastname, firstname
   * @param  string $staff_title The empty staff title
   * @return string              The correct staff title
   */
  public function save_staff_title( $staff_title ) {

    if ( $_POST['post_type'] == 'staff' )
      $first = $_POST[STAFF_META_PREFIX . 'first-name'];
      $last = $_POST[STAFF_META_PREFIX . 'last-name'];
      $staff_title = $last . ', ' . $first;

    return $staff_title;
    
  }

  /**
   * Add the required image sizes
   */
  public function add_image_sizes() {

    add_image_size( 'staff_single', 175, 175, true );
    add_image_size( 'staff_archive', 70, 70, true );

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

new AgriLife_Staff;